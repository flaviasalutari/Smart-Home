<?php

session_start();

$from = $_GET['from'];
$to = $_GET['to'];
$room = $_GET['room'];

$accessData = parse_ini_file('../../../configDB.ini',false, INI_SCANNER_RAW);

$conn = @mysqli_connect($accessData['host'],$accessData['username'],$accessData['password']);
@mysqli_select_db($conn,$accessData['dbname']);


//select * from temp_read where timestamp >= '2016-01-03' and timestamp < '2016-12-12' and rooms_Cod_room=(select Cod_room from rooms where user_cod_user = 47 and Name = 'Room1')


//$sth = mysqli_query($conn,"select * from temp_read where timestamp >= ".$from." and timestamp < ".$to." and rooms_Cod_room=(select Cod_room from rooms where user_cod_user = ".$_SESSION['cod_user']." and Name = '".$room."')");

$sth = mysqli_query($conn,"select * from temp_read where timestamp >= ".$from." and timestamp <= ".$to." and rooms_Cod_room=".$room." ORDER BY cod_read");
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


echo "<script>myLine".$room.".destroy();</script>
        <script>
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
            var ctx = document.getElementById('graph".$room."').getContext('2d');
            window.myLine".$room." = new Chart(ctx, config);
    </script>";   
}
else
{
    echo "<script>myLine".$room.".destroy();</script> ";
}

?>