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
                    <a href="#" class="nav-link">Edit Profile</a>
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
                        <div class="col-12 col-md-6 col-lg-4">
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
                                            <p class="fs-4 fw-bold mb-0 ms-2">30</p>
                                            <span class="text-success small pt-1 fw-bold">5%</span>
                                            <span class="text-muted small pt-2 ps-1">increase</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End of Total Rooms -->

                        <!-- Available Rooms -->
                        <div class="col-12 col-md-6 col-lg-4">
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
                                            <p class="fs-4 fw-bold mb-0 ms-2">13</p>
                                            <span class="text-success small pt-1 fw-bold">16%</span>
                                            <span class="text-muted small pt-2 ps-1">increase</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End of Available Rooms -->

                        <!-- Most Booked Room -->
                        <div class="col-12 col-md-6 col-lg-4">
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
                                            <p class="fs-4 fw-bold mb-0 ms-2">S40-1006</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End of Most Booked Room -->

                    </div> <!-- End of Cards -->

                    <!-- Booking Line Chart Report -->
                    <div class="col-12">
                        <div class="card">
                            <div class="dropdown filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#" id="filter-today">Today</a></li>
                                    <li><a class="dropdown-item" href="#" id="filter-month">This Month</a></li>
                                    <li><a class="dropdown-item" href="#" id="filter-year">This Year</a></li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Booking Report <span id="filter-title">| Today</span></h5>
                                <div id="bookingLineChart"></div>

                                <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        // Helper function to generate dates for the entire month of December
                                        function generateDecemberDates() {
                                            const dates = [];
                                            for (let day = 1; day <= 31; day++) {
                                                const date = new Date(2024, 11, day); // 11 represents December (0 = January, 11 = December)
                                                dates.push(date.toISOString().split('T')[0] + 'T00:00:00.000Z'); // Ensure the time is 00:00:00
                                            }
                                            return dates;
                                        }

                                        // Fake data for the full month of December (1st to 31st)
                                        const fakeData = Array.from({
                                            length: 31
                                        }, (_, i) => {
                                            return {
                                                totalRooms: Math.floor(Math.random() * 15) + 5, // Random total rooms between 5 and 20
                                                availableRooms: Math.floor(Math.random() * 10) + 1, // Random available rooms between 1 and 10
                                                occupiedRooms: Math.floor(Math.random() * 10) + 1, // Random occupied rooms between 1 and 10
                                            };
                                        });

                                        // Filter Data
                                        const filterData = {
                                            today: {
                                                series: [{
                                                        name: 'Total Rooms',
                                                        data: [5, 8] // Data for 8 AM and 9 PM
                                                    },
                                                    {
                                                        name: 'Available Rooms',
                                                        data: [3, 4] // Available rooms data for 8 AM and 9 PM
                                                    },
                                                    {
                                                        name: 'Occupied Rooms',
                                                        data: [2, 4] // Occupied rooms data for 8 AM and 9 PM
                                                    }
                                                ],
                                                categories: [
                                                    "2024-12-06T08:00:00.000Z", // 8 AM
                                                    "2024-12-06T21:00:00.000Z" // 9 PM
                                                ]
                                            },
                                            month: {
                                                series: [{
                                                        name: 'Total Rooms',
                                                        data: fakeData.map(item => item.totalRooms)
                                                    },
                                                    {
                                                        name: 'Available Rooms',
                                                        data: fakeData.map(item => item.availableRooms)
                                                    },
                                                    {
                                                        name: 'Occupied Rooms',
                                                        data: fakeData.map(item => item.occupiedRooms)
                                                    }
                                                ],
                                                categories: generateDecemberDates() // Generate dates for December (1st to 31st)
                                            },
                                            year: {
                                                series: [{
                                                        name: 'Total Rooms',
                                                        data: [120, 100, 110, 130, 125, 140, 135, 125, 145, 150, 160, 170]
                                                    },
                                                    {
                                                        name: 'Available Rooms',
                                                        data: [40, 30, 50, 60, 55, 70, 65, 60, 75, 80, 85, 90]
                                                    },
                                                    {
                                                        name: 'Occupied Rooms',
                                                        data: [60, 70, 80, 90, 85, 90, 95, 100, 105, 110, 115, 120]
                                                    }
                                                ],
                                                categories: [
                                                    "January", "February", "March", "April", "May", "June",
                                                    "July", "August", "September", "October", "November", "December"
                                                ]
                                            }
                                        };

                                        // Initialize Chart with "Today" as Default
                                        const chart = new ApexCharts(document.querySelector("#bookingLineChart"), {
                                            series: filterData.today.series,
                                            chart: {
                                                height: 350,
                                                type: 'area',
                                                toolbar: {
                                                    show: false
                                                }
                                            },
                                            markers: {
                                                size: 4
                                            },
                                            colors: ['#5c7bd9', '#9fe080', '#fe7979'],
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
                                                type: 'datetime', // Keep datetime for Today and This Month
                                                categories: filterData.today.categories
                                            },
                                            tooltip: {
                                                x: {
                                                    format: 'dd/MM/yyyy HH:mm'
                                                }
                                            }
                                        });

                                        chart.render();

                                        // Event Listeners for Filters
                                        document.querySelector("#filter-today").addEventListener("click", (e) => {
                                            e.preventDefault(); // Prevent page reload
                                            updateChart("Today", filterData.today);
                                        });

                                        document.querySelector("#filter-month").addEventListener("click", (e) => {
                                            e.preventDefault(); // Prevent page reload
                                            updateChart("This Month", filterData.month);
                                        });

                                        document.querySelector("#filter-year").addEventListener("click", (e) => {
                                            e.preventDefault(); // Prevent page reload
                                            updateChart("This Year", filterData.year);
                                        });

                                        // Update Chart Function
                                        function updateChart(filterTitle, data) {
                                            document.querySelector("#filter-title").innerText = `| ${filterTitle}`;

                                            if (filterTitle === "This Year") {
                                                // Update x-axis to use months as categories (non-datetime)
                                                chart.updateOptions({
                                                    xaxis: {
                                                        type: 'category', // Change to 'category' for the months
                                                        categories: data.categories // Month names as categories
                                                    }
                                                });
                                            } else {
                                                chart.updateOptions({
                                                    xaxis: {
                                                        type: 'datetime', // Keep datetime for Today and This Month
                                                        categories: data.categories
                                                    }
                                                });
                                            }

                                            chart.updateSeries(data.series);
                                        }
                                    });
                                </script>
                            </div>
                        </div>

                    </div> <!-- End of Booking Line Chart Report -->

                </div> <!-- End of left side column -->

                <!-- Right side column -->
                <div class="col-xxl-4 col-xl-12">

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

                            <div id="bookingDonutChart" style="min-height: 400px;" class="echart">
                                <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        echarts.init(document.querySelector("#bookingDonutChart")).setOption({
                                            tooltip: {
                                                trigger: 'item'
                                            },
                                            legend: {
                                                top: '5%',
                                                left: 'center'
                                            },
                                            series: [{
                                                name: 'This Year',
                                                type: 'pie',
                                                radius: ['40%', '70%'],
                                                avoidLabelOverlap: false,
                                                label: {
                                                    show: false,
                                                    position: 'center'
                                                },
                                                emphasis: {
                                                    label: {
                                                        show: true,
                                                        fontSize: '18',
                                                        fontWeight: 'bold'
                                                    }
                                                },
                                                labelLine: {
                                                    show: false
                                                },
                                                data: [{
                                                        value: 1284,
                                                        name: 'Total Booking'
                                                    },
                                                    {
                                                        value: 970,
                                                        name: 'Confirmed'
                                                    },
                                                    {
                                                        value: 84,
                                                        name: 'Canceled'
                                                    }
                                                ]
                                            }]
                                        });
                                    });
                                </script>
                            </div>

                        </div>
                    </div> <!-- Booking Donut Chart Report -->

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

                            <div id="occupancyChart" style="min-height: 400px;" class="echart">
                                <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        // Sample data for room occupancy
                                        const totalRooms = 150; // Total number of rooms
                                        const occupiedRooms = 120; // Number of rooms occupied
                                        const availableRooms = totalRooms - occupiedRooms; // Available rooms

                                        // Initialize ECharts for the pie chart
                                        echarts.init(document.querySelector("#occupancyChart")).setOption({
                                            tooltip: {
                                                trigger: 'item'
                                            },
                                            legend: {
                                                top: '5%',
                                                left: 'center'
                                            },
                                            series: [{
                                                name: 'Rooms Occupancy',
                                                type: 'pie',
                                                radius: '55%',
                                                data: [{
                                                        value: occupiedRooms,
                                                        name: 'Occupied Rooms',
                                                        itemStyle: {
                                                            color: '#ff7070' // Custom color for Occupied Rooms (Tomato)
                                                        }
                                                    },
                                                    {
                                                        value: availableRooms,
                                                        name: 'Available Rooms',
                                                        itemStyle: {
                                                            color: '#9fe080' // Custom color for Available Rooms (LimeGreen)
                                                        }
                                                    }
                                                ],
                                                label: {
                                                    show: true,
                                                    formatter: '{b}: {d}%'
                                                }
                                            }]
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </div> <!-- Room Occupancy Pie Chart Report -->

                </div>
            </div>

            <div class="col-lg4">
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Charts -->
    <script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Footer -->
    <?php include "../views/footer.php" ?>
</body>

</html>