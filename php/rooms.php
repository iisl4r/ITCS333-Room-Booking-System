<?php
require 'db.php';
require 'updateStatus.php';

try {
    // Fetching rooms details
    $sql = "SELECT id, room_number, department, room_status FROM rooms";
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
</head>

<body>
    <!-- Header -->
    <div class="header">
        <?php include "../views/header.php" ?>
    </div>

    <!-- Department Buttons -->
    <!-- <div class="container text-center my-4">
        <a href="rooms.php" class="btn <?php echo is_null($selectedDepartment) ? 'btn-primary' : 'btn-secondary'; ?> mx-2">All Departments</a>
        <a href="rooms.php?department=Information System" class="btn <?php echo ($selectedDepartment === 'Information System') ? 'btn-primary' : 'btn-secondary'; ?> mx-2">Information System</a>
        <a href="rooms.php?department=Computer Science" class="btn <?php echo ($selectedDepartment === 'Computer Science') ? 'btn-primary' : 'btn-secondary'; ?> mx-2">Computer Science</a>
        <a href="rooms.php?department=Network Engineering" class="btn <?php echo ($selectedDepartment === 'Network Engineering') ? 'btn-primary' : 'btn-secondary'; ?> mx-2">Network Engineering</a>
    </div> -->

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

                <!-- ======= IS Rooms ======= -->
                <?php if (!empty($departments["IS"])): ?>
                    <?php foreach ($departments["IS"] as $room): ?>
                        <div class="card shadow-sm mb-3">
                            <svg class="bd-placeholder-img card-img-top" width="100%" height="225px" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                                <title>Placeholder</title>
                                <rect width="100%" height="100%" fill="#55595c"></rect>
                                <text x="50%" y="50%" fill="#eceeef" text-anchor="middle" alignment-baseline="middle">Thumbnail</text>
                            </svg>

                            <div class="card-body">
                                <p class="card-text mb-1">Room: <?php echo $room["room_number"]; ?></p>

                                <!-- Room Status -->
                                <?php if ($room["room_status"] == "Available"): ?>
                                    <p class="card-text mb-1">Status: <span class="text-success"><?php echo $room["room_status"]; ?></span></p>

                                <?php elseif ($room["room_status"] == "Occupied"): ?>
                                    <p class="card-text mb-1">Status: <span class="text-danger"><?php echo $room["room_status"]; ?></span></p>

                                <?php endif; ?>

                                <!-- View Details - Button -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="room_details.php?id=<?php echo $room["id"]; ?>" class="btn btn-sm btn-outline-<?php echo ($room["room_status"] == "Available") ? "success" : "danger"; ?>">
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

                <!-- ======= CS Rooms ======= -->
                <?php if (!empty($departments["CS"])): ?>
                    <?php foreach ($departments["CS"] as $room): ?>
                        <div class="card shadow-sm mb-3">
                            <svg class="bd-placeholder-img card-img-top" width="100%" height="225px" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                                <title>Placeholder</title>
                                <rect width="100%" height="100%" fill="#55595c"></rect>
                                <text x="50%" y="50%" fill="#eceeef" text-anchor="middle" alignment-baseline="middle">Thumbnail</text>
                            </svg>

                            <div class="card-body">
                                <p class="card-text mb-1">Room: <?php echo $room["room_number"]; ?></p>

                                <!-- Room Status -->
                                <?php if ($room["room_status"] == "Available"): ?>
                                    <p class="card-text mb-1">Status: <span class="text-success"><?php echo $room["room_status"]; ?></span></p>

                                <?php elseif ($room["room_status"] == "Occupied"): ?>
                                    <p class="card-text mb-1">Status: <span class="text-danger"><?php echo $room["room_status"]; ?></span></p>

                                <?php endif; ?>

                                <!-- View Details - Button -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="room_details.php?id=<?php echo $room["id"]; ?>" class="btn btn-sm btn-outline-<?php echo ($room["room_status"] == "Available") ? "success" : "danger"; ?>">
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

                <!-- ======= NE Rooms ======= -->
                <?php if (!empty($departments["NE"])): ?>
                    <?php foreach ($departments["NE"] as $room): ?>
                        <div class="card shadow-sm mb-3">
                            <svg class="bd-placeholder-img card-img-top" width="100%" height="225px" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                                <title>Placeholder</title>
                                <rect width="100%" height="100%" fill="#55595c"></rect>
                                <text x="50%" y="50%" fill="#eceeef" text-anchor="middle" alignment-baseline="middle">Thumbnail</text>
                            </svg>

                            <div class="card-body">
                                <p class="card-text mb-1">Room: <?php echo $room["room_number"]; ?></p>

                                <!-- Room Status -->
                                <?php if ($room["room_status"] == "Available"): ?>
                                    <p class="card-text mb-1">Status: <span class="text-success"><?php echo $room["room_status"]; ?></span></p>

                                <?php elseif ($room["room_status"] == "Occupied"): ?>
                                    <p class="card-text mb-1">Status: <span class="text-danger"><?php echo $room["room_status"]; ?></span></p>

                                <?php endif; ?>

                                <!-- View Details - Button -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="room_details.php?id=<?php echo $room["id"]; ?>" class="btn btn-sm btn-outline-<?php echo ($room["room_status"] == "Available") ? "success" : "danger"; ?>">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
