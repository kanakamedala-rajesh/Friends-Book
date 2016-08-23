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
.style3 {
	color: #A7C942;
}
</style>
</head>

<body>

<form method="post">
	<fieldset name="Group1" style="height: 130px;">
	<legend><span class="style3">Select A Place You Are Interested</span> </legend>
	<br>
	<span class="style3">Place Name</span> &nbsp;<select name="Select1" style="width: 221px">
	<option style="color:#DBE9F0">Please Select a Nearby Place</option>

<?php
	$result = mysql_query("SELECT DISTINCT * FROM  `members` ORDER BY `location`  ",$con);
if(!$result)		// if not result die page
					{
					die(" query failed Sorry server might be too busy try again!");
					}
else
	{
	while($row = mysql_fetch_array($result))
		{
		//echo $row[0].$row[1].$row[2].$row[3]."<br/>";
?>

	<option><?php echo $row[10]; ?></option>
	
<?php
}
}
?>
	</select><br>
	<br>
<div  id="search"><input name="Submit1" type="submit" value="Search " style="width: 100px;"  /></div></fieldset></form>
<br/>
<fieldset><legend><span class="style3">You Selected</span> </legend>
<br/><br/>
<div id="results">
<table id="customers" style="height: 118px">
<tr>
  <th style="width: 87px; height: 20px;">Image</th>
  <th style="width: 285px; height: 20px;">Name</th>
  <th style="width: 427px; height: 20px;">E-Mail Address</th>
   <th style="width: 206px; height: 20px;">Location</th>
 <th style="height: 20px">Send Request</th>

</tr>
<?php
if(isset($_POST['Submit1']))
				{
				$sel = $_POST['Select1'];
				$result = mysql_query("SELECT * FROM  `members` WHERE  `location` LIKE  '$sel'",$con);
				if(!$result)		// if not result die page
					{
					die(" query failed Sorry server might be too busy try again!");
					}

				}
			while($rowp= mysql_fetch_array($result))
  {
?>
<tr class="alt">
<td style="width: 87px" class="style1"><img src="../images/members/<?php echo $rowp[7]; ?>"></td>
<td style="width: 285px" class="style1"><?php echo $rowp[1]?></td>
<td style="width: 427px" class="style1"><a href="mailto:<?php echo $rowp[6]?>" title="Click Here To Send a Mail To <?php echo $rowp[1]?>"><?php echo $rowp[6]?> </a></td>
<td style="width: 206px" class="style1"><a href="http://en.wikipedia.org/wiki/<?php echo $rowp[10]?>" target="_blank" title="Click here to know greatness of <?php echo $rowp[10]?>"><?php echo $rowp[10]?></a></td>
<td style="width: 169px" class="style2">
<form method="post" action="location.php">
	<input id="sndreq" type="button" value="Send Request" style="width: 92px; height: 32px"></form>
</td>
<?php
if (isset($_POST['sndreq'])){
   $request = mysql_query("INSERT INTO  `friend`.`frnd_request` (`id` ,`sendby_id` ,`sendto_id` ,`status` ,`date`)VALUES (NULL ,  '2',  '1',  '0', CURRENT_TIMESTAMP);",$con);
				if(!$result)		// if not result die page
					{
					die(" query failed Sorry server might be too busy try again!");
					}
}

?>
<?php 
}

?>
</tr>
</table>
 </div>
 </fieldset>
</body>

</html>

