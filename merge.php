<?PHP
    include_once("config/setup.php");
    include_once("config/database.php");
    

    $name = ft_escape_str($_POST['username']);
    $user_id = $_SESSION['logged-in'];
    var_dump($_POST);

    $i = 0;
    $nameqqq = $name .rand(0, 999). ".png";
    $dir = "./img/uploads/".$nameqqq;
    $image = $_POST['image'];
    $image2 = $_POST['image2'];
    $image3 = $_POST['image3'];
    imagepng(imagecreatefrompng($image), "./img/uploads/".$i++.$nameqqq);
    imagepng(imagecreatefrompng($image2), "./img/uploads/".$i++.$nameqqq);
    imagepng(imagecreatefrompng($image3), "./img/uploads/".$i++.$nameqqq);
    exit;
    $image = str_replace('data:image/png;base64, ', '', $image);
    $image = str_replace(' ', '+', $image);
    $data = base64_decode($image);
    file_put_contents("./img/uploads/".$i++ .$nameqqq , $data);

    $image2 = str_replace('data:image/png;base64, ', '', $image2);
    $image2 = str_replace(' ', '+', $image2);
    $data2 = base64_decode($image2);
    file_put_contents("./img/uploads/".$i++ .$nameqqq, $data2);
    $image3 = str_replace('data:image/png;base64, ', '', $image3);
    $image3 = str_replace(' ', '+', $image3);
    $data3 = base64_decode($image3);
    file_put_contents("./img/uploads/".$i++.$nameqqq , $data3);

    exit;

    $dest = imagecreatefrompng($image);

if($_POST['blank'] != 1){
    $src = imagecreatefrompng($image2);
        ImageCopyResampled($dest,$src,100,80,10,10,250,250,300,290);
     

        // imagedestroy($dest);
        // imagedestroy($src);
        echo "Done";
  
}
if ($_POST['blank2'] != 1)
{
    // echo $image3;
    $src = imagecreatefrompng($image3);
    // echo $src;
    // exit;
        ImageCopyResampled($dest,$src,100,80,10,10,150,150,300,290);
     
        // imagedestroy($dest);
        // imagedestroy($src);
        echo "Done";
}
else
{
    imagepng($dest, "./img/uploads/".$nameqqq);
}

imagepng($dest, $dir); /*9*/

    function merge_img($path, $dest, $img2){
        // $dest = imagecreatefrompng($img);
        $src = imagecreatefrompng($img2);
        ImageCopyResampled($dest,$src,100,80,10,10,250,250,300,290);
     

        // imagedestroy($dest);
        imagedestroy($src);
        echo "Done";
    }

    try{
        $handler = new PDO($DB_DSN . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASSWORD);
        $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $insert = $handler->prepare("INSERT INTO `images` (image_url, userID)
        VALUES(:image_url, :userID)");
        $insert->bindParam(':image_url', $dir);
        $insert->bindParam(':userID', $user_id);
        $insert->execute();
    } catch (PDOException $e) {
        echo "error: " .$sql . "<br>" . $e->getMessage();
    }
?>