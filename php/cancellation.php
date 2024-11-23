<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location:../auth.html");
    exit;
}
if($_SERVER['REQUEST_METHOD']==="POST"){
    if(empty($_POST['booking-id']) ){
         die('submittion error');
    }
}
 
require "db.php";
$query=$db->prepare("DELETE FROM booking WHERE id =:id ");
$query->execute(["id" => $_POST['booking-id']]);
header("Location:booked-rooms-view.php");


?>