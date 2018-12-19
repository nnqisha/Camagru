<?php
    include_once('config/database.php');

    $user = NULL;
    if(isset($_SESSION['logged-in'])){
        $user =  $_SESSION['logged-in'];
    }
     try
        {
            $handler = new PDO($DB_DSN . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASSWORD);
            $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $select = $handler->prepare("SELECT count(id) FROM `images` ORDER BY creation_date DESC");
            $select->execute();
            $userRow = $select->fetch();
			$total_images = $userRow['0'];
            $getLikes = $handler->prepare("SELECT * FROM `likes`");
            $getLikes->execute();
            $likeRow = $getLikes->fetchAll();
			
        }
        catch(PDOException $e){
            echo "Connection Failed: " . $e->getMessage();
        }

        function displayComs($theid)
        {
            try
            {
               global $handler;
                $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $select = $handler->prepare("SELECT * FROM `comments` WHERE id=:image_id");
                $select->execute();

                while($userRow = $select->fetch()){
                    echo $userRow['comment']."<br>";
                }
                
            }
            catch(PDOException $e){
                echo "Connection Failed: " . $e->getMessage();
            }

        }
?>

<html>
   <head>
      <link rel="stylesheet" type="text/css" href="styles/gall.css">
      <link rel="stylesheet" href="styles/w3.css" type="text/css" media="all">
      <style>
         body {
         color: black;
         }
      </style>
      <meta charest="UTF-8">
      <title>Camagru - Gallery Page</title>
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
         <div class="w3-third w3-mobile w3-cell" style="margin-top: 25px;">
            <p onclick="window.location = 'snap.php'" class="w3-right w3-bar-item w3-mobile w3-hover-gray "> 
            <ul class="w3-right">
            <?php
            if (isset($_SESSION['username']))
            { ?>
            <li><a class="active" href="#home">login: <?php if (isset($_SESSION['username'])) echo $_SESSION['username']; else echo "username"; ?></a></li>
            <?php } ?>
               <li><a href="home.php">Home</a></li>
               <?php
                  if (isset($_SESSION['username']))
                  {
               ?>
               <li><a href="Functions/signout.php">logout</a></li>
               <?php } ?>
            </ul>
            </p>
         </div>
      </div>
      <input type="hidden" name="username" id="username" value="<?php if (isset($_SESSION['username'])) echo $_SESSION['username']; else echo "username"; ?>">
      <div class="container w3-rest">
         <div class="main">
            <div id="pagination">
               <?php
                  $per_page = 5;
                   $disabled = '';
                  if (isset($_GET['page'])) {
                  $page = $_GET['page'] - 1;
                  $offset = $page * $per_page;
                  }
                  else {
                  $page = 0;
                  $offset = 0;
                  }
                  if ($total_images > $per_page) {
                  $pages_total = ceil($total_images / $per_page);
                  $page_up = $page + 2; 
                  $page_down = $page;
                  $display ='';
                  } 
                  else {
                  $pages = 1;
                  $pages_total = 1;
                  $display = ' class="display-none"';
                  }
                  
                  echo '<h2'.$display.'>Page '; echo $page + 1 .' of '.$pages_total.'</h2>';
                  
                  $i = 1;
                  
                  echo '<div id="pageNav"'.$display.'>';
                  if ($page) {
                  echo '<a href="gallery.php"><button><<</button></a>';
                  echo '<a href="gallery.php?page='.$page_down.'"><button><</button></a>';
                  } 
                  
                  for ($i=1;$i<=$pages_total;$i++) {
                  if(($i==$page+1)) {
                  echo '<a href="gallery.php?page='.$i.'"><button class="active">'.$i.'</button></a>';//Button for active page, underlined using 'active' class
                  }
                  
                  
                  if(($i!=$page+1)&&($i<=$page+3)&&($i>=$page-1)) {
                  echo '<a href="gallery.php?page='.$i.'"><button>'.$i.'</button></a>'; }
                  } 
                  
                  if (($page + 1) != $pages_total) {
                  echo '<a href="gallery.php?page='.$page_up.'"><button>></button></a>';
                  echo '<a href="gallery.php?page='.$pages_total.'"><button>>></button></a>';
                  }
                  echo "</div>";
                  
                              echo '<div id="gallery">';
                  
                  
                  
                  $select = $handler->prepare("SELECT * FROM `images` ORDER BY id DESC LIMIT $offset, $per_page");
                          $select->execute();
                  while($userRow = $select->fetch()) {
                  $image=$userRow['image_url'];
                  echo '<div class="img-container">';
                  echo '<div class="img">';
                  
                  foreach ($likeRow as $like)
                              {
                                  if ($like['image_id'] == $userRow['id'] && $like['username'] == $user){
                                      $disabled = "disabled";
                                  }
                  else
                  {
                                      $disabled = '';
                                  }
                  
                              }
                               echo '<div class="column"> Likes: ' . $userRow['likes'] . '<br/>
                  
                                  <form action="image_event.php" method="post">';
                                  
                              if (isset($_SESSION['logged-in'])){
                                  echo '<input type="hidden" name="imgid" value="' . $userRow['id'] .'"/>';
                                  echo '<input type="hidden" name="imgurl" value="' . $userRow['image_url'] .'"/>';
                                  echo '<input type="submit" ' . $disabled . ' name="like" value="Like"/>';
                                  echo '<input type="submit" name="comment" value="Comment"/>';
                                  if ($userRow['userID'] == $_SESSION['logged-in'])
                                  echo '<input type="submit" name="delete" value="Delete"/>';
                              }
                              echo '</form></div>';
                  
                  echo '<a href="'.$image.'">';
                  echo '<img src="'.$image.'">';
                  echo '</a>';
                  
                  echo '</div>';
                  echo '</div>';
                  }
                  
                  echo '</div>';
                  
                  echo '<div class="clearfix"></div>';
                  ?>
            </div>
         </div>
      </div>
      <footer class="footer w3-rest">
         <i>2018 - nnqisha&copy;</i>
         Camagru 
      </footer>
   </body>
</html>