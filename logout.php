<?php
	include "connect.php";
	session_start();
	$query = "call update_logout(?);";
	if($res = $con->prepare($query))
		{	
			$res->bind_param("s",$_SESSION["user"]);
			$res->execute();
			$res->close();
		}
	session_destroy();
	header('Location: '."index.html");
?>