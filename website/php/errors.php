<?php
	if (isset($_GET['errore']))
	{
		echo "<script>$(document).ready(function(){
			$('#myModal').modal('show');
		});</script>";

		switch ($_GET['errore']) {
			case 1:
				echo "	<script>
							$('#errors').text('The mail already exists').css('color','red');
						</script>";
				break;
			case 2:
				echo "	<script>
							$('#errors').text('Error in inserting in db');
						</script>";
				break;
			
			default:
				# code...
				break;
		}
	}
?>