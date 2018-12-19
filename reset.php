<?php
include_once('config/database.php');
$status = null;
if (isset($_POST['submit']))
{
    $pass = ft_escape_str($_POST['pass']);
    echo "$pass<br>";

    if ($status = validate_password($pass) === true){
        if (!empty($pass)){
            $encryppass = password_hash($pass, PASSWORD_BCRYPT);
            try{
                $handler = new PDO($DB_DSN . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASSWORD);
                $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $insert = $handler->prepare("UPDATE `verify` SET verify.pass = :pass WHERE verify.code = :code");
                $insert->bindParam(':pass', $encryppass);
                $insert->bindParam(':code', $_POST['code']);
                $balls = $_POST['code'];
                echo($balls);
                if ($tst = $insert->execute()){
                    var_dump($tst);
                    echo "execute done";
                    
                    header("Location: index.php");
                    exit();
                }
                else
                    echo 'query error';
            }
            catch(PDOException $e){
                echo "Connection Failed: " . $e->message();
            }
        }else
            echo "feild is empty";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Camagru Reset-password</title>
    <link rel="stylesheet" href="styles/forms.css" type="text/css" media="all">
    <link rel="stylesheet" href="styles/w3.css" type="text/css" media="all">
    <style>
        body {
            background-image: url("https://techflourish.com/images/cat-whiskers-clipart-transparent-14.png");
            height: 50%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>

<body>
<?php require("header.php"); ?>
        <div class="login-page">
            <div class="form">
                <form action="reset.php" class="login-form" method="post">
                <p><input name="pass" value = "" type="password" id="pass"  placeholder="Password" required /></p> <br />
                <p><input name="repasswd" value="" type="password" id="pass2" placeholder="Confirm Password" required onfocusout="varpass()" /></p> <br />
                    <button type="submit" name="submit" id="forget">submit</button>
                <input name="code" value = "<?php 
                    if (isset($_GET['id']))
                    {
                        $code = $_GET['id'];
                        echo $code;
                    }
                
                
                ?>" type="hidden" id="code"/>
                </form>
                <script>
            function varpass(){
            var pass = document.getElementById("pass");
            var pass2 = document.getElementById("pass2");
            if ((pass.value != pass2.value))
            {
                pass2.style.borderColor = "red";
                pass2.value = "";
            }
            else if (pass2.value == "" || pass.value == "")
                pass2.style.borderColor = "red";
            else
            {
                pass2.style.borderColor = "green";
                pass.style.borderColor = "green";
            }
            };
    </script>
            </div>
        </div>
</body>

</html>
