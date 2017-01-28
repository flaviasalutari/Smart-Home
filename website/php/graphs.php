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
		<script src="../js/mqttws31.js"></script>
		<script src="../js/MQTT.js"></script>
		<script src="../js/Chart.bundle.js"></script>


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

 		<script language="javascript">
	   		function PlotNowTemperature()
	   		{
	   			var fromdate = document.getElementById("fromyear").value + "-" + document.getElementById("frommonth").value + "-" +document.getElementById("fromday").value;
	   			var todate = document.getElementById("toyear").value + "-" + document.getElementById("tomonth").value + "-" +document.getElementById("today").value;

	   			var active = $('.nav-tabs').find('.active').attr('id'); 

				var xhttp;
				xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
				 	if (xhttp.readyState == 4 && xhttp.status == 200) {
				 		$('head').append(xhttp.responseText) //xhttp.responseText;
				 	}
				 };
				 xhttp.open("GET", "NewPlot.php?"+active+"&from='"+fromdate+"'&to='"+todate+"'", true);
				 xhttp.send();
	   		}

	   		function PlotNowLight()
	   		{
	   			var fromdate = document.getElementById("fromyear").value + "-" + document.getElementById("frommonth").value + "-" +document.getElementById("fromday").value;
	   			var todate = document.getElementById("toyear").value + "-" + document.getElementById("tomonth").value + "-" +document.getElementById("today").value;

	   			var active = $('.nav-tabs').find('.active').attr('id');

	   			var xhttp2;
				xhttp2 = new XMLHttpRequest();
				xhttp2.onreadystatechange = function() {
				 	if (xhttp2.readyState == 4 && xhttp2.status == 200) {
				 		$('head').append(xhttp2.responseText) //xhttp.responseText;
				 	}
				 };
				 xhttp2.open("GET", "NewPlotLight.php?"+active+"&from='"+fromdate+"'&to='"+todate+"'", true);
				 xhttp2.send(); 
	   		}
	   </script> 


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
							<li style="font-size: 10px"><a href="#">Logout</a></li>
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
						<li><a href="main.php">Overview<span class="sr-only">(current)</span></a></li>
						<li><a href="configuration.php">Configuration</a></li>
					</ul>
					<ul class="nav nav-sidebar">
						<li class="active"><a href="graphs.php">Graphs</a></li>
						<li><a href="#">Coming Soon</a></li>
						<li><a href="#">Coming Soon</a></li>
					</ul>
				</div>
				<div class="col-sm-1"></div>
				<div class="col-sm-8 paragB">
				    <div class="row">
				    	<div class="col-sm-12">
				    	<?php include("tabnav.php");?>
				    	<?php include("tabcontentgraphs.php");?>
				    	</div>
				    </div>
				    <div class="col-sm-12 text-center" style="padding-top: 20px">
						
						<form name="form">
						  <div class=form-group>
								From:
						    <select id="frommonth">
						        <option id="1" value="01">January</option>
						        <option id="2" value="02">February</option>
						        <option id="3" value="03">March</option>
						        <option id="4" value="04">April</option>
						        <option id="5" value="05">May</option>
						        <option id="6" value="06">June</option>
						        <option id="7" value="07">July</option>
						        <option id="8" value="08">August</option>
						        <option id="9" value="09">September</option>
						        <option id="10" value="10">October</option>
						        <option id="11" value="11">November</option>
						        <option id="12" value="12">December</option>
						    </select>
						    /
						    <select id="fromday">
						        <option id="1" value="01">1</option>
						        <option id="2" value="02">2</option>
						        <option id="3" value="03">3</option>
						        <option id="4" value="04">4</option>
						        <option id="5" value="05">5</option>
						        <option id="6" value="06">6</option>
						        <option id="7" value="07">7</option>
						        <option id="8" value="08">8</option>
						        <option id="9" value="09">9</option>
						        <option id="10" value="10">10</option>
						        <option id="11" value="11">11</option>
						        <option id="12" value="12">12</option>
						        <option id="13" value="13">13</option>
						        <option id="14" value="14">14</option>
						        <option id="15" value="15">15</option>
						        <option id="16" value="16">16</option>
						        <option id="17" value="17">17</option>
						        <option id="18" value="18">18</option>
						        <option id="19" value="19">19</option>
						        <option id="20" value="20">20</option>
						        <option id="21" value="21">21</option>
						        <option id="22" value="22">22</option>
						        <option id="23" value="23">23</option>
						        <option id="24" value="24">24</option>
						        <option id="25" value="25">25</option>
						        <option id="26" value="26">26</option>
						        <option id="27" value="27">27</option>
						        <option id="28" value="28">28</option>
						        <option id="29" value="29">29</option>
						        <option id="30" value="30">30</option>
						        <option id="31" value="31">31</option>
						    </select>
						    /
						    <select id="fromyear">
						        <option id="2016" value="2016">2016</option>
						    </select>

							To:     
						    <select id="tomonth">
						        <option id="1" value="01">January</option>
						        <option id="2" value="02">February</option>
						        <option id="3" value="03">March</option>
						        <option id="4" value="04">April</option>
						        <option id="5" value="05">May</option>
						        <option id="6" value="06">June</option>
						        <option id="7" value="07">July</option>
						        <option id="8" value="08">August</option>
						        <option id="9" value="09">September</option>
						        <option id="10" value="10">October</option>
						        <option id="11" value="11">November</option>
						        <option id="12" value="12">December</option>
						    </select>
						    /
						    <select id="today" >
						        <option id="1" value="01">1</option>
						        <option id="2" value="02">2</option>
						        <option id="3" value="03">3</option>
						        <option id="4" value="04">4</option>
						        <option id="5" value="05">5</option>
						        <option id="6" value="06">6</option>
						        <option id="7" value="07">7</option>
						        <option id="8" value="08">8</option>
						        <option id="9" value="09">9</option>
						        <option id="10" value="10">10</option>
						        <option id="11" value="11">11</option>
						        <option id="12" value="12">12</option>
						        <option id="13" value="13">13</option>
						        <option id="14" value="14">14</option>
						        <option id="15" value="15">15</option>
						        <option id="16" value="16">16</option>
						        <option id="17" value="17">17</option>
						        <option id="18" value="18">18</option>
						        <option id="19" value="19">19</option>
						        <option id="20" value="20">20</option>
						        <option id="21" value="21">21</option>
						        <option id="22" value="22">22</option>
						        <option id="23" value="23">23</option>
						        <option id="24" value="24">24</option>
						        <option id="25" value="25">25</option>
						        <option id="26" value="26">26</option>
						        <option id="27" value="27">27</option>
						        <option id="28" value="28">28</option>
						        <option id="29" value="29">29</option>
						        <option id="30" value="30">30</option>
						        <option id="31" value="31">31</option>
						    </select>
						    /
						    <select id="toyear" >
						        <option id="2016" value="2016">2016</option>
						    </select>
						   </div>

						   <div class="row">
						   		<div class="col-sm-12">
						   				<div class="col-sm-offset-2 col-sm-4">
						   				<input value ="Plot Now Temp" type="button" class="form-control btn btn-primary" name="Plot + Now Temp" onclick="PlotNowTemperature();">
						   				</div>
										<div class="col-sm-4">
										<input value ="Plot Now Light" type="button" class="form-control btn btn-primary" name="Plot + Now Light" onclick="PlotNowLight();">
									</div>
						   		</div>

						   </div>
						</form>

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

	</body>
</html>