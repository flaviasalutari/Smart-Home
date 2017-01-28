<?php 
	echo "	<div id='rooms' class='chart-wrapper' style='border-top:none'>
						<div class='chart-stage'>
				 			<p class='roomname'>" . $row['Name'] . "</p>
							<div class='row'>
								<div class='col-md-3'>
    								<div class='chart-title'>
    									<p class='sub-title'>Configuration</p>
    								</div>
								<div class='chart-stage'>
								<ul>";

	    				if ($row['Temperature'] == 1)
						{
							echo "<li><strong>Temperature &radic;</strong></li>";
						}
						else
						{
							echo "<li><strong>Temperature &otimes;</strong></li>";	
						}
						if ($row['Light'] == 1)
						{
							echo "<li><strong>Light &radic;</strong></li>";
						}
						else
						{
							echo "<li><strong>Light &otimes;</strong></li>";
						}

							echo "<li><strong>Profile</strong>: " . $row['Profile'] . "</li>
								</ul>
	 						</div>
	 					</div>";
	 			//*** END First : Configuration ***//

	//****new sql***//
	$SQLlasttemperature = "select * from temp_read where rooms_Cod_room = " . $row['Cod_room'] . " order by Cod_read DESC limit 1;";
	//echo $SQLlasttemperature;
	$resulttemperature = mysqli_query($conn,$SQLlasttemperature);

				if ($risp = mysqli_fetch_assoc($resulttemperature))
				{
    				//*** Second : Temperature *** //
    				echo "	<div class='col-md-3'>
			    				<div class='chart-title'>
			    					<p class='sub-title'>Temperature</p>
			    				</div>
				    			<div class='chart-stage'>
					    			<div class='GaugeMeter' id='GaugeMeter".$row['Name']."Temp"."' data-percent='" . $risp['Val_Temp'] . "' data-append='°C' data-total='50' data-size='150' data-theme='Green-Gold-Red' data-back='RGBa(0,0,0,.1)'' data-animate_gauge_colors='1' data-animate_text_colors='1' data-width='15' data-label='Temperature' data-style='Semi' data-label_color='gray'>
					    			</div>
			    				<p style='margin-top: -35px; margin-left: 12px'>Last measurement: ".$risp['Timestamp']. "</p>
	    						</div>
    						</div>";
    				//*** END Seconf : Temperature ***//

    				//*** Third : Humidity ***//
    				echo "	<div class='col-md-3'>
                				<div class='chart-title'>
                  					<p class='sub-title'>Humidity</p>
                				</div>
                				<div class='chart-stage'>
	                				<div class='GaugeMeter' id='GaugeMeter".$row['Name']."Hum"."' data-percent='" .$risp['Val_Hum'] . "' data-append='%'' data-total='50' data-size='150' data-theme='DarkBlue-LightBlue' data-back='RGBa(0,0,0,.1)' data-animate_gauge_colors='1' data-animate_text_colors='1' data-width='15' data-label='Humidity' data-style='Semi' data-label_color='gray'>
                  					</div>
                  					<p style='margin-top: -35px; margin-left: 12px'>Last measurement: " . $risp['Timestamp'] . "</p>
                				</div>
            				</div>";
            		//*** END Third : Humidity ***//

    				//*** Fourth : Light***//

				}
				else //Nothing in db
				{
					//*** No temp in db ***//
    				echo "	<div class='col-md-3'>
		    					<div class='chart-title'>
		    						<p class='sub-title'>Temperature</p>
		    					</div>
			    				<div class='chart-stage'>
				    				<div class='GaugeMeter' id='PreviewGaugeMeter1' data-percent='0' data-append='°C' data-total='50' data-size='150' data-theme='Green-Gold-Red' data-back='RGBa(0,0,0,.1)'' data-animate_gauge_colors='1' data-animate_text_colors='1' data-width='15' data-label='Temperature' data-style='Semi' data-label_color='gray'>
				    				</div>
		    					<p style='margin-top: -35px; margin-left: 12px'>Last measurement: -- </p>
    							</div>
    						</div>";

    				//***No hum in db***//
    				echo "	<div class='col-md-3'>
                				<div class='chart-title'>
                  					<p class='sub-title'>Humidity</p>
                				</div>
                				<div class='chart-stage'>
	                				<div class='GaugeMeter' id='PreviewGaugeMeter2' data-percent='0' data-append='%'' data-total='50' data-size='150' data-theme='DarkBlue-LightBlue' data-back='RGBa(0,0,0,.1)' data-animate_gauge_colors='1' data-animate_text_colors='1' data-width='15' data-label='Humidity' data-style='Semi' data-label_color='gray'>
                  					</div>
                  				<p style='margin-top: -35px; margin-left: 12px'>Last measurement: -- </p>
                				</div>
            				</div>";

				}
				 if ($row['Light'] == 1)
            		{
            		echo "	<div class='col-md-3'>
	    						<div class='chart-title'>
	    							<p class='sub-title'>Light</p>
	    						</div>
    							<div class='chart-stage'>
    								<div class='chart-stage' style='margin-top:50px;margin-left:7px' >
	    								<form>
	    									<input type='checkbox' data-indeterminate='true' data-size='normal' id='checkbox-on' name='" . $row['Name'] . "'>
	    								</form>
    								</div>
    							<p style='margin-top: 30px; margin-left: 18px'>Last change: -- </p>
    							</div>
    						</div>";
            		}
            		else 
            		{
            		//***No light***//
    				echo "	<div class='col-md-3'>
    							<div class='chart-title'>
    								<p>Light</p>
    							</div>
    							<div class='chart-stage' style='margin-top:50px;margin-left:15px' >
    								<input type='checkbox' data-size='normal' id='checkbox-on' readonly='yes'>
    							</div>
    						</div>";
            		}


				echo "		</div>
			</div>
		</div>"; //Row, chart-stage, chart-wrapper

				echo "<div class='chart-wrapper'>
						<div class='chart-stage'>
				 			<h3>Temperature from last week</h3>
							<div class='row'>
								<div class='col-md-12'>
									<canvas id='graph".$row['Cod_room']."'></canvas>
								</div>
							</div>
						</div>
					</div>";
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
?>



