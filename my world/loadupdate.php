<?php
	session_start();
	
	$id =$_SESSION['id'];
	$username =$_SESSION['username'];
	if(!isset($_SESSION['id']))
	{
	header('Location: http://localhost/projj/index.php?error=1');
	}
?>

<html>
<head>
<style>
/* chat block */
.chatmessages {
    border: 1px solid #888;
    color: #000;
    padding: 10px;
}
.chatmessages a, .priv_conv a {
    color: #000;
}
.chatmessages a img, .priv_conv a img {
    margin-right: 10px;
    vertical-align: middle;
    width: 22px;
}
.chatmessages .message, .priv_conv .message {
    background-color: #fff;
    margin: 5px;
    padding: 5px;

    -moz-border-radius: 5px;
    -ms-border-radius: 5px;
    -o-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;
}
.chatmessages .message span, .priv_conv .message span {
    color: #444;
    font-size: 10px;
    margin-left: 10px;
}
.chatsubmitform {
    margin: 10px 0px;
    overflow: hidden;
}
.chatsubmitform .error, .chatsubmitform .success, .chatsubmitform .protect {
    display: none;
}
.chatsubmitform .error {
    color: #f55;
}
.chatsubmitform .success {
    color: #5f5;
}
.chatsubmitform .protect {
    color: #55f;
}

</style>
</head>
<?php
$q=$_GET["q"];

$con = mysql_connect('localhost', 'user', '');
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("friend", $con);

$sql="SELECT * FROM  `update` WHERE 1 ";

$result = mysql_query($sql);

 header('Location: http://localhost/projj/my%20world/update.php');

       
?>
 <div class="chat_messages">
            <div class="message" id="message_1" ><b><a href="profile.php?id=1" target="_blank">
				<img src="../images/profile.png" width="51">test user:</a></b> hiii<span>(05:04:54)</span></div>
        </div>

</html>
