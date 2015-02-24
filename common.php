<?php
	include "connect.php";
	ob_start();
	session_start();
	$username = $_GET["user"];
	$user = $_SESSION["user"];
	//echo $user." visted ".$username;
	$temp = "call user_following_list(?);";
	$out = $con->prepare($temp);
	$out->bind_param("s",$_SESSION["user"]);
	$out->execute();
	$out->bind_result($follow,$fname,$country,$trust_level);
	$items = array();
	while($out->fetch())
		{
			$items[]=$fname;
		}
	$out->close();
	
?>


<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="css/index.css"/>
<link rel="stylesheet" href="css/mycss.css"/>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script src="js/index.js"></script>

</head>		
<body>
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="navbar-inner">
				<div id="navbar" class="navbar-collapse collapse">
					<form class="navbar-form navbar-right" role="form" name="sign_in" method="GET" action="logout.php">
						<button type="submit" name="logout" class="btn btn-success btn-md logout">
							<i class="glyphicon glyphicon-off off"></i> Log out
						</button>
					</form>
				</div>
			</div>	
			<a class="navbar-brand head" href="#">
				<img class="img-responsive profile-img" alt="Profile image" src="images/<?php echo $user?>.jpg"/><p class="name-font">
					<p class="name-font"><?php echo $username;?></p>
			</a>
	
			
		<form action="search.php" method="get">	
			<div class="input-group">
				<span class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span>
				</span>
				<input type="text" name="keyword" class="form-control" placeholder="Search by category from the dropdown here...">
				<span class="input-group-addon">
					<div class="btn-group">
						
						<button type="button" class="btn btn-default dropdown-toggle dropdown" data-toggle="dropdown" aria-expanded="false">
							<span class="caret"></span>
							<span class="sr-only">Toggle Dropdown</span>
						</button>
						
						<ul class="dropdown-menu">
							<li><button name="search" value="users" class="btn btn-default"><h5>Users</h5></button></li>
							<li><button name="search" value="bands" class="btn btn-default"><h5>Bands</h5></button></li>
							<li><button name="search" value="artists" class="btn btn-default"><h5>Artists</h5></button></li>
							<li><button name="search" value="concerts" class="btn btn-default"><h5>Concerts</h5></button></li>
						</ul>
					</div>
				</span>
				
			</div>
		</form>	
		
		</nav>

		<div class="conatianer-fluid">
			<div class="row-fluid col-wrap">
				<div class="col-xs-12 col-sm-6 col-md-8">
						<div class="tabbable tabs-left">
							<ul class="nav nav-tabs" data-tabs="tabs col">
								
								<li class="active">
									<a class = "page-links" href="#Profile" data-toggle="tab"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Profile</a>
								</li>
								
								<li >
									<a class = "page-links" href="#Following" data-toggle="tab"><span class="glyphicon glyphicon-user small" aria-hidden="true"></span><span class="glyphicon glyphicon-user small" aria-hidden="true"></span> Following</a>
								</li>
					 
								<li >
									<a class = "page-links" href="#Genres" data-toggle="tab">
										<span class="glyphicon glyphicon-user small" aria-hidden="true"></span> Genres
									</a>
								</li>
								
								<li >
									<a class = "page-links" href="#Bands" data-toggle="tab">
										<span class="glyphicon glyphicon-music" aria-hidden="true"></span> Bands
									</a>
								</li>
								
								<li >
									<a class = "page-links" href="#Events" data-toggle="tab">
										<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> My Events
									</a>
								</li>
								
								<li>
									<a class = "page-links" href="#MReco" data-toggle="tab">
										<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Recommendations
									</a>
								</li>
								
								<li>
									<a class = "page-links" href="#Feedback" data-toggle="tab">
										<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Feedback
									</a>
								</li>
								
								
							
							</ul>
				 
							<div class="tab-content">
							
								<div class="tab-pane fade in active temp" id="Profile" >
									
										<?php
											$query = "call get_user_data(?);";
											if($res=$con->prepare($query))
												{
													$res->bind_param("s",$username);
													$res->execute();
													$res->bind_result($fname,$lname,$dob,$age,$street,$city,$state,$country,$trust_level);
													
													$res->fetch();
												}
											?>
									<div class="table-responsive">          
										<table class="table table-hover">
											<tbody>
												<tr>
													<td>Name</td>
													<td><?php echo $fname." ".$lname;?></td>
												</tr>
												
												<tr>
													<td>Birthday</td>
													<td><?php echo $dob;?></td>
												</tr>

												<tr>
													<td>Age</td>
													<td><?php echo $age;?></td>
												</tr>

												<tr>
													<td>Address</td>
													
													<td>
														<?php echo $street;?><br />
														<?php echo $city;?><br />
														<?php echo $state;?><br />
														<?php echo $country;?>
													</td>
												</tr>
												
												<tr>
													<td>Trust Score</td>
													<td>
														<div class="progress">
															<div class=" progress progress-bar" role="progressbar" aria-valuenow="<?php echo $trust_level;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $trust_level;?>%;">
																<?php echo $trust_level."%";?>
															</div>
														</div>
													</td>
												</tr>
												
         
											</tbody>
										</table>
									</div>
									
													<?php $res->close();?>
												
										
									
								</div>
						 
								<div class="tab-pane fade temp" id="Following" >
									
											<?php
												/*$query = "call follows_list(?);";
												if($res=$con->prepare($query))
													{
														$res->bind_param("s",$username);
														$res->execute();
														$res->bind_result($fname,$follow);
														while($res->fetch())
															{
															//	echo "<form action='common.php' method='GET'>";
																echo "<div class='col-lg-3 col-md-4 col-xs-6 thumb row'>";
																echo "<a id='photos' href='common.php?user=$follow&fname=$fname' >";
																echo "<div class='caption text-center'>";
																echo "<img class='img-responsive follow-img' src='images/$follow.jpg' alt='image1'/>";
																echo "</a>";
																
																echo "Name: $fname<br/>
																		Username:<br/>
																		Trust Level:<br/>
																		Country:<br/>";
																if(in_array($fname,$items))
																	{
																	echo"<a class='btn btn-success btn1' disabled='disabled' role='button'>Mutual Friend</a>";
																	echo"<span class='glyphicon glyphicon-star' aria-hidden='true'></span>";
																	}
																else
																echo "<a class='btn btn-primary btn1' role='button'>Follow</a>";
																	
																	echo"</div>
																	</div>";
																
															}
														$res->close();
													}*/
													
																									
													$stmt=$con->prepare("call user_following_list(?);");
													$stmt->bind_param("s",$username);
													$stmt->execute();
													$stmt->bind_result($follow,$fname,$country,$trust_level);
													while($stmt->fetch())
													{
														$dir="uploads/";
														$extension=".jpg";
														$path=$dir.$follow.$extension;
														echo "<div class='col-lg-3 col-md-4 col-xs-6 thumb row text-center'>";
														echo "<a id='photos' href='common.php?user=$follow' >";
														echo "<div class='caption text-center'>";
														echo "<img class='img-responsive follow-img' src='images/$follow.jpg' alt='image1'/>";
														echo "</a>";
														echo "<h4>Name: $fname</h4>
																<h5>Trust Level: $trust_level</h5>
																<h5>Country: $country</h5>";
																												
														
															if(in_array($fname,$items))
															{
																echo"<a class='btn btn-success btn1' disabled='disabled' role='button'>Mutual Friend</a>";
																echo"<span class='glyphicon glyphicon-star mutual' aria-hidden='true'></span>";
															}
															else
															{
																
																if($follow==$user)
																echo "<a href='follow.php?follow=$follow&vuser=$username' class='btn btn-primary' disabled='disabled' role='button'>Myself</a>";	
																
																else
																echo "<a href='follow.php?follow=$follow&vuser=$username' class='btn btn-primary' role='button'>Follow</a>";
															}
														echo "</div></div>";
													 }
													 $stmt->close();
												?> 
											
										</div>
							
								
								<div class="tab-pane fade temp" id="Genres" >
									
									<?php
										
										$query = "call likes_genre(?);";
										if($res=$con->prepare($query))
													{
														$res->bind_param("s",$username);
														$res->execute();
														$res->bind_result($gname,$sgname);
														while($res->fetch())
															{
																echo "<div class='col-lg-3 col-md-4 col-xs-6 thumb row'>";
																echo "<a id='photos' href='#' >";
																echo "<div class='caption text-center'>";
																//echo "<img class='img-responsive follow-img' src='images/$follow.jpg' alt='image1'/>";
																echo "</a>";
																
																echo "Genre : $gname<br/>
																	Sub-Category : $sgname<br/>";
																	
																echo "</div>
																	  </div>";
														
															}
														$res->close();
													}
									?>
									
								</div>
								
								<div class="tab-pane fade temp" id="Bands" >
									
										<?php
											$temp = "call get_band_list(?);";
												$out = $con->prepare($temp);
												$out->bind_param("s",$_SESSION["user"]);
												$out->execute();
												$out->bind_result($bid,$bname);
												$items1 = array();
												while($out->fetch())
												{
													$items1[]=$bid;
												}
												$out->close();
												//print_r(array_values($items1));
											$query = "call get_band_list(?);";
											if($res=$con->prepare($query))
												{
													
													$res->bind_param("s",$username);
													$res->execute();
													$res->bind_result($band_id,$band_name);
													while($res->fetch())
														{
															echo "<div class='col-lg-3 col-md-4 col-xs-6 thumb row'>";
															echo "<a href='band.php?band_id=$band_id'>";
															echo "<div class='caption text-center'>";
															echo "<img class='img-responsive follow-img' src='images/$band_id.jpg' alt='image1'/>";
															echo "</a>";
															echo "Name: $band_name<br />";
															if(in_array($band_id,$items1))
																	{
																	echo"<a class='btn btn-success btn1' disabled='disabled' role='button'>Liked</a>";
																	echo"<span class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></span>";
																	}
																else
																echo "<a class='btn btn-primary btn1' role='button'>Be a fan</a>";
																	
															echo"</div>
																	</div>";
																
														}
													$res->close();
												}
										?>
										
								</div>
						 
								<div class="tab-pane fade temp" id="Events" >
									<div class="panel-group" id="accordion">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a data-toggle="collapse" data-parent="#accordion" href="#upcoming">Upcoming Concerts</a>
												</h4>
											</div>
											<div id="upcoming" class="panel-collapse collapse in">
												<div class="panel-body">
										<?php
											$query = "call get_upcoming_concert_list(?);";
											if($res=$con->prepare($query))
												{
													$res->bind_param("s",$username);
													$res->execute();
													$res->bind_result($concert_id,$name,$band_name,$street,$city,$state,$date,$stime,$etime);
													while($res->fetch())
														{
															echo "<div class='col-lg-3 col-md-4 col-xs-6 thumb event temp row'>";
															
															echo "<div class='caption text-center'>";
															echo "Name : <b>".$name."</b><br />";
															echo "Band name : ".$band_name."<br />";
															echo "Address : ".$street.", ";
															echo $city.", ";
															echo $state."<br />";
															echo "Date : ".$date."<br />";
															echo "Start : ".$stime."<br />";
															echo "End : ".$etime;

															echo "</div>	</div>";
														}
													$res->close();
												}
										?>

												</div>
											</div>
										</div>
										
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a data-toggle="collapse" data-parent="#accordion" href="#past">Past Concerts</a>
												</h4>
											</div>
										
										<div id="past" class="panel-collapse collapse">
											<div class="panel-body">
												<?php
													$query = "call get_past_concert_list(?);";
														if($res=$con->prepare($query))
															{
																$res->bind_param("s",$username);
																$res->execute();
																$res->bind_result($concert_id,$name,$band_name,$street,$city,$state,$date,$stime,$etime);
																while($res->fetch())
																	{
																		echo "<div class='col-lg-3 col-md-4 col-xs-6 thumb event temp row'>";
																		
																		echo "<div class='caption text-center'>";
																		echo "Name : <b>".$name."</b><br />";
																		echo "Band name : ".$band_name."<br />";
																		echo "Address : ".$street.", ";
																		echo $city.", ";
																		echo $state."<br />";
																		echo "Date : ".$date."<br />";
																		echo "Start : ".$stime."<br />";
																		echo "End : ".$etime;
			
																		echo "</div>	</div>";
																	}
																$res->close();
															}
													?>
												
											</div>
										</div>
										
										</div>
									</div>
								</div>
								
								<div class="tab-pane fade temp" id="MReco" >
									<div class="panel-group" id="accordion">
												
													<?php
																$query1 = "select reco_name,genre_name from user_recommendation NATURAL JOIN concert NATURAL JOIN sub_genre NATURAL JOIN genre where username = ? group by reco_name,genre_name";
																if($res = $con->prepare($query1))
																	{
																		$res->bind_param("s",$username);
																		$res->execute();
																		//$res->bind_result($reg_id,$username,$reco_name,$concert_name,$street,$city,$state,$country,$availability,$date,$stime,$etime);
																		$res->bind_result($reco_name,$genre_name);
																		$res->store_result();
																		while($res->fetch())
																		{	
																			echo "<div class='panel panel-default'>";
																				echo "<div class='panel-heading'>";
																					echo "<h4 class='panel-title'>";
																						echo "<a data-toggle='collapse' data-parent='#accordion' href='#$genre_name'>$reco_name under Genre - $genre_name</a>";
																					echo "</h4>";
																				echo "</div>";
																				
																				echo "<div id=$genre_name class='panel-collapse collapse in'>";
																				echo "<div class='panel-body'>";
																				//echo "<a class='btn btn-success reco-list' reco=\"$reco_name\" genre=\"$genre_name\" role='button'>Add New Recommendation to this list</a>";
																				echo"<ul class='list-group'>";
																				$query2 = "select concert_id,sub_genre_name,name from user_recommendation NATURAL JOIN concert NATURAL JOIN sub_genre NATURAL JOIN genre where username = ? and genre_name = ? ";
																				if($res1=$con->prepare($query2))
																					{	
																						$res1->bind_param("ss",$username,$genre_name);
																						$res1->execute();
																						$res1->bind_result($concert_id,$sub_genre_name,$concert_name);
																						
																						while($res1->fetch())
																							{	
																								
																								echo "<li class='list-group-item reco-list text-center'>";
																								echo "Sub-Category: ".$sub_genre_name."	<br/>";
																								echo "Concert name : ".$concert_name."<br/>";
																								//echo "<a class='btn btn-primary delete' data-toggle='modal' data-target='#NewReco' id='reco' concert=$concert_id role='button'>Delete</a>";
																								echo"</li>";
																								
																							}
																						echo "</ul>";
																						$res1->close();
																					}
																				echo "</div></div>";
																			echo "</div>";
																			
																		}
																		$res->close();
																	}
															?>
												</div>
									
								</div>
								
								<div class="tab-pane fade temp" id="Feedback" >
									<p> My Feedbacks </p>
								</div>
								
							</div>
							
						</div>
				</div>
				
				 <div class="col-xs-6 col-md-4">
					<p>
						all the activities goes here
						this is a sample text
						this is a sample text
						this is a sample text
						this is a sample text
						this is a sample text
						this is a sample text
						this is a sample text
					</p>
				 </div>
				
			</div>
		</div>
	
	 <footer class="footer navbar-fixed-bottom" align="right">
      <div class="container-fluid">
        <p class="text-muted">&copy; Project compiled by <br/>Nitin Wagadia and Saurabh Jain <br/>Principles and Design of Database</p>
      </div>
    </footer>
</body>
</html>
