<?php
session_start();
?>

<!DOCTYPE html>
<html lang ="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Home - Smart Home</title>
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
</head>
<body>
	<!-- HEADER -->
	<div class="container">
		<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse" aria-expanded="false" aria-controls="navbar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php">Smart Home</a>
			</div>

			<div class="navbar-collapse collapse" id="navbar">
				<ul class="nav navbar-nav">
					<li class="active"><a href="index.php" data-toggle="collapse" data-target=".navbar-collapse.in">Home</a></li>
					<li><a href="project.html" data-toggle="collapse" data-target=".navbar-collapse.in">Project</a></li>
					<li><a href="code.html" data-toggle="collapse" data-target=".navbar-collapse.in">Code</a></li>
					<li><a href="about.html" data-toggle="collapse" data-target=".navbar-collapse.in">About</a></li>
					<li><a href="contact.html" data-toggle="collapse" data-target=".navbar-collapse.in">Contact</a></li>
				</ul>
 				<ul class="nav navbar-nav navbar-right">
 				<!--<li><a href="signin" data-toggle="modal" data-target=".bs-example-modal-sm">Login / Register</a></li>-->
				</ul> 
			</div>

		</div> <!-- container full -->
		</nav>
	</div> <!-- END HEADER -->

	<!-- Body -->
<div class="container-fluid">
	<div class="container">	
		
		<!-- Smart Home -->
		<div class="row">		
			<div class="col-sm-7"></div>
			<div class="col-sm-4">
				<div class="page-header">
					<img src="images/xOkBWw1451252335.png" class="img-responsive">
				</div>
			</div>
			<div class="col-sm-1"></div>
		</div>

		<!-- Control your home -->
		<div class="row margin-bottom">
			<div class="col-sm-1"></div>
			<div class="col-sm-4 margin-top">
				<img src="images/house14.png" class="img-responsive">
			</div>

			<div class="col-sm-1"></div>

			<div class="col-sm-5">
				<h3 class="head head-right">Control your Home</h3>
				<p class="parag">
					Smart Home is a project that will ease your life at home and will give you the opportunity to choose among many profiles one that suits you best.
                    For now it's possible to control:
                   <ul class="list">
                    	<li>Light System</li>
                   		<li>Heat Systems </li>
                    	<li>Humidity System</li>
                    </ul>
                   <p class="parag"> but other Systems as Windows System, Doors System and an Advanced Audio Distribution will be available soon. </p>
				</p>
			</div>
			<div class="col-sm-1"></div>
		</div>
		<!-- Completerly autonomous-->
		<div class="row margin-bottom">
			<div class="col-sm-1"></div>
			<div class="col-sm-5 margin-top">
				<h3 class="head head-left">Completely autonomous</h3>
				<p class="parag">
					The System is completely autonomous since you have to choose during the initial setup the most important parameters as temperature for each room or the best profile for the lighting system.
                    After a few steps the system will be able to work in a totally autonomous way and control by itself temperature, humidity and light in your home giving you the best comfort.
				</p>
			</div>
			<div class="col-sm-1"></div>
			<div class="col-sm-4">
				<img src="images/retro_robot_thinking_400_clr_11439.png" class="img-responsive">
			</div>
			<div class="col-sm-1"></div>
		</div>
		<!-- Take a look at the historical -->

		<div class="row margin-bottom">
			<div class="col-sm-2"></div>
			<div class="col-sm-3">
				<img src="images/3D Pie Chart-256x256.png" class="img-responsive">
			</div>
			<div class="col-sm-1"></div>
			<div class="col-sm-5">
				<h3 class="head head-right">Take a look at the historical</h3>
				<p class="parag">
                	The system keeps track into a database of all the events and commands sent between the components inside the network. It's possible in this way to create charts and see how the system performs. Moreover with an accurate study of the personal habits it's possible to optimize consumtpions and needs by setting new parameters.
				</p>
			</div>
			<div class="col-sm-1"></div>
		</div>
		<div class="row">
		<div class="col-sm-12">
			
		</div>
		</div>
	
	</div>
</div>

<!-- Small modal -->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="myModal" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
    	<br>
           <div class="bs-example bs-example-tabs" style="padding-left: 10px;padding-right: 10px">
	            <ul id="myTab" class="nav nav-tabs" style="padding-left: 10px;padding-right: 10px">
	            	<li class="active"><a href="#signin" data-toggle="tab">Sign In</a></li>
	            	<li><a href="#register" data-toggle="tab">Register</a></li>
	              	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	            </ul>
        	</div>

        <div class="modal-body">
        <div id="myTabContent" class="tab-content">
  			<div class="tab-pane fade active in" id="signin">
	    		<form method="post" action="php/login.php" name ="register">
	    			<fieldset>
					<div class="form-group" style="padding-top: 5px">
						<div class="input-group">
							<input type="email" class="form-control" name="email" placeholder="Email">
							<label for="uLogin" class="input-group-addon glyphicon glyphicon-user"></label>
						</div>
					</div> <!-- /.form-group -->
					<div class="form-group">
						<div class="input-group">
							<input type="password" class="form-control" name="password" placeholder="Password">
							<label for="uPassword" class="input-group-addon glyphicon glyphicon-lock"></label>
						</div>
					</div> 
					<div class="checkbox" style="padding-top: 5px;padding-bottom: 10px">
						<label>
							<input type="checkbox"> Remember me
						</label>
					</div> 
		    		<div class="modal-footer">
		    			<button type="submit" class="form-control btn btn-primary" name="SignIn">Sign In</button>
		    		</div>
		    		</fieldset>
	    		</form>
        	</div>

  			<div class="tab-pane fade" id="register">
	      		<form method="post" action="php/register.php" name ="register">
	      			<fieldset>
	      	    		<div class="form-group">
	      	    			<p>Name</p>
							<input type="text" name="name" class="form-control" placeholder="Name"></input>
						</div>
						<div class="form-group">
							<p>Surname</p>
							<input type="text" name="surname" class="form-control" placeholder="Surname"></input>
						</div>
			        	<div class="form-group">
							<p>Email address</p>
							<input type="email" name="email" class="form-control" placeholder="Email"></input>
						</div>
						<div class="form-group">
							<p>Password</p>
							<input type="password" name="password" class="form-control" placeholder="Password"></input>	
						</div>
						<div class="form-group">
							<p id="errors"></p>
						</div>
						<div class="modal-footer">
		    				<button type="submit" class="form-control btn btn-primary" name="Register">Register</button>
		    			</div>
					</fieldset>
	      		</form>
        	</div>
       	 </div> <!-- Mytab content-->
     </div> <!--modal body -->
    </div>
  </div>
</div> <!--End Small Modal -->



	<!-- FOOTER -->
	<footer class="footer">
		<div class="container">
			<button type="button" class="btn btn-link btn-sm center-block" id="view-full" style="color:gray">Desktop Mode</button>
		</div>
		<div class="footer-background">
			<div class="container">
				<p class="text-footer">&#169; 2015 All Rights Reserved. &#169; Andrian 	Putina</p>
			</div>
		</div>
	</footer>
	<!-- END FOOTER -->

	<script src="js/jquery-2.2.1.js"></script>
	<script src="js/bootstrap.min.js"></script>

	<?php 
		include 'php/errors.php';
	?>

	<script>
	 $(document).ready(function(){
	 	$('#view-full').bind('click',function(){
	 		$('meta[name="viewport"]').attr('content','width=1200');
	 	});

	 });
	</script>

</body>
</html>