<?php
    include_once('config/database.php');
    
        if(isset($_POST['submit']))
        {
            $email = ft_escape_str($_POST['email']);

            if (!empty($email)){
        
                try
                {
                    $handler = new PDO($DB_DSN . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASSWORD);
                    $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $select = $handler->prepare("SELECT * FROM `verify` WHERE email = :email AND verified = TRUE");
                    $select->bindparam(':email', $email);
                    $select->execute();
                    $userRow = $select->fetch(PDO::FETCH_ASSOC);
                    echo $userRow['username'];
                    echo "excute done";
                    echo $select->rowCount();
                    
                    if($select->rowCount() > 0)
                    {
                        $code = $userRow['code'];

                        $to=$email;
                        $subject="Reset Password Code For Camagru";
                        $headers = "From: Camagru <admin@camagru.com>\r\n". 
                        "MIME-Version: 1.0" . "\r\n" . 
                        "Content-type: text/html; charset=UTF-8" . "\r\n";
                        $body='Your Password reset code is '.$code.' Please Click On This Link
                            <a href="http://'. $_SERVER['HTTP_HOST'] .'/camagru/reset.php?id='.$code.'">reset.php?id='.$code.'</a>to reset your password. If you did not request reset password just ignore this email';
                        if (mail($to,$subject,$body,$headers))
                            echo "Activation Code Sent, Please Check Your Emails. If you don't recieve this message, please check your junk folder.";
                        else
                            echo "Code Not Sent";
                    }
                    else
                        echo "invalid email";
                }
                catch(PDOException $e){
                    echo "Connection Failed: " . $e->message();
                }
            }
        }
?>