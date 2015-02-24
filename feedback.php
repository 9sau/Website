
<?php
	include "connect.php";
	ob_start();
	session_start();
	$concert_id = $_GET["concert"];
	$rating = $_GET["rating"];
	$comments = $_GET["comments"];
	$user = $_SESSION["user"];
	$password = $_SESSION["password"];
	
	$concert_id = $_GET["concert"];
	//echo $concert_id." - ".$rating." - ".$comments;
	$query = "Insert into feedback values(?,?,?,?)";
	if($res = $con->prepare($query))
		{
			$res->bind_param("siss",$user,$concert_id,$rating,$comments);
			$res->execute();
			header('Location: '."user_profile.php?login_username=$user&login_password=$password&login=");
			exit();
		}
?>
