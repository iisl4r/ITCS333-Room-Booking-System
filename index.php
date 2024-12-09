<?php
session_start();

// Check if the 'user' cookie exists
if (!isset($_COOKIE['user'])) {
    // If the cookie is missing or expired, redirect to the authentication page
    header("Location: /ITCS333-Room-Booking-System/auth.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f0f8ff;
            color: #333;
        }

        h1 {
            font-size: 2.5em;
            color: #007acc;
        }

        p {
            font-size: 1.2em;
            margin: 15px 0;
        }

        .logout-btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 1em;
            color: #fff;
            background-color: #007acc;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #005f99;
        }
    </style>
</head>

<body>
    <h1>Welcome to Our Website!</h1>
    <p>We're glad to have you here, <?= htmlspecialchars($_COOKIE['user']); ?>! Explore and enjoy your stay!</p>

    <!-- Logout Button -->
    <a href="php/logout.php" class="logout-btn">Logout</a>
</body>

</html>