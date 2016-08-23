<?php
	session_start();
	
	$id =$_SESSION['id'];
	$username =$_SESSION['username'];
	if(!isset($_SESSION['id']))
	{
	header('Location: http://localhost/projj/index.php?error=1');
	}
?>

<!DOCTYPE html>
<?php
$con = mysql_connect("localhost","user","");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("friend", $con);

$result = mysql_query("SELECT * FROM  `members` WHERE  `online` =1");
?>
<html>

<head>
<style type="text/css">
#customers
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
width:100%;
border-collapse:collapse;
}
#customers td, #customers th 
{
font-size:1em;
border:1px solid #98bf21;
padding:3px 7px 2px 7px;
}
#customers th 
{
font-size:1.1em;
text-align:left;
padding-top:5px;
padding-bottom:4px;
background-color:#A7C942;
color:#ffffff;
}
#customers tr.alt td 
{
color:#000000;
background-color:#EAF2D3;
}
.style1 {
	color: #FFFFFF;
}
.style2 {
	color: #FFFFFF;
	text-align: center;
}
</style>
</head>

<body>
<table id="customers" style="height: 118px">
<tr>
  <th style="width: 87px">Image</th>
  <th style="width: 285px">Name</th>
  <th style="width: 427px">E-Mail Address</th>
   <th style="width: 206px">Location</th>
 <th>Send Request</th>

</tr>
<?php
while($row = mysql_fetch_array($result))
  {
 
?>
<tr class="alt">
<td style="width: 87px" class="style1"><img src="../images/members/<?php echo $row[7]; ?>"></td>
<td style="width: 285px" class="style1"><?php echo $row[1]?></td>
<td style="width: 427px" class="style1"><a href="mailto:<?php echo $row[6]?>" title="Click Here To Send a Mail To <?php echo $row[1]?>"><?php echo $row[6]?> </a></td>
<td style="width: 206px" class="style1"><a href="http://en.wikipedia.org/wiki/<?php echo $row[10]?>" target="_blank" title="Click here to know greatness of <?php echo $row[10]?>"><?php echo $row[10]?></a></td>
<td style="width: 169px" class="style2">
<form method="post">
	<input id="sndreq" type="button" value="Send Request" style="width: 92px; height: 32px"></form>
</td>
<?php 
}
?>
</tr>
</table>
 

</body>

</html>

