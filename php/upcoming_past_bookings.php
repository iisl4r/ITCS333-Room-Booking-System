<?php
session_start();

try {
    require "db.php";

    if (!isset($_COOKIE['user'])) {
        // Cookie is missing or expired, redirect to the authentication page
        $_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
        header("Location: /ITCS333-Room-Booking-System/auth.php");
        exit();
    }

    $userId = $_SESSION["user_id"];
    $currentDate = date("Y-m-d");

    // Upcoming Bookings
    $upcomingSQL = "
        SELECT r.room_number, r.room_floor,
        r.department, b.id, b.booking_date,
        b.start_time, b.end_time, b.duration,
        b.booking_status
        FROM rooms r
        JOIN booking b ON r.room_number = b.class_id
        WHERE b.user_id = ? AND b.booking_date >= ? AND b.booking_status != 'Canceled'
        ORDER BY b.booking_date ASC
    ";

    $upcomingStatement = $db->prepare($upcomingSQL);
    $upcomingStatement->execute([$userId, $currentDate]);
    $upcomingBookings = $upcomingStatement->fetchAll(PDO::FETCH_ASSOC);

    // Past Bookings
    $pastSQL = "
        SELECT r.room_number, r.room_floor,
        r.department, b.booking_date,
        b.start_time, b.end_time, b.duration
        FROM rooms r
        JOIN booking b ON r.room_number = b.class_id
        WHERE b.user_id = ? AND b.booking_date < ?
        ORDER BY b.booking_date DESC
    ";

    $pastStatement = $db->prepare($pastSQL);
    $pastStatement->execute([$userId, $currentDate]);
    $pastBookings = $pastStatement->fetchAll(PDO::FETCH_ASSOC);

    // Canceled Bookings
    $canceledBookingsSQL = "
    SELECT COUNT(b.id) AS canceled_count
    FROM booking b
    WHERE LOWER(b.booking_status) = 'canceled' AND b.user_id = ?
    ";
    $canceledBookingsSQLStatement = $db->prepare($canceledBookingsSQL);
    $canceledBookingsSQLStatement->execute([$userId]);
    $canceledBookings = $canceledBookingsSQLStatement->fetch(PDO::FETCH_ASSOC)['canceled_count'];
} catch (PDOException $e) {
    echo "Error while fetching booking data: " . $e->getMessage();
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
    <title>Room Ratings</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="../css/analysis.css">
</head>

<body>
    <?php include "../views/header.php" ?>

    <div class="main row g-0">

        <div id="sidebar" class="container col-12 col-md-3 col-lg-2 p-3 mb-3 shadow-sm">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a href="./analysis.php" class="nav-link">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="./room_analysis.php" class="nav-link">Room Analysis</a>
                </li>
                <li class="nav-item">
                    <a href="./upcoming_past_bookings.php" class="nav-link active">Upcoming & Past Bookings</a>
                </li>
                <li class="nav-item">
                    <a href="./edit_profile.php" class="nav-link">Edit Profile</a>
                </li>
            </ul>
        </div>

        <main id="dashboard" class="col-12 col-md-9 col-lg-10">
            <div class="row row-cols-<?php echo empty($canceledBookings['canceled_count']) ? "2" : "3"; ?> row-cols-md-2 mb-3 text-center">

                <div class="col-12 col-lg-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4 class="my-0">Upcoming Bookings</h4>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($upcomingBookings)): ?>
                                <ul class="list-group">
                                    <?php foreach ($upcomingBookings as $upcomingBooking): ?>
                                        <li class="list-group-item text-center mb-3 border rounded fs-6">
                                            <strong class="text-primary fs-5"><?php echo $upcomingBooking["room_number"]; ?></strong> <br>
                                            <strong>Room Floor: </strong> <?php echo $upcomingBooking["room_floor"]; ?> <br>
                                            <strong>Room Department: </strong> <?php echo $upcomingBooking["department"]; ?> <br>
                                            <strong>Booking Date: </strong> <?php echo $upcomingBooking["booking_date"]; ?> <br>
                                            <strong>Start Time: </strong> <?php echo formatTime($upcomingBooking['start_time']); ?> <br>
                                            <strong>End Time: </strong> <?php echo formatTime($upcomingBooking['end_time']); ?> <br>
                                            <strong>Duration: </strong> <?php echo $upcomingBooking['duration'] . ' minutes'; ?> <br><br>
                                            <strong>
                                                <form action="./cancellation.php" method="POST">
                                                    <input type="hidden" name="booking_id" value="<?php echo $upcomingBooking['id']; ?>">
                                                    <button type="submit" class="btn btn-danger me-2">Cancel Booking</button>
                                                </form>
                                            </strong>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="align-middle">You don't have upcoming bookings yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4 class="my-0">Past Bookings</h4>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($pastBookings)): ?>
                                <ul class="list-group">
                                    <?php foreach ($pastBookings as $pastBooking): ?>
                                        <li class="list-group-item text-center mb-3 border rounded fs-6">
                                            <strong class="text-primary fs-5"><?php echo $pastBooking["room_number"]; ?></strong> <br>
                                            <strong>Room Floor: </strong> <?php echo $pastBooking["room_floor"]; ?> <br>
                                            <strong>Room Department: </strong> <?php echo $pastBooking["department"]; ?> <br>
                                            <strong>Booking Date: </strong> <?php echo $pastBooking["booking_date"]; ?> <br>
                                            <strong>Start Time: </strong> <?php echo formatTime($pastBooking['start_time']); ?> <br>
                                            <strong>End Time: </strong> <?php echo formatTime($pastBooking['end_time']); ?> <br>
                                            <strong>Duration: </strong> <?php echo $pastBooking['duration'] . ' minutes'; ?> <br>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p>You don't have past bookings yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if (!empty($canceledBookings['canceled_count'])): ?>
                    <div class="col-12 col-lg-6">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="my-0">Past Bookings</h4>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($pastBookings)): ?>
                                    <ul class="list-group">
                                        <?php foreach ($pastBookings as $pastBooking): ?>
                                            <li class="list-group-item text-center mb-3 border rounded fs-6">
                                                <strong class="text-primary fs-5"><?php echo $pastBooking["room_number"]; ?></strong> <br>
                                                <strong>Room Floor: </strong> <?php echo $pastBooking["room_floor"]; ?> <br>
                                                <strong>Room Department: </strong> <?php echo $pastBooking["department"]; ?> <br>
                                                <strong>Booking Date: </strong> <?php echo $pastBooking["booking_date"]; ?> <br>
                                                <strong>Start Time: </strong> <?php echo formatTime($pastBooking['start_time']); ?> <br>
                                                <strong>End Time: </strong> <?php echo formatTime($pastBooking['end_time']); ?> <br>
                                                <strong>Duration: </strong> <?php echo $pastBooking['duration'] . ' minutes'; ?> <br>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <?php include "../views/footer.php" ?>
</body>

</html>