<?php
session_start();

try {
    require "db.php";

    if (!isset($_SESSION['user_id'])) {
        header("Location:../auth.php");
        exit;
    }

    // ======== Total Rooms ========
    $filterTotalRooms = isset($_POST['filterTotalRooms']);

    $dateCondition = "";

    // Total Rooms Filters
    if ($filterTotalRooms === "Today") {
        $today = date('Y-m-d');
        $dateCondition = "WHERE DATE(booking_date) = CURDATE()";
    } elseif ($filterTotalRooms === "This Month") {
        $dateCondition = "WHERE MONTH(booking_date) = MONTH(CURRENT_DATE) AND YEAR(booking_date) = YEAR(CURRENT_DATE)";
    } elseif ($filterTotalRooms === "This Year") {
        $dateCondition = "WHERE YEAR(booking_date) = YEAR(CURRENT_DATE)";
    }

    $sqlTotalRooms = "
    SELECT COUNT(id) AS total_rooms
    FROM rooms
    $dateCondition
    ";

    $totalRoomsStatement = $db->prepare($sqlTotalRooms);
    $totalRoomsStatement->execute();
    $totalRooms = $totalRoomsStatement->fetch(PDO::FETCH_ASSOC)['total_rooms'] ?? 0;

    // ======== Available Rooms ========
    $sqlAvailableRooms = "
    SELECT COUNT(id) AS available_rooms
    FROM rooms
    WHERE LOWER(room_status) = 'available';
    ";

    $availableRoomsStatement = $db->prepare($sqlAvailableRooms);
    $availableRoomsStatement->execute();
    $availableRooms = $availableRoomsStatement->fetch(PDO::FETCH_ASSOC)['available_rooms'] ?? 0;

    // ======== Most Booked Room ========
    $sqlMostBookedRoom = "
    SELECT r.room_number, 
           COUNT(b.id) AS booking_count
    FROM rooms r
    LEFT JOIN booking b 
    ON r.id = b.class_id
    GROUP BY r.id
    ORDER BY booking_count DESC
    LIMIT 1
    ";

    $mostBookedRoomStatement = $db->prepare($sqlMostBookedRoom);
    $mostBookedRoomStatement->execute();
    $mostBookedRoom = $mostBookedRoomStatement->fetch(PDO::FETCH_ASSOC);

    $mostBookedRoomNumber = $mostBookedRoom['room_number'] ?? "None";

    // ======== Booking Report========
    $sqlBookingReport = "
        SELECT DATE(booking_date) AS booking_date, COUNT(*) AS total_bookings
        FROM booking
        $dateCondition
        GROUP BY DATE(booking_date)
        ORDER BY booking_date ASC
        ";
    $statement = $db->prepare($sqlBookingReport);
    $statement->execute();
    $bookings = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Prepare data for JavaScript
    $dates = [];
    $totalBookings = [];
    $confirmedBookings = [];
    $canceledBookings = [];

    foreach ($bookings as $booking) {
        $dates[] = $booking['booking_date'];
        $totalBookings[] = $booking['total_bookings'];

        // Query to fetch canceled bookings for the current date
        $sqlCanceledForDate = "
            SELECT COUNT(*) AS canceled_bookings
            FROM booking
            WHERE DATE(booking_date) = :booking_date
            AND LOWER(booking_status) = 'canceled'
        ";
        $canceledStmt = $db->prepare($sqlCanceledForDate);
        $canceledStmt->execute([':booking_date' => $booking['booking_date']]);
        $canceledBookings[] = $canceledStmt->fetch(PDO::FETCH_ASSOC)['canceled_bookings'] ?? 0;

        // Assuming confirmed bookings are total - canceled
        $confirmedBookings[] = $booking['total_bookings'] - end($canceledBookings);
    }

    // Convert data to JSON for JavaScript
    $datesJson = json_encode($dates);
    $totalBookingsJson = json_encode($totalBookings);
    $confirmedBookingsJson = json_encode($confirmedBookings);
    $canceledBookingsJson = json_encode($canceledBookings);

    // ======== To calculate increase or decrease ========

    // === Total Rooms ===

    // Current Period
    $currentDateCondition = "WHERE DATE(booking_date) = CURDATE()";
    $sqlTotalRoomsCurrent = "SELECT COUNT(id) AS total_rooms FROM rooms $currentDateCondition";
    $currentTotalRoomsStmt = $db->prepare($sqlTotalRoomsCurrent);
    $currentTotalRoomsStmt->execute();
    $currentTotalRooms = $currentTotalRoomsStmt->fetch(PDO::FETCH_ASSOC)['total_rooms'] ?? 0;

    // Previous Period
    $previousDateCondition = "WHERE DATE(booking_date) = CURDATE() - INTERVAL 1 DAY";
    $sqlTotalRoomsPrevious = "SELECT COUNT(id) AS total_rooms FROM rooms $previousDateCondition";
    $previousTotalRoomsStmt = $db->prepare($sqlTotalRoomsPrevious);
    $previousTotalRoomsStmt->execute();
    $previousTotalRooms = $previousTotalRoomsStmt->fetch(PDO::FETCH_ASSOC)['total_rooms'] ?? 0;

    // Calculate Percentage Change
    if ($previousTotalRooms > 0) {
        $percentageChangeTotalRooms = (($currentTotalRooms - $previousTotalRooms) / $previousTotalRooms) * 100;
    } else {
        $percentageChangeTotalRooms = $currentTotalRooms > 0 ? 100 : 0; // Handle edge cases
    }

    // === Available Rooms ===

    // Current Available Rooms
    $currentAvailableCondition = "WHERE LOWER(room_status) = 'available' AND DATE(booking_date) = CURDATE()";
    $sqlAvailableRoomsCurrent = "
    SELECT COUNT(id) AS available_rooms 
    FROM rooms 
    WHERE LOWER(room_status) = 'available'
    ";

    $currentAvailableRoomsStmt = $db->prepare($sqlAvailableRoomsCurrent);
    $currentAvailableRoomsStmt->execute();
    $currentAvailableRooms = $currentAvailableRoomsStmt->fetch(PDO::FETCH_ASSOC)['available_rooms'] ?? 0;

    // Previous Available Rooms
    $previousAvailableCondition = "WHERE LOWER(room_status) = 'available' AND DATE(booking_date) = CURDATE() - INTERVAL 1 DAY";
    $sqlAvailableRoomsPrevious = "
    SELECT COUNT(id) AS available_rooms 
    FROM rooms 
    WHERE LOWER(room_status) = 'available'
    ";
    $previousAvailableRoomsStmt = $db->prepare($sqlAvailableRoomsPrevious);
    $previousAvailableRoomsStmt->execute();
    $previousAvailableRooms = $previousAvailableRoomsStmt->fetch(PDO::FETCH_ASSOC)['available_rooms'] ?? 0;

    // Calculate Percentage Change for Available Rooms
    if ($previousAvailableRooms > 0) {
        $percentageChangeAvailableRooms = (($currentAvailableRooms - $previousAvailableRooms) / $previousAvailableRooms) * 100;
    } else {
        $percentageChangeAvailableRooms = $currentAvailableRooms > 0 ? 100 : 0; // Handle edge cases
    }
} catch (PDOException $e) {
    echo "Error while fetching booking data: " . $e->getMessage();
    die;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Linking to CSS file for styling -->
    <link rel="stylesheet" href="../css/analysis.css">
</head>

<body>

    <!-- ======= Header ======= -->
    <?php include "../views/header.php" ?>

    <div class="main row g-0">

        <!-- ======= Sidebar ======= -->
        <div id="sidebar" class="container col-12 col-md-3 col-lg-2 p-3 mb-3 shadow-sm">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a href="#" class="nav-link active">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="./room_analysis.php" class="nav-link">Room Analysis</a>
                </li>
                <li class="nav-item">
                    <a href="./upcoming_past_bookings.php" class="nav-link">Upcoming & Past Bookings</a>
                </li>
                <li class="nav-item">
                    <a href="./edit_profile.php" class="nav-link">Edit Profile</a>
                </li>
            </ul>
        </div>

        <!-- ======= Main Content ======= -->
        <main id="dashboard" class="col-12 col-md-9 col-lg-10">

            <?php
            // Cards
            $filterTotalRooms = isset($_POST['filterTotalRooms']) ? $_POST['filterTotalRooms'] : "Today";
            $filterAvailableRooms = isset($_POST['filterAvailableRooms']) ? $_POST['filterAvailableRooms'] : "Today";
            $filterAverageRating = isset($_POST['filterAverageRating']) ? $_POST['filterAverageRating'] : "Today";

            // Reports
            $filterBookingReport = isset($_POST['filterBookingReport']) ? $_POST['filterBookingReport'] : "Today";
            $filterDonutChart = isset($_POST['filterDonutChart']) ? $_POST['filterDonutChart'] : "This Year";
            ?>

            <div class="row">

                <!-- Left side column -->
                <div class="col-lg-8">

                    <!-- Cards -->
                    <div class="row mb-3">

                        <!-- Total Rooms -->
                        <div class="col">
                            <div class="card text-center shadow-sm total-rooms">

                                <!-- Filter -->
                                <form method="POST">
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <li class="dropdown-header text-start">
                                                <h6>Filter</h6>
                                            </li>
                                            <li><button type="submit" class="dropdown-item" name="filterTotalRooms" value="Today">Today</button></li>
                                            <li><button type="submit" class="dropdown-item" name="filterTotalRooms" value="This Month">This Month</button></li>
                                            <li><button type="submit" class="dropdown-item" name="filterTotalRooms" value="This Year">This Year</button></li>
                                        </ul>
                                    </div>
                                </form>

                                <h5 class="card-title mt-3 text-start mx-4">Total Rooms <span>| <?php echo htmlspecialchars($filterTotalRooms); ?></span></h5>
                                <div class="p-3">
                                    <div class="d-flex justify-content-left align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-calendar-date text-primary fs-2"></i>
                                        </div>
                                        <div class="ps-3">
                                            <p class="fs-4 fw-bold mb-0 ms-2"><?php echo $totalRooms; ?></p>
                                            <span class="text-<?php echo $percentageChangeTotalRooms >= 0 ? 'success' : 'danger'; ?> small pt-1 fw-bold">
                                                <?php echo number_format(abs($percentageChangeTotalRooms), 2); ?>%
                                            </span>
                                            <span class="text-muted small pt-2 ps-1">
                                                <?php echo $percentageChangeTotalRooms >= 0 ? 'increase' : 'decrease'; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End of Total Rooms -->

                        <!-- Available Rooms -->
                        <div class="col">
                            <div class="card text-center shadow-sm ava-room">

                                <!-- Filter -->
                                <form method="POST">
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <li class="dropdown-header text-start">
                                                <h6>Filter</h6>
                                            </li>
                                            <li><button type="submit" class="dropdown-item" name="filterAvailableRooms" value="Today">Today</button></li>
                                            <li><button type="submit" class="dropdown-item" name="filterAvailableRooms" value="This Month">This Month</button></li>
                                            <li><button type="submit" class="dropdown-item" name="filterAvailableRooms" value="This Year">This Year</button></li>
                                        </ul>
                                    </div>
                                </form>

                                <h5 class="card-title mt-3 text-start mx-4">Available Rooms <span>| <?php echo htmlspecialchars($filterAvailableRooms); ?></span></h5>
                                <div class="p-3">
                                    <div class="d-flex justify-content-left align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-calendar-check text-success fs-2"></i>
                                        </div>
                                        <div class="ps-3">
                                            <p class="fs-4 fw-bold mb-0 ms-2"><?php echo $availableRooms; ?></p>
                                            <span class="text-<?php echo $percentageChangeAvailableRooms >= 0 ? 'success' : 'danger'; ?> small pt-1 fw-bold">
                                                <?php echo number_format(abs($percentageChangeAvailableRooms), 2); ?>%
                                            </span>
                                            <span class="text-muted small pt-2 ps-1">
                                                <?php echo $percentageChangeAvailableRooms >= 0 ? 'increase' : 'decrease'; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End of Available Rooms -->

                        <!-- Most Booked Room -->
                        <div class="col">
                            <div class="card text-center shadow-sm avg-rating">
                                <!-- Filter -->
                                <form method="POST">
                                    <div class="filter">
                                        <a type="button" class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <li class="dropdown-header text-start">
                                                <h6>Filter</h6>
                                            </li>
                                            <li><button type="submit" class="dropdown-item" name="filterAverageRating" value="Today">Today</button></li>
                                            <li><button type="submit" class="dropdown-item" name="filterAverageRating" value="This Month">This Month</button></li>
                                            <li><button type="submit" class="dropdown-item" name="filterAverageRating" value="This Year">This Year</button></li>
                                        </ul>
                                    </div>
                                </form>

                                <h5 class="card-title mt-3 text-start mx-4">Most Booked Room <span>| <?php echo htmlspecialchars($filterAverageRating); ?></span></h5>
                                <div class="p-3">
                                    <div class="d-flex justify-content-left align-items-center">
                                        <!-- Icon -->
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-star text-warning fs-2"></i>
                                        </div>
                                        <div class="ps-3">
                                            <p class="fs-4 fw-bold mb-0 ms-2"><?php echo htmlspecialchars($mostBookedRoomNumber); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End of Most Booked Room -->

                    </div> <!-- End of Cards -->


                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Booking Report</h5>
                                <div id="bookingLineChart"></div>

                                <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

                                <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        // Use PHP data for chart
                                        const totalBookings = <?php echo $totalBookingsJson; ?>;
                                        const confirmedBookings = <?php echo $confirmedBookingsJson; ?>;
                                        const canceledBookings = <?php echo $canceledBookingsJson; ?>;
                                        const dates = <?php echo $datesJson; ?>;

                                        // Initialize the chart
                                        const chart = new ApexCharts(document.querySelector("#bookingLineChart"), {
                                            series: [{
                                                    name: 'Total Bookings',
                                                    data: totalBookings,
                                                },
                                                {
                                                    name: 'Confirmed Bookings',
                                                    data: confirmedBookings,
                                                },
                                                {
                                                    name: 'Canceled Bookings',
                                                    data: canceledBookings,
                                                }
                                            ],
                                            chart: {
                                                height: 350,
                                                type: 'area',
                                                toolbar: {
                                                    show: false
                                                },
                                            },
                                            markers: {
                                                size: 4
                                            },
                                            colors: ['#6486ed', '#adf58b', '#ff8686'],
                                            fill: {
                                                type: "gradient",
                                                gradient: {
                                                    shadeIntensity: 1,
                                                    opacityFrom: 0.3,
                                                    opacityTo: 0.4,
                                                    stops: [0, 90, 100]
                                                }
                                            },
                                            dataLabels: {
                                                enabled: false
                                            },
                                            stroke: {
                                                curve: 'smooth',
                                                width: 2
                                            },
                                            xaxis: {
                                                type: 'datetime',
                                                categories: dates.map(date => new Date(date).toISOString()) // Convert dates to ISO format
                                            },
                                            tooltip: {
                                                x: {
                                                    format: 'dd/MM/yyyy'
                                                },
                                            }
                                        });

                                        chart.render();
                                    });
                                </script>
                            </div>
                        </div>
                    </div>

                </div> <!-- End of left side column -->

                <!-- Right side column -->
                <div class="col-xxl-4 col-xl-12">
                    <?php
                    // Total Bookings
                    $sqlTotalBookings = "SELECT COUNT(*) FROM booking";
                    $totalBookingsStatement = $db->prepare($sqlTotalBookings);
                    $totalBookingsStatement->execute();
                    $totalBookings = $totalBookingsStatement->fetchColumn();

                    // Confirmed Bookings
                    $sqlConfirmedBookings = "SELECT COUNT(*) FROM booking WHERE LOWER(booking_status) = 'confirmed'";
                    $confirmedBookingsStatement = $db->prepare($sqlConfirmedBookings);
                    $confirmedBookingsStatement->execute();
                    $confirmedBookings = $confirmedBookingsStatement->fetchColumn();

                    // Canceled Bookings
                    $sqlCanceledBookings = "SELECT COUNT(*) FROM booking WHERE LOWER(booking_status) = 'canceled'";
                    $canceledBookingsStatement = $db->prepare($sqlCanceledBookings);
                    $canceledBookingsStatement->execute();
                    $canceledBookings = $canceledBookingsStatement->fetchColumn();
                    ?>

                    <!-- Booking Donut Chart Report -->
                    <div class="card shadow-sm mb-3">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>

                                <li><a class="dropdown-item" href="#">Today</a></li>
                                <li><a class="dropdown-item" href="#">This Month</a></li>
                                <li><a class="dropdown-item" href="#">This Year</a></li>
                            </ul>
                        </div>

                        <div class="card-body pb-0">
                            <h5 class="card-title">Booking Report <span>| This Year</span></h5>

                            <div id="bookingDonutChart" style="min-height: 400px;" class="echart"
                                data-total="<?= $totalBookings ?>"
                                data-confirmed="<?= $confirmedBookings ?>"
                                data-canceled="<?= $canceledBookings ?>">
                            </div>

                            <script src="../js/bookingDonutChart.js"></script>
                        </div>
                    </div> <!-- Booking Donut Chart Report -->


                    <?php
                    // Available Rooms
                    $sqlAvailableRooms = "
                    SELECT COUNT(id) AS available_rooms
                    FROM rooms
                    WHERE LOWER(room_status) = 'available';
                    ";

                    $availableRoomsStatement = $db->prepare($sqlAvailableRooms);
                    $availableRoomsStatement->execute();
                    $availableRooms = $availableRoomsStatement->fetch(PDO::FETCH_ASSOC)['available_rooms'] ?? 0;

                    // Occupied Rooms
                    $sqlOccupiedRooms = "
                    SELECT COUNT(id) AS occupied_rooms
                    FROM rooms
                    WHERE LOWER(room_status) = 'occupied';
                    ";

                    $occupiedRoomsStatement = $db->prepare($sqlOccupiedRooms);
                    $occupiedRoomsStatement->execute();
                    $occupiedRooms = $occupiedRoomsStatement->fetch(PDO::FETCH_ASSOC)['occupied_rooms'] ?? 0;
                    ?>

                    <!-- Room Occupancy Pie Chart Report -->
                    <div class="card shadow-sm mb-3">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>
                                <li><a class="dropdown-item" href="#">Today</a></li>
                                <li><a class="dropdown-item" href="#">This Month</a></li>
                                <li><a class="dropdown-item" href="#">This Year</a></li>
                            </ul>
                        </div>

                        <div class="card-body pb-0">
                            <h5 class="card-title">Room Occupancy <span>| This Year</span></h5>

                            <div id="occupancyChart" style="min-height: 400px;" class="echart"
                                data-total="<?= $totalRooms ?>"
                                data-occupied="<?= $occupiedRooms ?>"
                                data-available="<?= $availableRooms ?>">
                            </div>

                            <script src="../js/occupancyChart.js"></script>
                        </div>
                    </div> <!-- Room Occupancy Pie Chart Report -->

                </div> <!-- End of Right side column -->
            </div>

            <div class="col-lg4">
            </div>
        </main>
    </div>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JS External Libraries for Charts -->
    <script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Footer -->
    <?php include "../views/footer.php" ?>
</body>

</html>