<?php
header('Content-Type: text/html; charset=utf8');
header('Content-Type: text/html; charset=utf8');
session_save_path("/amd/cs/101/0116053/public_html/");
session_start();
date_default_timezone_set("Asia/Taipei");

$islogin = false;
if (!isset($_SESSION['uid'])) {
	echo "<script>window.alert(\"先登入再來發文喔！\"); window.location=\"index.php\"</script>";
}

$theme = $_POST['theme'];
$text = $_POST['text'];
$GID = $_POST['GID'];
$IN = $_POST['IN'];
$UID = $_SESSION['uid'];

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

if ($UID==null || $theme==null || $text==null) {
	echo "<script>window.alert(\"年輕不要留白啦！\"); history.go(-1);</script>";
}
else if (strlen($text) < 10) {
	echo "<script>window.alert(\"至少寫十個字吧？\"); history.go(-1);</script>";
}
else {
	$date = date('Y-m-d H-i-s');

	$sql = "INSERT IGNORE INTO Text (ID, Game, UID, In_Out, Theme, Text, Date) VALUES(null, {$GID}, {$UID}, {$IN}, '{$theme}', '{$text}', '{$date}')";
	$sth = $db->exec($sql);

	$sql_game = "UPDATE Game_Name SET Last='{$date}' WHERE ID={$GID}";
	$sth_game = $db->exec($sql_game);
	
	echo "<script>history.go(-1); location.reload();</script>";
}
?>
