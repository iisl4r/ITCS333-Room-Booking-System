<?php
session_start();

$roomNumber = isset($_POST['class']) ? htmlspecialchars($_POST['class']) : '';

if (empty($roomNumber)) {
    $_SESSION['missing_room'] = 'Missing room number';
    header('Location: ../booking.php');
    exit;
}

require 'db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth.php");
    exit;
}

// Ensure all required fields are provided
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (empty($_POST['date']) || empty($_POST['time']) || empty($_POST['duration'])) {
        die('Please fill all fields.');
    }
}

$date = $_POST['date'];
$time = $_POST['time'];
$duration = intval($_POST['duration']);
$class = $_POST['class'];

// Ensure valid date
$currentDate = date("Y-m-d");
$currentTime = date("H:i");
if (strtotime($date) < strtotime($currentDate)) {
    $_SESSION['date_error'] = "Selected date is in the past.";
    header("Location: ../booking.php");
    exit;
}

// Ensure valid time if the user chose the current date
if (strtotime($date) === strtotime($currentDate) && strtotime($time) < strtotime($currentTime)) {
    $_SESSION["time_error"] = "Selected time is in the past.";
    header("Location: ../booking.php");
    exit;
}

// Ensure booking is within the room's available slots
$s = $db->prepare("SELECT * FROM rooms WHERE room_number = :id");
$s->execute([":id" => $class]);
$room = $s->fetch(PDO::FETCH_ASSOC);
if (!$room) {
    $_SESSION['missing_room'] = 'Room not found.';
    header("Location: ../booking.php");
    exit;
}

$start = strtotime($room['available_start']);
$end = strtotime($room['available_end']);
$input_start = strtotime($time);
$input_end = $input_start + $duration * 60;
if ($input_start < $start || $input_end > $end) {
    $_SESSION["time_error"] = "Selected time is outside room availability.";
    header("Location: ../booking.php");
    exit;
}

// Check for exact duplicate bookings
$start_time = strtotime($date . ' ' . $time);
$duplicateQuery = $db->prepare("SELECT * FROM booking WHERE class_id = :classId AND booking_date = :inputDate AND start_time = :startTime");
$duplicateQuery->execute([
    ":classId" => $class,
    ":inputDate" => $date,
    ":startTime" => date("H:i:s", $start_time),
]);
if ($duplicateQuery->rowCount() > 0) {
    $_SESSION['time_conflic'] = "This time slot is already booked.";
    header("Location: ../booking.php");
    exit;
}

// Retrieve existing bookings to check for time conflicts
$query = $db->prepare("SELECT * FROM booking WHERE class_id = :classId AND booking_date = :inputDate");
$query->execute([":classId" => $class, ":inputDate" => $date]);
$data = $query->fetchAll(PDO::FETCH_ASSOC);

$end_time = $start_time + ($duration * 60);
$conflict = false;
if (!empty($data)) {
    foreach ($data as $row) {
        $rowstart = strtotime($row['booking_date'] . " " . $row['start_time']);
        $rowend = $rowstart + ($row['duration'] * 60);

        // Check for overlapping time slots
        if ($start_time < $rowend && $end_time > $rowstart) {
            $conflict = true;
            break;
        }
    }
}

if ($conflict) {
    $_SESSION['time_conflic'] = "There is a conflict with another booking.";
    header("Location: ../booking.php");
    exit;
} else {
    // Add the new booking to the database
    $formatted_time = (new DateTime($time))->format('H:i:s');
    $end_time_formatted = (new DateTime($formatted_time))->add(new DateInterval('PT' . $duration . 'M'))->format('H:i:s');

    $add = $db->prepare("INSERT INTO booking (user_id, class_id, booking_date, start_time, duration, end_time, booking_status) VALUES
    (:user_id, :class_id, :booking_date, :booking_time, :booking_duration, :end_time, :booking_status)");
    $add->execute([
        ":user_id" => $_SESSION['user_id'],
        "class_id" => $class,
        ":booking_date" => $date,
        ":booking_time" => $formatted_time,
        ":booking_duration" => $duration,
        ":end_time" => $end_time_formatted,
        "booking_status" => "active"
    ]);

    $_SESSION['successful_booking'] = "Booking successful!";
    header("Location: ../booking.php");
    exit;
}