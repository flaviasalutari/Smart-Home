
<?php

$sth = mysqli_query($conn,"select * from light_read where timestamp >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND rooms_Cod_room=".$row['Cod_room']." ORDER BY Cod_read");

$days = array();
$timeon = array();
$count_day = -1;
$temp_time_on = 0;
$temp_time_off = 0;
$temp_time = 0;
$last_status = 0;

if (mysqli_num_rows($sth)>0)
{
	$lastday = 0;

	while ($r = mysqli_fetch_assoc($sth))
	{
		if ($lastday != $r['Day'])
		{
			$count_day = $count_day + 1;
			$lastday = $r['Day'];
				$datetime = explode(" ",$r['Timestamp']);
				$date = $datetime[0];
				$reformatted_date = date('d-m-Y',strtotime($date));
			array_push($days, $reformatted_date);
			array_push($timeon, 0);
			
			if ($r['Status']==1)
			{
				$temp_time_on = strtotime($r['Timestamp']);
				$last_status = 1;
			}
			if ($r['Status']==0)
			{
				$temp_time_off = strtotime($r['Timestamp']);
				if ($last_status==1)
				{
					$temp_time = $temp_time_off - $temp_time_on;
					$timeon[$count_day] = $timeon[$count_day] + $temp_time;
				}
				$last_status = 0;
			}	
		}
		else
		{
			if ($r['Status']==1)
			{
				$temp_time_on = strtotime($r['Timestamp']);
				$last_status = 1;
			}
			if ($r['Status']==0)
			{
				$temp_time_off = strtotime($r['Timestamp']);
				if ($last_status==1)
				{
					$temp_time = $temp_time_off - $temp_time_on;
					$timeon[$count_day] = $timeon[$count_day] + $temp_time;
				}
				$last_status = 0;
			}	
		}
	}

$timeon = array_map("divide", $timeon);

echo "<script>

        var barChartData = {
            labels: ".json_encode($days)." ,
            datasets: [{
                label: 'Light Expenditure in minutes',
                backgroundColor: 'rgba(69,154,190,0.4)',
                data: ".json_encode($timeon)."
            }]
        }

            var ctx = document.getElementById('graphBar".$row['Cod_room']."').getContext('2d');
            window.myBar".$row['Cod_room']." = new Chart(ctx, {
                type: 'radar',
                data: barChartData,
                options: {
                    // Elements options apply to all of the options unless overridden in a dataset
                    // In this case, we are setting the border of each bar to be 2px wide and green
                    elements: {
                        rectangle: {
                            borderWidth: 2,
                            borderColor: 'rgb(140, 140, 200)'
                        }
                    },
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Light Expenditure in minutes'
                    }
                }
            });
    </script>";   
}
else
{
    echo "No enough data to plot a graph";
}

?>