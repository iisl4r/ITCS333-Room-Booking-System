<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp">
<link rel="stylesheet" href="/ITCS333-Room-Booking-System/css/header.css">

<header class="sticky">
    <div class="logo">
        <span class="material-icons">
            <img src="/ITCS333-Room-Booking-System/img/logo.png" width="60" height="60" alt="Logo">
        </span>
    </div>
    <nav class="nav-links">
        <ul>

            <li><a href="#">Home</a></li>
            <li><a href="#">Browse Rooms</a></li>
            <li><a href="#">My Bookings</a></li>
            <!-- Show Admin Panel only if the user is an admin -->
            <?php
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li><a href="../views/adminPanel.php">Admin Panel</a></li>
            <?php endif; ?>

            <li class="ste"><a href="#">Sign in</a></li>
        </ul>
    </nav>
    <div class="auth">
        <?php


        // Check if the user is signed in (session contains user_id)
        if (isset($_SESSION['user_id'])):
        ?>
            <!-- Sign Out Link -->
            <a href="../php/logout.php" class="sign-in">Logout
                <span class="material-symbols-sharp">
                    logout
                </span>
            </a>
        <?php else: ?>
            <!-- Sign In Link -->
            <a href="../auth.html" class="sign-in">Sign in
                <span class="material-symbols-sharp">
                    arrow_right_alt
                </span>
            </a>
        <?php endif; ?>
    </div>
    <div class="hamburger" id="hamburger">
        <span class="material-symbols-sharp">
            menu
        </span>
    </div>
</header>


<script src="/ITCS333-Room-Booking-System/js/header.js"></script>