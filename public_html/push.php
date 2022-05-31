<?php
header('Content-Type: text/html; charset=utf8');
session_save_path("/amd/cs/101/0116053/public_html/");
session_start();
date_default_timezone_set("Asia/Taipei");

$islogin = false;
if (!isset($_SESSION['uid'])) {
	echo "<script>window.alert(\"先登入再來參觀喔！\"); window.location=\"index.php\"</script>";
}

$text = $_POST['push'];
$TID = $_POST['TID'];
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

if ($text==null) {
	echo "<script>window.alert(\"年輕不要留白啦！\"); history.go(-1);</script>";
}
else if (strlen($text) > 100) {
	echo "<script>window.alert(\"長話短說喔，謝謝\"); history.go(-1);</script>";
}
else {
	$date = date('Y-m-d H:i:s');

	$sql_test = "SELECT * FROM Push WHERE TID={$TID} AND UID={$UID}";
	$sth_test = $db->prepare($sql_test);
	$sth_test->execute();
	$test_data = $sth_test->fetchAll();

	if (count($test_data) != 0) {
		$tt = 0;
		while ($tt < count($test_data)) {
			$timeDiff = strtotime($date) - strtotime($test_data[$tt][4]);
			if ($timeDiff < 30) {
				echo "<script>history.go(-1); location.reload();</script>";
			}
			$tt = $tt + 1;
		}
	}

	$sql = "INSERT IGNORE INTO Push (ID, TID, UID, Text, Date) VALUES(null, {$TID}, {$UID}, '{$text}', '{$date}')";
	$sth = $db->exec($sql);

	$sql_t = "SELECT * FROM Text WHERE ID={$TID}";
	$sth_t = $db->prepare($sql_t);
	$sth_t->execute();
	$t_data = $sth_t->fetchObject();

	$sql_g = "SELECT * FROM Game_Name WHERE ID={$t_data->Game}";
        $sth_g = $db->prepare($sql_g);
        $sth_g->execute();
        $g_data = $sth_g->fetchObject();

	$mail_theme = "有善心人士推薦您的回應";

	$mail_text = "您發表在 " . $g_data->Name . " 的回應被人推薦了！\n\n以下為該則回應的內容\n<table border=\"3\" rules=\"none\" style=\"border-style: outset; width: 750px;\"><tr><td>" . $t_data->Text . "</td></tr></table>\n" . "推薦人：" . $_SESSION['username'] . "\n推薦內容：" . $text;

	if ($UID != $t_data->UID) {
		$sql_mail = "INSERT IGNORE INTO PM (ID,Source,Destination,Theme,Text,Date,IsRead,Backup) VALUES(null, {$UID}, {$t_data->UID}, '{$mail_theme}', '{$mail_text}', '{$date}', 0, 0)";
		$sth_mail = $db->exec($sql_mail);
	}

	echo "<script>history.go(-1); location.reload();</script>";
}
?>
