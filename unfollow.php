<?php
include "connect.php";
session_start();
$user=$_SESSION["user"];

$follow= $_GET["follows"];
$password = $_SESSION["password"];


$stmt=$con->prepare("delete from following where follow=? and follower=? ");
$stmt->bind_param("ss",$follow,$user);
$stmt->execute();
header('Location: '."user_profile.php?login_username=$user&login_password=$password&login=");
exit;


?>