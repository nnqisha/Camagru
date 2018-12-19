<?php
	include_once('config/database.php');

$error = NULL;
$handler = new PDO($DB_DSN . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASSWORD);
$handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$username = $_SESSION['logged-in'];


if (!(isset($_POST['imgid']))){
	header("Location: gallery.php");
}
if (isset($_POST['new-comment']))
{
	$comment = htmlentities($_POST['reply']);
	if(!empty($_POST['reply'])){
		$commentIn = $handler->prepare("insert into comments" . "(comment, image_id, username) VALUES (:comment, :imid, :user)");
		$colsIn = array(':comment' => $_POST['reply'],
						':imid' => $_POST['imgid'],
						':user' => $username);
		$commentIn->execute($colsIn);

		$notify = $handler->prepare("SELECT email, notification FROM `verify`, `images` WHERE images.id=:id AND verify.id=images.userID");
		$notify->bindparam(':id', $_POST["imgid"]);
		$notify->execute();

		$userdata = $notify->fetchAll()[0];
		if($userdata['notification']){
			$email = $userdata['email'];
			$to=$email;
			$subject="Notification (comment)";
			$headers = "From: Camagru <rosiedmn@gmail.com>\r\n". 
				"MIME-Version: 1.0" . "\r\n" . 
				"Content-type: text/html; charset=UTF-8" . "\r\n";
			$body='Your image just got a new comment. Login to see more information';
			if (mail($to,$subject,$body,$headers)){
			}
		}
	}else
		$error = "Comment empty";
}
else if (isset($_POST['gallery']))
{
	header("Location: gallery.php");
}
?>

<html>
   <head>
      <title>Gallery</title>
      <link rel="stylesheet" type="text/css" href="styles/gall.css">
      <link rel="stylesheet" href="styles/w3.css" type="text/css" media="all">
      <style>
         body {
         color: black;
         }
      </style>
   </head>
   <body>

<div class="w3-mobile w3-cell-row w3-center w3-opacity-min w3-pink w3-quarter">
         <div class="w3-third w3-mobile w3-cell" style="margin:0px">
            <p onclick="window.location = 'index.php'" class="w3-left w3-bar-item w3-hover-sepia w3-animate-zoom"><img class="w3-image w3-left" src="http://images.clipartpanda.com/cute-camera-icon-AMD_962_0190301.gif~c200" id="logoimg" /></p>
         </div>
         <div class="w3-third w3-mobile w3-cell w3-cell-middle" style="margin:0px">
            <p onclick="window.location = 'profile.php'" class="w3-bar-item w3-mobile w3-hover-gray"> 
            <h4>CAMAGRU</h4>
            </p>
         </div>
      </div>
      </div>
      <div class="container">
         <img src= "<?php echo $_POST['imgurl'];?> ">
         <?php       
            $getComments = $handler->prepare("SELECT comment, verify.username FROM `comments`,`verify` WHERE image_id=:id AND verify.id=comments.username");
            $cols = array(':id' => $_POST["imgid"]);
            $imgId = $_POST["imgid"];
            $getComments->execute($cols);
            $commentRow = $getComments->fetchAll();
            
            foreach ($commentRow as $com) {
                echo '<p><b>' . $com['username'] . '</b><br />' . htmlentities($com['comment']) . '</p>';
            }       
            ?>
         <form action="comment.php" method="post">
            <?php
               if ($error)
               echo $error."<br/> <br/>";
               ?>
            <label for="reply">Your comment:</label><br/>
            <textarea name="reply" cols="70" rows="6"></textarea><br />
            <input type="hidden" name="imgid" value="<?php echo $imgId; ?>">
            <input type="hidden" name="imgurl" value="<?php echo $_POST['imgurl']; ?>">
            <input type="submit" name="new-comment" value="Comment" />
            <input type="submit" name="gallery" value="Back to gallery" />
         </form>
      </div>
      <footer class="footer w3-rest">
         <i>&copy; nnqisha</i>
         Camagru 
      </footer>
   </body>
</html>
