<?php
session_start();
require 'db.php';
//insure the user is logged in
if(!isset($_SESSION['user_id'] )){
    header("..\login.php");
}
//insure the user input all data
if($_SERVER['REQUEST_METHOD']==="POST"){
   if(empty($_POST['date']) ||empty($_POST['time'])||empty($_POST['duration']) ){
        die('please fill all fileds');
   }

}
$date=($_POST['date']);
$time=($_POST['time']);
$duration=intval($_POST['duration']);
//$class=$_POST['class'];

//start and end time
$start_time=strtotime($date.' '. $time);
$end_time=$start_time+($duration*60);
//insure valid date
$currentDate=date("y-m-d");
$currentTime=date("H:i");
if(strtotime($date)<strtotime($currentDate)){
    $_SESSION['date_error']=true;
    header("Location: booking.php");
    exit;
}

//ensure valid time if the user chosed the same date of the cuurent time 
if(strtotime($date)===strtotime($currentDate) && strtotime($time)<strtotime($currentTime)){
    $_SESSION["time_error"]=true;
    header("Location: booking.php");
    exit;
}

//rerieve records from database 
$query=$db->prepare("SELECT * FROM booking WHERE class_id = :classId and booking_date= :inputDate");
$query->execute([":classId" => $class, ":inputDate" => $date]);
$data=$query->fetchAll(PDO::FETCH_ASSOC);
//checking for conflict
$conflict=false;
if(!empty($data)){
    foreach($data as $row){

        $rowstart=strtotime($row['booking_date']." ". $row['booking_time']);
        $rowend=$rowstart+($row['booking_duration']*60);
        if($start_time < $rowend && $end_time > $rowstart){
            $conflict=true;
            break;
        }
        
    }
}

if($conflict){
    $_SESSION['time_conflic']=true;
    header("Location: booking.php");
    exit;
}
else{
    //adding to database
    $time=new DateTime($time);
    $time=$time->format('H:i:s'); //convert timr to format 'HH:MM:SS'
    $add=$db->prepare("INSERT INTO booking (user_id, class_id, booking_date, booking_time, booking_duration) VALUES
    (:user_id, :class_id, :booking_date,:booking_time, :booking_duration)");
    $add->execute([
        ":user_id"=> $_SESSION['user_id'],
        "class_id" => $class,
        ":booking_date"=>$date,
        "booking_time" =>$time ,
        "booking_duration"=>$duration 
    ]);
    $_SESSION['successful_booking']=true;
    header("Location: booking.php");
    exit;
}

?>