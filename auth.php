<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/auth.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;700&family=Outfit:wght@300;700&display=swap"
        rel="stylesheet">
    <title>Authentication</title>
</head>

<body>
    <div class="blur"></div>
    <div class="meteor"></div>
    <div class="meteor"></div>
    <div class="meteor"></div>
    <div class="meteor"></div>
    <div class="meteor"></div>
    <div class="meteor"></div>
    <div class="meteor"></div>
    <div class="meteor"></div>
    <div class="meteor"></div>
    <div class="meteor"></div>
    <!-- Register Container -->
    <div class="container2" id="container2">
        <header>
            <div class="loginTitle">REGISTER</div>
        </header>
        <form method="post" action="php/register.php">
            <div class="loginContainer registerCon">
                <input type="text" name="fname" autocomplete="off" title="Enter your username" maxlength="50"
                    placeholder=" " required>
                <div class="labelline ll1">Enter first name</div>
                <i class='bx bxs-user i1'></i>
                <input type="email" name="email" autocomplete="off" title="Enter your email" maxlength="50"
                    placeholder=" " required>
                <div class="labelline ll2">Enter email</div>
                <i class='bx bxs-envelope i2'></i>
                <input type="password" name="password" title="Enter your password" maxlength="50" placeholder=" "
                    required>
                <div class="labelline ll3">Enter password</div>
                <i class='bx bxs-lock i3'></i>
                <input type="password" class="secondpass" name="secondpass" title="Re-Enter your password"
                    maxlength="50" placeholder=" " required>
                <div class="labelline ll4">Re-Enter password</div>
                <i class='bx bxs-key i4'></i>
            </div>
            <!-- Display error message -->
            <?php
            if (isset($_SESSION['registration_error'])) {
                echo '<div class="errorSpacing">' . htmlspecialchars($_SESSION['registration_error']) . '</div>';
                unset($_SESSION['registration_error']); // Clear the error after displaying it
            }
            ?>
            <div class="butSp"><input type="submit" value="Submit"></div>
            <p>Already have an account? <a href="#container" class="signUp logIn">Login</a></p>
        </form>
    </div>
    <!-- Login Container -->
    <div class="container" id="container">
        <header>
            <div class="loginTitle">LOGIN</div>
        </header>
        <form method="post" action="php/login.php">
            <div class="loginContainer">
                <input type="email" name="email" autocomplete="off" title="Enter your Email" maxlength="50"
                    placeholder=" " required>
                <div class="labelline ll1">Enter your Email</div>
                <i class='bx bxs-user ii1'></i>
                <input type="password" name="password" title="Enter your password" maxlength="50" placeholder=" "
                    required>
                <div class="labelline lll2">Enter password</div>
                <i class='bx bxs-lock ii2'></i>
            </div>
            <!-- Display error message -->
            <?php
            if (isset($_SESSION['login_msg'])) {
                // Check if the message is "Registration successful. Please log in."
                $message = htmlspecialchars($_SESSION['login_msg']);
                $messageColor = ($message === "Registration successful. Please log in.") ? 'green' : 'red'; // You can change this to any color you'd prefer
                echo '<div class="errorSpacing" style="color: ' . $messageColor . ';">' . $message . '</div>';
                unset($_SESSION['login_msg']); // Clear the message after displaying it
            }
            ?>
            <div class="butSp"><input type="submit" value="Submit"></div>
            <p>Don't have an account? <a href="#container2" class="signUp">Sign Up</a></p>
        </form>
    </div>
</body>

</html>