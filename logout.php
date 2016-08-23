<?php
error_reporting(0);
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled 1</title>
</head>

<body>
<?php
if(isset($_SESSION['uname'])||isset($_SESSION['id']))
{
session_unset('username');
session_unset('id');
}

echo "if the page does not redirect automatically Please <a href='http://localhost/projj/index.php'>click here</a>";
		?>
		<script type="text/javascript" >
setTimeout(function () {
   window.location.href= 'http://localhost/projj/index.php'; // the redirect goes here

},0);
</script>
<?php

?>

</body>

</html>