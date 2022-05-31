<?php
header('Content-Type: text/html; charset=utf8');
header('Content-Type: text/html; charset=utf8');
session_save_path("/amd/cs/101/0116053/public_html/");
session_start();
date_default_timezone_set("Asia/Taipei");

$GID = $_GET['GID'];
$IN = $_GET['IN'];
if (isset($_SESSION['uid'])) {
	$UID = $_SESSION['uid'];
	$islogin = 1;
}
else {
	$islogin = 0;
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
$db->exec("SET character_set_connection = utf8");

/////////////////////////////////////////////////////////////////////////////
do {
$break = 0;

$del_push_sql = "SELECT * FROM Push";
$sth_del_push = $db->query($del_push_sql);
$del_data = $sth_del_push->fetchAll();

$del_n = count($del_data);
for ($i=($del_n-30); $i<$del_n; $i++) {
        $del_t_tid = $del_data[$i][1];
        $del_t_uid = $del_data[$i][2];
        $del_t_text = $del_data[$i][3];
        $del_t_date = $del_data[$i][4];
        for ($j = $i+1; $j<$del_n; $j++) {
                $timeDiff = strtotime($del_data[$j][4]) - strtotime($del_t_date);
                if ($del_t_tid == $del_data[$j][1] && $del_t_uid == $del_data[$j][2] &&
                    $del_t_text == $del_data[$j][3] && $timeDiff < 30) {
                        $sql = "DELETE FROM Push WHERE ID={$del_data[$j][0]}";
                        $sth = $db->exec($sql);
                        $break = 1;
                        goto next_test;
                }
        }
}
next_test:

} while ($break==1);
/////////////////////////////////////////////////////////////////////////////

//取出文章+判斷是否只看該作者
if (isset($_GET['U'])) {
	$sql = "SELECT * FROM Text WHERE Game = ? AND IN_OUT = ? AND UID = {$_GET['U']} ORDER BY Date";
}
else {
	$sql = "SELECT * FROM Text WHERE Game = ? AND IN_OUT = ? ORDER BY Date";
}
$sth = $db->prepare($sql);
$sth->execute(array($GID, $IN));
$result = $sth->fetchAll();

//總頁數計算
$n = count($result);
$MP = ceil($n/20);

//取出遊戲資訊 game_data
$game_sql = "SELECT * FROM Game_Name WHERE ID = {$GID}";
$sth2 = $db->prepare($game_sql);
$sth2->execute();
$game_data = $sth2->fetchObject();

?>

<html>
<head>
<title>
<?php
	//遊戲標題
	if ($IN==0) {
		$TITLE = $game_data->Name . "報名/場外區";
	}
	else {
		$TITLE = $game_data->Name . "遊戲區";
	}
	echo $TITLE;

	if ($game_data->GM == $_SESSION['username']) {
		$ISGM = 1;
		echo "-GM模式";
	}
	else {
		$ISGM = 0;
	}

	if ($IN == 0) {
		$DARK = $game_data->Voice;
                $DARK = $DARK >> 1;
	}
	else {
		$DARK = $game_data->Voice;
		$DARK = $DARK % 2;
	}
?>
</title>
<link rel="shortcut icon" href="http://people.cs.nctu.edu.tw/~hungsh/icon/social.ico">
<style type="text/css">
	body {
		background-image: url(bg.jpg);
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
	img {
		max-width: 850px;
	}
	T {
		color:#0000aa;
		font-size:30px;
		font-weight: bold;
		font-family: "Microsoft YaHei";
	}
	theme {
		color:#343434;
		font-size:18px;
		font-weight: bold;
		font-family: "Microsoft YaHei";
	}
	word {
                color:#343434;
                font-size:14px;
                font-weight: normal;
		font-family: "Microsoft JhengHei";
        }
	date {
		color:#343434;
                font-size:12px;
                font-weight: normal;
                font-family: "Microsoft JhengHei";
	}
	Mdate {
                color:#343434;
                font-size:12px;
                font-weight: bold;
                font-family: "Microsoft JhengHei";
        }
	push {
		color:#343434;
                font-size:12px;
                font-weight: bold;
                font-family: "Microsoft JhengHei";
	}
	a {
		font-size:14px;
	}
	input[type=button] {
		font-size:14px;
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
		width: 850px;
	}
	textarea {
		color:#343434;
                font-size:14px;
                font-weight: normal;
                font-family: "Microsoft JhengHei";
                border-radius:5px;
                box-shadow: 0px 0px 5px #a7a7a7;
                border: #a7a7a7 1px solid;
		width: 850px;
		height: 200px;
		max-width: 850px;
        }
	input[type=push] {
                color:#343434;
                font-size:14px;
                font-weight: 500;
                font-family: "Microsoft YaHei";
                border-radius:5px;
                box-shadow: 0px 0px 5px #a7a7a7;
                border: #a7a7a7 1px solid;
                width: 700px;
        }
	input[type=submit] {
                font-size:16px;
                border-radius:5px;
                box-shadow: 0px 0px 5px #a7a7a7;
                border: #a7a7a7 1px solid;
		width: 100px;
        }
</style>
<style>
<!--
	@import "css/word.css";
-->
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

<!-- 顯示遊戲標題 -->
<table align="center">
<tr><td>
<T><?php
	echo $TITLE;
?></T>
</td></tr>
<tr><td height="20px"></td></tr>
</table>

<!-- 上方跳頁 -->
<table align="center" width="700px">
<tr><td width="70px">
<a href=<?php echo "\"MB.php?GID={$GID}&IN={$IN}"; if (isset($_GET['U'])) echo "&U={$_GET['U']}\""; else echo "\""; ?>><input type="button" style="width: 70px" value="第一頁">
</td><td width="20px"></a>
</td><td width="70px">
<?php
        if ($NP!=1) {
                $page = $NP - 1;
                echo "<a href=\"MB.php?GID={$GID}&IN={$IN}&page={$page}"; if (isset($_GET['U'])) echo "&U={$_GET['U']}"; echo "\"><input type=\"button\" style=\"width: 70px;\" value=\"上一頁\"></a>";
        }
?>

</td><td width="10px">
<?php
for ($i=($NP-3); $i < ($NP+4); $i++) {
        echo "</td><td width=\"30px\">";
        if ($i == $NP) {
                echo "<input type=\"button\" style=\"width:30px; color:#ff0000;\" value=\"{$i}\">";
        }
        else if ($i > 0 && $i <= $MP) {
                echo "<a href=\"MB.php?GID={$GID}&IN={$IN}&page={$i}"; if (isset($_GET['U'])) echo "&U={$_GET['U']}"; echo "\"><input type=\"button\" style=\"width: 30px;\" value=\"{$i}\"></a>";
        }
        echo "</td><td width=\"10px\">";
}
?>

</td><td width="70px">
<?php
        if ($NP!=$MP) {
                $page = $NP + 1;
                echo "<a href=\"MB.php?GID={$GID}&IN={$IN}&page={$page}"; if (isset($_GET['U'])) echo "&U={$_GET['U']}"; echo "\"><input type=\"button\" style=\"width: 70px;\" value=\"下一頁\"></a>";
        }
?>
</td><td width="20px">
</td><td width="70px">
<a href=<?php echo "\"MB.php?GID={$GID}&IN={$IN}&page={$MP}"; if (isset($_GET['U'])) echo "&U={$_GET['U']}"; echo "\""; ?>><input type="button" style="width: 70px" value="最末頁"></a>
</td></tr>
<tr><td height="20px"></td></tr>
</table>
<!-- 上方跳頁完 -->

<!-- 文章區 -->
<?php
if ($ISGM) {
	echo "<table align=\"center\" width=\"200px\">";
	echo "<tr><td>";
	if ($DARK) {
		echo "<input type=\"button\" value=\"天黑請閉眼\" style=\"width:200px\" onclick=\"location.href='close.php?ID={$game_data->ID}&IN={$IN}'\">";
	}
	else {
		echo "<input type=\"button\" value=\"天亮請睜眼\" style=\"width:200px\" onclick=\"location.href='close.php?ID={$game_data->ID}&IN={$IN}'\">";
	}
	echo "</td></tr>";
	echo "<tr><td height=\"20px\"></td></tr></table>";
}
?>

<table align="center" width="1000px" border="3">
<col><col>

<?php
$i = 0;
for ($i = ($NP-1)*20; $i < $n; $i++) {
	if ($i == ($NP * 20)) break;

	$user_sql = "SELECT * FROM User WHERE UID = ?";
	$sth3 = $db->prepare($user_sql);
        $sth3->execute(array($result[$i][2]));
        $user_data = $sth3->fetchObject();
	//ID+樓層
	echo "<tr><td style=\"vertical-align:text-top;\">";
	echo "<table width=\"150px\">";		//table1：左側欄位
	echo "<tr><td><theme>";
	echo "#" . $i . " " . $user_data->Name;
	echo "</theme></td></tr>";

	//臉圖
	echo "<tr><td>";
	echo "<div class=\"picture\">";
	echo "<a href=\"personal/send.php?d={$user_data->User_Name}\"><img src=\"";
	echo $user_data->Picture_Addr . "\" title=\"{$user_data->User_Name}\"></div>";
	echo "</td></tr>";
	echo "</table>";			//table1：結束

	//標題+內文，還有神隱確認
	echo "</td><td style=\"vertical-align:text-top;\">";
	echo "<table width=\"850px\">";		//table2：右側標題+內容
	echo "<tr><td><theme>";
	if ($user_data->UID!=2) {
		echo $result[$i][4];
	}
	else {
		echo "被遺忘的故事";
	}
	echo "</theme></td></tr><tr><td height=\"150px\" valign=\"top\"><word>";
	if ($user_data->UID!=2) {
		echo nl2br($result[$i][5]);
	}
	else {
		echo "沒有任何人記得，這段故事……被世界遺忘。";
	}
	echo "</word></td></tr></table>";		//table2：結束

	$sql_push = "SELECT * FROM Push WHERE TID = ? ORDER BY Date";
	$sth_push = $db->prepare($sql_push);
	$sth_push->execute(array($result[$i][0]));
	$push_data = $sth_push->fetchAll();
	$push_num = count($push_data);
	//推文內容
	if ($push_num != 0) {
		echo "<table width=\"850px\">";
		echo "<tr><td width=\"50px\">";
		echo "</td><td width=\"750px\"><table border=\"3\" rules=\"none\" style=\"border-style: outset; width: 750px;\">";
		for ($j=0; $j < $push_num; $j++) {
			echo "<tr><td style=\"width:120px;\"><push>";
			$pu_sql = "SELECT * FROM User WHERE UID={$push_data[$j][2]}";
			$pu_sth = $db->prepare($pu_sql);
			$pu_sth->execute();
			$pu = $pu_sth->fetchObject();
			echo "<red>推 </red>" . $pu->Name . "：";
			echo "</push></td><td style=\"max-width:500px; overflow:hidden; text-overflow:ellipsis;\"><push>";
			echo $push_data[$j][3];
			echo "</push></td><td width=\"130px\"><push>";
			echo $push_data[$j][4];
			echo "</push></td></tr>";
		}
		echo "</table></td><td width=\"50px\">";
		echo "</td></tr></table>";
	}
	

	//最後修改時間
	echo "<table width=\"850px\">";			//table3：修改時間
	echo "<tr><td align=\"right\">";
	if ($result[$i][7]!=null) {
		echo "<Mdate>[最後編輯於{$result[$i][7]}]</Mdate>";
	}
	echo "</td></tr></table>";			//table3：結束

	//神隱按鈕，有GM檢查
	echo "<table width=\"850px\"><tr>";		//table4：按鈕+時間
	if ($ISGM) {
		echo "<td align=\"left\" width=\"50px\">";
		echo "<input type=\"button\" value=\"神隱\" width=\"50px\" onclick=\"var k=confirm('確定要神隱這篇文章嗎？'); if (k) location.href='kamikakushi.php?ID={$result[$i][0]}&GID={$GID}'\">";
		echo "</td>";
	}

	//推文按鈕
	echo "<td align=\"left\" width=\"50px\">";
	echo "<input type=\"button\" value=\"推文\" width=\"50px\" onclick=\"ReverseDisplay('{$i}');\"></td>";

	//只看該作者按鈕
	echo "<td align=\"left\" width=\"100px\">";
	if (isset($_GET['U'])) {
		echo "<input type=\"button\" value=\"看全部文章\" width=\"100px\" onclick=\"location.href='MB.php?GID={$GID}&IN={$IN}'\"></td>";
	}
	else {
		echo "<a href=\"MB.php?GID={$GID}&IN={$IN}&U={$user_data->UID}\"><input type=\"button\" value=\"只看該作者\" width=\"100px\"></td></a>";
	}
	echo "<td></td>";

	//刪除+編輯按鈕，有發文者檢查
	if ($user_data->UID == $_SESSION['uid']) {
		echo "<td align=\"right\" width=\"50px\">";
                echo "<input type=\"button\" value=\"刪除\" width=\"50px\" onclick=\"var del=confirm('確定要刪除這篇文章嗎？'); if (del) location.href='del.php?ID={$result[$i][0]}&UID={$result[$i][2]}'\">";
		echo "</td>";
		echo "<td align=\"right\" width=\"50px\">";
                echo "<input type=\"button\" value=\"修改\" width=\"50px\" onclick=\"location.href='modify_form.php?ID={$result[$i][0]}&story={$i}'\">";
		echo "</td>";
	}

	//發文時間
	echo "<td align=\"right\" width=\"200px\"><date>";
        echo "發表於" . $result[$i][6];
        echo "</date></td></tr>";
	echo "</table>";			//table4：結束

	//推文表單
	echo "<div class=\"phide\" style=\"display: none;\" id=\"{$i}\"i onKeyDown=\"if (event.keyCode == 13) {return false}\">";	//div+table
	echo "<table width=\"850px\"><form method=\"POST\" action=\"push.php\">";
	echo "<tr><td>";
	echo "<input type=\"push\" name=\"push\" maxlenght=\"100\">";
	echo "</td><td>";
	echo "<input type=\"hidden\" name=\"TID\" value={$result[$i][0]}>";
	echo "</td><td>";
	echo "<div class=\"phide\" style=\"display: block;\" id=\"{$i}b\">";
	echo "<input type=\"submit\" value=\"推\" name=\"submit\" onclick=\"DisableSwitch('{$i}b','{$i}bb')\">";
	echo "<div class=\"phide\" style=\"display: none;\" id=\"{$i}bb\">";
	echo "<input type=\"submit\" disabled=\"value\" value=\"推文中......\"></div>";
	echo "</td></tr></form>";
	echo "</table></div></td></tr>";
}

//發文表單+天黑GM只看該作者測試
if (($DARK || $ISGM) && isset($_SESSION['uid']) && !isset($_GET['U'])) {
?>

<tr><td style="vertical-align:text-top"><table>
<form method="POST" action="respond.php">
<tr><td>
<?php
echo "<theme>#" . $n . "</theme>";
?>
</td><td>
<div class="Rds" style="display: block;" id="Rds1">
<input type="submit" value="快速回覆" name="submit" onclick="DisableSwitch('Rds1','Rds2')"></div>
<div class="Rds" style="display: none;" id="Rds2">
<input type="submit" value="快速回覆" name="submit" disabled="value"></div>
</td></tr>
<tr style="height:30px"><td>
</td><td valign="bottom">
<a href="dice.php"><input type="button" value="線上擲骰" style="width:100px"></a>
</td></tr>
</table></td>
<td><table>
<tr><td>
<div onKeyDown="if (event.keyCode == 13) {return false}">
<input type="theme" name="theme" maxlength="30\">
</div>
</td></tr>
<tr><td>
<textarea name="text" id="textarea"></textarea>
</td></tr>
</table></td></tr>
<input type="hidden" name="GID" value="<?php echo $GID ?>">
<input type="hidden" name="IN" value="<?php echo $IN ?>">
</form></table>

<?php } ?>

<!-- 下方跳頁 -->
<table align="center" width="700px">
<tr><td height="40px"></td></tr>
<tr><td width="70px">
<a href=<?php echo "\"MB.php?GID={$GID}&IN={$IN}"; if (isset($_GET['U'])) echo "&U={$_GET['U']}\""; else echo "\""; ?>><input type="button" style="width: 70px" value="第一頁">
</td><td width="20px"></a>
</td><td width="70px">
<?php
        if ($NP!=1) {
                $page = $NP - 1;
                echo "<a href=\"MB.php?GID={$GID}&IN={$IN}&page={$page}"; if (isset($_GET['U'])) echo "&U={$_GET['U']}"; echo "\"><input type=\"button\" style=\"width: 70px;\" value=\"上一頁\"></a>";
        }
?>

</td><td width="10px">
<?php
for ($i=($NP-3); $i < ($NP+4); $i++) {
        echo "</td><td width=\"30px\">";
        if ($i == $NP) {
                echo "<input type=\"button\" style=\"width:30px; color:#ff0000;\" value=\"{$i}\">";
        }
        else if ($i > 0 && $i <= $MP) {
                echo "<a href=\"MB.php?GID={$GID}&IN={$IN}&page={$i}"; if (isset($_GET['U'])) echo "&U={$_GET['U']}"; echo "\"><input type=\"button\" style=\"width: 30px;\" value=\"{$i}\"></a>";
        }
        echo "</td><td width=\"10px\">";
}
?>

</td><td width="70px">
<?php
        if ($NP!=$MP) {
                $page = $NP + 1;
                echo "<a href=\"MB.php?GID={$GID}&IN={$IN}&page={$page}"; if (isset($_GET['U'])) echo "&U={$_GET['U']}"; echo "\"><input type=\"button\" style=\"width: 70px;\" value=\"下一頁\"></a>";
        }
?>
</td><td width="20px">
</td><td width="70px">
<a href=<?php echo "\"MB.php?GID={$GID}&IN={$IN}&page={$MP}"; if (isset($_GET['U'])) echo "&U={$_GET['U']}"; echo "\""; ?>><input type="button" style="width: 70px" value="最末頁"></a>
</td></tr>
<tr><td height="20px"></td></tr>
</table>

<table align="center" width="700px">
<tr><td align="center" width="700px">
<T>^</T></td></tr></table>
<!-- 下方跳頁完 -->

<!-- 列表轉換 -->
<table align="center" width="700px">
<tr><td width="100px">
<?php if ($game_data->State != 10) { ?>
<a href="index.php"><input type="button" style="width: 100px;" value="回遊戲列表"></a>
<?php } else { ?>
<a href="index.php?teach=1"><input type="button" style="width: 100px;" value="回討論區列表"></a>
<?php } ?>
</td><td width="200px">
</td><td width="100px">
<?php
	echo "<a href=\"MB.php?GID={$GID}&IN=1\"><input type=\"button\" style=\"width: 100px;\" value=\"到遊戲區\"></a>";
?>
</td><td width="200px">
</td><td width="100px">
<?php
        echo "<a href=\"MB.php?GID={$GID}&IN=0\"><input type=\"button\" style=\"width: 100px;\" value=\"到場外區\"></a>";
?>
</td></tr>
<tr><td height="40px"></td></tr>
</table>
<!-- 列表轉換完 -->

<?php if ($_SESSION['uid']==3) { ?>
<?php } ?>

</body>
</html>



<script type="text/javascript">
	function ReverseDisplay(d) {
		if (document.getElementById(d).style.display == "none") {
			document.getElementById(d).style.display = "block";
		}
		else {
			document.getElementById(d).style.display = "none";
		}
	}
	function DisableSwitch(d,dd) {
		document.getElementById(d).style.display = "none";
		document.getElementById(dd).style.display = "block";	
	}
	function insertAtCursor(myValue) {
		myField = document.getElementById("textarea");
		//IE support
		if (document.selection) {
	    		myField.focus();
	    		sel = document.selection.createRange();
	    		sel.text = myValue;
		}
		//MOZILLA and others
		else if (myField.selectionStart || myField.selectionStart == '0') {
	    		var startPos = myField.selectionStart;
	    		var endPos = myField.selectionEnd;
	    		myField.value = myField.value.substring(0, startPos) + myValue
	        	+ myField.value.substring(endPos, myField.value.length);
	        	myField.selectionStart = startPos + myValue.length;
	        	myField.selectionEnd = startPos + myValue.length;
		} else {
	    		myField.value += myValue;
		}	
	}
	document.getElementById('textarea').onkeydown = function(e){
		if (e.keyCode == 9) {
			insertAtCursor('　　　　');
			return false;
		}
	}
</script>
