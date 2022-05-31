<?php
header('Content-Type: text/html; charset=utf8');
session_save_path("/amd/cs/101/0116053/public_html/");
session_start();
date_default_timezone_set("Asia/Taipei");

$ID = $_GET['ID'];
$UID = $_GET['UID'];

if (!isset($_SESSION['uid'])) {
	echo "<script>window.alert(\"不准偷偷刪掉文章！\"); ";
	echo "window.location=\"index.php\"";
	echo "</script>";
}
else if ($_SESSION['uid'] != $UID) {
	echo "<script>window.alert(\"這是別人的文章！\"); ";
        echo "history.go(-1);";
        echo "</script>";
}
else {

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

$sql = "DELETE FROM Text WHERE ID={$ID}";
$sth = $db->exec($sql);

echo "<script>history.go(-1); location.reload();</script>";
}
?>
