
<?php
	include "connect.php";
	ob_start();
	session_start();
	$user = $_SESSION["user"];
	$password = $_SESSION["password"];
	$reco_name = $_GET["reco-name"];
	$concert_id = $_GET["concert"];
	
	$query = "Insert into user_recommendation values(?,?,?,now())";
	if($res = $con->prepare($query))
		{
			$res->bind_param("sis",$user,$concert_id,$reco_name);
			$res->execute();
			header('Location: '."user_profile.php?login_username=$user&login_password=$password&login=");
			exit();
		}
?>
