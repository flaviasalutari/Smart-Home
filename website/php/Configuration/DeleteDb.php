<?php
	session_start();
	require("../phpMQTT.php");

    class PubblicationLight {
    public $Type = "";
    public $Room  = "";
	}
	
	class PubblicationTemp {
    public $Type = "";
    public $Room  = "";
	}
	
	$mqtt = new phpMQTT("192.168.1.254", 1883, "ClientID".rand()); // Open mqtt connection

	if ($mqtt->connect(true,NULL,"",""))
	{
			$name = $_GET['var1'];
	$l = $_GET['var2'];
	$t = $_GET['var3'];
	
	if($l == 1){ // If true the light should be deleted
		$jsonLight = new PubblicationLight(); //define new object for json light
		
		$jsonLight->Type = "Delete Room Light";
		$jsonLight->Room = $name;

		$strJson = json_encode($jsonLight);
		
		if ($mqtt->connect(true,NULL,"","")) { //make the publish
			$mqtt->publish($_SESSION['cod_user'] . "/" . "Configuration",$strJson, 1);
			$mqtt->close();
		}
		else{
			echo "Fail or time out<br />";
		}
	}
	
	if($t == 1){
		$jsonTemp = new PubblicationTemp(); //define new object for json light
		
		$jsonTemp->Type = "Delete Room";
		$jsonTemp->Room = $name;
	
		$strJson = json_encode($jsonTemp);
		
		if ($mqtt->connect(true,NULL,"","")) { //make the publish
			$mqtt->publish($_SESSION['cod_user'] . "/" ."Configuration",$strJson, 1);
			$mqtt->close();
		}
		else{
			echo "Fail or time out<br />";
		}
	}
	
	echo "Success";
	}
	
	else
	{
		echo "Internal Error. Please try again later.";
	}

?>