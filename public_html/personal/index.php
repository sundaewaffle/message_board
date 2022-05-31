<?php
header('Content-Type: text/html; charset=utf8');
session_save_path("/amd/cs/101/0116053/public_html/");
session_start();
date_default_timezone_set("Asia/Taipei");

if (!isset($_SESSION['uid'])) {
	echo "<script>window.alert(\"對不起，我不認識你欸......\") window.location=\"index.php\"</script>";
}
else {
	$UID = $_SESSION['uid'];
}

if (isset($_GET['track'])) {
	$track = $_GET['track'];
}
else {
	$track = 0;
}

if (isset($_GET['page'])) {
	$NP = $_GET['page'];
}
else {
	$NP = 1;
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

$sql_user = "SELECT * FROM User WHERE UID = ?";
$sth_user = $db->prepare($sql_user);
$sth_user->execute(array($UID));
$user_data = $sth_user->fetchObject();

if ($track) {
$sql_PM = "SELECT * FROM PM WHERE Destination = {$UID} AND Backup = 1 ORDER BY Date DESC";
}
else {
$sql_PM = "SELECT * FROM PM WHERE Destination = {$UID} AND Backup = 0 ORDER BY Date DESC";
}
$sth_PM = $db->query($sql_PM);
$PM_des = $sth_PM->fetchAll();

#if (isset($_GET['mid'])) {
#	$sql_read = "UPDATE PM SET IsRead = 1 WHERE ID = {$_GET['mid']}";
#	$sth_read = $db->exec($sql_read);
#}

?>

<html>
<head>
<title><?php echo $user_data->User_Name; ?>的個人小天地</title>
<link rel="shortcut icon" href="http://people.cs.nctu.edu.tw/~hungsh/icon/social.ico">
<style type="text/css">
	body {
		background-image: url(../bg.jpg);
		background-repeat: repeat-y;
		background-position: 0% 0%;
		background-attachment: fixed;
	}
	.picture {
		table-layout: fixed;
		word-wrap: break-word;
		width: 150px;
		height: 150px;
		overflow: hidden;
		text-align : center;
	}
	.picture img {
		max-width: 150px;
		width:expression(this.width>150? "150px": this.width);
		max-height: 150px;
		height:expression(this.height>150? "150px": this.height);
		overflow:hidden;
	}
	.mail {
		width: 800px;
	}
	.mail td {
		border-bottom-style: groove;
                border-bottom-color: #aaaaaa;
		border-width: 2px;
	}
	.mhide {
		width: 800px;
	}
	img {
		max-width: 800px;
	}
	T {
		color:#0000aa;
		font-size:20px;
		font-weight: bold;
		font-family: "Microsoft YaHei";
	}
	Theme {
		color:#343434;
		font-size:14px;
		font-weight: normal;
		font-family: "Microsoft YaHei";
	}
	word {
                color:#343434;
                font-size:12px;
                font-weight: normal;
		font-family: "Microsoft JhengHei";
        }
	date {
		color:#343434;
                font-size:12px;
                font-weight: normal;
                font-family: "Microsoft JhengHei";
	}
	input[type=button] {
		font-size:14px;
		font-weight: 700;
                border-radius:5px;
                box-shadow: 0px 0px 5px #a7a7a7;
                border: #a7a7a7 1px solid;
	}
	input[type=theme] {
		color:#343434;
                font-size:18px;
                font-weight: 600;
                font-family: "Microsoft YaHei";
		border-radius:5px;
                box-shadow: 0px 0px 5px #a7a7a7;
                border: #a7a7a7 1px solid;
		width: 800px;
	}
	textarea {
		color:#343434;
                font-size:14px;
                font-weight: normal;
                font-family: "Microsoft JhengHei";
                border-radius:5px;
                box-shadow: 0px 0px 5px #a7a7a7;
                border: #a7a7a7 1px solid;
		width: 800px;
		height: 200px;
		max-width: 850px;
        }
</style>
<style>
<!--
        @import "../css/word.css";
-->
</style>
</head>
<body>
<?php
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
<?php } ?>
<table align="center" width="1000px" border="3">
<tr><td style="vertical-align:top;">
<table align="top" width="200px">
<tr><td align="center" width="200px">
<div class="picture"><img <?php echo "src=\"{$user_data->Picture_Addr}\""; ?>></div>
</td></tr>
<tr><td align="center" width="200px">
<T><?php echo $user_data->User_Name; ?></T>
</td></tr>
<tr><td align="center" width="200px">
<input type="button" style="width:120px; height:30px" value="(尚未實裝)">
</td></tr>
</table>
<table align="center" width="200px">
<tr><td width="25px">
</td><td align="center" width="50px">
<theme>綽號：</theme>
</td><td align="center" width="100px">
<theme><?php echo $user_data->Name; ?></theme>
</td><td width="25px">
</td></tr>
<tr><td width="25px">
</td><td align="center" width="50px">
<theme>性別：</theme>
</td><td align="center" width="100px">
<theme><?php
	if($user_data->Sex==null) echo "保密";
	else if ($user_data->Sex==0) echo "Boy";
	else echo "Girl";
?></theme>
</td><td width="25px">
</td></tr>
<tr><td width="25px">
</td><td align="center" width="50px">
<theme>生日：</theme>
</td><td align="center" width="100px">
<theme><?php
	if ($user_data->Birthday!=null) echo $user_data->Birthday;
	else echo "保密";
?></theme>
</td><td width="25px">
</td></tr>


<tr><td height="20px"></td></tr>
<table align="top" width="200px">
<tr><td width="90px">
<input type="button" style="width:90px; height:25px;" value="抓住信鴿" onclick="location.href='index.php';">
</td><td width="20px">
</td><td width="90px">
<a href="send.php"><input type="button" style="width:90px; height:25px;" value="送出信鴿"></a>
</td></tr>
<tr><td width="90px">
<input type="button" style="width:90px; height:25px;" value="已放信鴿" onclick="location.href='index.php?track=1';">
</td><td width="20px">
</td><td width="90px">
<input type="button" style="width:90px; height:25px;" value="登出" onclick="location.href='../logout.php';">
</td></tr>


</table>
</td><td align="top" width="800px" style="vertical-align:top;">
<div class="mail">
<table align="top" width="800px">
<tr><td align="center" width="570px">
<theme>信件標題</theme>
</td><td align="center" width="80px">
<theme>信鴿來自</theme>
</td><td align="center" width="150px">
<theme>時間</theme>
</td></tr>

<?php
function read() {
	$sql_read = "UPDATE PM SET IsRead = 1 WHERE ID = {$i}";
	$sth_read = $db->exec($sql_read);
}

$n = count($PM_des);
$MP = ceil($n/20);
for ($i = ($NP-1)*20; $i < $n; $i++) {
	if ($i == ($NP * 20)) break;
	echo "<tr onclick=\"ReverseDisplay('{$PM_des[$i][0]}');\">";
	echo "<td align=\"left\" width=\"570px\">";
	if ($PM_des[$i][6]==0) {
		echo "<theme>★ {$PM_des[$i][3]}</theme>";
	}
	else {
		echo "<theme>☆ {$PM_des[$i][3]}</theme>";
	}
	$sql_source = "SELECT * From User WHERE UID = {$PM_des[$i][1]}";
	$sth_source = $db->prepare($sql_source);
	$sth_source->execute();
	$source = $sth_source->fetchObject();
        echo "</td><td align=\"center\" width=\"80px\">";
        echo "<word>{$source->User_Name}</word>";
	echo "</td><td align=\"center\" width=\"150px\">";
        echo "<word>{$PM_des[$i][5]}</word>";
	echo "</td></tr>";
	#if (isset($_GET['mid']) && $_GET['mid']==$PM_des[$i][0]) {
	$text = nl2br($PM_des[$i][4]);
	echo "</div>";

	echo "</table><div class=\"mhide\" style=\"display: none;\" id=\"{$PM_des[$i][0]}\"><table align=\"top\" width=\"800px\">";
	echo "<tr><td align=\"left\" width=\"800px\">";
	echo "<word>{$text}</word>";
	echo "</td></tr>";
	echo "</table><table align=\"left\" width=\"800px\">";
	echo "<tr><td width=\"70px\">";
	echo "<input type=\"button\" value=\"回覆\" width=\"100px\" onclick=\"location.href='send.php?mid={$PM_des[$i][0]}&type=1'\">";
	echo "</td><td width=\"70px\">";
	echo "<input type=\"button\" value=\"轉寄\" width=\"100px\" onclick=\"location.href='send.php?mid={$PM_des[$i][0]}&type=2'\">";
	echo "</td><td width=\"70px\">";
        echo "<input type=\"button\" value=\"刪除\" width=\"100px\" onclick=\"location.href='del.php?mid={$PM_des[$i][0]}'\">";
	echo "</td><td width=\"590px\">";
	echo "</td></tr>";
	echo "</table></div><table align=\"top\" width=\"800px\">";

	echo "<div class=\"mail\">";
	#}
}
?>
</table></div>

<table align="center" width="750px">
<tr><td height="40px"></td></tr>
<tr><td width="70px">
<input type="button" style="width: 70px" value="第一頁" <?php echo "onclick=\"location.href='index.php?track={$track}'\"" ?>>
</td><td width="20px">
</td><td width="70px">
<?php
        if ($NP!=1) {
                $page = $NP - 1;
                echo "<input type=\"button\" style=\"width: 70px;\" value=\"上一頁\" onclick=\"location.href='index.php?page={$page}&track={$track}'\">";
        }
?>
</td><td width="10px">
<?php
for ($i=($NP-3); $i < ($NP+4); $i++) {
        echo "</td><td width=\"35px\">";
        if ($i == $NP) {
                echo "<input type=\"button\" style=\"width:35px; color:#ff0000;\" value=\"{$i}\">";
        }
        else if ($i > 0 && $i <= $MP) {
                echo "<input type=\"button\" style=\"width: 35px;\" value=\"{$i}\" onclick=\"location.href='index.php?page={$i}&track={$track}'\">";
        }
        echo "</td><td width=\"10px\">";
}
?>
</td><td width="70px">
<?php
	if ($NP!=$MP) {
		$page = $NP + 1;
                echo "<input type=\"button\" style=\"width: 70px;\" value=\"下一頁\" onclick=\"location.href='index.php?page={$page}&track={$track}'\">";
        }
?>
</td><td width="20px">
</td><td width="70px">
<input type="button" style="width: 70px" value="最末頁" <?php echo "onclick=\"location.href='index.php?page={$MP}&track={$track}'\"" ?>>
</td></tr></table>

</td></tr>
</table>
<table align="center">
<tr><td height="20px"></td></tr>
<tr><td width="100px">
<input type="button" style="width:100px; height:25px;" value="回去玩互遊" onclick="location.href='../index.php'">
</td></tr>
</table>
</body>
</html>

<script type="text/javascript">
	function ReverseDisplay(d) {
		if (document.getElementById(d).style.display == "none") {
			document.getElementById(d).style.display = "block";
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

				}
			}
			xmlhttp.open("GET", "read.php?mid=" + d,true);
			xmlhttp.send();

			<?php
				#$sql_read = "UPDATE PM SET IsRead = 1 WHERE ID = {$i}";
		        	#$sth_read = $db->exec($sql_read);
			?>
		}
		else {
			document.getElementById(d).style.display = "none";
		}
	}
</script>

