<?php
session_start();
$connection = mysql_connect("localhost", "root", "");
if (!$connection) {
    die("Server connection failed server might be too busy try again!");
}
$dbselect = mysql_select_db("friend", $connection);

if (!$dbselect) {
    die("Database selection failed server might be too busy try again! ");
}
$email  = $_POST['email'];
$pwd    = $_POST['password'];
$result = mysql_query("SELECT * FROM  `members` WHERE  `pasword` LIKE  '$pwd' AND  `email_id` LIKE  '$email' ", $connection);
if (!$result) { // if not result die page
    die(" query failed Sorry server might be too busy try again!");
}
else {
    $count = 0;
    while ($row   = mysql_fetch_array($result)) {
        $id                = $row[0];
        $username          = $row[1];
        $count++;
        $row[9]            = 1;
    }
    if($count > 0 && $count < 2)
		{
    $_SESSION['username'] = $username;
    $_SESSION['id']    = $id;
    $url = "Location:myworld.php?id=" . $id . "&username=" . $username;
     header($url);
    }
    else {
        header('Location: http://localhost/projj/index.php?error=1');
    }
    }
?>      