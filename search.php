<?php
	include "connect.php";
	ob_start();
	session_start(); 
	$search = $_GET["search"];
	$keyword = "%".$_GET["keyword"]."%";
	$user = $_SESSION["user"];
	$password = $_SESSION["password"];
	$url = "user_profile.php?login_username=".$user."&login_password=".$password."&login=";
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
						<a class="btn btn-primary home" href="<?php echo $url;?>">
							<span class="glyphicon glyphicon-home" aria-hidden="true"></span> Home
						</a>
					</form>
				</div>
			</div>	
			
	
			
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
	
	<div class="container">
		<?php
			if(isset($_GET["search"]))
				{
					if($search=="users")
						{	
							$query = "select username,fname,lname,dob,age,street,city,state,country,trust_level from user where fname like ? or lname like ? or username like ?";
							if($res=$con->prepare($query))
								{	
									
									$res->bind_param("sss",$keyword,$keyword,$keyword);
									$res->execute();
									$res->bind_result($username,$fname,$lname,$dob,$age,$street,$city,$state,$country,$trust_level);			
									while($res->fetch())
										{
											echo "<div class='col-lg-3 col-md-4 col-xs-6 thumb row text-center'>";
											echo "<a id='photos' href='common.php?user=$username' >";
											echo "<div class='caption text-center'>";
											echo "<img class='img-responsive follow-img' src='images/$username.jpg' alt='image1'/>";
											echo "</a>";
											echo "<h4>Name: $fname</h4>
											<h5>Trust Level: $trust_level</h5>
											<h5>Country: $country</h5>";
											//echo "<a href='unfollow.php?follows=$follow' class='btn btn-primary unfollow' role='button'>Unfollow</a>";
											echo "</div></div>";
										}
									$res->close();
								}
							
						}
					
					if($search=="bands")
						{
							$query = "select band_id,band_name from band where band_name like ?";
								if($res=$con->prepare($query))
									{
										$res->bind_param("s",$keyword);
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
						}
						
					if($search=="artists")
						{
							$query = "select username,fname,lname,dob,age,street,city,state,country,trust_level from artist where fname like ? or lname like ? or username like ?";
							if($res=$con->prepare($query))
								{	
									
									$res->bind_param("sss",$keyword,$keyword,$keyword);
									$res->execute();
									$res->bind_result($username,$fname,$lname,$dob,$age,$street,$city,$state,$country,$trust_level);			
									while($res->fetch())
										{
											echo "<div class='col-lg-3 col-md-4 col-xs-6 thumb row text-center'>";
											//echo "<a id='photos' href='common.php?user=$username' >";
											echo "<div class='caption text-center'>";
											//echo "<img class='img-responsive follow-img' src='images/$username.jpg' alt='image1'/>";
											//echo "</a>";
											echo "<h4>Name: $fname</h4>
											<h5>Trust Level: $trust_level</h5>
											<h5>Country: $country</h5>";
											//echo "<a href='unfollow.php?follows=$follow' class='btn btn-primary unfollow' role='button'>Unfollow</a>";
											echo "</div></div>";
										}
									$res->close();
								}
						}
					
					if($search=="concerts")
						{
							$query = "select name from concert where name like ?";
								if($res=$con->prepare($query))
									{
										$res->bind_param("s",$keyword);
										$res->execute();
										$res->bind_result($name);
										while($res->fetch())
											{
												echo "<div class='col-lg-3 col-md-4 col-xs-6 thumb row'>";
												//echo "<a href='band.php?band_id=$band_id'>";
												echo "<div class='caption text-center'>";
												//echo "<img class='img-responsive follow-img' src='images/$band_id.jpg' alt='image1'/>";
												//echo "</a>";
												echo "Concert name: $name";
												echo"</div>
												</div>";
											}
										$res->close();
									}
						}
				}
		?>
	</div>	
	
			
	<footer class="footer navbar-fixed-bottom" align="right">
      <div class="container-fluid">
        <p class="text-muted">&copy; Project compiled by <br/> Nitin Wagadia and Saurabh Jain <br/> Principles and Design of Database</p>
      </div>
    </footer>
</body>
</html>
