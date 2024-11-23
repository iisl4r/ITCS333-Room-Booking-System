<?php
session_start();
if(!isset($_SESSION['user_id'] )){
    header("Location:login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking System</title>
    <!-- Bootstrap CSS -->
    <link href="./css/booking.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    </head>
    <body>
        <main>
            <div class="container">
            <!-- Header -->
                <div class="text-center">
                    <h1>Booking a class</h1>
                    <p class="text-muted">you selceted class <?php// echo ".$_POST['classID']";?></p>
                </div>
                <!-- Booking Form -->
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="card-title">Book the Class</h3>
                        <form method="POST" action="bookingexe.php">
                            <!-- Select Date -->
                            <div class="mb-3">
                                <label for="date" class="form-label">Select Date</label>
                                <input type="date" name="date" id="date" class="form-control" required>
                            </div>
                            <?php
                            if(isset($_SESSION['data_error']) && $_SESSION['date_error']==true){
                                echo "<p style='color:red';>please input valid date</p>";
                                $_SESSION['date_error']=false;
                            }

                            ?>
                           <!-- <input type='hidden' name='class' value=<?php// echo "$_POST['classID']"; ?> > -->
                            <!-- Select Time -->
                            <div class="mb-3">
                                <label for="time" class="form-label">Select Time</label>
                                <input type="time" name="time" id="time" class="form-control" required>
                            </div>
                            <?php
                            if(isset($_SESSION['time_error']) && $_SESSION['time_error']==true){
                                echo "<p style='color:red;'>please input valid date</p>";
                                $_SESSION['time_error']=false;
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
                            if(isset($_SESSION['time_conflic']) && $_SESSION['time_conflic']==true){
                                echo "<p style='color:red;'>please input valid date, there is a conflict</p>";
                                $_SESSION['time_conflic']=false;
                            }
                            if(isset($_SESSION['successful_booking']) && $_SESSION['successful_booking']==true){
                                echo "<p style='color:green;'>succsseful booking!</p>";
                                $_SESSION['successful_booking']=false;
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
       
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        </body>
</html>
