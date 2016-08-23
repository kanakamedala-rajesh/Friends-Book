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
<html lang="en">
	<head>
		<title></title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="css/reset.css" type="text/css" media="all">
		<link rel="stylesheet" href="css/layout.css" type="text/css" media="all">
		<link rel="stylesheet" href="css/style.css" type="text/css" media="all">
		<script type="text/javascript" src="js/jquery-1.6.js" ></script>
				<!--[if lt IE 9]>
		<script type="text/javascript" src="js/html5.js"></script>
		<link rel="stylesheet" href="css/ie.css" type="text/css" media="all">
		<![endif]-->
		<!--[if lt IE 7]>
			<div style=' clear: both; text-align:center; position: relative;'>
				<a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode"><img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." /></a>
			</div>
		<![endif]-->
	</head>
	<body id="page1">
		<div class="main">
<!--header -->
			<header>
				<div class="wrapper">
					<h1><a href="index.php" id="logo">FriendsBook.com</a></h1>
					<form id="search" method="post">
						<fieldset>
							<div class="bg"><input class="input" type="text" value="Search"  onblur="if(this.value=='') this.value='Search'" onFocus="if(this.value =='Search' ) this.value=''" ></div>
						</fieldset>
					</form>
				</div>
				<nav>
				<ul id="menu">
						<li class="active"><a href="myworld.php"><span>my world</span></a></li>
						<li><a href="wannafrndz.php"><span>Wanna Frndz</span></a></li>
						<li><a href="geteducated.php"><span>Get Educated</span></a></li>
						<li><a href="contactus.php"><span>Contact Us</span></a></li>
						<li class="last"><a href="logout.php"><span>Logout</span></a></li>
					</ul>

				</nav>
				<br/><br/><br/><br/>
				<nav style="float:left">
					<ul id="menu1">
						<li class="active"><a href="my world/update.php" target="iframe"><span>Post Updates</span></a></li>
						<li><a href="my world/friend requests.php" target="iframe"><span>Friend Requests</span></a></li>
						<li><a href="my world/chat.php" target="iframe"><span>Chat with Friends</span></a></li>
						<li><a href="profile.php"><span>Your Profile</span></a></li>
						<li class="last"><a href="settings.php" target="iframe"><span>Settings</span></a></li>
					</ul>
				</nav>
	<iframe style="height:573px; vertical-align: baseline; width: 762px; padding-top:45px" seamless="seamless" frameborder="1" name="iframe" id="iframe" border="1"></iframe>
			</header>
<!--header end-->
<!--content -->
					
	
		</div>
		</body>

</html>
