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
    die("Server connection failed server might be too busy try again!");
}
$dbselect = mysql_select_db("friend", $connection);

if (!$dbselect) {
    die("Database selection failed server might be too busy try again! ");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="1"/>
<title>chat</title>
<style type="text/css">
.style1 {
	text-align: left;
}
</style>
</head>

<body>
<div style="height: 190px; width: 50%" class="style1" align="center">

<form method="post">
<input name="Text1" type="text" style="width: 185px" />
<br />
<?php
$result= mysql_query("SELECT * FROM  `messages` WHERE  `senderid` =1 AND  `recieverid` =2",$connection);
if (!$result) { // if not result die page
                die(" query failed Sorry server might be too busy try again!");
            }
            else {
                while ($row = mysql_fetch_array($result)) {
                    echo '<span style="color:#ffffff">'.$row[1].'</span>';
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                    echo '<span style="color:#ffffff">'.$row[3].'</span>';
                    echo "<br/>";
                }
            }
?>
</form>

</div>
</body>

</html>