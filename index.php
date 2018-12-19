<?PHP
    require("config/setup.php");
    // var_dump($_SESSION);
    function error()
    {
        if (isset($_SESSION['errors']))
        {
           echo($_SESSION['errors']);
            // foreach ($status as $key) {
            //     echo $status[$key];
            //     echo '<br>';
            //}
        }

    }
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>camagru - login</title>
        <link rel="stylesheet" href="styles/home.css" type="text/css" media="all">
        <link rel="stylesheet" href="styles/w3.css" type="text/css" media="all">
        <style>
            body {
                background-image: url("https://techflourish.com/images/cat-whiskers-clipart-transparent-14.png");
                background-repeat: no-repeat;
            }
            a:link, a:visited {
    color: white;
    padding: 1px 25px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
}
        </style>
    </head>
    <body>
        <div class="a w3-animate-top w3-rest">
            <img src="http://images.clipartpanda.com/cute-camera-icon-AMD_962_0190301.gif~c200" id="logoimg" />
            <div class="b">
                <div class="logo">
                    <h1>CAMAGRU</h1>
                </div>
                <div class="login">
                    <form action="Functions/login.php" method="post">
                        <table>
                            <tr>
                                <td>
                                    <p>Username</p>
                                </td>
                                <td>
                                    <p>Password</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="username" value="<?php if(isset($_POST["username"])) echo $_POST["username"]; ?>" required class="inputa" />
                                </td>
                                <td>
                                    <input name="pass" valu="" type="password" class="inputa" />
                                </td>
                                <td>
                                    <input type="submit" name="submit" class="submitlogin" value="login" />
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><a href="gallery.php">Gallery</a></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <p style="color:#ffffff"><a href="forgot.php">Click here to reset Password</a></p>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
        <section class="section">
        <div class="content">
            <div class="left">
            </div>
            <div class="container">
                <div class="right w3-animate-left">
                    <h2>Create an account</h2>
                    <p>It's free and always will be.</p>
                    <div class="login-page">
                        <div class="form w3-rest">
                            <form class="login-form" action="Functions/register.php" method="post" >
                                <input name="uname" value="<?php if(isset($_POST[" uname "])) echo $_POST["uname "]; ?>" placeholder="Fullname" class="name" required/>
                                <input name="username" value="<?php if(isset($_POST[" username "])) echo $_POST["username "]; ?>" placeholder="Username" class="name" required/>
                                <input name="pass" value="" type="password" id="pass" class="up" placeholder="password"  />
                                <input name="repasswd" value="" type="password" id="pass2" class="up" placeholder=" Re-type Password" required onfocusout="varpass()" />
                                <input type="email" name="email" class="up" value="<?php if(isset($_POST[" email "])) echo $_POST["email "]; ?>" placeholder="email address" required/>
                                <p><?php error(); ?></p>
                                <button type="Submit" name="Submit" id="register">Submit</button>
                            </form>
                            <script>
    function varpass() {
        var pass = document.getElementById("pass");
        var pass2 = document.getElementById("pass2");
        if ((pass.value != pass2.value)) {
            pass2.style.borderColor = "red";
            pass2.value = "";
        } else if (pass2.value == "" || pass.value == "")
            pass2.style.borderColor = "red";
        else {
            pass2.style.borderColor = "green";
            pass.style.borderColor = "green";
        }
    }; </script>
                        </div>
                    </div>
                </div>
            </div>

   </section>
<footer class=footer>
  <p>nnqisha 2018</p>
</footer>
</body>
    </html>