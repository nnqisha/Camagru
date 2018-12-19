<?php
	include_once('config/database.php');

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
		header ("Location: index.php");
	}
	$select = $handler->prepare("SELECT * FROM `images` WHERE userID = :userID ORDER BY creation_date DESC");
	$select->bindParam(":userID" , $_SESSION["logged-in"]);
	$select->execute();
	$userRow = $select->fetchAll();

	if(isset($_GET['notify']) && !empty($_GET['notify'])){
		if($_GET['notify'] === "disable"){
			$notifca = 0;
			$insert = $handler->prepare("UPDATE verify SET notification = :notification WHERE id = :id;");
            $insert->bindParam(":notification" , $notifca);
            $insert->bindParam(":id" ,  $_SESSION["logged-in"]);
            $insert->execute();
		}
		else if($_GET['notify'] === "enable"){
			$notifca = 1;
			$insert = $handler->prepare("UPDATE verify SET notification = :notification WHERE id = :id;");
            $insert->bindParam(":notification" , $notifca);
            $insert->bindParam(":id" ,  $_SESSION["logged-in"]);
            $insert->execute();
		}
		header("Location: home.php");
	}
?>

<!DOCTYPE html>
<html lang='en'>
   <head>
      <meta charest="UTF-8">
      <title>Camagru - Home Page</title>
      <link rel="stylesheet" href="styles/cam.css" type="text/css" media="all">
      <link rel="stylesheet" href="styles/w3.css" type="text/css" media="all">
   </head>
   <body>
      <div class="w3-mobile w3-cell-row w3-center w3-opacity-min w3-pink w3-quarter">
         <div class="w3-third w3-mobile w3-cell" style="margin:0px">
            <p onclick="window.location = 'index.php'" class="w3-left w3-bar-item w3-hover-sepia w3-animate-zoom"><img class="w3-image w3-left" src="http://images.clipartpanda.com/cute-camera-icon-AMD_962_0190301.gif~c200" id="logoimg" /></p>
         </div>
         <div class="w3-third w3-mobile w3-cell w3-cell-middle" style="margin:0px">
            <p onclick="window.location = 'profile.php'" class="w3-bar-item w3-mobile w3-hover-gray"> 
            <h1>CAMAGRU</h1>
            </p>
         </div>
         <div class="w3-third w3-mobile w3-cell" style="margin-top: 25px;">
            <p onclick="window.location = 'snap.php'" class="w3-right w3-bar-item w3-mobile w3-hover-gray "> 
            <ul class="w3-right">
					<li><a class="active" href="#home">login: <?php if (isset($_SESSION['username'])) echo $_SESSION['username']; 
					else echo "username"; ?></a></li>
               <li><a href="gallery.php">Gallery</a></li>
               <li><a href="Functions/signout.php">logout</a></li>
            </ul>
            </p>
         </div>
      </div>
		<input type="hidden" name="username" id="username" value="<?php if (isset($_SESSION['username'])) echo $_SESSION['username'];
		else echo "username"; ?>">
      <div class ="container">
         <div class="main">
            <div class="camera_container">
               <div class="inner">
                  <img id="emoji1"  style="visibility: hidden; position: absolute; z-index: 5; width: 150px">
                  <img id="emoji2"  style="visibility: hidden; position: absolute; z-index: 5; width: 150px">
                  <video id="video" autoplay></video>
                  <canvas id="canvas"></canvas>
               </div>
            </div>
            <button onclick="final_img();" id="startbutton" class="capture-pic"> Capture </button>
            <input type="file" crossOrigin="Anonymous" class="form-control-file" id="upload" accept="image/png" />
            <div class="super">
               <table>
                  <tr>
                     <td><img id="e1" src="./img_super/overlays.png"  alt=""></td>
                     <td><img id="e2" src="./img_super/forever.png"  alt=""></td>
                     <td><img id="e3" src="./img_super/Boober.png"  alt=""></td>
                     <td><img id="e4" src="./img_super/phone.png"  alt=""></td>
                     <td><img id="e5" src="./img_super/unicorn.png" alt=""></td>
                     <td><img id="e6" src="./img_super/butterfly.png"  alt=""></td>
                     <td><img id="e7" src="./img_super/gun.png"  alt=""></td>
                     <td><img id="e8" src="./img_super/lord.png"  alt=""></td>
                     <td><img id="e9" src="./img_super/cursed.png"  alt=""></td>
                     <td><img id="e10" src="./img_super/aliens.png"  alt=""></td>
                     <td><img id="e11" src="./img_super/fuck.png"  alt=""></td>
                     <td><img id="e12" src="./img_super/Magic.png"  alt=""></td>
                     <td><img id="e13" src="./img_super/hp.png"  alt=""></td>
                     <td><img id="e14" src="./img_super/fries.png"  alt=""></td>
                  </tr>
               </table>
               <a href="profile.php?change=name">Change Name</a>
               <a href="profile.php?change=username">Change Username</a>
               <a href="profile.php?change=email">Change Email</a>
               <a href="profile.php?change=password">Change Password</a>
               <?php
                  $userdata= $userdata[0];
                  if($userdata['notification']){
                  	echo '<a href="home.php?notify=disable">Disable Notifications</a>';
                  }else
                  	echo '<a href="home.php?notify=enable">Enable Notifications</a>';
						?>
            </div>
         </div>
         <div class="side_nav">
            <div class="pics">
               <?php
                  $count = 0;
                  
                  echo '<div class="row">';
                  
                  foreach ($userRow as $value) {
                  				if ($count % 3 == 0)
                       		echo '</div><div class="row">';
                  			echo '<div class="column">
                      		<img src="' . $value['image_url'] . '"></div>';
                  			$count = $count + 1;
                  }
                  
                  echo ' </div>';
                  ?>
            </div>
         </div>
      </div>
      <footer>
         <div class="footer w3-rest">
			<i>2018 - nnqisha&copy;</i>
         Camagru
         </div>
      </footer>
   </body>
	<script>
 function off() {
		document.getElementById("emoji1").style.visibility = "hidden";
		document.getElementById("emoji1").removeAttribute('src');
	}
	function off2() {
		document.getElementById("emoji2").style.visibility = "hidden";
		document.getElementById("emoji2").removeAttribute('src');
	}
	emo1 = document.getElementById("e1");
	emo2 = document.getElementById("e2");
	emo3 = document.getElementById("e3");
	emo4 = document.getElementById("e4");
	emo5 = document.getElementById("e5");
	emo6 = document.getElementById("e6");
	emo7 = document.getElementById("e7");
	emo8 = document.getElementById("e8");
	emo9 = document.getElementById("e9");
	emo10 = document.getElementById("e10");
	emo11 = document.getElementById("e11");
	emo12 = document.getElementById("e12");
	emo13 = document.getElementById("e13");
	emo14 = document.getElementById("e14");
	
	emo1.addEventListener("click", function(){switchsrc(emo1);}, false);
	emo2.addEventListener("click", function(){switchsrc(emo2);}, false);
	emo3.addEventListener("click", function(){switchsrc(emo3);}, false);
	emo4.addEventListener("click", function(){switchsrc(emo4);}, false);
	emo5.addEventListener("click", function(){switchsrc(emo5);}, false);
	emo6.addEventListener("click", function(){switchsrc(emo6);}, false);
	emo7.addEventListener("click", function(){switchsrc(emo7);}, false);
	emo8.addEventListener("click", function(){switchsrc(emo8);}, false);
	emo9.addEventListener("click", function(){switchsrc(emo9);}, false);
	emo10.addEventListener("click", function(){switchsrc(emo10);}, false);
	emo11.addEventListener("click", function(){switchsrc(emo11);}, false);
	emo12.addEventListener("click", function(){switchsrc(emo12);}, false);
	emo13.addEventListener("click", function(){switchsrc(emo13);}, false);
	emo14.addEventListener("click", function(){switchsrc(emo14);}, false);
	function switchsrc(emonew)
	{
		document.getElementById("emoji1").style.visibility = "visible";
		if (document.getElementById("emoji1").hasAttribute("src"))
		{
			document.getElementById("emoji2").style.visibility = "visible";
			var emoswitch = document.getElementById("emoji2");
		}
		else
		{
			var emoswitch = document.getElementById("emoji1");
		}
		var ovl = document.getElementById("overlay");
		switch (emonew.id)
		{
			case "e1" :
				emoswitch.setAttribute('src', emonew.src);
				emoswitch.style.top = "10px";
				emoswitch.style.left = "10px";
				break;
			case "e2" :
				emoswitch.setAttribute('src', emonew.src);
				emoswitch.style.top = "10px";
				emoswitch.style.left = "200px";
				break;
			case "e3" :
				emoswitch.setAttribute('src', emonew.src);
				emoswitch.style.top = "10px";
				emoswitch.style.left = "150px";
				break;
			case "e4" :
				emoswitch.setAttribute('src', emonew.src);
				emoswitch.style.top = "10px";
				emoswitch.style.left = "10px";
				break;
			case "e5" :
				emoswitch.setAttribute('src', emonew.src);
				emoswitch.style.top = "100px";
				emoswitch.style.left = "20px";
				break;
			case "e6" :
				emoswitch.setAttribute('src', emonew.src);
				emoswitch.style.top = "10px";
				emoswitch.style.left = "300px";
				break;
			case "e7" :
				emoswitch.setAttribute('src', emonew.src);
				emoswitch.style.top = "150px";
				emoswitch.style.left = "40px";
				break;
			case "e8" :
				emoswitch.setAttribute('src', emonew.src);
				emoswitch.style.top = "25px";
				emoswitch.style.left = "200px";
				break;
			case "e9" :
				emoswitch.setAttribute('src', emonew.src);
				emoswitch.style.top = "10px";
				emoswitch.style.left = "40px";
				break;
			case "e10" :
				emoswitch.setAttribute('src', emonew.src);
				emoswitch.style.top = "100px";
				emoswitch.style.left = "200px";
				break;
			case "e11" :
				emoswitch.setAttribute('src', emonew.src);
				emoswitch.style.top = "100px";
				emoswitch.style.left = "200px";
				break;
			case "e12" :
				emoswitch.setAttribute('src', emonew.src);
				emoswitch.style.top = "100px";
				emoswitch.style.left = "200px";
				break;
			case "e13" :
				emoswitch.setAttribute('src', emonew.src);
				emoswitch.style.top = "100px";
				emoswitch.style.left = "200px";
				break;
			case "e14" :
				emoswitch.setAttribute('src', emonew.src);
				emoswitch.style.top = "10px";
				emoswitch.style.left = "15px";
				break;
		}
	} 
	</script>
	<script src="camera.js"></script>
</html>