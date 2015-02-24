<?php
	
	include "connect.php";
	
	if(isset($_GET["login"]))
		{
			
			$user = $_GET["login_username"];
			$password = $_GET["login_password"];
			$query = "select fname,lname from user where username = ?";
			if($res=$con->prepare($query))
				{
					$res->bind_param("s",$user);
					$res->execute();
					$res->bind_result($fname,$lname);
					$res->store_result();
					$rows = $res->num_rows;
					if($rows==0)
						echo "<script type='text/javascript'>
								alert('No Such User! Please check your credentials ');
								window.location.href = 'index.html';
								</script>";
					else
						{	
							$res->fetch();
							$res->close();
							ob_start();
							session_start();
							$_SESSION["user"]=$user;
							$_SESSION["password"]=$password;
								$query = "call update_login(?);";
									if($res = $con->prepare($query))
										{	
											$res->bind_param("s",$_SESSION["user"]);
											$res->execute();
											$res->close();
										}
															
						}
				}
		}
		
	else if(isset($_GET["reg_submit"]))
		{
			
			$user = $_GET["uid"];
			$password = md5($_GET["reg_password"]);
			$fname = $_GET["fname"];
			$lname = $_GET["lname"];
			$dob = $_GET["dob"];
			
			$query = "select * from user where username = ?";
			if($res=$con->prepare($query))
				{
					$res->bind_param("s",$user);
					$res->execute();
					$res->store_result();
					$rows = $res->num_rows;
					if($rows>0)
						echo "<script type='text/javascript'>
								alert('Username already exists!');
								window.location.href = 'index.html';
								</script>";
					else
						{	
							$query = "call user_sign_up(?,?,?,?,?);";
								if($res=$con->prepare($query))
									{
										$res->bind_param("sssss",$user,$password,$fname,$lname,$dob);
										$res->execute();
										//mysqli_free_result($res);
										$query = "select fname,lname from user where username = ?";
											if($res=$con->prepare($query))
												{
													$res->bind_param("s",$user);
													$res->execute();
													$res->bind_result($fname,$lname);
													
													$res->fetch();
													ob_start();
													session_start();
													$_SESSION["user"]=$user;
													$_SESSION["password"]=$password;
													$res->close();
												}
										
									}
									
								else 
									echo "<script type='text/javascript'>
											alert('Error!');
										</script>";
						}
				}
		}
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
					<p class="name-font"><?php echo $fname." ".$lname;?></p>
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
									<a class = "page-links" href="#Home" data-toggle="tab">
										<span class="glyphicon glyphicon-home" aria-hidden="true"></span> Home
									</a>
								</li>
								
								<li>
									<a class = "page-links" href="#Notifications" data-toggle="tab">
										<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> Notifications
										<?php 
											$query = "call user_last_logout(?);";
											$res=$con->prepare($query);
											$res->bind_param("s",$user);
											$res->execute();
											$res->store_result();
											$rows = $res->num_rows;	
											$res->close();
										?>
										<span class="badge"><?php echo $rows;?></span>
									</a>
								</li>
								
								<li>
									<a class = "page-links" href="#Profile" data-toggle="tab">
										<span class="glyphicon glyphicon-user" aria-hidden="true"></span> Profile
									</a>
								</li>
								
								<li >
									<a class = "page-links" href="#Following" data-toggle="tab">
										<span class="glyphicon glyphicon-user small" aria-hidden="true"></span><span class="glyphicon glyphicon-user small" aria-hidden="true"></span> Following
									</a>
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
										<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> My Recommendations
									</a>
								</li>
								
								<li>
									<a class = "page-links" href="#Feedback" data-toggle="tab">
										<span class="glyphicon glyphicon-user small" aria-hidden="true"></span> Feedback
									</a>
								</li>
								
							</ul>
				 
							<div class="tab-content temp">
								<div class="tab-pane fade in active temp" id="Home" >
									<?php
										$query = "call concert_recommendation_of_people_i_follow(?);";
										if($res = $con->prepare($query))
											{
												$res->bind_param("s",$user);
												$res->execute();
												$res->bind_result($reg_id,$username,$reco_name,$concert_name,$street,$city,$state,$country,$availability,$date,$stime,$etime);
												while($res->fetch())
													{	
														echo "<div class='col-lg-3 col-md-4 col-xs-6 thumb event temp row'>";
														echo "<div class='caption'>";
														echo "Recommended by : ";
														echo "<a href='common.php?user=$username'>";
														echo "<b>".$username."</b><br />";
														echo "</a>";
														echo "Reco name : ".$reco_name."<br />";
														echo "Concert name : ".$concert_name."<br />";
														echo "Address : ".$street.", ";
														echo $city.", ";
														echo $state.", ";
														echo $country."<br />";
														echo "Availability : ".$availability."<br />";
														echo "Date : ".$date."<br />";
														echo "Start : ".$stime."<br />";
														echo "End : ".$etime;
														echo "</div>	</div>";
													}
												$res->close();
											}
																
																
									?>
							
								</div>
								
								<div class="tab-pane fade in temp" id="Notifications" >
									<p class="temp">
										<?php
											$query = "call user_last_logout(?);";
											if($res=$con->prepare($query))
												{
													
													$res->bind_param("s",$user);
													$res->execute();
													$res->bind_result($band_name,$name,$date,$availability,$price,$city);
													
													while($res->fetch())
														{
															echo "<div class='col-lg-3 col-md-4 col-xs-6 thumb event temp row'>";
															
															echo "<div class='caption'>";
															echo "Band name : <b>".$band_name."</b><br />";
															echo "Concert name : ".$name."<br />";
															//echo "Address : ".$street.", ";
															//echo $city.", ";
															//echo $state."<br />";
															echo "Availability : ".$availability."<br />";
															echo "Price : ".$price."<br />";
															echo "Date : ".$date."<br />";
															//echo "Start : ".$stime."<br />";
															//echo "End : ".$etime;
															echo "</div>	</div>";
														}
														
													$res->close();
												}
												else echo mysqli_error($con);
										?>
									</p>
								</div>
								
								<div class="tab-pane fade in temp" id="Profile" >
									
										<?php
											$query = "call get_user_data(?);";
											if($res=$con->prepare($query))
												{
													$res->bind_param("s",$user);
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
														$res->bind_param("s",$user);
														$res->execute();
														$res->bind_result($fname,$follow);
														while($res->fetch())
															{
																echo "<div class='col-lg-3 col-md-4 col-xs-6 thumb row'>";
																echo "<a id='photos' href='common.php?user=$follow' >";
																echo "<div class='caption text-center'>";
																echo "<img class='img-responsive follow-img' src='images/$follow.jpg' alt='image1'/>";
																echo "</a>";
																
																echo "Name: $fname<br/>
																		Trust Level:<br/>
																		Country:<br/>";
																echo "</div>
																	  </div>";
														
															}
														$res->close();
													}*/
												$stmt=$con->prepare("call user_following_list(?);");
												$stmt->bind_param("s",$user);
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
													echo "<a href='unfollow.php?follows=$follow' class='btn btn-primary unfollow' role='button'>Unfollow</a>";
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
														$res->bind_param("s",$user);
														$res->execute();
														$res->bind_result($gname,$sgname);
														while($res->fetch())
															{
																echo "<div class='col-lg-3 col-md-4 col-xs-6 thumb reco row'>";
																echo "<a id='photos' href='#' >";
																echo "<div class='caption'>";
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
											$query = "call get_band_list(?);";
											if($res=$con->prepare($query))
												{
													$res->bind_param("s",$user);
													$res->execute();
													$res->bind_result($band_id,$band_name);
													while($res->fetch())
														{
															echo "<div class='col-lg-3 col-md-4 col-xs-6 thumb row'>";
															echo "<a href='band.php?band_id=$band_id'>";
															echo "<div class='caption text-center'>";
															echo "<img class='img-responsive follow-img' src='images/$band_id.jpg' alt='image1'/>";
															echo "</a>";
															echo "Name: $band_name";
															echo"</div>
																	</div>";
														}
													$res->close();
												}
										?>
										
								</div>
						 
								<div class="tab-pane fade text-center temp" id="Events" >
								
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
													$res->bind_param("s",$user);
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
															echo "End : ".$etime."<br/>";
															echo"<span>
																	<a class='btn btn-primary' role='button' data-target='#recommend' data-toggle='modal' date=$date id=$concert_id onclick=\"myFunctionReco(this.id)\">Recommend</a>
																</span>";
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
																$res->bind_param("s",$user);
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
																		echo "End : ".$etime."<br/>";
																		echo"<span
																				<a class='btn btn-primary' role='button' data-target='#feedback' data-toggle='modal' id=$concert_id onclick=\"myFunctionFeedback(this.id)\">Feedback</a>
																			</span>";
																		echo "</div>	</div>";
																	}
																$res->close();
															}
													?>
												
											</div>
										</div>
										
										</div>
									</div>
									
									<div class="modal fade" id="recommend" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										 <div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
													<h4 class="modal-title" id="recommend-title">Details</h4>
												</div>
												<div class="modal-body text-center" id="recommend-body">
																	
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" onclick="location.reload(true)" data-dismiss="modal">Ok</button>
												</div>
											</div>
										</div>
									</div>
									
									<div class="modal fade" id="feedback" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										 <div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
													<h4 class="modal-title" id="feedback-title">Details</h4>
												</div>
												<div class="modal-body text-center" id="feedback-body">
																	
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" onclick="location.reload(true)" data-dismiss="modal">Ok</button>
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
																		$res->bind_param("s",$user);
																		$res->execute();
																		//$res->bind_result($reg_id,$username,$reco_name,$concert_name,$street,$city,$state,$country,$availability,$date,$stime,$etime);
																		$res->bind_result($reco_name,$genre_name);
																		$res->store_result();
																		while($res->fetch())
																		{	
																			echo "<div class='panel panel-default'>";
																				echo "<div class='panel-heading'>";
																					echo "<h4 class='panel-title'>";
																						echo "<a data-toggle='collapse' data-parent='#accordion' href='#$genre_name' >$reco_name under Genre - $genre_name</a>";
																					echo "</h4>";
																				echo "</div>";
																				
																				echo "<div id=\"$genre_name\" class='panel-collapse collapse in'>";
																				echo "<div class='panel-body'>";
																				//echo "<a class='btn btn-success reco-list' id='reco'  reco=\"$reco_name\" genre=\"$genre_name\" role='button'>Add New Recommendation to this list</a>";
																				echo"<ul class='list-group'>";
																				$query2 = "select concert_id,sub_genre_name,name from user_recommendation NATURAL JOIN concert NATURAL JOIN sub_genre NATURAL JOIN genre where username = ? and genre_name = ? ";
																				if($res1=$con->prepare($query2))
																					{	
																						$res1->bind_param("ss",$user,$genre_name);
																						$res1->execute();
																						$res1->bind_result($concert_id,$sub_genre_name,$concert_name);
																						
																						while($res1->fetch())
																							{	
																								
																								echo "<li class='list-group-item reco-list text-center'>";
																								echo "Sub-Category: ".$sub_genre_name."	<br/>";
																								echo "Concert name : ".$concert_name." $concert_id<br/>";
																								echo "<a class='btn btn-primary delete' id=$concert_id data-toggle='modal' data-target='#NewReco' onclick=\"return newfunction(this.id);\" role='button'>Delete</a>";
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
															<div class="modal fade" id="NewReco" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
															  <div class="modal-dialog">
																<div class="modal-content">
																  <div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
																	<h4 class="modal-title" id="myModalLabel">Details</h4>
																  </div>
																  <div class="modal-body" id="modal-body">
																	
																  </div>
																  <div class="modal-footer">
																	<button type="button" class="btn btn-default" onclick="location.reload(true)" data-dismiss="modal">Ok</button>
																	
																  </div>
																</div>
															  </div>
															</div>
											</div>
											<script type="text/javascript">
													
											</script>
									
								</div>
												
								
								
								<div class="tab-pane fade temp" id="Feedback">
									
									<?php
										$query = "call get_feedback(?);";
										if($res=$con->prepare($query))
													{
														$res->bind_param("s",$user);
														$res->execute();
														$res->bind_result($concert_name,$rating,$comments);
														while($res->fetch())
															{
																echo "<div class='col-lg-3 col-md-4 col-xs-6 thumb feedback row'>";
																echo "<div class='caption'>";
																echo "Concert Name : $concert_name<br/>
																	Rating : $rating<br/>
																	Comments : $comments<br/>";
																	
																echo "</div>
																	  </div>";
														
															}
														$res->close();
													}
									?>
									
								</div>
								
								<div class="tab-pane fade in temp" id="searchuser" >
									<p>Search User</p>
								</div>
								
								<div class="tab-pane fade in temp" id="searchband" >
									<p>Search Band</p>
								</div>
								
								<div class="tab-pane fade in temp" id="searchartist" >
									<p>Search Artist</p>
								</div>
								
								<div class="tab-pane fade in temp" id="searchconcert" >
									<p>Search Concert</p>
								</div>
		
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
        <p class="text-muted">&copy; Project compiled by <br/> Nitin Wagadia and Saurabh Jain <br/> Principles and Design of Database</p>
      </div>
    </footer>
</body>
</html>
