<?php
	header('Content-Type: text/html; charset=utf8');
	session_save_path("/amd/cs/101/0116053/public_html/");
	session_start();
	date_default_timezone_set("Asia/Taipei");

	$db_host = "dbhome.cs.nctu.edu.tw";
	$db_name = "hungsh_cs";
	$db_user = "hungsh_cs";
	$db_pw = "ji394zj6x06";

	$dsn = "mysql:host={$db_host}; dbname={$db_name}";
	$db = new PDO($dsn, $db_user, $db_pw);
	$db->exec("SET NAMES 'utf-8'");
	$db->exec("SET CHARACTER_SET_CLIENT='utf8'");
	$db->exec("SET CHARACTER_SET_RESULTS='utf8'");
	$db->exec("SET character_set_connection = utf8");

	$mid = $_REQUEST['mid'];

	$sql_read = "UPDATE PM SET IsRead = 1 WHERE ID = {$mid}";
	$sth_read = $db->exec($sql_read);
?>
