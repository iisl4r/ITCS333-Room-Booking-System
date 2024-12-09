<?php
session_start();
// Check if the 'user' cookie exists
if (!isset($_COOKIE['user'])) {
    // Cookie is missing or expired, redirect to the authentication page
    $_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
    header("Location: /ITCS333-Room-Booking-System/auth.php");
    exit();
}

// Verify that the session has a valid role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // If not admin, redirect to the regular user home page
    header("Location: /ITCS333-Room-Booking-System/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp" />
    <link rel="stylesheet" href="../css/admin.css">

</head>

<body>
    <?php include "../views/header.php" ?>
    <div class="container">
        <div class="top">
            <button id="menu-btn">
                <span class="material-symbols-sharp">menu</span>
            </button>
        </div>
        <aside>
            <div class="close" id="close-btn">
                <span class="material-symbols-sharp">close</span>
            </div>

            <div class="sidebar">
                <a href="#" class="menu-item  active" data-section="dashboard-section">
                    <span class="material-symbols-sharp">
                        grid_view
                    </span>
                    <h3>Dashboard</h3>
                </a>

                <a href="#" class="menu-item" data-section="rooms-section">
                    <span class="material-symbols-sharp">
                        table_rows
                    </span>
                    <h3>Rooms Management</h3>
                </a>


                <a href="#" class="menu-item" data-section="feedback-section">
                    <span class="material-symbols-sharp">
                        reviews
                    </span>
                    <h3>Feedback</h3>
                </a>
                <a href="../php/logout.php">
                    <span class="material-symbols-sharp">
                        logout
                    </span>
                    <h3>Logout</h3>
                </a>
            </div>
        </aside>

        <!-- main -->
        <main>
            <div class="content-section active" id="dashboard-section">

                <div class="widget-container">
                    <!-- Widget 1 -->
                    <?php
                    // fetch number of rooms
                    include "../php/db.php";
                    $totalRoomsQuery = "SELECT COUNT(*) as total_rooms FROM rooms";
                    $stmt = $db->prepare($totalRoomsQuery);
                    $stmt->execute();
                    $totalRoomsResult = $stmt->fetch(PDO::FETCH_ASSOC);
                    $totalRooms = $totalRoomsResult['total_rooms'];
                    ?>
                    <div class="widget">
                        <div class="icon">
                            <svg width="24" height="24" viewBox="0 0 43 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M40.64 15.7094H2V11.3784C2 8.30475 2.51893 5.82988 5.59256 5.82988H12.638C13.7395 4.62322 15.0805 3.65943 16.5754 3C18.0702 2.34058 19.6862 2 21.32 2C22.9538 2 24.5698 2.34058 26.0646 3C27.5595 3.65943 28.9005 4.62322 30.002 5.82988H37.0474C40.1211 5.82988 40.64 8.30475 40.64 11.3784V15.7094Z"
                                    stroke="#0A5239" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M11.7797 15.7094H5.85202V37.2049H11.7797V15.7094Z" stroke="#0A5239"
                                    stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M36.788 15.7094H30.8602V37.2049H36.788V15.7094Z" stroke="#0A5239" stroke-width="4"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M2.97797 43.911H39.662" stroke="#0A5239" stroke-width="4" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="content">
                            <div class="value"><?php echo $totalRooms; ?></div>
                            <div class="extra">

                                <div class="manager">Total Rooms</div>
                            </div>
                        </div>
                    </div>

                    <!-- Widget 2 -->
                    <div class="widget">
                        <div class="icon">
                            <svg width="58" height="42" viewBox="0 0 58 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M29 28C19.7797 28 12 29.4455 12 35.0242C12 40.6053 19.83 42 29 42C38.2203 42 46 40.5545 46 34.9758C46 29.3947 38.17 28 29 28Z"
                                    fill="#0A5239" />
                                <path opacity="0.4"
                                    d="M29 22C35.1049 22 40 17.1049 40 11C40 4.89279 35.1049 0 29 0C22.8951 0 18 4.89279 18 11C18 17.1049 22.8951 22 29 22Z"
                                    fill="#0A5239" />
                                <path opacity="0.4"
                                    d="M52.6974 14.0146C54.2276 7.68484 49.7413 2 44.0286 2C43.4074 2 42.8135 2.07193 42.2332 2.19421C42.1561 2.21339 42.0699 2.25415 42.0246 2.32608C41.9725 2.41719 42.011 2.53947 42.0677 2.61859C43.7838 5.1649 44.7699 8.26507 44.7699 11.593C44.7699 14.7819 43.8654 17.755 42.2785 20.2222C42.1153 20.4763 42.2604 20.8192 42.5483 20.8719C42.9473 20.9463 43.3553 20.9846 43.7724 20.9966C47.9323 21.1117 51.666 18.2801 52.6974 14.0146Z"
                                    fill="#0A5239" />
                                <path
                                    d="M57.4889 28.4318C56.6828 26.6344 54.7369 25.4019 51.7785 24.7969C50.3821 24.4405 46.6031 23.9384 43.088 24.0062C43.0353 24.0137 43.0065 24.0514 43.0017 24.0765C42.9945 24.1116 43.0089 24.1719 43.0784 24.2095C44.7028 25.0505 50.9819 28.7079 50.1925 36.422C50.1589 36.7558 50.4157 37.0445 50.7348 36.9943C52.28 36.7634 56.2557 35.8697 57.4889 33.0858C58.1704 31.6148 58.1704 29.9053 57.4889 28.4318Z"
                                    fill="#0A5239" />
                                <path opacity="0.4"
                                    d="M15.7657 2.19421C15.1876 2.06953 14.5914 2 13.9702 2C8.25732 2 3.77086 7.68484 5.30338 14.0146C6.33261 18.2801 10.0664 21.1117 14.2264 20.9966C14.6436 20.9846 15.0539 20.9439 15.4506 20.8719C15.7385 20.8192 15.8836 20.4763 15.7204 20.2222C14.1335 17.7526 13.2289 14.7819 13.2289 11.593C13.2289 8.26267 14.2174 5.16251 15.9335 2.61859C15.9879 2.53947 16.0287 2.41719 15.9743 2.32608C15.929 2.25175 15.8451 2.21339 15.7657 2.19421Z"
                                    fill="#0A5239" />
                                <path
                                    d="M6.22116 24.795C3.26264 25.4001 1.31909 26.6328 0.512881 28.4304C-0.17096 29.9042 -0.17096 31.6139 0.512881 33.0877C1.7462 35.8695 5.72207 36.7658 7.26732 36.9943C7.58644 37.0445 7.84078 36.7583 7.80719 36.4218C7.01777 28.7091 13.2971 25.0511 14.9239 24.21C14.9911 24.1698 15.0055 24.1121 14.9983 24.0744C14.9935 24.0493 14.9671 24.0117 14.9143 24.0067C11.3968 23.9364 7.62003 24.4385 6.22116 24.795Z"
                                    fill="#0A5239" />
                            </svg>

                        </div>
                        <div class="content">
                            <?php
                            include "../php/db.php";
                            $totalBookingsQuery = "SELECT COUNT(*) AS total FROM booking";
                            $stmt = $db->prepare($totalBookingsQuery);
                            $stmt->execute();
                            $totalBookingsResult = $stmt->fetch(PDO::FETCH_ASSOC);
                            $totalBookings = $totalBookingsResult['total'];
                            ?>
                            <div class="value"><?= $totalBookings ?></div>
                            <div class="extra">

                                <div class="manager">Total Bookings</div>

                            </div>
                        </div>
                    </div>

                    <!-- Widget 3 -->
                    <div class="widget">
                        <div class="icon">
                            <svg width="41" height="39" viewBox="0 0 41 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M31.6992 39C31.2121 39 30.7273 38.8513 30.3101 38.5552L20.5002 31.5601L10.6904 38.5552C10.284 38.8459 9.79556 39.0015 9.29487 38.9998C8.79418 38.9981 8.30684 38.8391 7.90244 38.5456C7.49766 38.2537 7.19574 37.8424 7.03965 37.3701C6.88355 36.8978 6.88122 36.3886 7.033 35.9149L10.6916 24.1742L0.969847 17.3627C0.56694 17.067 0.267719 16.6529 0.114506 16.1789C-0.0387062 15.7049 -0.0381539 15.1949 0.116085 14.7212C0.271541 14.2487 0.572421 13.8367 0.976201 13.5434C1.37998 13.2502 1.86624 13.0905 2.36625 13.087L14.4069 13.069L18.2404 1.61967C18.3986 1.14801 18.7021 0.73779 19.1077 0.447104C19.5134 0.156419 20.0008 0 20.5009 0C21.0009 0 21.4883 0.156419 21.894 0.447104C22.2996 0.73779 22.6031 1.14801 22.7613 1.61967L26.5296 13.069L38.6318 13.087C39.1324 13.0898 39.6194 13.2494 40.0236 13.5431C40.4278 13.8368 40.7286 14.2497 40.8834 14.723C41.0381 15.1964 41.0389 15.7063 40.8856 16.1801C40.7323 16.654 40.4327 17.0678 40.0294 17.3627L30.3077 24.1742L33.9663 35.9149C34.1184 36.3885 34.1163 36.8977 33.9604 37.37C33.8046 37.8423 33.5028 38.2537 33.0981 38.5456C32.6924 38.8414 32.2023 39.0006 31.6992 39Z"
                                    fill="#0A5239" />
                            </svg>

                        </div>
                        <div class="content">
                            <?php
                            include "../php/db.php";
                            $totalUsersQuery = "SELECT COUNT(*) AS users FROM users";
                            $stmt = $db->prepare($totalUsersQuery);
                            $stmt->execute();
                            $totalusersResult = $stmt->fetch(PDO::FETCH_ASSOC);
                            $totalusers = $totalusersResult['users'];
                            ?>
                            <div class="value"><?php echo $totalusers ?></div>
                            <div class="extra">

                                <div class="manager">Total Users</div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="main-container">
                    <?php
                    // Include the database connection
                    include '../php/db.php';

                    // Fetch upcoming bookings from the database
                    try {
                        $query = "SELECT 
                        b.booking_date, 
                        b.start_time, 
                        b.booking_status, 
                        u.fname AS user_name, 
                        b.class_id 
                        FROM booking b
                        JOIN users u ON b.user_id = u.id
                        WHERE b.booking_date >= CURDATE()    
                        ";

                        $stmt = $db->prepare($query);
                        $stmt->execute();
                        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {
                        die("Failed to fetch bookings: " . $e->getMessage());
                    }
                    ?>

                    <div class="booking-section">
                        <h1>Upcoming Bookings</h1>

                        <?php if (!empty($bookings)): ?>
                            <?php foreach ($bookings as $booking): ?>
                                <?php
                                // Determine the CSS class and status text based on booking_status
                                $statusClass = strtolower($booking['booking_status']) === 'active' ? 'confirmed' : 'cancelled';
                                $statusText = ucfirst(strtolower($booking['booking_status']));
                                ?>
                                <div class="booking <?= $statusClass ?>">
                                    <div class="room">
                                        Room <?= $booking['class_id'] ?> -
                                        <?= date("d M, g:i A", strtotime($booking['booking_date'] . ' ' . $booking['start_time'])) ?>
                                    </div>
                                    <div class="status"><?= $statusText ?></div>
                                    <div class="name"><?= htmlspecialchars($booking['user_name']) ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No upcoming bookings.</p>
                        <?php endif; ?>
                    </div>



                    <div class="feedback">
                        <h1>Recent Feedback</h1>
                        <div class="fedback-1">
                            <div class="profile">
                                <img src="../img/profile_pic.png" alt="Mohamed Salah">
                                <div class="name">Mohamed Salah</div>
                            </div>
                            <div class="rating">
                                <div class="rat-contr">
                                    <span class="fa fa-star fa-3x checked"></span>
                                    <span class="fa fa-star fa-3x checked"></span>
                                    <span class="fa fa-star fa-3x checked"></span>
                                    <span class="fa fa-star fa-3x checked"></span>
                                    <span class="fa fa-star fa-3x "></span>
                                </div>
                            </div>
                            <div class="review">
                                Room is clean, well-maintained, and comfortable.
                            </div>
                            <div class="date">Nov 17, 2024</div>
                        </div>


                        <div class="fedback-1">
                            <div class="profile">
                                <img src="../img/profile_pic.png" alt="Hamza">
                                <div class="name">Hamza</div>
                            </div>
                            <div class="rating">
                                <div class="rat-contr">
                                    <span class="fa fa-star fa-3x checked"></span>
                                    <span class="fa fa-star fa-3x checked"></span>
                                    <span class="fa fa-star fa-3x checked"></span>
                                    <span class="fa fa-star fa-3x "></span>
                                    <span class="fa fa-star fa-3x"></span>
                                </div>
                            </div>
                            <div class="review">
                                Room is spacious, tidy, and equipped with all necessary facilities.
                            </div>
                            <div class="date">Nov 12, 2024</div>
                        </div>


                    </div>
                </div>

            </div>


            <div class="content-section" id="rooms-section">

                <div class="add">
                    <a href="#" class="addroom" id="addRoomBtn">Add Room</a>
                </div>
                <!-- 
-->
                <div id="popupForm" class="popup-form">
                    <div class="popup-content">
                        <form id="roomForm">


                            <label for="department">Department</label>
                            <input type="text" id="department" name="department" placeholder="Enter department name">

                            <label for="roomCapacity">Room Capacity</label>
                            <input type="text" id="roomCapacity" name="roomCapacity" placeholder="Enter room capacity">

                            <label for="equipment">Room Equipment</label>
                            <input type="text" id="equipment" name="equipment" placeholder="Enter room equipment">

                            <label for="roomFloor">Room Floor</label>
                            <input type="text" id="roomFloor" name="roomFloor" placeholder="Enter floor no">

                            <label for="startTime">Start Time</label>
                            <input type="time" id="startTime" name="startTime" placeholder="Enter start time">

                            <label for="endTime">End Time</label>
                            <input type="time" id="endTime" name="endTime" placeholder="Enter end time">

                            <label for="roomNumber">Room Number</label>
                            <input type="text" id="roomNumber" name="roomNumber" placeholder="Enter room number">

                            <label for="roomStatus">Room Status</label>
                            <input type="text" id="roomStatus" name="roomStatus" placeholder="Enter room status">


                            <div class="popup-buttons">
                                <button type="button" id="cancelBtn" class="cancel">Cancel</button>
                                <button type="submit" class="save">Add</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Edit Room Form -->
                <div id="editRoomForm" class="popup-form">
                    <div class="popup-content">

                        <form id="editForm">
                            <label for="editRoomId">Room ID</label>
                            <input type="text" id="editRoomId" name="roomId" readonly>

                            <label for="editDepartment">Department</label>
                            <input type="text" id="editDepartment" name="department" placeholder="Enter department name">

                            <label for="editRoomCapacity">Room Capacity</label>
                            <input type="text" id="editRoomCapacity" name="roomCapacity"
                                placeholder="Enter room capacity">

                            <label for="editEquipment">Room Equipment</label>
                            <input type="text" id="editEquipment" name="equipment" placeholder="Enter room equipment">


                            <label for="editRoomFloor">Room Floor</label>
                            <input type="text" id="editRoomFloor" name="roomFloor" placeholder="Enter floor no">


                            <label for="editStartTime">Start Time</label>
                            <input type="time" id="editStartTime" name="startTime" placeholder="Enter start time">

                            <label for="editEndTime">End Time</label>
                            <input type="time" id="editEndTime" name="endTime" placeholder="Enter end time">



                            <label for="editRoomNumber">Room Number</label>
                            <input type="text" id="editRoomNumber" name="roomNumber" readonly>

                            <label for="editRoomStatus">Room Status</label>
                            <input type="text" id="editRoomStatus" name="roomStatus" placeholder="Enter room status">

                            <div class="popup-buttons">
                                <button type="button" id="editCancelBtn" class="cancel">Cancel</button>
                                <button type="submit" class="save">Save</button>
                            </div>
                        </form>
                    </div>
                </div>


                <h1>Room Mangement</h1>
                <?php
                include "../php/db.php";

                // Query to fetch all room data
                $query = "SELECT id, department, capacity, equipments, room_floor, room_number, room_status, available_start, available_end FROM rooms";
                $stmt = $db->prepare($query);
                $stmt->execute();
                $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <div class="table-container">
                    <table class="content-table" id="roomsTable">
                        <thead>
                            <tr>
                                <th>Room ID</th>
                                <th>Department</th>
                                <th>Capacity</th>
                                <th>Equipment</th>
                                <th>Room Floor</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Room Number</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($rooms)) {
                                foreach ($rooms as $room) {
                                    echo "
                                <tr>
                                    <td data-label='Room ID'>{$room['id']}</td>
                                    <td data-label='Department'>{$room['department']}</td>
                                    <td data-label='Capacity'>{$room['capacity']}</td>
                                    <td data-label='Equipment'>{$room['equipments']}</td>
                                    <td data-label='Room Floor'>Floor {$room['room_floor']}</td>
                                    <td data-label='Start Time'>{$room['available_start']}</td>
                                    <td data-label='End Time'>{$room['available_end']}</td>
                                    <td data-label='Room Number'>{$room['room_number']}</td>
                                    <td data-label='Status'>{$room['room_status']}</td>
                                    <td data-label='Actions'>
                                        <span class='material-symbols-sharp more-options'>more_vert</span>
                                        <div class='context-menu hidden'>
                                            <a href='#' class='edit' data-room-index='{$room['id']}'>Edit</a>
                                            <a href='#' class='delete'>Delete</a>
                                        </div>
                                    </td>
                                </tr>
                                ";
                                }
                            } else {
                                echo "
        <tr>
            <td colspan='10'>No rooms available</td>
        </tr>";
                            }
                            ?>
                        </tbody>

                    </table>
                </div>

            </div>



            <div class="content-section" id="feedback-section">
                <div class="feedback">
                    <h1>Recent Feedback</h1>
                    <div class="fedback-1">
                        <div class="profile">
                            <img src="../img/users/elon-musk.jpg" alt="Elon Musk">
                            <div class="name">Elon Musk</div>
                        </div>
                        <div class="rating">
                            <div class="rat-contr">
                                <span class="fa fa-star fa-3x checked"></span>
                                <span class="fa fa-star fa-3x checked"></span>
                                <span class="fa fa-star fa-3x checked"></span>
                                <span class="fa fa-star fa-3x checked"></span>
                                <span class="fa fa-star fa-3x "></span>
                            </div>
                        </div>
                        <div class="review">
                            Wi-Fi slower than a SpaceX rocket launch. Room was fine, but can we get a Tesla charger
                            next
                            time? 🚀🔋
                        </div>
                        <div class="date">Nov 17, 2024</div>
                    </div>


                    <div class="fedback-1">
                        <div class="profile">
                            <img src="../img/users/the-rock.jpg" alt="The Rock">
                            <div class="name">The Rock</div>
                        </div>
                        <div class="rating">
                            <div class="rat-contr">
                                <span class="fa fa-star fa-3x checked"></span>
                                <span class="fa fa-star fa-3x checked"></span>
                                <span class="fa fa-star fa-3x checked"></span>
                                <span class="fa fa-star fa-3x "></span>
                                <span class="fa fa-star fa-3x"></span>
                            </div>
                        </div>
                        <div class="review">
                            Room was great, but where’s the gym? Also, the Wi-Fi? Not fast enough to stream Fast &
                            Furious.
                        </div>
                        <div class="date">Nov 12, 2024</div>
                    </div>


                </div>
            </div>
    </div>

    </main>
    </div>

    <script src="../js/admin.js"></script>

    <?php include "../views/footer.php" ?>
</body>

</html>