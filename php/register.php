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
        $_SESSION['registration_error'] = "All fields are required.";
        header("Location: ../auth.php#container2");
        exit;
    }

    // Validate the fname to only allow alphabet characters and underscores
    if (!preg_match('/^[a-zA-Z]+$/', $fname)) {
        $_SESSION['registration_error'] = "Invalid name format. Only alphabet characters are allowed.";
        header("Location: ../auth.php#container2");
        exit;
    }

    // Validate that the email is in the correct UoB format (using regex)
    $emailPattern = '/^(\d{9})@(uob\.edu\.bh|stu\.uob\.edu\.bh)$/';
    if (preg_match($emailPattern, $email, $matches)) {
        $yearAndId = $matches[1];
        $year = intval(substr($yearAndId, 0, 4));
        $id = intval(substr($yearAndId, 4, 5));

        if (
            !($year >= 2015 && $year <= 2024) || 
            !($id >= 1 && $id <= 11111)
        ) {
            $_SESSION['registration_error'] = "Invalid year or ID range in UoB email.";
            header("Location: ../auth.php#container2");
            exit;
        }
    } else {
        $_SESSION['registration_error'] = "Invalid UoB email format.";
        header("Location: ../auth.php#container2");
        exit;
    }

    // Check if the passwords match
    if ($password !== $secondpass) {
        $_SESSION['registration_error'] = "Passwords do not match.";
        header("Location: ../auth.php#container2");
        exit;
    }

    // Ensure that the fname does not exceed 16 characters
    if (strlen($fname) > 16) {
        $_SESSION['registration_error'] = "The name length should not exceed 16 characters.";
        header("Location: ../auth.php#container2");
        exit;
    }

    // Validate the password requirements
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $_SESSION['registration_error'] = "Password must be at least 8 characters long, include an uppercase letter, and a number.";
        header("Location: ../auth.php#container2");
        exit;
    }

    // Hash the password for secure storage
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Prepare SQL statement to insert user into the database
        $stmt = $db->prepare("INSERT INTO users (fname, email, password_hash) VALUES (:fname, :email, :password_hash)");
        $stmt->execute([
            ':fname' => $fname,
            ':email' => $email,
            ':password_hash' => $passwordHash,
        ]);

        // Redirect to authentication page after successful registration
        $_SESSION['login_msg'] = "Registration successful. Please log in.";
        header("Location: ../auth.php");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            if (str_contains($e->getMessage(), 'fname')) {
                $_SESSION['registration_error'] = "The name is already taken.";
            } elseif (str_contains($e->getMessage(), 'email')) {
                $_SESSION['registration_error'] = "The email is already registered.";
            } else {
                $_SESSION['registration_error'] = "A unique constraint violation occurred.";
            }
        } else {
            $_SESSION['registration_error'] = "An unexpected error occurred. Please try again later.";
        }
        header("Location: ../auth.php#container2");
        exit;
    }
}
?>