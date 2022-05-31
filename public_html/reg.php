<?php
header('Content-Type: text/html; charset=utf8');
session_save_path("/amd/cs/101/0116053/public_html/");
session_start();
date_default_timezone_set("Asia/Taipei");

$islogin = false;
if (isset($_SESSION['uid'])) {
	$username = $_SESSION['username'];
	echo "<script>window.alert(\"";
	echo $username;
	echo "，一個帳號很夠用了喔！\");";
	echo "window.location=\"index.php\"";
	echo "</script>";
}

$username = $_POST['uid'];
$password = hash('sha256' ,$_POST['password']);
$pw_con = hash('sha256' ,$_POST['pw_con']);
$addr = $_POST['addr'];

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

$sql = "SELECT * FROM User WHERE User_Name = '{$username}'";
$sth = $db->prepare($sql);
$sth->execute();
$result = $sth->fetchObject();

if ($username==null || $password==null || $pw_con==null) {
	header('location: reg_form.php');
	exit;
}
else if ($result != null) {
	echo "<script>window.alert(\"這個帳號有人用過了喔！\"); window.location=\"reg_form.php\"</script>";
}
else if (strlen($_POST['password']) < 4) {
	echo "<script>window.alert(\"密碼最少要四個字......\"); window.location=\"reg_form.php\"</script>";
}
else if ($password != $pw_con) {
	echo "<script>window.alert(\"兩次輸入密碼不一樣欸\"); window.location=\"reg_form.php\"</script>";
}
else {
	if ($addr==null) {
		$addr = 'http://people.cs.nctu.edu.tw/~hungsh/PA.jpg';
	}
	$sql_reg = "INSERT INTO User (UID,User_Name,Name,Password,Picture_Addr) VALUES(?,?,?,?,?)";
	$sth = $db->prepare($sql_reg);
	$sth->execute(array(null,$username,$username,$password,$addr));

	echo "<script>window.alert(\"註冊成功！請重新登入，謝謝\"); window.location=\"index.php\"</script>";
}
?>
