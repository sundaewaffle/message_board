<?php
header('Content-Type: text/html; charset=utf8');
session_save_path("/amd/cs/101/0116053/public_html/");
session_start();
date_default_timezone_set("Asia/Taipei");

$ID = $_GET['ID'];

$db_host = "dbhome.cs.nctu.edu.tw";
$db_name = "hungsh_cs";
$db_user = "hungsh_cs";
$db_pw = "ji394zj6x06";

$dsn = "mysql:host={$db_host}; dbname={$db_name}";
$db = new PDO($dsn, $db_user, $db_pw);
$db->exec("SET NAMES 'utf-8'");
$db->exec("SET CHARACTER_SET_CLIENT='utf8'");
$db->exec("SET CHARACTER_SET_RESULTS='utf8'");
$db->exec("SET character_set_connection='utf8'");

$game_sql = "SELECT * FROM Game_Name WHERE ID = {$ID}";
$sth2 = $db->prepare($game_sql);
$sth2->execute();
$game_data = $sth2->fetchObject();

$GM = $game_data->GM;
$state = $game_data->State;

if (!isset($_SESSION['uid'])) {
	echo "<script>window.alert(\"先登入再來改喔！\"); window.location=\"index.php\"</script>";
}
else if ($_SESSION['username']!=$GM) {
	echo "<script>window.alert(\"不要偷改別人的狀態！\"); history.go(-1);</script>";
}
else {

$state = $state - 1;
if ($state == 0) {
	$state = 3;
}

$sql = "UPDATE Game_Name SET State={$state} WHERE ID={$ID}";
$sth = $db->exec($sql);

echo "<script>history.go(-1); location.reload();</script>";
}
