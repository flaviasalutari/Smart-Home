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
<script language="javascript">
	function DELRoom(){
		light = document.getElementById("DELLightCase").checked;  
		temp = document.getElementById("DELTempCase").checked;
		name = document.getElementById("listNameId").value; 
		if (light == 1){
			light = 1;
		}
		else{
			light = 0;
		}
		if (temp == 1){
			temp = 1;
		}
		else{
			temp = 0;
		}
		
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (xhttp.readyState == 4 && xhttp.status == 200) {
				document.getElementById("eventualErrorDEL").innerHTML = xhttp.responseText;
			}
		};
		xhttp.open("GET", "Configuration/DeleteDb.php?var1="+name+"&var2="+light+"&var3="+temp, true);
		xhttp.send();	
	}
</script>
	<script language="javascript">
	function loadList1(){
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (xhttp.readyState == 4 && xhttp.status == 200) {
				document.getElementById("list1").innerHTML = xhttp.responseText;
			}
		};
		xhttp.open("GET", "Configuration/ListaNomiDelete.php/?value=1", true);
		xhttp.send();
	}
</script>

<script language="javascript">
	function showCustomer(str){
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (xhttp.readyState == 4 && xhttp.status == 200) {
				document.getElementById("showCheck").innerHTML = xhttp.responseText;
			}
		};
		xhttp.open("GET", "Configuration/ListaNomiDelete.php?value=2&room="+str, true);
		xhttp.send();
	}
</script>

<!-- UPDATE SCRIPTS-->
<script language="javascript">

	function function_lightUP(){
		
		a = document.getElementById("UPprint_light");
		b = document.getElementById("UPLightCase");
		btn = document.getElementById("UPsubmit");

		document.getElementById("UPProfile0").checked = true;
		
		if ( b.checked == true ) { 
			a.style.display ='block';
			btn.style.display = 'block';
		}	
		else {
			document.getElementById("UPProf0").style.display = 'none';
			document.getElementById("UPProf1").style.display = 'none';
			document.getElementById("UPProf2").style.display = 'none';
			document.getElementById("UPProf3").style.display = 'none';
			a.style.display = 'none';
			if(!document.getElementById("UPTempCase").checked){
				btn.style.display = 'none';
			}
		}
		
	}
</script>

<script language="javascript">
	var flagUP = 0;
	function function_temperatureUP(){
		
		a = document.getElementById("UPprint_temp");
		b = document.getElementById("UPTempCase");
		btn = document.getElementById("UPsubmit");
		
		if ( b.checked == true) { 
			var th_low = document.getElementById("UPt_low");
			var th_high = document.getElementById("UPt_high");
			if(flagUP == 0){
				for(var i = 5; i < 41; i++){
					var option1 = document.createElement("option");
					var option2 = document.createElement("option");
					option1.text = i;
					option2.text = i;
					th_low.add(option1);
					th_high.add(option2);
				}
				flagUP = 1;
			}
			
			a.style.display = 'block';
			btn.style.display = 'block';
		}	
		else {
			a.style.display ='none';
			if(!document.getElementById("UPLightCase").checked){
				btn.style.display = 'none';
			}
		}	
	}
</script>

<script language="javascript">
	var flag1UP = 0;
	var flag2UP = 0;
	var flag3UP = 0;
	function profileSelectionUP(val){
	a = document.getElementById("UPProf0");
	b = document.getElementById("UPProf1");
	c = document.getElementById("UPProf2");
	d = document.getElementById("UPProf3");
	a.style.display ='none';
	b.style.display ='none';
	c.style.display ='none';
	d.style.display ='none';
		if (val == 0) {
			a.style.display ='block';
		}
		else if (val == 1) {
			var hon = document.getElementById("UPHonID");
			var mon = document.getElementById("UPMonID");
			var hoff = document.getElementById("UPHoffID");
			var moff = document.getElementById("UPMoffID");
			
			if(flag1UP == 0){
				for(var i = 0; i < 24; i++){
					var option1 = document.createElement("option");
					var option2 = document.createElement("option");
					option1.text = i;
					option2.text = i;
					hon.add(option1);
					hoff.add(option2);
				}
				for(var i = 0; i < 60; i=i+5){
					var option1 = document.createElement("option");
					var option2 = document.createElement("option");
					option1.text = i;
					option2.text = i;
					mon.add(option1);
					moff.add(option2);
				}
				flag1UP = 1;
			}
			b.style.display ='block';
		}	
		else if (val == 2) {
			var th2 = document.getElementById("UPTh2ID");
			if(flag2UP == 0){
				for(var i = 1; i < 60; i++){
					var option = document.createElement("option");
					option.text = i;
					th2.add(option);
				}
				flag2UP = 1;
			}
			c.style.display ='block';
		}	
		else if (val == 3) {
			var th3 = document.getElementById("UPTh3ID");
			if(flag3UP == 0){
				for(var i = 1; i < 60; i++){
					var option = document.createElement("option");
					option.text = i;
					th3.add(option);
				}
				flag3UP = 1;
			}
			
			d.style.display ='block';
		}	
	}
</script>

<script language="javascript">
	function summaryUP(){
		light = document.getElementById("UPLightCase").checked; // //<!-- Retrieve if light is checked
		temp = document.getElementById("UPTempCase").checked; ////<!-- Retrieve if temperature is checked
		var hon = parseInt(document.getElementById("UPHonID").value); ////<!-- Retrieve the hour and minute for profile 1
		var mon = parseInt(document.getElementById("UPMonID").value); ////<!-- le ho messe fuori dall'if sottostante perche tanto servono in ogni caso per il json
		var hoff = parseInt(document.getElementById("UPHoffID").value);
		var moff = parseInt(document.getElementById("UPMoffID").value);
		if(light == true){
			light=1; ////<!-- faccio questa assegnazione cosi che light diventa 1 ed è a posto per il json
			if(document.getElementById("UPProfile1").checked == true){
				var on = hon*60 + mon;
				var off = hoff*60 + moff;
				if(off <= on){
					alert("Can you travel back in time?!");
					return;
				}
			}	
		}
		else{
			light=0;
		}
		Tlow = parseInt(document.getElementById("UPt_low").value); //<!-- Retrieve the temperature threshold
		Thigh = parseInt(document.getElementById("UPt_high").value); //<!-- le ho messe fuori dall'if sottostante perche tanto servono in ogni caso per il json
		if(temp == true){
			temp = 1; //<!-- faccio questa assegnazione cosi che true diventa 1 ed è a posto per il json
			if(Thigh <= Tlow){
				alert("Temperature must be in obvious range");
				return;
			}
		}
		else{
			temp = 0;
		}
		name = document.getElementById("UPlistNameId").value; //<!-- Retrieve the name
		flag = 0;
		for(var i=0; (i<4 && flag==0); i++){ //<!-- Retrieve the profile
			str = "UPProfile"+i;
			if(document.getElementById(str).checked == true){
				flag=1;
			}
		}
		i = i-1;
		th2 = parseInt(document.getElementById("UPTh2ID").value); //<!-- Retrieve the threshold light for profile 2
		th3 = parseInt(document.getElementById("UPTh3ID").value); //<!-- Retrieve the threshold light for profile 3
		
		var item = [hon, mon, hoff, moff, th2, th3, Tlow, Thigh];
		for(var y=0; y<8; y++){
			if (isNaN(item[y])){
				item[y] = '"NULL"';
			}
		}
		//<!-- Creating the json jsonString
		//<!--jsonString = '{"Name":"'+name+'","Light":'+light+',"Temperature":'+temp+',"HourOn":'+hon+',"MinuteOn":'+mon+',"HourOff":'+hoff+',"MinuteOff":'+moff+',"Thre2":'+th2+',"Thre3":'+th3+',"Tlow":'+Tlow+',"Thigh":'+Thigh+'}';
		jsonString = '{"Name":"'+name+'","Light":'+light+',"Temperature":'+temp+',"Profile":'+i+',"HourOn":'+item[0]+',"MinuteOn":'+item[1]+',"HourOff":'+item[2]+',"MinuteOff":'+item[3]+',"Thre2":'+item[4]+',"Thre3":'+item[5]+',"Tlow":'+item[6]+',"Thigh":'+item[7]+'}';
		
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (xhttp.readyState == 4 && xhttp.status == 200) {
				document.getElementById("UPeventualError").innerHTML = xhttp.responseText;
			}
		};
		xhttp.open("GET", "Configuration/LeggoDbUpdate.php?var="+jsonString, true);
		xhttp.send();
		//<!--window.open('PagTransizione.html','_self',false); //<!-- Call the new page after the submit
	}
</script>
<script language="javascript">
	function loadListUP(){
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (xhttp.readyState == 4 && xhttp.status == 200) {
				document.getElementById("UPlist").innerHTML = xhttp.responseText;
			}
		};
		xhttp.open("GET", "Configuration/ListaNomiUpdate.php", true);
		xhttp.send();
	}
</script>

<!-- END UPDATE SCRIPTS-->

<!-- END JS -->
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
				</ul>
			</div>

		</div> <!-- container full -->
		</nav>
	</div>

	<div class="container" style="padding-bottom: 100px">
		<div class="row">
			<div class="col-sm-3 col-sm-2 sidebar">
					<ul class="nav nav-sidebar">
						<li><a href="main.php">Overview<span class="sr-only">(current)</span></a></li>
						<li class="active"><a href="configuration.php">Configuration</a></li>
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
				    	<?php include("tabcontentconfiguration.php");?>
				    	</div> 
			    </div>
			</div>


</div>

</div>

		<!-- FOOTER -->
		<footer class="footer">
			<div class="container">
				<div class="col-sm-12">
					<div class="col-sm-offset-4 btn-group btn-group-sm">
					<button type="button" class="btn btn-default" id="view-full" data-toggle="modal" data-target="#AddRoomModal" style="margin-left: 55px;margin-right: 10px">Add Room</button>

					<button type="button" class="btn btn-default" id="view-full" style="margin-right: 10px" data-toggle="modal" data-target="#UpdateRoomModal">Update Room</button>
					<button type="button" class="btn btn-default" id="view-full" data-toggle="modal" data-target="#DeleteModal">Delete Room</button>
					</div>
				</div>
				<button type="button" class="btn btn-link btn-sm center-block" id="view-full" style="color:gray">Desktop Mode</button>
			</div>
			<div class="footer-background">
				<div class="container">
					<p class="text-footer">&#169; 2015 Politecnino di Torino. All Rights Reserved. &#169; Andrian Putina</p>
				</div>
			</div>
		</footer>
		<!-- END FOOTER -->



<!-- Modal -->
<div class="modal fade bs-example-modal-sm" id="AddRoomModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">

	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Add Room</h4>
	      </div>

	      <div class="modal-body">
	      	<form name="FrmPrenotazione" method="post" id="form1">
	      	<fieldset>
		      	<div class="form-group">
					<input type="text" name="nome" id="CasellaNome" class="form-control" placeholder="Room name" required>
				</div>

				<div class="form-group">
					Enable Light: <input type="checkbox" name="light" id="LightCase" onchange="function_light();" >
					<div style="padding-left: 10%"> </div>
					<div id="print_light" style="display:none">
						Profile: <input type="radio" name="profile" id="Profile0" value="0" onchange="profileSelection(value);"> 0
								<input type="radio" name="profile" id="Profile1" value="1" onchange="profileSelection(value);"> 1
								<input type="radio" name="profile" id="Profile2" value="2" onchange="profileSelection(value);"> 2
								<input type="radio" name="profile" id="Profile3" value="3" onchange="profileSelection(value);"> 3
					</div>
					<div id="Prof0" style="display:none">
					</div>
					<div id="Prof1" style="display:none">
						Turn on time: <select id="HonID"></select>
						<select id="MonID"></select><br></br>
						Turn off time: <select id="HoffID"></select>
						<select id="MoffID"></select><br></br>
					</div>
					<div id="Prof2" style="display:none">
						Turn off in <select name="thr2" id="Th2ID"></select> seconds<br></br>
					</div>
					<div id="Prof3" style="display:none">
						Turn off in <select name="thr3" id="Th3ID"></select> seconds<br></br>
					</div>
				</div>

				<div class="form-group">
					Enable Temperature:<input type="checkbox" name="temp" id="TempCase" onchange="function_temperature();"> </br></br>
					<div style="padding-left: 10%"></div>
					<div id="print_temp" style="display:none">
						Threshold Low (&deg C): <select name="thrL" id="t_low"></select><br></br>
						Threshold High (&deg C): <select name="thrH" id="t_high"></select><br></br><br></br>
					</div>
				</div>

				<div class="form-group">
					<div align="center" id="submit" style="display:none; padding-bottom: 5%">
						<input value ="Add Room" type="button" class="form-control btn btn-primary" name="SUB" onclick="summary();">
					</div>
					<div id="eventualError">
					</div>
				</div>
				</fieldset>
			</form>
	      </div>

	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
    </div>
  </div>
</div>
<!--END AddRoom Modal -->


<!-- Modal Delete Room -->
<div class="modal fade bs-example-modal-sm" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">

	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Delete Room</h4>
	      </div>

	      <div class="modal-body">
				<form name="FrmPrenotazione" method="post" id="form1">
					<div id="list1">
						<script type="text/javascript">
							loadList1('list1');
						</script>
					</div>
					<div id='showCheck' style='padding-bottom: 5%'>
					</div>
					<div id="eventualErrorDEL">
					</div>
				</form>
	      </div>

	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
    </div>
  </div>
</div>
<!--END DeleteRoom Modal -->

<!-- UPDATE ROOM -->
<!-- Modal -->
<div class="modal fade bs-example-modal-sm" id="UpdateRoomModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">

	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Update Room</h4>
	      </div>

	      <div class="modal-body">
	      	<form name="FrmPrenotazione" method="post" id="UPform1">
	      	<fieldset>
	      	<div class="form-group">
			    <div id="UPlist">
					<script type="text/javascript">
						loadListUP('UPlist');
					</script>
				</div>
	      	</div>

	      	<div class="form-group">
	      		Enable Light:<input type="checkbox" name="light" id="UPLightCase" onchange="function_lightUP();" >
	      	</div>

	      	<div class="form-group">
	      		<div id="UPprint_light" style="display:none">
				Profile: <input type="radio" name="profile" id="UPProfile0" value="0" onchange="profileSelectionUP(value);"> 0
						<input type="radio" name="profile" id="UPProfile1" value="1" onchange="profileSelectionUP(value);"> 1
						<input type="radio" name="profile" id="UPProfile2" value="2" onchange="profileSelectionUP(value);"> 2
						<input type="radio" name="profile" id="UPProfile3" value="3" onchange="profileSelectionUP(value);"> 3<br></br>
				</div>
	      	</div>

	      	<div class="form-group">
		      	<div id="UPProf0" style="display:none">
				</div>
				<div id="UPProf1" style="display:none">
					Turn on time: <select id="UPHonID"></select>
					<select id="UPMonID"></select><br></br>
					Turn off time: <select id="UPHoffID"></select>
					<select id="UPMoffID"></select><br></br>
				</div>
				<div id="UPProf2" style="display:none">
					Threshold: <select name="thr2" id="UPTh2ID"></select> seconds<br></br>
				</div>
				<div id="UPProf3" style="display:none">
					Threshold: <select name="thr3" id="UPTh3ID"></select> seconds<br></br>
				</div>
			</div>

			<div class="form-group">
				Enable Temperature: <input type="checkbox" name="temp" id="UPTempCase" onchange="function_temperatureUP();"> <br></br>
			</div>

			<div class="form-group">
				<div id="UPprint_temp" style="display:none">
					Threshold Low (&deg C): <select name="thrL" id="UPt_low"></select><br></br>
					Threshold High (&deg C): <select name="thrH" id="UPt_high"></select><br></br><br></br>
				</div>
			</div>

			<div class="form-group">
					<div align="center" id="UPsubmit" style="display:none; padding-bottom: 5%">
						<input value ="Update Room" type="button" class="form-control btn btn-primary" name="SUB" onclick="summaryUP();">
					</div>
			</div>

			<div class="form-group">
				<div id="UPeventualError">
				</div>
			</div>

				</fieldset>
			</form>
	      </div>

	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
    </div>
  </div>
</div>
<!-- END UDPATE ROOM -->


<script src="../js/jquery-2.2.1.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/bootstrap-switch.js"></script>
<script src="../js/jquery.AshAlom.gaugeMeter-2.0.0.min.js"></script>
<script>
$(document).ready(function(){
	$('#view-full').bind('click',function(){
		$('meta[name="viewport"]').attr('content','width=1200');
	});
});
</script>

<!-- SCRIPTS -->


<script language="javascript">

	function function_light(){
		
		a = document.getElementById("print_light");
		b = document.getElementById("LightCase");
		btn = document.getElementById("submit");
		
		document.getElementById("Profile0").checked = true;
		
		if ( b.checked == true ) { 
			a.style.display ='block';
			btn.style.display = 'block';
		}	
		else {
			document.getElementById("Prof0").style.display = 'none';
			document.getElementById("Prof1").style.display = 'none';
			document.getElementById("Prof2").style.display = 'none';
			document.getElementById("Prof3").style.display = 'none';
			a.style.display = 'none';
			if(!document.getElementById("TempCase").checked){
				btn.style.display = 'none';
			}
		}
		
	}
</script>

<script language="javascript">
	var flag = 0;
	function function_temperature(){
		
		a = document.getElementById("print_temp");
		b = document.getElementById("TempCase");
		btn = document.getElementById("submit");
		
		if ( b.checked == true) { 
			var th_low = document.getElementById("t_low");
			var th_high = document.getElementById("t_high");
			if(flag == 0){
				for(var i = 5; i < 41; i++){
					var option1 = document.createElement("option");
					var option2 = document.createElement("option");
					option1.text = i;
					option2.text = i;
					th_low.add(option1);
					th_high.add(option2);
				}
				flag = 1;
			}
			
			a.style.display = 'block';
			btn.style.display = 'block';
		}	
		else {
			a.style.display ='none';
			if(!document.getElementById("LightCase").checked){
				btn.style.display = 'none';
			}
		}	
	}
</script>

<script language="javascript">
	var flag1 = 0;
	var flag2 = 0;
	var flag3 = 0;
	function profileSelection(val){
	a = document.getElementById("Prof0");
	b = document.getElementById("Prof1");
	c = document.getElementById("Prof2");
	d = document.getElementById("Prof3");
	a.style.display ='none';
	b.style.display ='none';
	c.style.display ='none';
	d.style.display ='none';
		if (val == 0) {
			a.style.display ='block';
		}
		else if (val == 1) {
			var hon = document.getElementById("HonID");
			var mon = document.getElementById("MonID");
			var hoff = document.getElementById("HoffID");
			var moff = document.getElementById("MoffID");
			
			if(flag1 == 0){
				for(var i = 0; i < 24; i++){
					var option1 = document.createElement("option");
					var option2 = document.createElement("option");
					option1.text = i;
					option2.text = i;
					hon.add(option1);
					hoff.add(option2);
				}
				for(var i = 0; i < 60; i=i+5){
					var option1 = document.createElement("option");
					var option2 = document.createElement("option");
					option1.text = i;
					option2.text = i;
					mon.add(option1);
					moff.add(option2);
				}
				flag1 = 1;
			}
			b.style.display ='block';
		}	
		else if (val == 2) {
			var th2 = document.getElementById("Th2ID");
			if(flag2 == 0){
				for(var i = 1; i < 60; i++){
					var option = document.createElement("option");
					option.text = i;
					th2.add(option);
				}
				flag2 = 1;
			}
			
			c.style.display ='block';
		}	
		else if (val == 3) {
			var th3 = document.getElementById("Th3ID");
			if(flag3 == 0){
				for(var i = 1; i < 60; i++){
					var option = document.createElement("option");
					option.text = i;
					th3.add(option);
				}
				flag3 = 1;
			}
			
			d.style.display ='block';
		}	
	}
</script>

<script language="javascript">
	function summary(){
		room_name = document.getElementById("CasellaNome").value;
		light = document.getElementById("LightCase").checked;
		temp = document.getElementById("TempCase").checked;
		
		if(room_name == ""){
			alert("A room name should be given");
			return;
		}
		
		if(light == true){
			light = 1;
			if(document.getElementById("Profile1").checked == true){
				var hon = parseInt(document.getElementById("HonID").value);
				var mon = parseInt(document.getElementById("MonID").value);
				var hoff = parseInt(document.getElementById("HoffID").value);
				var moff = parseInt(document.getElementById("MoffID").value);
				var val_on = hon*60 + mon;
				var val_off = hoff*60 + moff;
				if(val_off <= val_on){
					alert("Turn off time should be posterior to turn on time...");
					return;
				}
			}
		}
		else{
			light = 0;
		}
		
		if(temp == true){
			temp = 1;
			var th_low = parseInt(document.getElementById("t_low").value);
			var th_high = parseInt(document.getElementById("t_high").value);
			if(th_low >= th_high){
				alert("The lower threshold should be... LOWER!!!");
				return;
			}
		}
		else{
			temp = 0;
		}
		flag = 0;
		for(var i=0; (i<4 && flag==0); i++){ <!-- Retrieve the profile
			str = "Profile"+i;
			if(document.getElementById(str).checked == true){
				flag=1;
			}
		}
		i = i-1;
		th2 = parseInt(document.getElementById("Th2ID").value); 
		th3 = parseInt(document.getElementById("Th3ID").value); 

		var item = [hon, mon, hoff, moff, th2, th3, th_low, th_high];
		for(var y=0; y<8; y++){
			if (isNaN(item[y])){
				item[y] = 'NULL';
			}
		}
		
		var obj = new Object();
		obj.Name = room_name;
		obj.Light = light;
		obj.Temp = temp;
		obj.Profile  = i;
		obj.HourOn = item[0];
		obj.MinuteOn = item[1];
		obj.HourOff = item[2];
		obj.MinuteOff = item[3];
		obj.Thre2 = item[4];
		obj.Thre3 = item[5];
		obj.Tlow = item[6];
		obj.Thigh = item[7];
		var jsonString = JSON.stringify(obj);
		
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (xhttp.readyState == 4 && xhttp.status == 200) {
				document.getElementById("eventualError").innerHTML = xhttp.responseText;	
			}
		};
		xhttp.open("GET", "Configuration/LeggoDbCreate.php?var="+jsonString, true);
		xhttp.send();
	}
</script>
<!--END SCRIPTS -->






</body>
</html>