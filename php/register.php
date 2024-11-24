<?php
session_start();
require 'db.php';

// Check if the request method is POST (form submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and trim user input from the form
    $fname = trim($_POST['fname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $secondpass = $_POST['secondpass'];

    // Ensure that no fields are empty
    if (empty($fname) || empty($email) || empty($password) || empty($secondpass)) {
        die("All fields are required."); // Stop execution and display error message
    }

    // Validate the fname to only allow alpahbet characters and underscores
    if (!preg_match('/^[a-zA-Z]+$/', $fname)) {
        die("Invalid name format. Only alpahbet characters are allowed.");
    }

// Validate that the email is in the correct UoB format (using regex)
$emailPattern = '/^(\d{9})@(uob\.edu\.bh|stu\.uob\.edu\.bh)$/';
if (preg_match($emailPattern, $email, $matches)) {
    // Extract the first 9 digits
    $yearAndId = $matches[1];
    
    // Extract year (first 4 digits) and ID (last 5 digits)
    $year = intval(substr($yearAndId, 0, 4));
    $id = intval(substr($yearAndId, 4, 5));
    
    // Validate year and ID range
    if (
        !($year >= 2015 && $year <= 2024) || // Year is not between 2015 and 2024
        !($id >= 1 && $id <= 11111)          // ID is not between 00001 and 11111
    ) {
        die("Invalid year or ID range in UoB email.");
    }
} else {
    // Invalid email format
    die("Invalid UoB email format.");
}


    // Check if the passwords match
    if ($password !== $secondpass) {
        die("Passwords do not match.");
    }

    // Ensure that the fname does not exceed 16 characters
    if (strlen($fname) > 16) {
        die("The name length should not exceed 16 characters.");
    }

    // Validate the password requirements: at least 8 characters, 1 uppercase letter, and 1 number
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        die("Password must be at least 8 characters long, include an uppercase letter, and a number.");
    }

    // Hash the password for secure storage
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Prepare an SQL statement to insert the new user into the 'users' table
        $stmt = $db->prepare("INSERT INTO users (fname, email, password_hash) VALUES (:fname, :email, :password_hash)");

        // Execute the statement with user data
        $stmt->execute([
            ':fname' => $fname,
            ':email' => $email,
            ':password_hash' => $passwordHash,
        ]);

        // Redirect the user to the authentication page after successful registration
        header("Location: /ITCS333-Room-Booking-System/auth.html");
        exit; // Stop further script execution after redirect
    } catch (PDOException $e) {
        // Handle database errors (like unique constraint violation)
        if ($e->getCode() == 23000) {
            // Check if the error is due to a duplicate fname
            if (str_contains($e->getMessage(), 'fname')) {
                die("The name is already taken.");
            // Check if the error is due to a duplicate email
            } elseif (str_contains($e->getMessage(), 'email')) {
                die("The email is already registered.");
            } else {
                die("A unique constraint violation occurred.");
            }
        } else {
            // Catch any other unexpected errors
            die("An unexpected error occurred. Please try again later.");
        }
    }
}
?>