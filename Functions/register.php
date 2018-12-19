<?php
	include_once("../config/database.php");

$handler = NULL;
$status = NULL;
try{
	$handler = new PDO($DB_DSN . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASSWORD);

	$handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
	echo "Connection Failed: " . $e->getMessage();
}

if(isset($_POST['Submit']))
{
	$name = ft_escape_str($_POST['uname']);
	$username = ft_escape_str($_POST['username']);
	$email = ft_escape_str($_POST['email']);
	$pass = ft_escape_str($_POST['pass']);
	$verified = 0;
	$code = substr(md5(mt_rand()),0,15);

	$passError = null;
	$emailError = null;
	if (($passError = validate_password($pass)) !== true)
		$status[] = $passError; 
	if (($emailError = validate_email($email)) !== true)
		$status[] = $emailError;

	if (!empty($name) && !empty($username) && !empty($email) && !empty($pass)){

		$encryppass = password_hash($pass, PASSWORD_BCRYPT);
		try{
			$select = $handler->prepare("SELECT email FROM `verify` WHERE email = :email");
			$select->bindparam(':email', $email);
			$select->execute();
			$userRow = $select->fetch(PDO::FETCH_ASSOC);
			if (!$userRow){
				$select = $handler->prepare("SELECT username FROM `verify` WHERE username = :username");
				$select->bindparam(':username', $username);
				$select->execute();
				$userRow = $select->fetch(PDO::FETCH_ASSOC);
				if (!$userRow){
					$insert = $handler->prepare("INSERT INTO `verify` (uname, username, email, pass, code, verified)
                    VALUES (:uname, :username, :email, :pass, :code, :verified)");
					$insert->bindParam(':uname',$name);
					$insert->bindParam(':username',$username);
					$insert->bindParam(':email',$email);
					$insert->bindParam(':pass',$encryppass);
					$insert->bindParam(':code',$code);
					$insert->bindParam(':verified', $verified);
					$insert->execute();

					$to=$email;
					$subject="Activation Code For Camagru";
					$headers = "From: Camagru <rosiedmn@gmail.com>\r\n". 
						"MIME-Version: 1.0" . "\r\n" . 
						"Content-type: text/html; charset=UTF-8" . "\r\n";
					$body='Your Activation Code is '.$code.' Please Click On This Link
                        <a href="http://'. $_SERVER['HTTP_HOST'] .'/camagru/Functions/verify.php?id='.$code.'">verify.php?id='.$code.'</a>to activate your account.';
					if (mail($to,$subject,$body,$headers)){
						$status = "Activation Code Sent, Please Check Your Emails To Verify Your Account. If you don't receive this message please check your junk folder.";
						$_POST["uname"] = "";
						$_POST["username"] = "";
						$_POST["email"] = "";
					}else
						$status[] = "Could not send email.";
				}
				else
					$status[] = "username already exist!";
			}
			else
				$status[] = "email already exist!";
        }
		catch(PDOException $e){
			echo "Connection Failed: " . $e->getMessage();
        }
    }
        else
				$status[] = "Fields incomplete";

		$_SESSION['errors'] = $status;
		header('Location: ../index.php');;
	}
?>