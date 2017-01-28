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
					echo "<div class='chart-wrapper' style='border-top:none'>
							<div class='chart-stage'>
				 				<p class='roomname'>Last week</p>
								<div class='row'>
									<div class='col-md-12'>
										<canvas id='graph".$row['Cod_room']."'></canvas>
									</div>
								</div>
							</div>
							</div>";
						include('plotgraph.php');
					echo "<div class='chart-wrapper'>
						<div class='chart-stage'>
				 			<h3>Light from last week</h3>
							<div class='row'>
								<div class='col-md-12'>
									<canvas id='graphBar".$row['Cod_room']."'></canvas>
								</div>
							</div>
						</div>
					</div>";
						include('lightgraph.php');
				echo "</div>";
				$active=0;
			}
			else
			{
				echo "<div id='".$row['Cod_room']."' class ='tab-pane fade'>";
					echo "	<div class='chart-wrapper' style='border-top:none'>
								<div class='chart-stage'>
						 			<p class='roomname'>Last Week</p>
									<div class='row'>
										<div class='col-md-12'>
											<canvas id='graph".$row['Cod_room']."'></canvas>
										</div>
									</div>
								</div>
							</div>";
						include('plotgraph.php');
											echo "<div class='chart-wrapper'>
						<div class='chart-stage'>
				 			<h3>Light from last week</h3>
							<div class='row'>
								<div class='col-md-12'>
									<canvas id='graphBar".$row['Cod_room']."'></canvas>
								</div>
							</div>
						</div>
					</div>";
						include('lightgraph2.php');
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