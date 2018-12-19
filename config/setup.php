<?php
include_once("database.php");
try {
    $handler = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $handler->query("CREATE DATABASE IF NOT EXISTS $DB_NAME");
    $handler->query("USE $DB_NAME;charset=utf8mb4");
    

    $handler->query("CREATE TABLE IF NOT EXISTS `verify` (
        id int(11) NOT NULL AUTO_INCREMENT,
        uname VARCHAR(80) NOT NULL,
        username VARCHAR(30) NOT NULL,
        email VARCHAR(120) NOT NULL,
        pass text NOT NULL,
        code text NOT NULL,
        verified BOOLEAN NOT NULL,
        notification BOOLEAN DEFAULT TRUE,
        PRIMARY KEY (`id`)
       ) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4");
    
       $handler->query("CREATE TABLE IF NOT EXISTS `images` (
        id int(11) NOT NULL AUTO_INCREMENT,
        image_url VARCHAR(50) NOT NULL,
        likes int NOT NULL DEFAULT 0,
        creation_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        userID int(11) NOT NULL,
        PRIMARY KEY (`id`)
       ) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4");
       
        $handler->query("CREATE TABLE IF NOT EXISTS `comments` (
        id int(11) NOT NULL AUTO_INCREMENT,
        comment VARCHAR(150),
        image_id int NOT NULL,
        username VARCHAR(20) NOT NULL,
        PRIMARY KEY (`id`)
       )ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4");

       $handler->query("CREATE TABLE IF NOT EXISTS `likes` (
        id int(11) NOT NULL AUTO_INCREMENT,
        image_id int NOT NULL,
        username VARCHAR(20) NOT NULL,
        PRIMARY KEY (`id`)
       ) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4");
}
       catch (PDOException $e) {
    echo 'Connection Failed: ' . $e->getMessage();
    die();
}
?>