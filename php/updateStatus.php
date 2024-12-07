<?php
function updateStatus($room_id, $new_status)
{
    require 'db.php';

    try {
        $sql = "UPDATE rooms SET room_status = :new_status WHERE id = :room_id";
        $statement = $db->prepare($sql);
        $statement->bindParam(':new_status', $new_status);
        $statement->bindParam(':room_id', $room_id);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error while updating room status: " . $e->getMessage();
        return false;
    }
}
?>
