<?php
header('Content-Type: text/html; charset=utf8');
session_save_path("/amd/cs/101/0116053/public_html/");
session_start();
date_default_timezone_set("Asia/Taipei");

$uid = $_POST['uid'];
$pw = hash('sha256' ,$_POST['password']);
#$pw = hash('sha256' ,$_POST['password']);

$db_host = "dbhome.cs.nctu.edu.tw";
$db_name = "hungsh_cs";
$db_user = "hungsh_cs";
$db_password = "ji394zj6x06";

$dsn = "mysql:host={$db_host};dbname={$db_name}";
$db = new PDO($dsn, $db_user, $db_password);
$db->exec("SET NAMES 'utf-8'");
$db->exec("SET CHARACTER_SET_CLIENT='utf8'");
$db->exec("SET CHARACTER_SET_RESULTS='utf8'");
$db->exec("SET character_set_connection = utf8");

$sql = "SELECT * FROM User WHERE User_Name = ?";
$sth = $db->prepare($sql);
$sth->execute(array($uid));
$result = $sth->fetchObject();

if($uid==null || $pw==null)
{
	header('location: index.php');
	exit;
}
else 
{
	if ($result->Password==$pw) {
		$_SESSION['uid'] = $result->UID;
		$_SESSION['username'] = $result->Name;
		$_SESSION['picture'] = $result->Picture_Addr;
		header('location: index.php');
		exit;
	}
	header('location: index.php');
        exit;
}

?>
