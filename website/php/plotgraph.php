<?php
$sth = mysqli_query($conn,"select * from temp_read where timestamp >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND rooms_Cod_room=".$row['Cod_room']." ORDER BY Cod_read");
$time = array();
$temp = array();
$hum = array();

if (mysqli_num_rows($sth)>0)
{
    while($r = mysqli_fetch_assoc($sth)) 
    {
        $time[] = $r['Timestamp']; 
        $temp[] = $r['Val_Temp'];
        $hum[] = $r['Val_Hum'];
    }

echo "<script>
        var config = {
            type: 'line',
            data: {
                labels: ".json_encode($time).",
                datasets: [{
                    label: 'Temperature °C',
                    data: ". json_encode($temp).",
                    // fill: false,
                    //borderDash: [5, 5],
                }, {
                    label: 'Humidity %',
                    data: ".json_encode($hum).",
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'Temperature - Humidity'
                },
                scales: {
                    xAxes: [{
                        display: true,
                        ticks: {
                            userCallback: function(dataLabel, index) {
                                return index % 2 === 0 ? dataLabel : '';
                            }
                        }
                    }],
                    yAxes: [{
                        display: true,
                        beginAtZero: false
                    }]
                }
            }
        };

        $.each(config.data.datasets, function(i, dataset) {
          if (i==0)
          {
            dataset.borderColor = 'rgba(69,154,170,0.7)';
            dataset.backgroundColor = 'rgba(69,154,190,0.4)';
            dataset.pointBorderColor = 'rgba(120,180,220,0.4)';
            dataset.pointBackgroundColor = 'rgba(200,170,180,0.3)';
            dataset.pointBorderWidth = 1;
          }
          if (i==1)
          {
            dataset.borderColor = 'rgba(151,187,205,0.8)';
            dataset.backgroundColor = 'rgba(151,187,205,0.5)';
            dataset.pointBorderColor = 'rgba(120,220,220,0.4)';
            dataset.pointBackgroundColor = 'rgba(200,200,200,0.3)';
            dataset.pointBorderWidth = 1;
          }

        });
            var ctx = document.getElementById('graph".$row['Cod_room']."').getContext('2d');
            window.myLine".$row['Cod_room']." = new Chart(ctx, config);
    </script>";  
}
else
{
    echo "No enough data to plot a graph";
}

?>