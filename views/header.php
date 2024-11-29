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
            <li><a href="#">Admin Panel</a></li>
            <li class="ste"><a href="#">Sign in</a></li>
        </ul>
    </nav>
    <div class="auth">
        <?php
        // Start the session to check if the user is logged in
        session_start();

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