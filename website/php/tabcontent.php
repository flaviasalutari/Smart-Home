<?php
$accessData = parse_ini_file('../../../configDB.ini',false, INI_SCANNER_RAW);

$conn = @mysqli_connect($accessData['host'],$accessData['username'],$accessData['password']);
@mysqli_select_db($conn,$accessData['dbname']);

$SQLcommand = "select * from rooms where user_cod_user = " . $_SESSION['cod_user'];

$result = @mysqli_query($conn,$SQLcommand);

if ($result)
{
	echo "<div class='tab-content'>";

	if (mysqli_num_rows($result) > 0 )
	{
		$active=1;
		while ($row = mysqli_fetch_assoc($result))
		{
			if ($active)
			{
				echo "<div id='".$row['Cod_room']."' class ='tab-pane fade in active'>";
						include('chart-wrapper.php');
						include('plotgraph.php');
						include('lightgraph.php');
				echo "</div>";
				$active=0;
			}
			else
			{
				echo "<div id='".$row['Cod_room']."' class ='tab-pane fade'>";
						include('chart-wrapper.php');
						include('plotgraph.php');
						include('lightgraph2.php');
				echo "</div>";

			}
		}
	}
	else
	{
		echo "You don't have any kind of config in your home. </br>
			  Your cod_user is: <p class='head'>".$_SESSION['cod_user']."</p></br></br>
			  Please read the documentation for more details: you need it for arduino since the topics for the broker looks like:
			  <h4>xx/RoomName/Light</h4> where xx is the cod_user";
	}

	echo "</div>";
}
mysqli_close($conn);

?>