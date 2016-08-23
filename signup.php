<?php
	session_start();
	
	$id =$_SESSION['id'];
	$username =$_SESSION['username'];
	if(!isset($_SESSION['id']))
	{
	header('Location: http://localhost/projj/index.php?error=1');
	}
?>

<?php
$connection = mysql_connect("localhost", "root", "");

	if(!$connection)
		{
		die("Server connection failed server might be too busy try again!");
		}
?>
<?php
	$dbselect = mysql_select_db("friend",$connection);
	
	if(!$dbselect)
	{
		die("Database selection failed server might be too busy try again! ");
	}
	
?>
<?php

/* @var $uname callable */
$uname= $_POST["usernamesignup"];
/* @var $email type */
$email= $_POST["emailsignup"];
$password= $_POST["passwordsignup"];
$cpassword= $_POST["passwordsignup_confirm"];

//echo $uname.$email.$password.$cpassword;

$result = mysql_query("SELECT * FROM `basic` WHERE 1",$connection);
if(!$result)		// if not result die page
					{
					die(" query failed Sorry server might be too busy try again!");
					}
else
		{
		   if($password!=$cpassword)
		   {
		   header('Location: http://localhost/Practice/php/proj/#toregister?errorp=1');
		   }
		   else 
		   {
		   echo "Password Correct";
		   }
		}
?>