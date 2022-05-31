<?php
header('Content-Type: text/html; charset=utf8');
session_save_path("/amd/cs/101/0116053/public_html/");
session_start();
date_default_timezone_set("Asia/Taipei");

$islogin = false;
if (!isset($_SESSION['uid'])) {
	echo "<script>window.alert(\"先登入再開遊戲喔！\"); window.location=\"index.php\"</script>";
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

if (isset($_GET['name'])) {
	#echo $_GET['name'];
	$n = $_GET['name'];
	$g = $_SESSION['username'];
        $date = date('Y-m-d H-i-s');

	$sql_test = "SELECT * FROM Game_Name WHERE Name='{$n}'";
	$sth_test = $db->query($sql_test);
	$res_test = $sth_test->fetchAll();
	if (count($res_test) != 0 && isset($_GET['state'])) {
		echo "<script>window.alert(\"這個名字用過了喔！\"); window.location=\"index.php?teach=1\"</script>";
		exit();
	}
	else if (count($res_test) != 0) {
		echo "<script>window.alert(\"這個名字用過了喔！\"); window.location=\"index.php\"</script>";
                exit();
	}

	if (isset($_GET['state'])) {
		$state = 10;
		$tag = $_GET['tag'];
	}
	else {
		$state = 2;
		$tag = NULL;
	}

	$sql = "INSERT INTO Game_Name (ID,Name,GM,State,Voice,Last,Tag) VALUES(null,'{$n}','{$g}',{$state},0,'{$date}','{$tag}')";
	$sth = $db->exec($sql);

	if ($state==2) {
		header('location: http://people.cs.nctu.edu.tw/~hungsh/index.php');
	}
	else {
		header('location: http://people.cs.nctu.edu.tw/~hungsh/index.php?teach=1');
	}
	exit();
	#echo $sql;
}
else {
if (isset($_GET['state'])) {
?>

<script type="text/javascript">
var q1 = confirm('你確定要開一篇新討論嗎？');
if (!q1) {
        history.go(-1);
        exit();
}
var q3 = prompt('這篇討論的名字是？','新討論');
if (q3==null || q3=='新討論') {
        alert('別鬧了，孩子');
        history.go(-1);
        exit();
}
var q2 = prompt('這篇討論的類型是？(閒話家常、教學討論、互遊創作、原創專區)','錯誤類型會被刪除喔！');
if (q2==null || (q2!='閒話家常' && q2!='教學討論' && q2!='互遊創作' && q2!='原創專區')) {
        alert('別鬧了，孩子');
        history.go(-1);
        exit();
}
var q4 = confirm('你確定要開啟『' + q3 + '』這篇新討論嗎？');
if (!q4) {
        history.go(-1);
        exit();
i}

location.href='new.php?state=10&name='+q3+'&tag='+q2;
</script>

<?php
}
else {
?>

<script type="text/javascript">
var q1 = confirm('你確定要開一場新遊戲嗎？');
if (!q1) {
	history.go(-1);
	exit();
}
var q2 = confirm('你是否有詳讀過置頂公告中的開帖說明呢？');
if (!q2) {
	history.go(-1);
	exit();
}
var q3 = prompt('這場互動遊戲的名字是？','互動遊戲');
if (q3==null || q3=='互動遊戲') {
	alert('別鬧了，孩子');
	history.go(-1);
	exit();
}
var q4 = confirm('你願意開起' + q3 + '，並且愛它、忠誠於它、無論它斷頭、爛尾、沒有人回應，直到遊戲結束為止嗎？');
if (!q4) {
	history.go(-1);
	exit();
i}

location.href='new.php?name='+q3;
</script>

<?php  } } ?>
