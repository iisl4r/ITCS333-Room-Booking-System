<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location:auth.html");
    exit;
}
require "./php/db.php";
$user_id=$_SESSION['user_id'];

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MY Bookings</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="./css/My-bookings.css" rel="stylesheet">
    </head>
    <body>
        <?php
        //retrieve data from db
        $query=$db->prepare("SELECT * FROM booking WHERE user_id = :user_id ");
        $query->execute([":user_id" => $user_id]);
        $data=$query->fetchAll(PDO::FETCH_ASSOC);
        if(empty($data)){
            echo "<p class=empty>NO BOOKED ROOM</p>";
        }
        else{
        ?>
        <div class="container my-5">
            <h1 class="text-center mb-5">Reservations</h1>
        <!-- Reservation Cards -->
            <div class="row g-4">
                <!-- Reservation Cards code -->
                <?php
                foreach($data as $record){
                    //$query=$db->prepare("SELECT * FROM rooms  WHERE id = :room_id ");
                    //$query->execute([":user_id" => $record['class_id']]);
                    //$data=$query->fetch(PDO::FETCH_ASSOC);
                ?>
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">##after rooms table##</h5>
                                <p class="card-text">
                                <strong>Date: </strong><?php echo $record['booking_date']; ?><br>
                                <strong>Time: </strong><?php echo $record['start_time']; ?><br>
                                <strong>Duration: </strong><?php echo $record['duration']; ?></br>
                                <strong>Status: </strong><?php echo $record['booking_status']; ?>
                                </p>
                                <div class="forms">
                                    <!--action buttons-->
                                    <form  method="post" action="##">
                                        <input type="hidden" name="classID" value=<?php echo $record['class_id'] ;?> >
                                        <input type="submit" class="btn btn-primary btn-sm" value="Room Details">
                                    </form>
                                    <form  method="post" action="./php/cancellation.php">
                                        <input type="hidden" name="booking-id" value="<?php echo $record['id']; ?>" >
                                        <input type="submit" class="btn btn-primary btn-sm" value="Cancel Booking">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php            
                }
                ?>
            </div>
            <?php }?>
        </div>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>