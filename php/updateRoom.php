<?php
include "../php/db.php";  // Ensure that the connection is correctly included

// Check if the POST request contains the required data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Capture form data
    $roomId = $_POST['roomId'];  // The room's unique ID, passed from the form
    $department = $_POST['department'];
    $roomCapacity = $_POST['roomCapacity'];
    $equipment = $_POST['equipment'];
    $roomFloor = $_POST['roomFloor'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $roomNumber = $_POST['roomNumber'];  // The unique room number
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
              WHERE id = :roomId";  // Update using the 'id' column, not 'room_id'

    // Prepare the SQL statement
    $stmt = $db->prepare($query);

    // Bind parameters to the query
    $stmt->bindParam(':roomId', $roomId);        // Binding the room ID (unique identifier)
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':capacity', $roomCapacity);
    $stmt->bindParam(':equipment', $equipment);
    $stmt->bindParam(':roomFloor', $roomFloor);
    $stmt->bindParam(':startTime', $startTime);
    $stmt->bindParam(':endTime', $endTime);
    $stmt->bindParam(':roomNumber', $roomNumber);  // Ensure room_number is passed correctly
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
