<?php
session_start();
//ensure the user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location:../auth.php");
    exit;
}
if($_SERVER['REQUEST_METHOD']==="POST"){
    if(empty($_POST['booking_id']) ){
         die('submittion error');
    }
}
else:
    die('submittion error');

 
require "db.php";
//update status 
$query=$db->prepare("UPDATE booking SET booking_status='canceled' WHERE id =:id ");
$query->execute(["id" => $_POST['booking_id']]);
header("Location:./analysis.php");


?>