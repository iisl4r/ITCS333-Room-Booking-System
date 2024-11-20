<?php
try {
    $dsn = "mysql:host=localhost;dbname=Room_System;charset=utf8mb4";
    $db = new PDO($dsn, "root", ""); 
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
