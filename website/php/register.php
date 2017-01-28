<?php
session_start();

include('PasswordStorage.php');
include('genhash.php');

$accessData = parse_ini_file('../../../configDB.ini',false, INI_SCANNER_RAW);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	
	if (isset($_POST['Register']))
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
			$SQLcommand = "select * from user where email ='" . mysqli_escape_string($conn,$_POST['email']) ."'";
			$result = @mysqli_query($conn,$SQLcommand);

			if (@mysqli_fetch_assoc($result))
			{
				mysqli_close($conn);
				header("Location: ../index.php?errore=1"); //email already exist
				exit;
			}
			else
			{
				//INSERT INTO `user` (`cod_user`, `email`, `password`,'pswmqtt' `name`, `surname`) VALUES (NULL, 'ciao@lol.it', 'ciao', '', '');
				$SQLcommand2 = "INSERT INTO user (cod_user,email,password,pswmqtt,name,surname) values (NULL, '" . $_POST['email'] . "','" . PasswordStorage::create_hash($_POST['password']) . "','" . create_hash($_POST['password']) . "', '".$_POST['name']."','".$_POST['surname']."' )";

				if (@mysqli_query($conn,$SQLcommand2))
				{
					$_SESSION['cod_user'] = mysqli_insert_id($conn);
					$_SESSION['email']=$_POST['email'];
					$_SESSION['name']=$_POST['name'];
					$_SESSION['surname']=$_POST['surname'];
					
					//INSERT INTO `acls` (`id`, `username`, `topic`, `rw`) VALUES (NULL, 'andron', 'fas', '2');
					$topic = $_POST['email'] . "/#";
					$SQLcommand3 = "insert into acls values (NULL,'".$_POST['email']."','".$topic."','2')";

					if (@mysqli_query($conn,$SQLcommand3))
					{
						mysqli_close($conn);
						header("Location: main.php");
					}

				}
				else
				{
					mysqli_close($conn);
					header("Location: ../index.php?errore=2");
					exit;
				}
				exit;
			}
		}
	}
}

?>