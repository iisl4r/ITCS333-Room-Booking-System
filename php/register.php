<?php
session_start();
require 'db.php';

// Check if the request method is POST (form submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and trim user input from the form
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $secondpass = $_POST['secondpass'];

    // Ensure that no fields are empty
    if (empty($username) || empty($email) || empty($password) || empty($secondpass)) {
        die("All fields are required."); // Stop execution and display error message
    }

    // Validate the username to only allow alphanumeric characters and underscores
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        die("Invalid username format. Only alphanumeric characters and underscores are allowed.");
    }

    // Validate that the email is in the correct UoB format (using regex)
    $emailPattern = '/^\d{9}@(uob\.edu\.bh|stu\.uob\.edu\.bh)$/';
    if (!preg_match($emailPattern, $email)) {
        die("Invalid UoB email format.");
    }

    // Check if the passwords match
    if ($password !== $secondpass) {
        die("Passwords do not match.");
    }

    // Ensure that the username does not exceed 16 characters
    if (strlen($username) > 16) {
        die("The username length should not exceed 16 characters.");
    }

    // Validate the password requirements: at least 8 characters, 1 uppercase letter, and 1 number
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        die("Password must be at least 8 characters long, include an uppercase letter, and a number.");
    }

    // Hash the password for secure storage
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Prepare an SQL statement to insert the new user into the 'users' table
        $stmt = $db->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)");

        // Execute the statement with user data
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password_hash' => $passwordHash,
        ]);

        // Redirect the user to the authentication page after successful registration
        header("Location: /ITCS333-Room-Booking-System/auth.html");
        exit; // Stop further script execution after redirect
    } catch (PDOException $e) {
        // Handle database errors (like unique constraint violation)
        if ($e->getCode() == 23000) {
            // Check if the error is due to a duplicate username
            if (str_contains($e->getMessage(), 'username')) {
                die("The username is already taken.");
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
