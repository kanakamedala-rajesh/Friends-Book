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

if (!$connection) {
    die("Database connection failed server might be too busy try again!");
}
?>

<?php

$dbselect = mysql_select_db("friend", $connection);

if (!$dbselect) {
    die("Database selection failed server might be too busy try again! ");
}
?>

<?php

$update = $_POST['update'];
//echo $update;
$sql    = "INSERT INTO `friend`.`update` (`id`, `postedid`, `update`, `time`) VALUES (NULL, '1', '$update', CURRENT_TIMESTAMP);";

if (!mysql_query($sql, $connection)) {
    die('Error: ' . mysql_error());
}
//echo "1 record added";

header('Location: http://localhost/projj/my world/update.php');
?>
