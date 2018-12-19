<?php
    include_once("config/setup.php");
    include_once("config/database.php");

    $user_id = $_SESSION['logged-in'];
    $newimgname = $user_id.rand(1,999);
    $emojitest = $_POST['emoji64'];
  $data = explode( ',', $_POST["img64"] );
    $test = base64_decode($data[1]);
    
    file_put_contents("img/uploads/".$user_id."$".$newimgname.".png", $test);
    $dest= imagecreatefrompng("img/uploads/".$user_id."$".$newimgname.".png");
    if(!empty($_POST["emoji64"]))
    {
        $emo = explode ('camagru/',$_POST["emoji64"]);
        $src = imagecreatefrompng($emo[1]);
        $width = ImageSx($src);
        $height = ImageSy($src);
        pic_position($emo);
        ImageCopyResampled($dest,$src,$pos2,$pos1,0,0,100,100,$width,$height);
    }
    
    if(!empty($_POST["emoji64_2"]))
    {
        $emo2 = explode ('camagru/',$_POST["emoji64_2"]);
        $src = imagecreatefrompng($emo2[1]);
        $width = ImageSx($src);
        $height = ImageSy($src);
        pic_position($emo2);
        ImageCopyResampled($dest,$src,$pos2,$pos1,0,0,100,100,$width,$height);
    }
    
    imagepng($dest, "img/uploads/".$user_id."$".$newimgname.".png");
    try {
        $handler = new PDO($DB_DSN . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASSWORD);
        $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
      }
      
       try {
        $handler->query("USE ".$DB_NAME);
      } catch (Exception $e) {
         die("db creation failed!");
      } 
      
        try {
      
          $sql = "INSERT INTO images (image_url, userID) VALUES (:image_url, :userID)";
          $stmt= $handler->prepare($sql);
          $stmt->bindParam(':userID', $user_id);
          $picname = "img/uploads/".$user_id."$".$newimgname.".png";
          $stmt->bindParam(':image_url', $picname);
          if($stmt->execute()){
              header("Location: home.php");
          }
         
       } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
       }
       function pic_position($emo)
    {
        global $x, $y, $width, $height, $pos1, $pos2;
        switch ($emo[1])
        {
            case "img_super/overlays.png" :
                $pos1 = 10;
                $pos2 = 70;
                $x = $width/5; $y = $height/5;
                break;
            case "img_super/forever.png" :
                $pos1 = 10;
                $pos2 = 200;
                $x = $width/5; $y = $height/5;
                break;
            case "img_super/Boober.png" :
                $pos1 = 10;
                $pos2 = 400;
                $x = $width/5; $y = $height/5;
                break;
            case " img_super/phone.png" :
                $pos1 = 10;
                $pos2 = 500;
                $x = $width/5; $y = $height/5;
                break;
            case "img_super/unicorn.png" :
                $pos1 = 100;
                $pos2 = 52;
                $x = $width/5; $y = $height/5;
                break;
            case "img_super/butterfly.png" :
                $pos1 = 100;
                $pos2 = 65;
                $x = $width/5; $y = $height/5;
                break;
            case "img_super/gun.png" :
                $pos1 = 100;
                $pos2 = 70;
                $x = $width/5; $y = $height/5;
                break;
            case "img_super/lord.png" :
                $pos1 = 100;
                $pos2 = 50;
                $x = $width/5; $y = $height/5;
                break;
            case "img_super/cursed.png" :
                $pos1 = 10;
                $pos2 = 100;
                $x = $width/5; $y = $height/5;
                break;
            case "img_super/aliens.png" :
                $pos1 = 100;
                $pos2 = 60;
                $x = $width/5; $y = $height/5;
                break;
            case "img_super/fuck.png" :
                $pos1 = 100;
                $pos2 = 60;
                $x = $width/5; $y = $height/5;
                break;
            case "img_super/Magic.png" :
                $pos1 = 20;
                $pos2 = 300;
                $x = $width/5; $y = $height/5;
                break;
            case "img_super/hp.png" :
                $pos1 = 100;
                $pos2 = 70;
                $x = $width/5; $y = $height/5;
                break;
            case "img_super/fries.png" :
                $pos1 = 100;
                $pos2 = 70;
                $x = $width/5; $y = $height/5;
                break;
        }
    }
   
   ?>