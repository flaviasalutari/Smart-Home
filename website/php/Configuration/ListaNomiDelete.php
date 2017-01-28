<?php
    try{
		$cn = mysql_connect("localhost", "root", ""); //Open database
		mysql_select_db("iot", $cn);
	}
	catch (Exception $e) {
		echo 'Caught exception: ',  $e->getMessage(), "\n";
	}
	
	session_start();
	
	
	$app = $_GET['value'];
	
	if($app==1){
		//$appMail = "'{$_SESSION['email']}'"; // Use it while running the whole system
		$string = "SELECT * FROM rooms where user_cod_user =".$_SESSION['cod_user'] ;
		
		//$string = ("SELECT * FROM rooms where user_cod_user = 47");
		$query = mysql_query($string, $cn);
		$quanti = mysql_num_rows($query);
		if ($quanti == 0)
		{
			echo "No record!";
		}
		else
		{
			echo "<b>Room Name: </b>&nbsp&nbsp";
			echo "<select name='listName' id='listNameId' onchange='showCustomer(this.value);'>";
			echo "<option value=''></option>";
			for($x=0; $x<$quanti; $x++)
			{
				$rs = mysql_fetch_row($query);
				echo "<option value='$rs[1]'>$rs[1]</option>";
			}
			echo "</select>";
		}
	}
	else{
		$rm = $_GET['room'];
		$appName = "'{$rm}'";
		
		//$appMail = "'{$_SESSION['email']}'"; // Use it while running the whole system
		//$string = ("SELECT * FROM rooms where Name = $appName AND user_cod_user = (SELECT cod_user FROM user where email = $appMail) AND Light = 1");
		
		$string = ("SELECT * FROM rooms where Name = $appName AND user_cod_user = ".$_SESSION['cod_user']." AND Light = 1");
		$query = mysql_query($string, $cn);
		$no1 = mysql_num_rows($query);
		if ($no1 == 1){
			echo "<div id='LightDiv'>";
			echo "<b>Delete Light:</b> <input type='checkbox' name='light' id='DELLightCase'> <br></br>";
			echo "</div>";
		}
		else{
			echo "<div id='LightDiv' style='display:none'>";
			echo "<input type='checkbox' name='light' id='DELLightCase'> <br></br>";
			echo "</div>";
		}
		
		$string = ("SELECT * FROM rooms where Name = $appName AND user_cod_user = ".$_SESSION['cod_user']." AND Temperature = 1");
		$query = mysql_query($string, $cn);
		$no2 = mysql_num_rows($query);
		if($no2 == 1){
			echo "<div id='TempDiv'>";
			echo "<b>Delete Temperature: </b><input type='checkbox' name='temp' id='DELTempCase'> <br></br>";
			echo "</div>";
		}
		else{
			echo "<div id='TempDiv' style='display:none'>";
			echo "<input type='checkbox' name='temp' id='DELTempCase'> <br></br>";
			echo "</div>";
		}
		if(($no1==1)||($no2==1)){
			echo "<input type='button' value ='Delete Room' id='clickDEL' class='form-control btn btn-primary' onclick='DELRoom();'>";
		}
		else{
			echo "<input type='button' value='Submit!!' id='clickDEL' style='display:none'>";
		}
	}
    mysql_close($cn);
?>