<?php
$DB_NAME = "camagru;charset=utf8mb4";
 $DB_DSN = "mysql:host=".$_SERVER['SERVER_NAME'];
$DB_USER = "root";
$DB_PASSWORD = "Maureen12";
session_start();

function ft_escape_str($string){
    return (filter_var($string, FILTER_SANITIZE_STRING));
}

function validate_password($pass) {
    if (strlen($pass) < 8)
        return "Password too short, atleast 8 charecters";
    if (!preg_match('/\d/', $pass))
        return "Password must contain a digit";
    if (!preg_match('/[^a-zA-Z]+/', $_POST['pass']))
        return "Password must contain a special character";
    return true;
}


function validate_email($email){
    if (filter_var($email, FILTER_VALIDATE_EMAIL)){
        return "Your email is ok.";
    }else
        return "Wrong email address format.";
    return true;
}
?>