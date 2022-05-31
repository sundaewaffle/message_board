<?php
header('Content-Type: text/html; charset=utf8');
session_save_path("/amd/cs/101/0116053/public_html/");
session_start();
date_default_timezone_set("Asia/Taipei");

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

if (!isset($_SESSION['uid'])) {
	echo "<script>window.alert(\"不登入無法寄信喔！\"); window.location=\"index.php\"</script>";
}
else {

if (isset($_GET['mid'])) {
	$mid = $_GET['mid'];
	$sql_mail = "SELECT * FROM PM WHERE ID = {$mid}";
	$sth_mail = $db->prepare($sql_mail);
	$sth_mail->execute();
	$mail_data = $sth_mail->fetchObject();
	if ($mail_data->Destination != $_SESSION['uid']) {
		echo "<script>window.alert(\"這好像不是你的信鴿喔！\"); ";
        	echo "window.location=\"../index.php\"";
	        echo "</script>";
	}
	else {
		if ($_GET['type']==1) {
			$sql_s = "SELECT * FROM User WHERE UID={$mail_data->Source}";
        		$sth_s = $db->prepare($sql_s);
        		$sth_s->execute();
        		$user_s = $sth_s->fetchObject();

			$sql_d = "SELECT * FROM User WHERE UID={$mail_data->Destination}";
                        $sth_d = $db->prepare($sql_d);
                        $sth_d->execute();
                        $user_d = $sth_d->fetchObject();

			$source = $user_d->User_Name;
                        $destination = $user_s->User_Name;
                        $theme = "Re: " . $mail_data->Theme;

			$line = "==========================================================";
			$text = "\n\n" . $mail_data->Date . "來自" . $user_s->User_Name . "\n" . $line . "\n" . $mail_data->Text . "\n" . $line;
		}
                else if ($_GET['type']==2) {
			$sql_d = "SELECT * FROM User WHERE UID={$mail_data->Destination}";
                        $sth_d = $db->prepare($sql_d);
                        $sth_d->execute();
                        $user_d = $sth_d->fetchObject();

                        $source = $user_d->User_Name;
                        $destination = null;
                        $theme = "Fw: " . $mail_data->Theme;
                        $line = "==========================================================";
                        $text = "\n\n" . $mail_data->Date . "來自" . "\n" . $line . "\n" . $mail_data->Text . "\n" . $line;
                }
        }
}
else {
	$source = $_SESSION['username'];
	if (isset($_GET['d'])) {
		$destination = $_GET['d'];
	}
	else {
		$destination = null;
	}
	$theme = null;
	$text = null;
}

}
?>

<html>
<head>
<title>用鴿毛筆寫信</title>
<link rel="shortcut icon" href="http://people.cs.nctu.edu.tw/~hungsh/icon/social.ico">
<style type="text/css">
        body {
		background-image: url(../bg.jpg);
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
	theme {
		color:#343434;
		font-size:18px;
		font-weight: 600;
		font-family: "Microsoft YaHei";
	}
	word {
                color:#343434;
                font-size:14px;
                font-weight: 500;
		font-family: "Microsoft JhengHei";
        }
	date {
		color:#343434;
                font-size:12px;
                font-weight: 500;
                font-family: "Microsoft JhengHei";
	}
	input[type=button] {
		font-size:16px;
                border-radius:5px;
                box-shadow: 0px 0px 5px #a7a7a7;
                border: #a7a7a7 1px solid;
	}
	input[type=word] {
		color:#343434;
                font-size:14px;
                font-weight: 500;
                font-family: "Microsoft JhengHei";
		border-radius:5px;
                box-shadow: 0px 0px 5px #a7a7a7;
                border: #a7a7a7 1px solid;
		width: 800px;
	}
	textarea {
		color:#343434;
                font-size:14px;
                font-weight: 500;
                font-family: "Microsoft JhengHei";
                border-radius:5px;
                box-shadow: 0px 0px 5px #a7a7a7;
                border: #a7a7a7 1px solid;
		width: 800px;
		height: 200px;
		max-width: 850px;
        }
	input[type=submit] {
                font-size:16px;
                border-radius:5px;
                box-shadow: 0px 0px 5px #a7a7a7;
                border: #a7a7a7 1px solid;
		width: 100px;
        }
</style>
</head>
<body>
<table align="center">
<tr><td>
<T>寫信中</T>
</td></tr>

<form method="POST" action="send_mail.php">

<table align="center" width="1000px" border="3">
<tr><td width="200px">
<theme>信鴿來自</theme>
</td><td>
<input type="word" name="source" value=<?php
	echo $source;
?> readonly="readonly">
</td></tr>
<tr><td width="200px">
<theme>信鴿飛往</theme>
</td><td>
<div onKeyDown="if (event.keyCode == 13) {return false}">
<input type="word" name="destination" value=<?php
        echo $destination;
?>>
</div>
</td></tr>
<tr><td width="200px">
<theme>信件主題</theme>
</td><td>
<div onKeyDown="if (event.keyCode == 13) {return false}">
<input type="word" name="theme" value=<?php
        echo "\"{$theme}\"";
?>>
</div>
</td></tr>
<tr><td width="200px">
<theme>信件內容</theme>
</td><td>
<textarea name="text" id="textarea">
<?php echo $text; ?>
</textarea>
</td></tr>
</table>
<table align="center" width="1000px">
<tr><td align="center" height="20px">
</td></tr>
<tr><td align="center">
<div class="DS" id="1" style="display: block;">
<input type="submit" value="放出信鴿" name="submit" onclick="DisableSwitch('1','2')"></div>
<div class="DS" id="2" style="display: none;">
<input type="submit" value="信鴿飛走了~" name="submit" disabled="value"></div>
</td></tr>
</table></form>

</body>
</html>

<script type="text/javascript">
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
