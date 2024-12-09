<?php
session_start();

try {
    require "db.php";

    if (!isset($_SESSION['user_id'])) {
        header("Location:../auth.php");
        exit;
    }

    // Fetching rooms details
    $sql = "SELECT * FROM rooms";
    $statement = $db->prepare($sql);
    $statement->execute();
    $rooms = $statement->fetchAll(PDO::FETCH_ASSOC);

    if ($rooms) {
        $departments = [
            "IS" => [],
            "CS" => [],
            "NE" => []
        ];

        foreach ($rooms as $room) {
            $room_department = $room["department"];
            $departments[$room_department][] = $room;
        }
    }

    // Get number of equipment for each room
    $sql_equipments = "
    SELECT id AS room_id, room_number, 
           (CASE WHEN equipments IS NOT NULL AND equipments != ''
                 THEN (LENGTH(equipments) - LENGTH(REPLACE(equipments, ',', '')) + 1) 
                 ELSE 0 
            END) AS num_of_equipments
    FROM rooms;
    ";
    $statement = $db->prepare($sql_equipments);
    $statement->execute();
    $equipment_data = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Total Bookings for that user for every room
    $sql_bookings = "
    SELECT r.id AS room_id, r.room_number, 
           COUNT(b.id) AS total_bookings, 
           COALESCE(
               (SUM(
                   TIMESTAMPDIFF(MINUTE, b.start_time, b.end_time)
               ) / (780 * COUNT(DISTINCT DATE(b.start_time)))) * 100, 
               0
           ) AS occupancy_rate
    FROM rooms r
    LEFT JOIN booking b ON r.room_number = b.class_id
    GROUP BY r.id;
    ";

    $statement = $db->prepare($sql_bookings);
    $statement->execute();
    $booking_data = $statement->fetchAll(PDO::FETCH_ASSOC);

    $rooms_data = [];
    foreach ($equipment_data as $equipment) {
        $rooms_data[$equipment['room_id']] = $equipment;
    }

    foreach ($booking_data as $booking) {
        if (isset($rooms_data[$booking['room_id']])) {
            $rooms_data[$booking['room_id']]['total_bookings'] = $booking['total_bookings'];
            // $rooms_data[$booking['room_id']]['occupancy_rate'] = $booking['occupancy_rate'];
        }
    }

    // Calculating overall occupancy for all users
    $sql_overall_occupancy = "
    SELECT 
        COALESCE(
            (SUM(TIMESTAMPDIFF(MINUTE, b.start_time, b.end_time)) /
            (COUNT(DISTINCT r.id) * 780 * COUNT(DISTINCT DATE(b.start_time))) * 100),
            0
        ) AS overall_occupancy_rate
    FROM rooms r
    LEFT JOIN booking b ON r.room_number = b.class_id;
    ";

    $statement = $db->prepare($sql_overall_occupancy);
    $statement->execute();
    $overall_occupancy_data = $statement->fetch(PDO::FETCH_ASSOC);

    $overall_occupancy_rate = $overall_occupancy_data['overall_occupancy_rate'] ?? 0;
} catch (PDOException $e) {
    echo "Error while fetching the data: " . $e->getMessage();
    die;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Analysis</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Linking to CSS file for styling -->
    <link rel="stylesheet" href="../css/analysis.css">

    <style>
        .room-img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .card {
            overflow: hidden;
            /* Ensures content stays within the card's boundaries */
        }

        .header-img {
            margin-right: 15px;
        }

        .header {
            margin-left: 50px;
            margin-bottom: 20px;
        }
    </style>
    </style>
</head>

<body>

    <!-- ======= Header ======= -->
    <?php include "../views/header.php" ?>

    <div class="main row g-0">

        <!-- ======= Sidebar ======= -->
        <div id="sidebar" class="container col-12 col-md-3 col-lg-2 p-3 mb-3 shadow-sm">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a href="./analysis.php" class="nav-link">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="./room_analysis.php" class="nav-link active">Room Analysis</a>
                </li>
                <li class="nav-item">
                    <a href="./upcoming_past_bookings.php" class="nav-link">Upcoming & Past Bookings</a>
                </li>
                <li class="nav-item">
                    <a href="./edit_profile.php" class="nav-link">Edit Profile</a>
                </li>
            </ul>
        </div> <!-- End of Sidebar -->

        <main id="dashboard" class="col-12 col-md-9 col-lg-10">
            <div class="row">

                <!-- Departments -->
                <div class="container">
                    <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
                        <!-- IS Department -->
                        <div class="is-department" class="col">
                            <div class="card mb-4 rounded-3 shadow-sm">
                                <div class="card-header py-3">
                                    <h4 class="my-0 fw-normal">IS Department Rooms</h4>
                                </div>
                            </div>

                            <!-- IS Rooms -->
                            <?php if (!empty($departments["IS"])): ?>
                                <?php foreach ($departments["IS"] as $room): ?>
                                    <?php
                                    $data = $rooms_data[$room['id']] ?? null;
                                    if (!$data) continue;
                                    ?>
                                    <div class="card shadow-sm mb-3">
                                        <img src="../img/IS/<?php echo $room["room_number"]; ?>/IS_<?php echo $room["room_number"]; ?>_001.jpg"
                                            alt="Room Image">

                                        <div class="card-body">
                                            <p class="card-text mb-1 fs-4 fw-bold text-primary"><?php echo $room["room_number"]; ?></p>

                                            <!-- Total Bookings -->
                                            <p class="fs-6 fw-bold">
                                                Total Bookings:
                                                <span class="fw-normal">
                                                    <?php echo htmlspecialchars($data['total_bookings']); ?>
                                                </span>
                                            </p>
                                            <!-- Number of Equipments -->
                                            <p class="fs-6 fw-bold">
                                                Number of Equipments:
                                                <span class="fw-normal">
                                                    <?php echo htmlspecialchars($data['num_of_equipments']); ?>
                                                </span>
                                            </p>
                                            <!-- Occupancy Rate -->
                                            <p class="fs-6 fw-bold">
                                                Overall Occupancy Rate:
                                                <span class="fw-normal">
                                                    <?php echo number_format($overall_occupancy_rate, 2); ?>%
                                                </span>
                                            </p>

                                            <!-- Room Status -->
                                            <p class="fs-5 fw-bold">Status:
                                                <span class="badge bg-<?php echo ($room["room_status"] == "Available") ? "success" : "danger"; ?>">
                                                    <?php echo $room["room_status"]; ?>
                                                </span>
                                            </p>

                                            <!-- View all ratings - Button -->
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="btn-group">
                                                    <a href="./room_details.php?id=<?php echo $room["id"]; ?>" class="btn btn-sm btn-outline-primary">
                                                        View Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </div>

                        <!-- CS Department -->
                        <div id="cs-department" class="col">
                            <div class="card mb-4 rounded-3 shadow-sm">
                                <div class="card-header py-3">
                                    <h4 class="my-0 fw-normal">CS Department Rooms</h4>
                                </div>
                            </div>

                            <!-- CS Rooms -->
                            <?php if (!empty($departments["CS"])): ?>
                                <?php foreach ($departments["CS"] as $room): ?>
                                    <?php
                                    $data = $rooms_data[$room['id']] ?? null;
                                    if (!$data) continue;
                                    ?>
                                    <div class="card shadow-sm mb-3">
                                        <img src="../img/CS/<?php echo $room["room_number"]; ?>/CS_<?php echo $room["room_number"]; ?>_001.jpg"
                                            alt="Room Image">

                                        <div class="card-body">
                                            <p class="card-text mb-1 fs-4 fw-bold text-primary"><?php echo $room["room_number"]; ?></p>

                                            <!-- Total Bookings -->
                                            <p class="fs-6 fw-bold">
                                                Total Bookings:
                                                <span class="fw-normal">
                                                    <?php echo htmlspecialchars($data['total_bookings']); ?>
                                                </span>
                                            </p>
                                            <!-- Number of Equipments -->
                                            <p class="fs-6 fw-bold">
                                                Number of Equipments:
                                                <span class="fw-normal">
                                                    <?php echo htmlspecialchars($data['num_of_equipments']); ?>
                                                </span>
                                            </p>
                                            <!-- Occupancy Rate -->
                                            <p class="fs-6 fw-bold">
                                                Overall Occupancy Rate:
                                                <span class="fw-normal">
                                                    <?php echo number_format($overall_occupancy_rate, 2); ?>%
                                                </span>
                                            </p>

                                            <!-- Room Status -->
                                            <p class="fs-5 fw-bold">Status:
                                                <span class="badge bg-<?php echo ($room["room_status"] == "Available") ? "success" : "danger"; ?>">
                                                    <?php echo $room["room_status"]; ?>
                                                </span>
                                            </p>

                                            <!-- View all ratings - Button -->
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="btn-group">
                                                    <a href="./room_details.php?id=<?php echo $room["id"]; ?>" class="btn btn-sm btn-outline-primary">
                                                        View Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <!-- NE Department -->
                        <div id="ne-department" class="col">
                            <div class="card mb-4 rounded-3 shadow-sm">
                                <div class="card-header py-3">
                                    <h4 class="my-0 fw-normal">NE Department Rooms</h4>
                                </div>
                            </div>

                            <!-- NE Rooms -->
                            <?php if (!empty($departments["NE"])): ?>
                                <?php foreach ($departments["NE"] as $room): ?>
                                    <?php
                                    $data = $rooms_data[$room['id']] ?? null;
                                    if (!$data) continue;
                                    ?>
                                    <div class="card shadow-sm mb-3">
                                        <img src="../img/NE/<?php echo $room["room_number"]; ?>/NE_<?php echo $room["room_number"]; ?>_001.jpg"
                                            alt="Room Image">

                                        <div class="card-body">
                                            <p class="card-text mb-1 fs-4 fw-bold text-primary"><?php echo $room["room_number"]; ?></p>

                                            <!-- Total Bookings -->
                                            <p class="fs-6 fw-bold">
                                                Total Bookings:
                                                <span class="fw-normal">
                                                    <?php echo htmlspecialchars($data['total_bookings']); ?>
                                                </span>
                                            </p>
                                            <!-- Number of Equipments -->
                                            <p class="fs-6 fw-bold">
                                                Number of Equipments:
                                                <span class="fw-normal">
                                                    <?php echo htmlspecialchars($data['num_of_equipments']); ?>
                                                </span>
                                            </p>
                                            <!-- Occupancy Rate -->
                                            <p class="fs-6 fw-bold">
                                                Overall Occupancy Rate:
                                                <span class="fw-normal">
                                                    <?php echo number_format($overall_occupancy_rate, 2); ?>%
                                                </span>
                                            </p>

                                            <!-- Room Status -->
                                            <p class="fs-5 fw-bold">Status:
                                                <span class="badge bg-<?php echo ($room["room_status"] == "Available") ? "success" : "danger"; ?>">
                                                    <?php echo $room["room_status"]; ?>
                                                </span>
                                            </p>

                                            <!-- View all ratings - Button -->
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="btn-group">
                                                    <a href="./room_details.php?id=<?php echo $room["id"]; ?>" class="btn btn-sm btn-outline-primary">
                                                        View Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <?php include "../views/footer.php" ?>
</body>

</html>