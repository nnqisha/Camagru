<?php
	include_once("config/database.php");

	$handler = NULL;
	$userdata = NULL;
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
		if(!($userdata=$select->fetchAll()))
			header ("Location: login.php");
	}
	else{
		header ("Location: login.php");
	}
	$select = $handler->prepare("SELECT * FROM `images` WHERE userID = :userID ORDER BY creation_date DESC");
	$select->bindParam(":userID" , $_SESSION["logged-in"]);
	$select->execute();
	$userRow = $select->fetchAll();

	if(isset($_GET['notify']) && !empty($_GET['notify'])){
		if($_GET['notify'] === "disable"){
			$notifca = FALSE;
			$insert = $handler->prepare("UPDATE verify SET notification = :notification WHERE id = :id;");
            $insert->bindParam(":notification" , $notifca);
            $insert->bindParam(":id" ,  $_SESSION["logged-in"]);
            $insert->execute();
		}
		else if($_GET['notify'] === "enable"){
			$notifca = TRUE;
			$insert = $handler->prepare("UPDATE verify SET notification = :notification WHERE id = :id;");
            $insert->bindParam(":notification" , $notifca);
            $insert->bindParam(":id" ,  $_SESSION["logged-in"]);
            $insert->execute();
		}
		header("Location: ./home.php");
	}
?>