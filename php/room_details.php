<?php
require "db.php";
require 'updateStatus.php';

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
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <div class="container py-4">

        <!-- Header -->
        <?php include "../views/header.php" ?>

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
                <div class="row mb-3">

                    <!-- Room Information -->
                    <div class="col-md-6">
                        <div class="card p-3 my-3">
                            <h4 class="card-title mb-4 text-center text-primary fs-4">Room Information</h4>
                            <p class="fs-5"><strong>Department:</strong> <?php echo $room["department"]; ?></p>
                            <p class="fs-5"><strong>Floor:</strong> <?php echo $room["room_floor"]; ?></p>
                            <p class="fs-5"><strong>Capacity:</strong> <?php echo $room["capacity"]; ?> people</p>
                            <p class="fs-5"><strong>Available From:</strong> <?php echo $room["available_start"]; ?></p>
                            <p class="fs-5"><strong>Available Until:</strong> <?php echo $room["available_end"]; ?></p>
                        </div>
                    </div>

                    <!-- Room Image -->
                    <div class="col-md-6">
                        <div class="card p-3 my-3">
                            <div class="card-title text-center">
                                <!-- Placeholder Image -->
                                <svg class="bd-placeholder-img card-img-top" width="100%" height="245px" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                                    <title>Placeholder</title>
                                    <rect width="100%" height="100%" fill="#55595c"></rect>
                                    <text x="50%" y="50%" fill="#eceeef" text-anchor="middle" alignment-baseline="middle">Thumbnail</text>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div>
                    <div class="d-flex justify-content-center align-items-center mt-2">
                        <?php if ($room["room_status"] == "Available"): ?>
                            <form action="booking.php" method="POST">
                                <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                                <button type="submit" class="btn btn-success me-2">Book Now!</button>
                            </form>
                        <?php else: ?>
                            <button type="button" class="btn btn-danger me-2" disabled>Room is occupied</button>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

        <div class="row align-items-md-stretch">

            <!-- Room Equipments -->
            <div class="col-md-6">
                <div class="h-100 p-4 text-bg-primary rounded-3">
                    <!-- Room Equipments -->
                    <h2 class="card-title mb-4">Equipments</h2>
                    <ul>
                        <?php
                        // Split equipment string into an array
                        $equipment_list = explode(',', $room["equipments"]);

                        foreach ($equipment_list as $equipment):
                        ?>
                            <li><?php echo htmlspecialchars(trim($equipment)); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Room Guidelines -->
            <div class="col-md-6">
                <div class="h-100 p-4 bg-body-tertiary border rounded-3">
                    <h2>Room Guidelines</h2>
                    <ul>
                        <li>Eating and drinking inside the room is not allowed.</li>
                        <li>Smoking is strictly prohibited.</li>
                        <li>Please clean the room after use.</li>
                        <li>Switch off all lights, equipment, and air conditioning when leaving.</li>
                        <li>Adhere to the room's designated booking schedule strictly.</li>
                        <li>Don't forget your personal belongings left when you left the room.</li>
                    </ul>
                </div>
            </div>


            <!-- Footer -->
            <?php include "../views/footer.php" ?>

        </div>

        <style>
            .header-img {
                margin-right: 15px;
            }
        </style>
        <!-- Bootstrap Javascript Link For Functionality -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
