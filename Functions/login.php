<?php
	include_once('../config/database.php');

$errors = NULL;
$handler = NULL;

try
{
	$handler = new PDO($DB_DSN . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASSWORD);
	$handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
catch(PDOException $e){
	echo "Connection Failed: " . $e->getMessage();
}

if (isset($_SESSION["logged-in"])){
	$select = $handler->prepare("SELECT * FROM `verify` WHERE id= :id");
	$select->bindParam(":id" , $_SESSION["logged-in"]);
	$select->execute();
	if($userdata=$select->fetchAll())
		header ("Location: ./home.php");
}

if(isset($_POST['submit']))
{
	$username = ft_escape_str($_POST['username']);
	$pass = ft_escape_str($_POST['pass']);

	if (!empty($username) && !empty($pass)){
		try
		{
			$select = $handler->prepare("SELECT * FROM `verify` WHERE username = :username AND verified = 1");
			$select->bindparam(':username', $username);
			$select->execute();
			$userRow = $select->fetch(PDO::FETCH_ASSOC);
			if($select->rowCount() > 0)
			{
				echo $userRow["pass"];
				if(password_verify($pass, $userRow['pass']))
				{
					$_SESSION['logged-in'] = $userRow['id'];
					$_SESSION['username'] = $userRow['username'];
				}
				else
				{
					echo $errors = "Incorrect username/passwordasfasfa";

				}
			}
			else
				echo $errors = "Incorrect username/password";
			header("Location: ../index.php");
		}
		catch(PDOException $e){
			echo "Connection Failed: " . $e->getMessage();
		}

		if(isset($_SESSION['logged-in']))
		{
			header("Location: ../home.php");
			exit();
		}
	}
}
?>