<?php
include "connect.php";
session_start();
//echo " hiiiii";
$user= $_SESSION["user"];
$follow= $_GET["follow"];
$vuser=$_GET["vuser"];
//$location="http://localhost/band/user_visit_followers.php?uid=$follower&vuid=$vuser";

$stmt=$con->prepare("call follow_user(?,?); ");
$stmt->bind_param("ss",$user,$follow);
$stmt->execute();
header('Location: '."common.php?user=$vuser");
exit();


?>