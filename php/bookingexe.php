<?php
session_start();
require 'db.php';
//insure the user is logged in
if(!isset($_SESSION['user_id'] )){
    header("..\auth.php");
    exit;
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
$class=$_POST['class'];

//insure valid date
$currentDate=date("y-m-d");
$currentTime=date("H:i");
if(strtotime($date)<strtotime($currentDate)){
    $_SESSION['date_error']=true;
}

//ensure valid time if the user chosed the same date of the cuurent time 
if(strtotime($date)===strtotime($currentDate) && strtotime($time)<strtotime($currentTime)){
    $_SESSION["time_error"]=true;
}
//insure booking is between the room available slots
$s=$db->prepare("SELECT * FROM rooms WHERE id=:id");
$s->execute([":id"=>$class]);
$room=$s->fetch(PDO::FETCH_ASSOC);
//room's available slots is between $start and $end
$start=strtotime($room['available_start']);
$end=strtotime($room['available_end']);
$input_start=strtotime($time);
$input_end=$input_start+($duration*60);
if($input_start<$start || $input_end>$end){
    $_SESSION["time_error"]=true;
}
//exit for invalid inputs
if(isset($_SESSION["time_error"]) || isset($_SESSION['date_error'])){
    header("Location: ../booking.php");
    exit;
}

//rerieve records from database 
$query=$db->prepare("SELECT * FROM booking WHERE class_id = :classId and booking_date= :inputDate");
$query->execute([":classId" => $class, ":inputDate" => $date]);
$data=$query->fetchAll(PDO::FETCH_ASSOC);


//start and end time
$start_time=strtotime($date.' '. $time);
$end_time=$start_time+($duration*60);
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
    header("Location: ../booking.php");
    exit;
}
else{
    //adding to database
    $time=new DateTime($time);
    $time=$time->format('H:i:s'); //convert time to format 'HH:MM:SS'
    $ob=new DateTime($time);
    $ob->add(new DateInterval('PT'.$duration.'M'));
    $end=$ob->format('H:i:s');
    $add=$db->prepare("INSERT INTO booking (user_id, class_id, booking_date, start_time, duration, end_time, booking_status) VALUES
    (:user_id, :class_id, :booking_date,:booking_time, :booking_duration, :end_time,:booking_status)");
    $add->execute([
        ":user_id"=> $_SESSION['user_id'],
        "class_id" => $class,
        ":booking_date"=>$date,
        ":booking_time" =>$time ,
        ":booking_duration"=>$duration ,
        ":end_time"=>$end,
        "booking_status"=>"active"
    ]);
    $_SESSION['successful_booking']=true;
    header("Location: ../booking.php");
    exit;
}

?>