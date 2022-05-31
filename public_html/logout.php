<?php
	header('Content-Type: text/html; charset=utf8');
	session_save_path("/amd/cs/101/0116053/public_html/");
	session_start();
	date_default_timezone_set("Asia/Taipei");

	$uid = $_SESSION['uid'];
	$username = $_SESSION['username'];
	session_destroy();

	echo "<script>window.alert(\"(っ﹏-).｡o 掰掰~~ ";
	echo $username;
	echo "\");";
	echo "window.location=\"index.php\"";
	echo "</script>";
?>
