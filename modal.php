<?php
include "connect.php";
ob_start();
session_start();
$user=$_SESSION["user"];
$concert_id = $_GET["concert_id"];
$query = "delete from user_recommendation where username = ? and concert_id = ?";

	if($res = $con->prepare($query))
		{
			$res -> bind_param("si",$user,$concert_id);
			$res -> execute();
			echo "You have successfully delete the record..!";
			
		}
	else echo mysqli_error($con);
	
?>