<?php
session_start();
if (isset($_SESSION['id'])) {
    $id       = $_SESSION['id'];
    $username = $_SESSION['username'];
    $url      = "Location:myworld.php?id=" . $id . "&username=" . $username;
    header($url);
}
?>
<!DOCTYPE html>
<style type="text/css">
    .style2 {
        color: #FF0000;
    }
    .style3 {
        font-size: xx-large;
        color: #D584D2;
        font-family: Algerian;
        text-align: center;
    }
    .style4 {
        width: 100%;
        height: 100%;
        position: relative;
    }
</style>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6 lt8"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7 lt8"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8 lt8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><![endif]-->

<html lang="en" class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="UTF-8" />
        <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  -->
        <title>Login and Registration Form For Friends Book</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="description" content="Login and Registration Form For Friends Book" />
        <meta name="author" content="Gec" />
        <link rel="shortcut icon" href="../"> 
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <link rel="stylesheet" type="text/css" href="css/style2.css" />
        <link rel="stylesheet" type="text/css" href="css/animate-custom.css" />
    </head>
    <body>
        <div class="style4">

            <h1 class="style3"><strong><em>Login</em> and Registration Form</strong></h1>


            <section>				
                <div id="container_demo" style="background:url('images/bg1.png') no-repeat scroll center center">

                    <a class="hiddenanchor" id="toregister"></a>
                    <a class="hiddenanchor" id="tologin"></a>
                    <div id="wrapper">

                        <div id="login" class="animate form">
                            <form  action="login.php" autocomplete="on" method="post"> 
                                <h1>Log in</h1> 

                                <p>
                                    <?php
                                    if (isset($_GET['error'])) {
                                        ?>

                                        <span class="style2">*Check user name and password</span>
    <?php
}
?>

                                </p>
                                <p> 
                                    <label for="username"  data-icon="u" > Your E-mail </label>
                                    <input id="email" name="email" required="required" type="email" placeholder="emailid@mail.com"/>
                                </p>
                                <br>
                                <p> 
                                    <label for="password" data-icon="p"> Your Password </label>
                                    <input id="password" name="password" required="required" type="password" placeholder="eg. jjkfjhf7356" /> 
                                </p>
                                <br>
                                <p class="keeplogin"> 
                                    <input type="checkbox" name="loginkeeping" id="loginkeeping" value="loginkeeping" /> 
                                    <label for="loginkeeping">Keep me Logged in</label>
                                </p>

                                <p class="login button"> 
                                    <input type="submit" value="Login" /> 
                                </p>
                                <br>
                                <p class="change_link">
                                    Not a member yet ?
                                    <a href="#toregister" class="to_register">Join us</a>
                                </p>
                            </form>
                        </div>

                        <div id="register" class="animate form">
                            <form  action="signup.php" autocomplete="on" method="post"> 
                                <h1> Sign up </h1> 
                                <p>
<?php
if (isset($_GET['errorp'])) {
    ?>

                                        <span class="style2">*Check user name and password</span>
                                        <?php
                                    }
                                    ?>

                                </p>


                                <p> 
                                    <label for="usernamesignup" class="uname" data-icon="u">Your Name</label>
                                    <input id="usernamesignup" name="usernamesignup" required="required" type="text" placeholder="Please enter your user name" />
                                </p>
                                <p> 
                                    <label for="emailsignup" class="youmail" data-icon="e" > Your E-mail</label>
                                    <input id="emailsignup" name="emailsignup" required="required" type="email" placeholder="Please enter your email id"/> 
                                </p>
                                <p> 
                                    <label for="passwordsignup" class="youpasswd" data-icon="p">Your Password </label>
                                    <input id="passwordsignup" name="passwordsignup" required="required" type="password" placeholder="Your desired Password please"/>
                                </p>
                                <p> 
                                    <label for="passwordsignup_confirm" class="youpasswd" data-icon="p">Please Confirm Your Password </label>
                                    <input id="passwordsignup_confirm" name="passwordsignup_confirm" required="required" type="password" placeholder="Conform Password given above"/>
                                </p>
                                <br>
                                <p class="signin button"> 
                                    <input type="submit" value="Sign up"/> 
                                </p>
                                <br>
                                <p class="change_link">  
                                    Already a member ?
                                    <a href="#tologin" class="to_register"> Go and log in </a>
                                </p>
                            </form>
                        </div>

                    </div>
                </div>  
            </section>
        </div>
    </body>
</html>
