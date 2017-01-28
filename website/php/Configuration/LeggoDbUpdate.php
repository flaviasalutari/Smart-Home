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
	if ($mqtt->connect(true,NULL,"","")) { //third fiel username , forth field password		
		try{
			$cn = mysql_connect("localhost", "root", ""); //Open database
			mysql_select_db("iot", $cn);
		}
		catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		
		$app = $_GET['var']; // Get the json string from the URL
		$result = json_decode($app); //Decode the json string
		
		if($result->Light == 1){ // If true there are changes for light
			$jsonLight = new PubblicationLight(); //define new object for json light
			
			
			//$appMail = "'{$_SESSION['email']}'"; // Use it while running the whole system
			$appName = "'{$result->Name}'";
			//$string = ("SELECT Profile FROM rooms where Name = $appName AND user_cod_user = (SELECT cod_user FROM user where email = $appMail) AND light = 1");
			$string = ("SELECT Profile FROM rooms where Name = $appName AND user_cod_user = ".$_SESSION['cod_user']." AND light = 1");
			$query = mysql_query($string, $cn);
			$number = mysql_num_rows($query);
			if($number == 1){ //Other decision
				$rs = mysql_fetch_row($query);
				if($rs[0] == $result->Profile){ //same profile --> change threshold or change parameters
					if($rs[0]==0){ //if there is no change for profile 0 there is no need to do the publish
						$flag = 0;
					}
					else if($rs[0]==1){ //change parameters
						$flag = 1;
						$jsonLight->Type = "Change parameters";
					}
					else{ //change threshold
						$flag = 1;
						$jsonLight->Type = "Change threshold";
					}
				}
				else{ //other profile --> change profile
					$flag = 1;
					$jsonLight->Type = "Change profile";
				}
			}
			else{ //AddRoom
				$flag = 1;
				$jsonLight->Type = "AddRoom";
			}
			if($flag == 1){
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
					echo "Fail or time out<br />";
				}
			}
		}
		
		if($result->Temperature == 1){
			$jsonTemp = new PubblicationTemp(); //define new object for json light
			
			//$appMail = "'{$_SESSION['email']}'"; // Use it while running the whole system
			$appName = "'{$result->Name}'";
			//$string = ("SELECT * FROM rooms where Name = $appName AND user_cod_user = (SELECT cod_user FROM user where email = $appMail) AND temperature = 1");
			$string = ("SELECT * FROM rooms where Name = $appName AND user_cod_user = ".$_SESSION['cod_user']." AND temperature = 1");
			$query = mysql_query($string, $cn);
			$number = mysql_num_rows($query);
			if($number == 1){ //Change thresholds
				$jsonTemp->Type = "Change thresholds";
			}
			else{ // AddRoom
				$jsonTemp->Type = "Add Room";
			}
			$jsonTemp->Room = $result->Name;
			$jsonTemp->Threshold_Low = $result->Tlow;
			$jsonTemp->Threshold_High = $result->Thigh;
			
			$strJson = json_encode($jsonTemp);
			
			if ($mqtt->connect(true,NULL,"","")) { //make the publish
				$mqtt->publish($_SESSION['cod_user'] . "/" ."Configuration",$strJson, 0);
				$mqtt->close();
			}
			else{
				echo "Fail or time out<br />";
			}
		}
		
		echo "Success";
		mysql_close($cn);
	}
	else{
		echo "Server or internal error";
	}
?>