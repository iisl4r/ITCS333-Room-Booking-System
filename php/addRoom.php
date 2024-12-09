<?php
include '../php/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collecting form data
    $department = $_POST['department'] ?? null;
    $roomNumber = $_POST['roomNumber'] ?? null;
    $capacity = $_POST['roomCapacity'] ?? null;
    $equipment = $_POST['equipment'] ?? null;
    $roomFloor = $_POST['roomFloor'] ?? null;
    $startTime = $_POST['startTime'] ?? null;
    $endTime = $_POST['endTime'] ?? null;
    $roomStatus = $_POST['roomStatus'] ?? null;

    // Validate required fields
    if (!$department || !$capacity || !$equipment || !$roomFloor || !$startTime || !$endTime || !$roomStatus || !$roomNumber) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    // Check if the room number already exists
    try {
        $stmt = $db->prepare("SELECT COUNT(*) FROM rooms WHERE room_number = :roomNumber");
        $stmt->bindParam(':roomNumber', $roomNumber);
        $stmt->execute();
        $roomExists = $stmt->fetchColumn();

        if ($roomExists) {
            echo json_encode(['success' => false, 'message' => 'Room number already exists.']);
            exit;
        }

        // Insert new room into the database
        $stmt = $db->prepare("INSERT INTO rooms (department, room_floor, capacity, equipments, available_start, available_end, room_number, room_status)
                              VALUES (:department, :roomFloor, :capacity, :equipment, :startTime, :endTime, :roomNumber, :roomStatus)");
        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':roomFloor', $roomFloor);
        $stmt->bindParam(':capacity', $capacity);
        $stmt->bindParam(':equipment', $equipment);
        $stmt->bindParam(':startTime', $startTime);
        $stmt->bindParam(':endTime', $endTime);
        $stmt->bindParam(':roomNumber', $roomNumber);
        $stmt->bindParam(':roomStatus', $roomStatus);

        $stmt->execute();

        $roomId = $db->lastInsertId();

        echo json_encode([
            'success' => true,
            'message' => 'Room added successfully',
            'room' => [
                'roomId' => $roomId,
                'roomNumber' => $roomNumber,
                'department' => $department,
                'capacity' => $capacity,
                'equipment' => $equipment,
                'roomFloor' => $roomFloor,
                'startTime' => $startTime,
                'endTime' => $endTime,
                'roomStatus' => $roomStatus
            ]
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error adding room: ' . $e->getMessage()]);
    }
}
