<?php
require 'db.php';
require 'updateStatus.php';

session_start();
if (!isset($_COOKIE['user'])) {
    // Cookie is missing or expired, redirect to the authentication page
    $_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
    header("Location: /ITCS333-Room-Booking-System/auth.php");
    exit();
}
// Function to determine the status of a room
function determineRoomStatus($db, $roomId)
{
    try {
        // Dynamically generate current date and time in the correct formats
        $currentDate = date('Y-m-d'); // Correct format: YYYY-MM-DD
        $currentTime = date('H:i:s'); // Correct format: HH:MM:SS

        $sql = "SELECT * FROM booking 
                WHERE class_id = :roomId 
                AND booking_status = 'active' 
                AND booking_date = :currentDate 
                AND start_time <= :currentTime    
                AND end_time >= :currentTime";

        $statement = $db->prepare($sql);
        $statement->bindParam(':roomId', $roomId, PDO::PARAM_STR);
        $statement->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);
        $statement->bindParam(':currentTime', $currentTime, PDO::PARAM_STR);
        $statement->execute();

        $booking = $statement->fetch(PDO::FETCH_ASSOC);

        return $booking ? "Occupied" : "Available";
    } catch (PDOException $e) {
        echo "Error while checking room status: " . $e->getMessage();
        die;
    }
}

// Function to update room status in the rooms table
function updateRoomStatus($db, $roomId, $status)
{
    try {
        $sql = "UPDATE rooms SET room_status = :status WHERE room_number = :roomId";
        $statement = $db->prepare($sql);
        $statement->bindParam(':status', $status, PDO::PARAM_STR);
        $statement->bindParam(':roomId', $roomId, PDO::PARAM_STR);
        $statement->execute();
    } catch (PDOException $e) {
        echo "Error while updating room status: " . $e->getMessage();
        die;
    }
}

try {
    // Fetching rooms details
    $sql = "SELECT id, room_number, department FROM rooms";
    $statement = $db->prepare($sql);
    $statement->execute();
    $rooms = $statement->fetchAll(PDO::FETCH_ASSOC);

    if (isset($rooms)) {
        $departments = [
            "IS" => [],
            "CS" => [],
            "NE" => []
        ];

        foreach ($rooms as $room) {
            $room_department = $room["department"];
            $room_status = determineRoomStatus($db, $room["room_number"]);

            // Update the status in the database
            updateRoomStatus($db, $room["room_number"], $room_status);

            // Add the status to the room details for display
            $room["room_status"] = $room_status;
            $departments[$room_department][] = $room;
        }
    }
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
    <title>Rooms</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

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
</head>

<body>
    <!-- Header -->

    <?php include "../views/header.php" ?>


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
                        <div class="card shadow-sm mb-3">
                            <img src="../img/IS/<?php echo $room["room_number"]; ?>/IS_<?php echo $room["room_number"]; ?>_001.jpg"
                                alt="Room Image">

                            <div class="card-body">
                                <p class="card-text mb-1">Room: <?php echo $room["room_number"]; ?></p>
                                <p class="card-text mb-1">Current Status: <span
                                        class="<?php echo ($room["room_status"] == "Available") ? "text-success" : "text-danger"; ?>">
                                        <?php echo $room["room_status"]; ?></span></p>

                                <!-- View Details - Button -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="room_details.php?id=<?php echo $room["id"]; ?>"
                                            class="btn btn-sm btn-outline-<?php echo ($room["room_status"] == "Available") ? "success" : "danger"; ?>">
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
                        <div class="card shadow-sm mb-3">
                            <img src="../img/CS/<?php echo $room["room_number"]; ?>/CS_<?php echo $room["room_number"]; ?>_001.jpg"
                                alt="Room Image">

                            <div class="card-body">
                                <p class="card-text mb-1">Room: <?php echo $room["room_number"]; ?></p>
                                <p class="card-text mb-1">Current Status: <span
                                        class="<?php echo ($room["room_status"] == "Available") ? "text-success" : "text-danger"; ?>">
                                        <?php echo $room["room_status"]; ?></span></p>

                                <!-- View Details - Button -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="room_details.php?id=<?php echo $room["id"]; ?>"
                                            class="btn btn-sm btn-outline-<?php echo ($room["room_status"] == "Available") ? "success" : "danger"; ?>">
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
                        <div class="card shadow-sm mb-3">
                            <img src="../img/NE/<?php echo $room["room_number"]; ?>/NE_<?php echo $room["room_number"]; ?>_001.jpg"
                                alt="Room Image">

                            <div class="card-body">
                                <p class="card-text mb-1">Room: <?php echo $room["room_number"]; ?></p>
                                <p class="card-text mb-1">Current Status: <span
                                        class="<?php echo ($room["room_status"] == "Available") ? "text-success" : "text-danger"; ?>">
                                        <?php echo $room["room_status"]; ?></span></p>

                                <!-- View Details - Button -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="room_details.php?id=<?php echo $room["id"]; ?>"
                                            class="btn btn-sm btn-outline-<?php echo ($room["room_status"] == "Available") ? "success" : "danger"; ?>">
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

    <!-- Footer -->
    <?php include "../views/footer.php" ?>

    <style>
        .header-img {
            margin-right: 15px;
        }

        .header {
            margin-left: 50px;
            margin-bottom: 20px;
        }
    </style>

    <!-- Bootstrap Javascript Link For Functionality -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>