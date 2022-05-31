<?php
header('Content-Type: text/html; charset=utf8');
session_save_path("/amd/cs/101/0116053/public_html/");
session_start();
date_default_timezone_set("Asia/Taipei");

$ID = $_GET['mid'];

if (!isset($_SESSION['uid'])) {
	echo "<script>window.alert(\"不准偷偷殺掉信鴿！\"); ";
	echo "window.location=\"../index.php\"";
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

$sql_mail = "SELECT * FROM PM WHERE ID={$ID}";
$sth_mail = $db->prepare($sql_mail);
$sth_mail->execute();
$mail_data = $sth_mail->fetchObject();

if ($_SESSION['uid'] == $mail_data->Destination) {
	$sql = "DELETE FROM PM WHERE ID={$ID}";
	$sth = $db->exec($sql);
	echo "<script>location.href='index.php'</script>";
}
else {
	echo "<script>window.alert(\"不准偷偷殺掉信鴿！\"); ";
        echo "window.location=\"../index.php\"";
        echo "</script>";
}

}
?>
