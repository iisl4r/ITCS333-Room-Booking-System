<?php
session_start();
require 'db.php';

// Check if the request method is POST (form submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trim any extra spaces from the fname input
    $fname = trim($_POST['fname']);
    
    // Get the password from the form input
    $password = $_POST['password'];

    // Check if both fname and password are provided
    if (empty($fname) || empty($password)) {
        die("Both fname and password are required.");
    }

    // Validate that the fname only contains alpahbet characters and underscores
    if (!preg_match('/^[a-zA-Z]+$/', $fname)) {
        die("Invalid name format. Only alpahbet characters and underscores are allowed.");
    }

    try {
        // Prepare a SQL statement to fetch the user from the database by fname
        $stmt = $db->prepare("SELECT * FROM users WHERE fname = :fname");
        
        // Execute the query with the provided fname
        $stmt->execute([':fname' => $fname]);
        
        // Fetch the user record as an associative array
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if a user was found and if the password matches the hashed password in the database
        if ($user && password_verify($password, $user['password_hash'])) {
            // Regenerate the session ID to prevent session fixation attacks
            session_regenerate_id(true);

            // Store user details in the session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fname'] = $user['fname'];
            $_SESSION['role'] = $user['role'];

            // Set a cookie to store the fname for 2 minutes
            setcookie('user', $user['fname'], time() + (60 * 30), "/"); // 2 minutes

            // Redirect user based on their role (admin or regular user)
            if ($user['role'] === 'admin') {
                header("Location: /ITCS333-Room-Booking-System/indexAdmin.php");
            } else {
                header("Location: /ITCS333-Room-Booking-System/index.php");
            }
            exit(); // Ensure no further code is executed after redirection
        } else {
            // If fname or password is incorrect, display an error message
            die("Invalid name or password.");
        }
    } catch (PDOException $e) {
        // Catch any database connection errors and display a generic error message
        die("An unexpected error occurred. Please try again later.");
    }
}
?>
