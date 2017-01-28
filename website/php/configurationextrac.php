<?php
			echo "<div id='rooms' class='chart-wrapper' style='border-top:none'>
					<div class='chart-stage'>
			 		<p class='roomname'>" . $row['Name'] . "</p> ";
			echo "<div class='row'>
					<div class='col-md-4'>
						<div class='chart-title'>
						<p class='sub-title'>Configuration</p>
						</div>
						<div class='chart-stage'>";

				echo "<ul>";
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

			/***LIGHT***/
			if ($row['Profile'] == NULL)
			{
				echo "<li><strong>Profile</strong>: // </li>";
			}
			else
			{
				echo "<li><strong>Profile</strong>: " . $row['Profile'] . "</li>";	
			}

			echo "</ul>";
 			echo "</div></div>";

 			echo "<div class='col-md-4'>
	    			<div class='chart-title'>
	    				<p class='sub-title'>Light</p>
	    			</div>
		    		<div class='chart-stage'>";

		    	echo "<ul>";

		    	if ($row['Hour_On'] == NULL)
		    	{
			    	echo "<li><strong>Time On: // </li>";
			    	echo "<li><strong>Time Off: // </li>";
		    	}
		    	else
		    	{
			    	echo "<li><strong>Time On: " . $row['Hour_On'] . " . " . $row['Minute_On'] . "</li>";
			    	echo "<li><strong>Time Off: " . $row['Hour_Off'] . " . " . $row['Minute_Off'] . "</li>";
		    	}

		    	if ($row['Threshold'] == NULL)
		    	{
		    		echo "<li><strong>Threshold: // </li>";
		    	}
		    	else
		    	{
		    		echo "<li><strong>Threshold: " . $row['Threshold'] . " seconds</li>";
		    	}

			echo "	</div>
				</div>";

				/****TEMPERATURE***/
				echo "<div class='col-md-4'>
	    			<div class='chart-title'>
	    				<p class='sub-title'>Temperature</p>
	    			</div>
		    		<div class='chart-stage'>";

		    	echo "<ul>";
	
				if ($row['Temp_Threshold_Low'] == NULL)
				{
			    	echo "<li><strong>Temp min: //</li>";
			    	echo "<li><strong>Temp max: //</li>";
				}
				else
				{
			    	echo "<li><strong>Temp min: " . $row['Temp_Threshold_Low'] . "</li>";
		    		echo "<li><strong>Temp max: " . $row['Temp_Threshold_High'] . "</li>";	
				}

			echo "	</div>
				</div>";



			echo "</div></div></div>";
			    ?>