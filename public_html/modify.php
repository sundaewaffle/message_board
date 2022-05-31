<?php
header('Content-Type: text/html; charset=utf8');
session_save_path("/amd/cs/101/0116053/public_html/");
session_start();
date_default_timezone_set("Asia/Taipei");

$ID = $_GET['ID'];
$story = $_GET['story'];

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

$ID = $_POST['ID'];
$theme = $_POST['theme'];
$text = $_POST['text'];

if (!isset($_SESSION['uid'])) {
	echo "<script>window.alert(\"先登入再來編輯喔！\"); window.location=\"index.php\"</script>";
}
else {
	$date = date('Y-m-d H-i-s');
	$sql = "UPDATE Text SET Theme='{$theme}',Text='{$text}',Mdate='{$date}' WHERE ID={$ID}";
	$sth = $db->exec($sql);

	echo "<script>history.go(-2); location.reload();</script>";
}

?>
