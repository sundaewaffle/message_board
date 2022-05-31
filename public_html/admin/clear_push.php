<?php
header('Content-Type: text/html; charset=utf8');
session_save_path("/amd/cs/101/0116053/public_html/");
session_start();
date_default_timezone_set("Asia/Taipei");

$UID = $_SESSION['uid'];

if (!isset($_SESSION['uid']) || $UID != 1) {
	echo "<script>window.location=\"../index.php\"</script>";
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
$db->exec("SET character_set_connection = utf8");

do {
$break = 0;

$push_sql = "SELECT * FROM Push";
$sth_push = $db->query($push_sql);
$data = $sth_push->fetchAll();

$n = count($data);
for ($i=0; $i<$n; $i++) {
	$t_tid = $data[$i][1];
	$t_uid = $data[$i][2];
	$t_text = $data[$i][3];
	$t_date = $data[$i][4];
	for ($j = $i+1; $j<$n; $j++) {
		$timeDiff = strtotime($data[$j][4]) - strtotime($t_date);
		if ($t_tid == $data[$j][1] && $t_uid == $data[$j][2] &&
		    $t_text == $data[$j][3] && $timeDiff < 30) {
			$sql = "DELETE FROM Push WHERE ID={$data[$j][0]}";
			$sth = $db->exec($sql);
			$break = 1;
			echo "delete one push id = " . $data[$j][0] . "!\t";
			goto next_test;
		}
	}
}
next_test:

} while ($break==1);

echo "\tclear done!";

?>
