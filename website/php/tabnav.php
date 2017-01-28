<?php

$accessData = parse_ini_file('../../../configDB.ini',false, INI_SCANNER_RAW);

$conn = @mysqli_connect($accessData['host'],$accessData['username'],$accessData['password']);
@mysqli_select_db($conn,$accessData['dbname']);

$SQLcommand = "select * from rooms where user_cod_user = " . $_SESSION['cod_user'];

$result = @mysqli_query($conn,$SQLcommand);

if ($result)
{
	echo "<ul class='nav nav-tabs' id='tabs'>";
	$active = 1;
	while ($row = mysqli_fetch_assoc($result))
	{
		if ($active)
		{
			echo "<li class='active' id='room=".$row['Cod_room']."' ><a data-toggle='tab' href='#".$row['Cod_room']."'>".$row['Name']."</a></li>";
			$active=0;
		}
		else
		{
			echo "<li id='room=".$row['Cod_room']."'><a data-toggle='tab' href='#".$row['Cod_room']."'>".$row['Name']."</a></li>";	
		}
	}
	echo "</ul>";
}
mysqli_close($conn);
?>