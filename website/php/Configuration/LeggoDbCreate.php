<?php
	require("../phpMQTT.php");
	
	session_start();

    class PubblicationLight {
    public $Type = "";
    public $Room  = "";
    public $Profile = "";
	public $Hour_On = "";
	public $Minute_On = "";
	public $Hour_Off = "";
	public $Minute_Off = "";
	public $Threshold = "";
	}
	
	class PubblicationTemp {
    public $Type = "";
    public $Room  = "";
    public $Threshold_Low = "";
	public $Threshold_High = "";
	}
	
	$mqtt = new phpMQTT("192.168.1.254", 1883, "ClientID".rand()); // Open mqtt connection

	if ($mqtt->connect(true,NULL,"",""))
	{
	try{
		$cn = mysql_connect("localhost", "root", ""); //Open database
		mysql_select_db("iot", $cn);
	}
	catch (Exception $e) {
		echo 'Caught exception: ',  $e->getMessage(), "\n";
	}
	
	$app = $_GET['var']; // Get the json string from the URL
	$result = json_decode($app); //Decode the json string
	
	//$appMail = "'{$_SESSION['email']}'"; // Use it while running the whole system
	$appName = "'{$result->Name}'";
	//$string = ("SELECT * FROM rooms where Name = $appName AND user_cod_user = 3");
	$string = ("SELECT * FROM rooms where Name = $appName AND user_cod_user = ".$_SESSION['cod_user']." ");
	$query = mysql_query($string, $cn);
	$row = mysql_num_rows($query);
	
	if($row){
		/*echo '<script language="text/javascript">';
		echo 'alert("This room already exist")';
		echo '</script>';*/
		echo "This room already exist";
	}
	else{
		if($result->Light == 1){ // If true there are changes for light
			$jsonLight = new PubblicationLight(); //define new object for json light

			$jsonLight->Type = "AddRoom";
			$jsonLight->Room = $result->Name;
			$jsonLight->Profile = $result->Profile;
			$jsonLight->Hour_On = $result->HourOn;
			$jsonLight->Minute_On = $result->MinuteOn;
			$jsonLight->Hour_Off = $result->HourOff;
			$jsonLight->Minute_Off = $result->MinuteOff;
			if($result->Profile == 2){
				$jsonLight->Threshold = $result->Thre2;
			}
			else{
				$jsonLight->Threshold = $result->Thre3;
			}
			$strJson = json_encode($jsonLight);
			
			if ($mqtt->connect(true,NULL,"","")) { //make the publish
				$mqtt->publish($_SESSION['cod_user'] . "/" ."Configuration",$strJson, 0);
				$mqtt->close();
			}
			else{
				echo "Fail or time out<br></br>";
			}
		}
		
		if($result->Temp == 1){
			$jsonTemp = new PubblicationTemp(); //define new object for json light
			
			$jsonTemp->Type = "Add Room";
			$jsonTemp->Room = $result->Name;
			$jsonTemp->Threshold_Low = $result->Tlow;
			$jsonTemp->Threshold_High = $result->Thigh;
			
			$strJson = json_encode($jsonTemp);
			
			if ($mqtt->connect(true,NULL,"","")){ //make the publish
				$mqtt->publish($_SESSION['cod_user'] . "/" ."Configuration",$strJson, 0);
				$mqtt->close();
				echo "Success";
			}
			else{
				echo "Fail or time out<br />";
			}
		}
	}
    mysql_close($cn);
	}

	else
	{
		echo "Internal Error. Please try again later.";
	}


?>