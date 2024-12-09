<?php

include_once 'db.php';

header('Content-Type: application/json');

// Get the input data (roomId)
$data = json_decode(file_get_contents('php://input'), true);
$roomId = $data['roomId'];

if ($roomId) {
    try {
        // Prepare the SQL query to delete the room
        $query = "DELETE FROM rooms WHERE id = :roomId";
        $stmt = $db->prepare($query);

        // Bind the roomId to the parameter
        $stmt->bindParam(':roomId', $roomId, PDO::PARAM_INT);

        // Execute the query
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete room']);
        }
    } catch (PDOException $e) {
        // Catch any errors and output them
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Room ID is required']);
}
