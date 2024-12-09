<?php
session_start();
require 'db.php';

// Check if the request method is POST (form submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trim any extra spaces from the fname input
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check if both fname and password are provided
    if (empty($email) || empty($password)) {
        $_SESSION['login_msg'] = "Both name and password are required.";
        header("Location: ../auth.php");
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
            $_SESSION['login_msg'] = "Invalid year or ID range in UoB email.";
            header("Location: ../auth.php");
            exit;
        }
    } else {
        $_SESSION['login_msg'] = "Invalid UoB email format.";
        header("Location: ../auth.php");
        exit;
    }


    try {
        // Prepare a SQL statement to fetch the user from the database by email
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if a user was found and if the password matches
        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fname'] = $user['fname'];
            $_SESSION['role'] = $user['role'];

            // Set a cookie for the fname
            setcookie('user', $user['fname'], time() + (60 * 30), "/");

            $redirectUrl = $_SESSION['previous_page'] ?? "../index.php";
            unset($_SESSION['previous_page']);
            header("Location: $redirectUrl");
            exit;
        } else {
            $_SESSION['login_msg'] = "Invalid email or password.";
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
