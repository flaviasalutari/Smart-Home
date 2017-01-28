<?php
session_start();
if (!isset ($_SESSION['cod_user']))
	header("Location: ../index.php");
?>

<!DOCTYPE html>
<html lang ="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Main - Smart Home</title>
		<link rel="stylesheet" href="../css/bootstrap.css">
		<link rel="stylesheet" href="../css/bootstrap-switch.css">
		<link rel="stylesheet" type="text/css" href="../css/index.css">
		<link rel="stylesheet" type="text/css" href="../css/charts.css">
				<!-- JS -->
		<script src="../js/jquery-2.2.1.js"></script>
		<script src="../js/bootstrap.js"></script>
		<script src="../js/bootstrap-switch.js"></script>
		<script src="../js/jquery.AshAlom.gaugeMeter-2.0.0.min.js"></script>
		<script src="../js/Chart.bundle.js"></script>
		<!-- -END JS- -->
	</head>

	<body>
		<!-- HEADER -->
		<div class="container">
			<nav class="navbar navbar-default">
				<div class="container-fluid">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"></button>
						<a class="navbar-brand" href="../index.php">Smart Home</a>
					</div>

					<div class="navbar-collapse collapse" id="navbar">
						<ul class="nav navbar-nav">
							<li><a href="../index.php">Home</a></li>
							<li><a href="../project.html">Project</a></li>
							<li><a href="../code.html">Code</a></li>
							<li><a href="../about.html">About</a></li>
							<li><a href="../contact.html">Contact</a></li>
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<li><a href="main.php">Welcome, <?php echo $_SESSION['name'] . " " . $_SESSION['surname']?></a></li>
							<li style="font-size: 10px"><a href="logout.php">Logout</a></li>
						</ul>
					</div>
				</div> <!-- container fluid -->
			</nav>
		</div>
		<!-- END HEADER -->

		<!-- BODY -->
		<div class="container" style="padding-bottom: 100px">
			<div class="row">
				<div class="col-sm-3 col-sm-2 sidebar">
					<ul class="nav nav-sidebar">
						<li class="active"><a href="main.php">Overview<span class="sr-only">(current)</span></a></li>
						<li><a href="configuration.php">Configuration</a></li>
					</ul>
					<ul class="nav nav-sidebar">
						<li><a href="graphs.php">Graphs</a></li>
						<li><a href="#">Coming Soon</a></li>
						<li><a href="#">Coming Soon</a></li>
					</ul>
				</div>
				<div class="col-sm-1"></div>
				<div class="col-sm-8 paragB">
				    <div class="row">
				    	<div class="col-sm-12">
				    	<?php include("tabnav.php");?>
				    	<?php include("tabcontent.php");?>
				    	</div>
				    </div>
				</div>

			</div>
		</div>
		<!-- END BODY -->

		<!-- FOOTER -->
		<footer class="footer">
			<div class="container">
				<button type="button" class="btn btn-link btn-sm center-block" id="view-full" style="color:gray">Desktop Mode</button>
			</div>
			<div class="footer-background">
				<div class="container">
					<p class="text-footer">&#169; 2015 Politecnino di Torino. All Rights Reserved. &#169; Andrian 	Putina</p>
				</div>
			</div>
		</footer>
		<!-- END FOOTER -->
		
		<script src="../js/mqttws31.js"></script>

		<!-- MQTT Javascript, same code in mqtt.js -->
		<script>
		var mqttBrokerIp = "192.168.1.254";
		var mqttBrokerPort = 9001;
		var client = null;

		$(document).ready(function () {
		    try {
		        client = new Paho.MQTT.Client(mqttBrokerIp, mqttBrokerPort, "JavaScriptWebPage");
		        client.onConnectionLost = onConnectionLost;
		        client.onMessageArrived = onMessageArrived;
		        client.connect({ onSuccess: onConnect, onFailure: onFailure });

		    } catch (e) {
		        console.log(e.message);
		    } 
		});

		// MQTT stuff
		function onConnect() {
		    // Once a connection has been made, make a subscription and send a message.
		    console.log("Successfully connected to MQTT broker on " + mqttBrokerIp + ":" + mqttBrokerPort);
		    var topic = "<?php echo $_SESSION['cod_user'];?>" + "/" + "+/Light/State";
		    console.log(topic);
		    client.subscribe(topic);
		    var message = new Paho.MQTT.Message("Hello from web");
		    message.destinationName = "motionsensor";
		    client.send(message);
		};
		function onConnectionLost(responseObject) {
		    if (responseObject.errorCode !== 0)
		        console.log("onConnectionLost: " + responseObject.errorMessage);
		};
		function onMessageArrived(message) {
		    console.log("onMessageArrived: " + message.payloadString);

		    var json = JSON.parse(message.payloadString);

			try
			{
				if (json['State'] === "ON" )
			    {	
			    	$("[name='"  + json['Room'] + "']").bootstrapSwitch('state',true, true);
			    }

				if (json['State'] === "OFF" )
			    {
			    	$("[name='"  + json['Room'] + "']").bootstrapSwitch('state',false, true);
			    } 
			}
			catch (e) 
			{
		        console.log(e.message);
		    }        


		};
		function onFailure(responseObject) {
			alert("Impossible to connect to the Broker. Look the console log for more details. Please Try again later.");
		    console.log("onFailure: " + message.payloadString);
		}

		$('input[type="checkbox"]').on('switchChange.bootstrapSwitch', function(event,state){

			//console.log(event);
			
			if (state)
			{
				var message = new Paho.MQTT.Message('{"command":"ON","Room":"' + this.name +  '"}');
		 		message.destinationName = "<?php echo $_SESSION['cod_user'];?>" + "/" + this.name + "/Light";
		 		message.qos=1;
		 		client.send(message);
			}
			else
			{
				var message = new Paho.MQTT.Message('{"command":"OFF","Room":"' + this.name +  '"}');
		 		message.destinationName = "<?php echo $_SESSION['cod_user'];?>" + "/" + this.name + "/Light";
		 		message.qos=1;
		 		client.send(message);
			}


		});
		</script>

		<script type="text/javascript">
			$(document).ready(function(){
				$("[id='checkbox-on']").bootstrapSwitch();});
		</script>

		<script>
		  $(document).ready(function(){
		    $(".GaugeMeter").gaugeMeter();
		  });
		</script>

		<script>
		$(document).ready(function(){
			$('#view-full').bind('click',function(){
				$('meta[name="viewport"]').attr('content','width=1200');
			});
		});
		</script>
	</body>
</html>