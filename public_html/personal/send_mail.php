<?php
header('Content-Type: text/html; charset=utf8');
session_save_path("/amd/cs/101/0116053/public_html/");
session_start();
date_default_timezone_set("Asia/Taipei");

$source = $_POST['source'];
$destination = $_POST['destination'];
$theme = $_POST['theme'];
$text = $_POST['text'];

if ($source==null || $destination==null) {
	echo "<script>window.alert(\"年輕不要留白啦！\"); history.go(-1);</script>";
}

$islogin = false;
if (!isset($_SESSION['uid'])) {
	echo "<script>window.alert(\"先登入再來寄信喔！\"); window.location=\"index.php\"</script>";
}
else if ($source != $_SESSION['username']) {
	echo "<script>window.alert(\"怎麼不用自己的名字呢？\"); window.location=\"../index.php\"</script>";
}

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

$sql_s = "SELECT * FROM User WHERE User_Name = '{$source}'";
$sth_s = $db->prepare($sql_s);
$sth_s->execute();
$rs_s = $sth_s->fetchAll();
$s = $rs_s[0][0];

if ($_SESSION['username'] == 'Admin' && $destination == 'All') {
	$sql_d = "SELECT * FROM User";
}
else {
	$sql_d = "SELECT * FROM User WHERE User_Name = '{$destination}'";
}
$sth_d = $db->prepare($sql_d);
$sth_d->execute();
$rs_d = $sth_d->fetchAll();

if (count($rs_d) == 0) {
	echo "<script>window.alert(\"沒有這個人欸......\"); history.go(-1);\"</script>";
}
else if (count($rs_d) > 1) {
	for ($i=2; $i < $rs_d; $i++) {
		$d = $rs_d[$i][0];
		$date = date('Y-m-d H-i-s');
		$sql_1 = "INSERT INTO PM (ID, Source, Destination, Theme, Text, Date, IsRead, Backup) VALUES(null, {$s}, {$d}, '{$theme}', '{$text}', '{$date}', 0, 0)";
        	$sth_1 = $db->exec($sql_1);
	}

	echo "<script>location.href='index.php';</script>";
}
else {

$d = $rs_d[0][0];

if ($theme==null || $text==null) {
	echo "<script>window.alert(\"年輕不要留白啦！\"); history.go(-1);</script>";
}
else if (strlen($text) < 10) {
	echo "<script>window.alert(\"至少寫十個字吧？\"); history.go(-1);</script>";
}
else {
	$date = date('Y-m-d H-i-s');

	$sql_1 = "INSERT IGNORE INTO PM (ID, Source, Destination, Theme, Text, Date, IsRead, Backup) VALUES(null, {$s}, {$d}, '{$theme}', '{$text}', '{$date}', 0, 0)";
	$sth_1 = $db->exec($sql_1);

	$sql_2 = "INSERT IGNORE INTO PM (ID, Source, Destination, Theme, Text, Date, IsRead, Backup) VALUES(null, {$d}, {$s}, '{$theme}', '{$text}', '{$date}', 0, 1)";
        $sth_2 = $db->exec($sql_2);

	echo "<script>location.href='index.php';</script>";
}

}
?>
