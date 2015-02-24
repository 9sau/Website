<?php
	
	include "connect.php";
	
	if(isset($_GET["login"]))
		{
			
			$user = $_GET["login_username"];
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
							ob_start();
							session_start();
							$_SESSION["user"]=$user;
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<style type="text/css">
	
</style>
</head>	
<body>
	<div class="container-fluid">
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				
				<div class="navbar-header">
					<a class="navbar-brand" href="#"><?php echo "Welcome, ".$fname;?></a>
				</div>
				
				<div id="navbar" class="navbar-collapse collapse">
					<form class="navbar-form navbar-right" role="form" name="sign_in" method="GET" action="logout.php">
						<button type="submit" name="logout" class="btn btn-success">Log out</button>
					</form>
				</div>
			</div>
		</nav>
		
		<div class="conatianer-fluid">
			<div class="tabbable tabs-left">
				<div class="row">
					<div class="col-xs-6 col-sm-4">
						<ul class="nav nav-tabs" data-tabs="tabs">
								<li class="active">
									<a href="#Home" data-toggle="tab">Home</a>
								</li>
								
								<li>
									<a href="#Notifications" data-toggle="tab">Notifications</a>
								</li>
								
								<li>
									<a href="#Profile" data-toggle="tab">Profile</a>
								</li>
								
								<li>
									<a href="#Profile" data-toggle="tab">Profile</a>
								</li>
								
								<li >
									<a href="#Following" data-toggle="tab">Following</a>
								</li>
					 
								<li >
									<a href="#Events" data-toggle="tab">My Events</a>
								</li>
								
								<li>
									<a href="#MReco" data-toggle="tab">Recommendations</a>
								</li>
							
						</ul>
					</div>
					
					<div class="col-xs-6 col-sm-4">
						<div class="tab-content">
							
								<div class="tab-pane fade in active" id="Home" >
									<p> This is my Home </p>
								</div>
								
								<div class="tab-pane fade in" id="Notifications" >
									<p> Notifications </p>
								</div>
								
								<div class="tab-pane fade in" id="Profile" >
									
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
									
										<div>
											Hello
											Hello
											HelloHellov
											HelloHello
										</div>
													<?php $res->close();?>
												
										
									
								</div>
						 
								<div class="tab-pane fade" id="Following" >
									<p> This is my Following list </p>
										<?php
											$query = "call follows_list(?);";
											if($res=$con->prepare($query))
												{
													$res->bind_param("s",$user);
													$res->execute();
													$res->bind_result($fname,$follow);
													while($res->fetch())
														{
														//	echo "<form action='common.php' method='GET'>";
															echo "<a href='common.php?user=$follow&fname=$fname'>";
															echo $fname;
															echo "</a>";
															echo "<br />";
														}
													$res->close();
												}
										?>
										
								</div>
						 
								<div class="tab-pane fade" id="Events" >
									<p> This is my Event list </p>
								</div>
								
								<div class="tab-pane fade" id="MReco" >
									<p> My Recommendations </p>
								</div>
								
								<div class="tab-pane fade" id="SReco" >
									<p> System Recommendations </p>
								</div>
								
							</div>
					</div>
					<div class="clearfix visible-xs-block"></div>
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
		</div>
	</div>
		 
     
	 <footer class="footer navbar-fixed-bottom" align="right">
      <div class="container-fluid">
        <p class="text-muted">&copy; Project compiled by Nitin Wagadia and Saurabh Jain at NYU Poly for the Course Principles and Design of Database</p>
      </div>
    </footer>
</body>
</html>
