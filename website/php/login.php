<?php
session_start();

include('PasswordStorage.php');

//Parse user and password for accessing DB
$accessData = parse_ini_file('../../../configDB.ini',false, INI_SCANNER_RAW);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

	$email = $_POST['email'];
	$psw = $_POST['password'];

	if (isset($_POST['SignIn']))

	{
		$conn = @mysqli_connect($accessData['host'],$accessData['username'],$accessData['password']);
		

			if (!$conn)
				{
					//echo mysqli_connect_errno();
					//echo mysqli_connect_error();
					echo "Connection to db fail";
					die;
				}

		if ( @mysqli_select_db($conn,$accessData['dbname']) )
		{
			$SQLcommand = "select * from user where email ='" . mysqli_escape_string($conn,$email) ."'"; 
			//echo $SQLcommand;
			$result = @mysqli_query($conn,$SQLcommand);

				if ($result)
				{
					if ($row = mysqli_fetch_assoc($result))
					{
						$authenticated = (PasswordStorage::verify_password($psw, $row['password']));
					//	$authenticated = ($psw === $row['password']);
					}
					else
						$authenticated = false;
					
					mysqli_close($conn);

					if($authenticated)
					{
						$_SESSION['cod_user']=$row['cod_user'];
						$_SESSION['email']=$row['email'];
						$_SESSION['name']=$row['name'];
						$_SESSION['surname']=$row['surname'];
						header("Location: main.php");
					}
					else
					{
						header("Location: ../index.php?error=3");
						exit;
					}
				}
		else
		{
			echo "Mysql query fail";
			die;
		}
	}

	else 
	{
		echo "Mysql select db fail";
		die;
	}
}

}

?>