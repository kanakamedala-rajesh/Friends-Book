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
$result = mysql_query("SELECT * FROM `settings`", $connection);
if (!$result) { // if not result die page
    die(" query failed Sorry server might be too busy try again!");
    $row   = mysql_fetch_array($result);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Language" content="en-us" />
<style type="text/css">
.style2 {
	color: #5F870E;
	text-align: center;
}
.style3 {
	text-align: center;
}
</style>
</head>
<body>

<table style="width: 100%">
	<tr>
		<td style="width: 343px" class="style2">Nickname</td>
		<td class="style2">&nbsp;</td>
	</tr>
	<tr>
		<td style="width: 343px" class="style2">Date of Birth</td>
		<td class="style3">&nbsp;</td>
	</tr>
	<tr>
		<td style="width: 343px" class="style2">About Urself</td>
		<td class="style3">&nbsp;</td>
	</tr>
	<tr>
		<td style="width: 343px" class="style2">Relationship</td>
		<td class="style3">&nbsp;</td>
	</tr>
	<tr>
		<td style="width: 343px" class="style2">Interested in</td>
		<td class="style3">&nbsp;</td>
	</tr>
	<tr>
		<td style="width: 343px" class="style2">Phone</td>
		<td class="style3">&nbsp;</td>
	</tr>

<tr>
		<td style="width: 343px" class="style2">Likes</td>
		<td class="style3">&nbsp;</td>
	</tr>

<tr>
		<td style="width: 343px" class="style2">Education</td>
		<td class="style3">&nbsp;</td>
	</tr>
<tr>
		<td style="width: 343px" class="style2">Hobbies</td>
		<td class="style3">&nbsp;</td>
	</tr>

<tr>
		<td style="width: 343px" class="style2">Other details</td>
		<td class="style3">&nbsp;</td>
	</tr>

<tr>
		<td style="width: 343px" class="style2">Privacy</td>
		<td class="style3">&nbsp;</td>
	</tr>


	
</table>

</body>
</html>
