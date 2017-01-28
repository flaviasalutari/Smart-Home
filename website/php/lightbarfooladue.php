
<?php
$sth = mysqli_query($conn,"select * from light_read where timestamp >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND rooms_Cod_room=".$row['Cod_room']." ORDER BY Cod_read");
// $time = array();
// $temp = array();
// $hum = array();

if (mysqli_num_rows($sth)>0)
{
    class dayClass 
{
   function __construct($day, $timeon, $timestamp) 
   {
    $this->day = $day;
    $this->timeon = $timeon;
    $this->timestamp = $timestamp;
  }
}

$days=array();    
$lastday = 0;
$j = -1;

    while($r = mysqli_fetch_assoc($sth)) 
{
  // echo strtotime($r['Timestamp']);
  // echo "\n";
    if ($lastday != $r['Day']) 
    {
     $now = $r['Day']."/".$r['Month']."/".$r['Year'];
     if ($r['Status']==1) 
     {
       $room = new dayClass($r['Day'], 0, $now);
       array_push($days, $room);
       $j = $j +1;
       //$days[]= array($room);    
     }
     else 
      {
        $dateSrc = "'".$r['Year']."-".$r['Month']."-".$r['Day']." ". "0:0:0" ."'"; 
        $midnightTimestamp = strtotime($dateSrc);
        $room = new dayClass($r['Day'], (strtotime($r['Timestamp']) - $midnightTimestamp), $now);
        array_push($days, $room);
        $j = $j +1;
        $days[$j-1]->timeon = $days[$j-1]->timeon + ($almostmidnightTimestamp - $lasttimestamp);
      }
    $lastday = $r['Day'];
    $lasttimestamp = strtotime($r['Timestamp']);
    }

    else 
    {
      //for ($i=0;$i < count($days); $i++)
      //{
      if ($days[$j]->day == $r['Day']) 
        {
          if ($r['Status']==0)
          {
            $days[$j]->timeon = $days[$j]->timeon + (strtotime($r['Timestamp']) - $lasttimestamp);
            $lasttimestamp = strtotime($r['Timestamp']);
          }
          else 
          {
            $dateSrc = "'".$r['Year']."-".$r['Month']."-".$r['Day']." ". "23:59:59" ."'"; 
            // echo $dateSrc;
            $almostmidnightTimestamp = strtotime($dateSrc);
            $lasttimestamp = strtotime($r['Timestamp']);
          }
        }
      //}
    }
}


$count=0;
for ($i=0;$i<count($days);$i++) {
    $time[] = $days[$i]->timestamp; 
    $timeON[] = ($days[$i]->timeon)/60;
    $counter[] = $count;
    $count=$count+1;
}

echo "<script>

        var barChartData = {
            labels: ".json_encode($time)." ,
            datasets: [{
                label: 'Light Expenditure',
                backgroundColor: 'rgba(220,220,240,0.6)',
                data: ".json_encode($timeON)."
            }]
        }

        window.onload = function() {
            var ctx = document.getElementById('graphBar".$row['Cod_room']."').getContext('2d');
            window.myBar".$row['Cod_room']." = new Chart(ctx, {
                type: 'bar',
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
                        text: 'Light Expenditure'
                    }
                }
            });

        };

    </script>";  
}
else
{
    echo "No enough data to plot a graph";
}

?>



