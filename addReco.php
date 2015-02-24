<?php
	include "connect.php";
	ob_start();
	session_start();
	$user = $_SESSION["user"];
	$concert_id = $_GET["concert_id"];
	$date =  $_GET["date"];
	
	$query1 = "select genre_name from band_reg_concert natural join concert natural join sub_genre natural join genre where concert_id = ? and date = ? ";
	if($res1 = $con->prepare($query1))
		{
			$res1->bind_param("is",$concert_id,$date);
			$res1->execute();
			$res1->bind_result($genre);
			$res1->fetch();
				
			$genre_name = $genre;
			$res1->close();
			//echo $genre_name;
			
			$query2 = "select reco_name from user_recommendation natural join concert natural join sub_genre natural join genre where genre_name = ? and username=? group by reco_name";
			$res2 = $con->prepare($query2);
			$res2 -> bind_param("ss",$genre_name,$user);
			$res2->execute();
			
			$res2 -> bind_result($reco);
			
			$res2 ->store_result();
			$rows = $res2->num_rows;
			$res2 -> fetch();
			$reco_name = $reco;
			$res2 -> close();
			//echo $reco_name."Hello".$rows;
			if($rows==0)
				{
					echo "<form class='form-horizontal' role='form' method='get' action='process_reco.php' onsubmit='return validateReco();'>";
					echo "<input type='text' maxlength='32' class='form-control' id='reco-name' name='reco-name' placeholder='Insert a new Recommendation name'>";
					echo "<input type='hidden' name='concert' value=$concert_id>";
					echo "<button class='btn btn-primary delete'>Submit</button>";
					echo "</form>";
				}
			
			else
				{
					$query3 = "insert into user_recommendation values(?,?,?,now())";
					$res3 = $con->prepare($query3);
					$res3 -> bind_param("sis",$user,$concert_id,$reco_name);
					$res3->execute();
			
				}
			echo "Recommendation added successfully";
			
		}	
	
?>
