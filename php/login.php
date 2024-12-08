<?php
session_start();
require 'db.php';

// Check if the request method is POST (form submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trim any extra spaces from the fname input
    $fname = trim($_POST['fname']);
    $password = $_POST['password'];

    // Check if both fname and password are provided
    if (empty($fname) || empty($password)) {
        $_SESSION['login_msg'] = "Both name and password are required.";
        header("Location: ../auth.php");
        exit;
    }

    // Validate that the fname only contains alphabet characters
    if (!preg_match('/^[a-zA-Z]+$/', $fname)) {
        $_SESSION['login_msg'] = "Invalid name format. Only alphabet characters are allowed.";
        header("Location: ../auth.php");
        exit;
    }

    try {
        // Prepare a SQL statement to fetch the user from the database by fname
        $stmt = $db->prepare("SELECT * FROM users WHERE fname = :fname");
        $stmt->execute([':fname' => $fname]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if a user was found and if the password matches
        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fname'] = $user['fname'];
            $_SESSION['role'] = $user['role'];

            // Set a cookie for the fname
            setcookie('user', $user['fname'], time() + (60 * 30), "/");

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: ../indexAdmin.php");
            } else {
                header("Location: ../index.php");
            }
            exit;
        } else {
            $_SESSION['login_msg'] = "Invalid name or password.";
            header("Location: ../auth.php");
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['login_msg'] = "An unexpected error occurred. Please try again later.";
        header("Location: ../auth.php");
        exit;
    }
}
?>