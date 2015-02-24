<?php
	include "connect.php";
	ob_start();
	session_start();

	$band_id = $_GET["band_id"];
	$query = "call get_band_data(?);";
	$res = $con->prepare($query);
	$res -> bind_param("i",$band_id);
	$res -> execute();
	$res -> bind_result($bid,$bname);
	$res -> fetch();
	
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
				<img class="img-responsive profile-img" alt="Profile image" src="images/<?php echo $band_id?>.jpg"/><p class="name-font">
					<p class="name-font">
						<?php 
							echo $bname;
							$res->close();
						?>
					</p>
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
									<a class = "page-links" href="#Profile" data-toggle="tab">
										<span class="glyphicon glyphicon-user" aria-hidden="true"></span> Profile
									</a>
								</li>
								
								<li >
									<a class = "page-links" href="#Followers" data-toggle="tab">
										<span class="glyphicon glyphicon-user small" aria-hidden="true"></span><span class="glyphicon glyphicon-user small" aria-hidden="true"></span> Following
									</a>
								</li>
					 
								<li >
									<a class = "page-links" href="#Gallery" data-toggle="tab">
										<span class="glyphicon glyphicon-picture" aria-hidden="true"></span> Gallery
									</a>
								</li>
								
								<li >
									<a class = "page-links" href="#Events" data-toggle="tab">
										<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Events
									</a>
								</li>
								
								<li >
									<a class = "page-links" href="#Artists" data-toggle="tab">
										<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Artists
									</a>
								</li>
								
							</ul>
				 
							<div class="tab-content">
							
								<div class="tab-pane fade in active temp" id="Profile" >
									<p>Band Profile</p>
										<?php
											
										?>
								</div>
								
								<div class="tab-pane fade in temp" id="Artists" >
									<p>Artists</p>
										<?php
											
										?>
								</div>
						 
								<div class="tab-pane fade temp" id="Followers" >
									<p> People who like us </p>
										<?php
												$query = "call get_band_followers(?);";
												if($res=$con->prepare($query))
													{
														$res->bind_param("s",$band_id);
														$res->execute();
														$res->bind_result($username,$fname,$lname);
														while($res->fetch())
															{
															//	echo "<form action='common.php' method='GET'>";
																echo "<div class='col-lg-3 col-md-4 col-xs-6 thumb row'>";
																echo "<a id='photos' href='common.php?user=$username' >";
																echo "<img class='img-responsive' src='images/$username.jpg' alt='image1'>";
																echo $fname." ".$lname;
																echo "</a>";
																
																echo "</div>";
																
																
															}
														$res->close();
													}
											?>
										
								</div>
								
								<div class="tab-pane fade temp" id="Events" >
									
										<?php
											$query = "call get_band_concerts(?);";
											if($res=$con->prepare($query))
												{
													$res->bind_param("i",$band_id);
													$res->execute();
													$res->bind_result($band_name,$concert_name,$date,$stime,$etime,$street,$city,$state,$country);
													while($res->fetch())
														{
															echo "<div class='col-lg-3 col-md-4 col-xs-6 thumb event temp row'>";
															
															echo "<div class='caption'>";
															echo "Concert Name : <b>".$concert_name."</b><br />";
															echo "Address : ".$street.", ";
															echo $city.", ";
															echo $state.", ";
															echo $country."<br />";
															echo "Date : ".$date."<br />";
															echo "Start : ".$stime."<br />";
															echo "End : ".$etime;
															echo "</div>	</div>";
														}
													$res->close();
												}
										?>
								</div>
								
								<div class="tab-pane fade temp" id="Gallery" >
									<p> Gallery </p>
										
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
        <p class="text-muted">&copy; Project compiled by Nitin Wagadia and Saurabh Jain at NYU Poly for the Course Principles and Design of Database</p>
      </div>
    </footer>
</body>
</html>
