<?php
header('Content-Type: text/html; charset=utf8');
session_save_path("/amd/cs/101/0116053/public_html/");
session_start();
date_default_timezone_set("Asia/Taipei");

$ID = $_GET['ID'];
$IN = $_GET['IN'];

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
$voice = $game_data->Voice;

$islogin = false;
if (!isset($_SESSION['uid'])) {
	echo "<script>window.alert(\"先登入再來參觀喔！\"); window.location=\"index.php\"</script>";
}
else if ($_SESSION['username']!=$GM) {
	echo "<script>window.alert(\"不要偷關別人家的燈！\"); history.go(-1);</script>";
}
else {

$t0 = $voice >> 1;
$t1 = $voice % 2;

if (!$IN) {
	if ($t0==0) $t0 = 1;
	else $t0 = 0;
}
else {
	if ($t1==0) $t1 = 1;
        else $t1 = 0;
}

$voice = $t0 * 2 + $t1;

$sql = "UPDATE Game_Name SET Voice={$voice} WHERE ID={$ID}";
$sth = $db->exec($sql);

echo "<script>history.go(-1); location.reload();</script>";
}
