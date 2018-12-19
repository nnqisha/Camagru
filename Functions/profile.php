<?php
	include_once('config/database.php');

    $handler = NULL;
    $user_data = null;
    $error = NULL;
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
		if(!($user_data = $select->fetchAll()))
            header ("Location: login.php");

        $user_data = $user_data[0];

        if (isset($_POST['update_nam'])){
            
            $name = ft_escape_str($_POST['new_name']);

            if(!empty($name)){
                $insert = $handler->prepare("UPDATE verify SET name = :fname WHERE id = :id;");
                $insert->bindParam(":fname" , $name);
                $insert->bindParam(":id" ,  $_SESSION["logged-in"]);
                $insert->execute();

                $error = "Name Updated successfully.";
            }else
                $error = "Name fieled is empty, could not update";
            $_SESSION['error'] = $error;
            header('Location: profile.php?change=name');
        }
        else if(isset($_POST['update_user'])){

            $user = ft_escape_str($_POST['new_user']);

            if(!empty($user)){
                $select = $handler->prepare("SELECT username FROM `verify` WHERE username = :username");
                $select->bindparam(':username', $user);
                $select->execute();
                $userRow = $select->fetch(PDO::FETCH_ASSOC);
                    if (!$userRow){
                        $insert = $handler->prepare("UPDATE `verify` SET username = :username WHERE id = :id; ");
                        $insert->bindParam(":username" , $user);
                        $insert->bindParam(":id" ,  $_SESSION["logged-in"]);
                        $insert->execute();

                        $select = $handler->prepare("SELECT * FROM `verify` WHERE id= :id");
                        $select->bindParam(":id" , $_SESSION["logged-in"]);
                        $select->execute();
                        

                        $user_data = $select->fetchAll()[0];

                        $_SESSION['username'] = $user_data['username'];

                        $error = "Username Updated successfully.";
            }else
                $error = "User fieled is empty, could not update";
            $_SESSION['error'] = $error;
                header('Location: profile.php?change=username');
                    
            }
        }
        else if(isset($_POST['update_email'])){

            $email = ft_escape_str($_POST['new_email']);

            if(!empty($email)){
                $select = $handler->prepare("SELECT email FROM `verify` WHERE email = :email");
                $select->bindparam(':email', $email);
                $select->execute();
                $userRow = $select->fetch(PDO::FETCH_ASSOC);
                if (!$userRow){  
                    $insert = $handler->prepare("UPDATE `verify` SET email = :email WHERE id = :id;");
                    $insert->bindParam(":email" , $email);
                    $insert->bindParam(":id" ,  $_SESSION["logged-in"]);
                    $insert->execute();
                
                    $error = "New email Updated successfully.";
                }else
                    $error = "Email fieled is empty, could not update";
                $_SESSION['error'] = $error;
                header('Location: profile.php?change=email');
            }
        }
        else if(isset($_POST['update_pass'])){

            $pass = ft_escape_str($_POST['new_pass']);
            $oldpass = ft_escape_str($_POST['password']);

            if(!empty($pass) && !empty($oldpass)){

                $encryppass = password_hash($pass, PASSWORD_BCRYPT);

                $select = $handler->prepare("SELECT password FROM `verify` WHERE id = :id; ");
                $select->bindParam(":id" ,  $_SESSION["logged-in"]);
                $select->execute();
                $userRow = $select->fetch(PDO::FETCH_ASSOC);

                if (password_verify($oldpass, $userRow['password'])){
                    $insert = $handler->prepare("UPDATE `verify` SET password = :password WHERE id = :id;");
                    $insert->bindParam(":password" , $encryppass);
                    $insert->bindParam(":id" ,  $_SESSION["logged-in"]);
                    $insert->execute();
                    
                    $error = "Password Updated successfully.";
                }else
                    $error = "Could not update, current password do not match";
                $_SESSION['error'] = $error;
                    header('Location: profile.php?change=password');
            }else
                $error = "Password feild empty, could not update";
        }
	}
	else{
		header ("Location: login.php");
	}
?>