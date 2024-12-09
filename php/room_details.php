<?php
require "db.php";
require 'updateStatus.php';
session_start();
if (!isset($_COOKIE['user'])) {
    // Cookie is missing or expired, redirect to the authentication page
    $_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
    header("Location: /ITCS333-Room-Booking-System/auth.php");
    exit();
}
function determineRoomStatus($db, $roomId)
{
    try {
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

try {
    if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
        echo "Invalid room ID.";
        die;
    }

    $sql = "SELECT * FROM rooms WHERE id = ?";
    $statement = $db->prepare($sql);
    $statement->execute([$_GET["id"]]);

    $room = $statement->fetch(PDO::FETCH_ASSOC);
    if (!isset($room)) {
        echo "Room not found.";
        die;
    }
    // Determine the real-time status of the room
    $room["room_status"] = determineRoomStatus($db, $room["room_number"]);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die;
}

function formatTime($time)
{
    $dateTime = DateTime::createFromFormat('H:i:s', $time);
    return $dateTime ? $dateTime->format('h:i A') : $time;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <?php include "../views/header.php" ?>
    <div class="container py-4">



        <!-- May need some styling -->
        <div class="p-3 mb-4 bg-body-tertiary rounded-3">
            <div class="container-fluid p-3">
                <h1 class="display-5 fw-bold text-center"><?php echo $room["room_number"]; ?> | Room Details</h1>

                <!-- Room Status -->
                <h5 class="text-center card-subtitle my-3 text-muted">Status:
                    <span class="badge bg-<?php echo ($room["room_status"] == "Available") ? "success" : "danger"; ?>">
                        <?php echo $room["room_status"]; ?>
                    </span>
                </h5>

                <!-- Room Details -->
                <div class="container my-4">

                    <!-- Row: Room Information and Carousel -->
                    <div class="row mb-4">
                        <!-- Room Information Card -->
                        <div class="col-md-6">
                            <div class="card p-3">
                                <h4 class="card-title mb-4 text-center text-primary fs-4">Room Information</h4>
                                <p class="fs-5"><strong>Department:</strong> <?php echo $room["department"]; ?></p>
                                <p class="fs-5"><strong>Floor:</strong> <?php echo $room["room_floor"]; ?></p>
                                <p class="fs-5"><strong>Capacity:</strong> <?php echo $room["capacity"]; ?> people</p>
                                <p class="fs-5"><strong>Available From:</strong> <?php echo formatTime($room["available_start"]); ?></p>
                                <p class="fs-5"><strong>Available Until:</strong> <?php echo formatTime($room["available_end"]); ?></p>
                            </div>
                        </div>

                        <!-- Room Image Carousel -->
                        <div class="col-md-6">
                            <div class="card p-3">
                                <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active" data-bs-interval="4000">
                                            <img class="d-block w-100 carousel-img"
                                                src="../img/<?php echo $room["department"]; ?>/<?php echo $room["room_number"]; ?>/<?php echo $room["department"]; ?>_<?php echo $room["room_number"]; ?>_001.jpg"
                                                alt="Room Image">
                                        </div>
                                        <div class="carousel-item" data-bs-interval="4000">
                                            <img class="d-block w-100 carousel-img"
                                                src="../img/<?php echo $room["department"]; ?>/<?php echo $room["room_number"]; ?>/<?php echo $room["department"]; ?>_<?php echo $room["room_number"]; ?>_002.jpg"
                                                alt="Room Image">
                                        </div>
                                        <div class="carousel-item">
                                            <img class="d-block w-100 carousel-img"
                                                src="../img/<?php echo $room["department"]; ?>/<?php echo $room["room_number"]; ?>/<?php echo $room["department"]; ?>_<?php echo $room["room_number"]; ?>_003.jpg"
                                                alt="Room Image">
                                        </div>
                                    </div>
                                    <button class="carousel-control-prev" type="button"
                                        data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row: Equipment and Guidelines -->
                    <div class="row">
                        <!-- Equipment -->
                        <div class="col-md-6">
                            <div class="card p-4 shadow-sm">
                                <h4 class="card-title mb-4 text-center text-success fs-3">Equipment</h4>
                                <ul class="list-group">
                                    <?php
                                    // Define mapping for equipment icons
                                    $equipment_icons = [
                                        'Whiteboard' => 'bi-easel',
                                        'Projector' => 'bi-projector-fill',
                                        'Speaker' => 'bi-speaker-fill',
                                        'Laptop' => 'bi-laptop-fill',
                                        'Microphone' => 'bi-mic-fill',
                                        'Monitors' => 'bi-display-fill'
                                    ];

                                    // Split equipment string into an array
                                    $equipment_list = explode(',', $room["equipments"]);

                                    foreach ($equipment_list as $equipment):
                                        $equipment = trim($equipment);
                                        // Get the corresponding icon or fallback icon
                                        $icon_class = $equipment_icons[$equipment] ?? 'bi-question-circle';
                                    ?>
                                        <li class="list-group-item d-flex align-items-center mb-3 bg-light rounded">
                                            <i class="<?php echo $icon_class; ?> text-primary me-3 fs-4"></i>
                                            <span
                                                class="fs-5 fw-semibold"><?php echo htmlspecialchars($equipment); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>

                        <!-- Room Guidelines -->
                        <div class="col-md-6">
                            <div class="card p-3">
                                <h4 class="card-title mb-4 text-center text-warning fs-3">Room Guidelines</h4>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="bi bi-x-circle-fill text-danger me-2"></i> Eating and drinking inside
                                        the room is not allowed.
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="bi bi-slash-circle-fill text-warning me-2"></i> Smoking is strictly
                                        prohibited.
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="bi bi-check-square-fill text-primary me-2"></i> Please clean the room
                                        after use.
                                    </li>

                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="bi bi-lightbulb-off-fill text-secondary me-2"></i> Switch off all
                                        lights, equipment, and air conditioning when leaving.
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="bi bi-calendar-check-fill text-success me-2"></i> Adhere to the room's
                                        designated booking schedule strictly.
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="bi bi-bag-fill text-info me-2"></i> Don't forget your personal
                                        belongings when you leave the room.
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>

                </div>

                <style>
                    .carousel-img {
                        height: 100%;
                        /* Matches the card's height */
                        object-fit: cover;
                        /* Ensures images fit the space proportionally */
                    }

                    .card {
                        height: 100%;
                        /* Ensures all cards are consistent in height */
                    }
                </style>
            </div>

            <!-- Action Button -->
            <div>
                <div class="d-flex justify-content-center align-items-center mt-2">
                    <?php if ($room["room_status"] == "Available"): ?>
                        <form action="../booking.php" method="POST">
                            <input type="hidden" name="class" value="<?php echo $room['room_number']; ?>">
                            <button type="submit" class="btn btn-success me-2">Book Now!</button>
                        </form>
                    <?php else: ?>
                        <button type="button" class="btn btn-danger me-2" disabled>Room is occupied</button>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>


    <!-- Footer -->
    <?php include "../views/footer.php" ?>


    <style>
        .header-img {
            margin-right: 15px;
        }
    </style>
    <!-- Bootstrap Javascript Link For Functionality -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>