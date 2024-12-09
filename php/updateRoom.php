<?php
include "../php/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Capture form data
    $roomId = $_POST['roomId'];
    $department = $_POST['department'];
    $roomCapacity = $_POST['roomCapacity'];
    $equipment = $_POST['equipment'];
    $roomFloor = $_POST['roomFloor'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $roomNumber = $_POST['roomNumber'];
    $roomStatus = $_POST['roomStatus'];

    // Prepare SQL to update the room details
    $query = "UPDATE rooms SET 
              department = :department, 
              capacity = :capacity, 
              equipments = :equipment, 
              room_floor = :roomFloor, 
              available_start = :startTime, 
              available_end = :endTime, 
              room_number = :roomNumber, 
              room_status = :status 
              WHERE id = :roomId";

    // Prepare the SQL statement
    $stmt = $db->prepare($query);

    // Bind parameters to the query
    $stmt->bindParam(':roomId', $roomId);
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':capacity', $roomCapacity);
    $stmt->bindParam(':equipment', $equipment);
    $stmt->bindParam(':roomFloor', $roomFloor);
    $stmt->bindParam(':startTime', $startTime);
    $stmt->bindParam(':endTime', $endTime);
    $stmt->bindParam(':roomNumber', $roomNumber);
    $stmt->bindParam(':status', $roomStatus);

    // Execute the query and check for success
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        // Output error if the query fails
        $errorInfo = $stmt->errorInfo();
        echo json_encode(['success' => false, 'error' => $errorInfo]);
    }
}
