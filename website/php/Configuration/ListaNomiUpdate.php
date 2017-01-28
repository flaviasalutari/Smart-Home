<?php
    try{
		$cn = mysql_connect("localhost", "root", ""); //Open database
		mysql_select_db("iot", $cn);
	}
	catch (Exception $e) {
		echo 'Caught exception: ',  $e->getMessage(), "\n";
	}
	
	session_start();
	//$appMail = "'{$_SESSION['email']}'"; // Use it while running the whole system
	//$string = ("SELECT * FROM rooms where user_cod_user = (SELECT cod_user FROM user where email = $appMail)");
	
	$string = ("SELECT * FROM rooms where user_cod_user = ".$_SESSION['cod_user']);
	$query = mysql_query($string, $cn);
    $quanti = mysql_num_rows($query);
	if ($quanti == 0)
    {
        echo "No record!";
    }
    else
    {
		echo "<b>Room Name: </b>&nbsp&nbsp";
		echo "<select name='listName' id='UPlistNameId'>";
        for($x=0; $x<$quanti; $x++)
        {
            $rs = mysql_fetch_row($query);
            echo "<option value='$rs[1]'>$rs[1]</option>";
        }
		echo "</select>";
    }
    mysql_close($cn);
?>