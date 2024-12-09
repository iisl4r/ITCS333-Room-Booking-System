<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking System</title>
    <!--  CSS -->
    <link href="./css/booking.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <!-- header -->
    <?php include "views/header.php"; ?>
    <main>
        <div class="container">
            <!-- Header -->
            <div class="text-center">
                <h1>Booking a class</h1>
                <p class="text-muted">
                    <?php
                    if (isset($_POST['class'])) {
                        echo "You selected class " . htmlspecialchars($_POST['class']);
                    } else {
                        echo "No class selected. Please go back and select a class.";
                    }
                    ?>
                </p>
            </div>
            <!-- Booking Form -->
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="card-title">Book the Class</h3>
                    <form method="POST" action="./php/bookingexe.php">
                        <!-- Select Date -->
                        <div class="mb-3">
                            <label for="date" class="form-label">Select Date</label>
                            <input type="date" name="date" id="date" class="form-control" required>
                        </div>
                        <?php
                        if (isset($_SESSION['missing_room'])) {
                            $error = $_SESSION['missing_room'];
                            echo "<p style='color:red';>$error</p>";
                            unset($_SESSION['missing_room']);
                        }

                        if (isset($_SESSION['date_error'])) {
                            echo "<p style='color:red';>please input valid date</p>";
                            unset($_SESSION['date_error']);
                        }
                        ?>

                        <input type='hidden' name='class'
                            value="<?php echo isset($_POST['class']) ? htmlspecialchars($_POST['class']) : ''; ?>">

                        <!-- Select Time -->
                        <div class="mb-3">
                            <label for="time" class="form-label">Select Time (8:00 AM - 9:00 PM)</label>
                            <input type="time" name="time" id="time" class="form-control" required>
                        </div>
                        <?php
                        if (isset($_SESSION['time_error'])) {
                            echo "<p style='color:red;'>please input valid time </p>";
                            unset($_SESSION['time_error']);
                        }

                        ?>
                        <!-- Select Duration -->
                        <div class="mb-3">
                            <label for="duration" class="form-label">Select Duration</label>
                            <select id="duration" name="duration" class="form-select" required>
                                <option value="" disabled selected>Choose duration...</option>
                                <option value="30">30 minutes</option>
                                <option value="60">1 hour</option>
                                <option value="90">1.5 hours</option>
                                <option value="120">2 hours</option>
                            </select>
                        </div>
                        <?php
                        if (isset($_SESSION['time_conflic'])) {
                            echo "<p style='color:red;'>please input valid date, there is a conflict</p>";
                            unset($_SESSION['time_conflic']);
                        }
                        if (isset($_SESSION['successful_booking'])) {
                            echo "<p style='color:green;'>succsseful booking!</p>";
                            unset($_SESSION['successful_booking']);
                        }

                        ?>
                        <!-- Submit Button -->
                        <div class="d-grid">
                            <input type="submit" class="btn btn-primary" value="Book Now">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- footer -->
    <?php include "views/footer.php"; ?>

</body>

</html>
