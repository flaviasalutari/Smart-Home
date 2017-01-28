<?php

$accessData = parse_ini_file('../../../configDB.ini',false, INI_SCANNER_RAW);

$conn = @mysqli_connect($accessData['host'],$accessData['username'],$accessData['password']);
@mysqli_select_db($conn,$accessData['dbname']);

$SQLcommand = "select * from rooms where user_cod_user = " . $_SESSION['cod_user'];

$result = @mysqli_query($conn,$SQLcommand);

if ($result)
{
	echo "<div class='tab-content'>";

	if (mysqli_num_rows($result) > 0)
	{
		$active=1;
		while ($row = mysqli_fetch_assoc($result))
		{
			if ($active)
			{
				echo "<div id='".$row['Cod_room']."' class ='tab-pane fade in active'>";
						include ('configurationextrac.php');
				echo "</div>";
				$active=0;
			}
			else
			{
				echo "<div id='".$row['Cod_room']."' class ='tab-pane fade'>";
						include ('configurationextrac.php');
				echo "</div>";
			}
		}
	}
	else
	{
		echo "You don't have any kind of config in your home";
	}



	echo "</div>";
}
mysqli_close($conn);

?>