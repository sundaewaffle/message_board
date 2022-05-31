<?php
header('Content-Type: text/html; charset=utf8');
session_save_path("/amd/cs/101/0116053/public_html/");
session_start();
date_default_timezone_set("Asia/Taipei");

$islogin = false;
if (isset($_SESSION['uid'])) {
	$islogin = true;
	$UID = $_SESSION['uid'];
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

//$sql = "(SELECT * FROM Game_Name WHERE State=0 ORDER BY ID ASC) UNION (SELECT * FROM Game_Name WHERE State=1 ORDER BY ID ASC) UNION  (SELECT * FROM Game_Name WHERE State=2 ORDER BY ID ASC) UNION  (SELECT * FROM Game_Name WHERE State=3 ORDER BY ID ASC)";
if (isset($_GET['teach'])) {
	$sql = "SELECT * FROM Game_Name WHERE (State=10 OR State=0) ORDER BY State ASC, Last DESC";
}
else {
	$sql = "SELECT * FROM Game_Name WHERE State!=10 ORDER BY State ASC, Last DESC";
}
$rs = $db->query($sql);
$result = $rs->fetchAll();
#$sth = $db->prepare($sql);
#$sth->execute();
#$result = $sth->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<title>Game List</title>
<link rel="shortcut icon" href="http://people.cs.nctu.edu.tw/~hungsh/icon/social.ico">
<style type="text/css">
	body {
		background-image: url(bg.jpg);
		background-repeat: repeat-y;
		background-position: 0% 0%;
		background-attachment: fixed;
	}
	T {
		color:#0000aa;
		font-size:30px;
		font-weight: 600;
		font-family: "Microsoft YaHei";
	}
	word {
		color:#343434;
		font-size:20px;
		font-weight: 400;
		font-family: "Microsoft YaHei";
	}
	red {
		color:#aa3434;
                font-size:20px;
                font-weight: 400;
		font-family: "Microsoft YaHei";
	}
	img {
		max-width: 80px;
	}
	input[type=uid] {
		font-size:16px;
		border-radius:5px;
                box-shadow: 0px 0px 5px #a7a7a7;
                border: #a7a7a7 1px solid;
	}
	input[type=password] {
                font-size:16px;
                border-radius:5px;
                box-shadow: 0px 0px 5px #a7a7a7;
                border: #a7a7a7 1px solid;
        }
	input[type=submit] {
                font-size:16px;
                border-radius:5px;
                box-shadow: 0px 0px 5px #a7a7a7;
                border: #a7a7a7 1px solid;
		font-family: "Microsoft YaHei";
        }
	input[type=button] {
		font-size:16px;
                border-radius:5px;
                box-shadow: 0px 0px 5px #a7a7a7;
                border: #a7a7a7 1px solid;
		font-family: "Microsoft YaHei";
	}
	col:first-child {
                width: 50px;
        }
	col:nth-child(2) {
		width: 400px;
	}
	col:nth-child(3) {
		width: 100px;
	}
	col:nth-child(4) {
                width: 80px;
        }
	col:nth-child(5) {
                width: 120px;
        }
	col:nth-child(6) {
                width: 120px;
        }
	a {
                color:#0000aa;
                font-size:20px;
        }
	a:link {
		color:#0000aa;
		font-size:20px;
	}
	a:visited {
		color:#0000aa;
		font-size:20px;
	}
	a:hover {
		color:#aa0000;
		font-size:20px;
	}
</style>
</head>
<body>

<?php
if ($islogin) {
$sql_read_mail = "SELECT * FROM PM WHERE Destination = {$UID} AND IsRead = 0 AND Backup = 0";
$sth_read_mail = $db->query($sql_read_mail);
$unread = $sth_read_mail->fetchAll();
$unread_num = count($unread);
if ($unread_num != 0) {
?>
<table align="left" width="50px" height="50px">
<tr><td></td></tr></table>
<table align="right" width="50px" height="50px">
<tr><td>
<img src="http://people.cs.nctu.edu.tw/~hungsh/mail.jpg" width="50px" height="50px" <?php echo "title=\"有{$unread_num}隻信鴿在等你喔！\""; ?> onclick="location.href='http://people.cs.nctu.edu.tw/~hungsh/personal/index.php'"></div>
</td></tr></table>
<?php } } ?>

<table align="center">
<tr><td>
<?php if (isset($_GET['teach'])) { ?>
<T>互遊討論區列表</T>
<?php } else { ?>
<T>互動遊戲列表</T>
<?php } ?>
</td></tr>
</table>

<table align="center">
<col><col><col><col><col><col>

<?php
if (isset($_GET['teach'])) {
echo "<tr><td></td>";
echo "<td align=\"center\"><word>Name</word></td>";
echo "<td align=\"center\"><word>作者</word></td>";
echo "<td align=\"center\"><word>類型</word></td>";
echo "<td align=\"center\"></td>";
echo "<td align=\"center\"><word>進入主題</word></td>";
}
else {
echo "<tr><td></td>";
echo "<td align=\"center\"><word>Name</word></td>";
echo "<td align=\"center\"><word>GM</word></td>";
echo "<td align=\"center\"><word>State</word></td>";
echo "<td align=\"center\"><word>報名/場外</word></td>";
echo "<td align=\"center\"><word>遊戲區</word></td>";
}

$n = count($result);
$p = ceil($n/20);
if (isset($_GET['page'])) {
	$now_p = $_GET['page'];
}
else {
	$now_p = 1;
}

for ($i=($now_p-1)*20; $i < $n; $i++) {
	echo "<tr>";

	//new圖標
	$now = date('Y-m-d H:i:s');
	if (((strtotime($now) - strtotime($result[$i][5])) / (60*60*24)) < 1) {
		echo "<td><img src=\"new.gif\" width=\"50px\" height=\"20px\"></img></td>";
	}
	else {
		echo "<td></td>";
	}

	//遊戲標題
	echo "<td><word>{$result[$i][1]}</word></td>";

	//遊戲作者，本人變紅
	echo "<td align=\"center\">";
	if ($islogin) {
		if ($_SESSION['username'] == $result[$i][2]) {
			echo "<red>{$result[$i][2]}</red>";
		}
		else {
			echo "<word>{$result[$i][2]}</word>";
		}
	}
	else {
		echo "<word>{$result[$i][2]}</word>";
	}

	//狀態圖標，含公告
	echo "</td>";
	if ($result[$i][3] == 2) {
		echo "<td align=\"center\"><img src=\"start.png\"";
		if ($_SESSION['username'] == $result[$i][2]) {
			echo "onclick=\"location.href='next_state.php?ID={$result[$i][0]}'\"";
		}
		echo "></img></td>";
	}
	else if ($result[$i][3] == 1) {
		echo "<td align=\"center\"><img src=\"run.png\"";
                if ($_SESSION['username'] == $result[$i][2]) {
                        echo "onclick=\"location.href='next_state.php?ID={$result[$i][0]}'\"";
                }
                echo "></img></td>";
        }
	else if ($result[$i][3] == 3) {
        	echo "<td align=\"center\"><img src=\"end.png\"";
                if ($_SESSION['username'] == $result[$i][2]) {
                        echo "onclick=\"location.href='next_state.php?ID={$result[$i][0]}'\"";
                }
                echo "></img></td>";
	}
	else if ($result[$i][3] == 0) {
		echo "<td align=\"center\"><img src=\"top.png\"></img></td>";
	}
	else if ($result[$i][3] == 10) {
                echo "<td align=\"center\"><word>{$result[$i][6]}</word></td>";
        }

	//進入
	if ($result[$i][3] != 0 && $result[$i][3] != 10) {
		echo "<td align=\"center\">";
		echo "<a href=\"MB.php?GID={$result[$i][0]}&IN=0\">";
		echo "進入</a></td><td align=\"center\">";
		echo "<a href=\"MB.php?GID={$result[$i][0]}&IN=1\">";
                echo "進入</a></td>";
	}
	else if ($result[$i][3] == 0 || $result[$i][3] == 10) {
                echo "<td></td><td align=\"center\">";
                echo "<a href=\"MB.php?GID={$result[$i][0]}&IN=1\">";
                echo "進入</a></td>";
	}
	else {
		echo "<td align=\"center\"><word>先登入喔！</word></td>";
		echo "<td></td>";
	}
	echo "</tr>";
	if ($i == ($now_p * 20)) break;
}
if ($p != 1) {
	echo "</table>";
	echo "<table align=\"center\"><tr>";
	if ($now_p > 1) {
		$page = $now_p - 1;
		echo "<td>";
		echo "<input type=\"button\" value=\"上一頁\" onclick=\"location.href='index.php?page={$page}'\">";
		echo "</td>";
	}
	if ($now_p < $p) {
		$page = $now_p + 1;
		echo "<td>";
                echo "<input type=\"button\" value=\"下一頁\" onclick=\"location.href='index.php?page={$page}'\">";
                echo "</td>";
	}
	echo "</tr>";
}

?>

</table>

<table align="center">
<?php
if (!$islogin) {
	echo "<form method=\"POST\" action=\"login.php\">";
	echo "<tr><td style=\"width:20px\"><input type=\"uid\" name=\"uid\" maxlength=\"20\" placeholder=\"請輸入帳號\" pattern=\"[^ ]*\"></td>";
	echo "<td style=\"width:30px\"></td>";
	echo "<td style=\"width:20px\"><input type=\"password\" name=\"password\" maxlength=\"255\" placeholder=\"請輸入密碼\"></td>";
	echo "<td style=\"width:30px\"></td>";
	echo "<td><input type=\"submit\" value=\"登入\" name=\"submit\"></td></tr>";
	echo "</form>";
	echo "<tr><td><input type=\"button\" value=\"加入互動遊戲的一份子！\" onclick=\"location.href='reg_form.php'\"></td></tr>";
}
else { ?>
<tr><td>
<a href="personal/index.php"><input type="button" value="個人空間" name="personal"></a>
</td><td width="50px">
</td><td>
<?php if (isset($_GET['teach'])) { ?>
<input type="button" value="開新討論" name="logout" style="width: 200px; height: 30px" onclick="location.href='new.php?state=10'">
<?php } else { ?>
<input type="button" value="開新遊戲" name="logout" style="width: 200px; height: 30px" onclick="location.href='new.php'">
<?php } ?>
</td><td width="50px">
</td><td>
<?php if (isset($_GET['teach'])) { ?>
<a href="index.php"><input type="button" value="到遊戲列表" name="sw"></a>
<?php } else { ?>
<a href="index.php?teach=1"><input type="button" value="到討論區列表" name="sw"></a>
<?php } ?>
</td><td width="50px">
</td><td>
<input type="button" value="登出" name="logout" onclick="location.href='logout.php'">
</td></tr>";
<?php } ?>
</table>

</body>
</html>
