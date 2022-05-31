<?php
header('Content-Type: text/html; charset=utf8');
session_save_path("/amd/cs/101/0116053/public_html/");
session_start();
date_default_timezone_set("Asia/Taipei");

$ID = $_GET['ID'];
$story = $_GET['story'];

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

$sql = "SELECT * FROM Text WHERE ID = {$ID}";
$sth = $db->prepare($sql);
$sth->execute();
$data = $sth->fetchObject();

$user = $data->UID;
$theme = $data->Theme;
$text = $data->Text;

if (!isset($_SESSION['uid'])) {
	echo "<script>window.alert(\"先登入再來編輯喔！\"); window.location=\"index.php\"</script>";
}
else if ($_SESSION['uid']!=$user) {
	echo "<script>window.alert(\"不可以偷改別人的文章！\"); history.go(-1);</script>";
}

?>

<html>
<head>
<title>偷偷編輯文章</title>
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
	input[type=button] {
		font-size:16px;
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
                font-weight: 500;
                font-family: "Microsoft JhengHei";
                border-radius:5px;
                box-shadow: 0px 0px 5px #a7a7a7;
                border: #a7a7a7 1px solid;
		width: 850px;
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
<T>編輯文章</T>
</td></tr>

<form method="POST" action="modify.php">

<table align="center" width="1000px" border="3">
<tr><td style="vertical-align:text-top;">
<table width="150px">
<tr><td><theme>
<?php echo "#" . $story . " " . $_SESSION['username']; ?>
</theme></td></tr><tr><td>
<div class="picture"><img src=<?php echo $_SESSION['picture']; ?>></div>
</td></tr>
</table></td>

<td style="vertical-align:text-top;">
<table width="850px">
<tr><td>
<div onKeyDown="if (event.keyCode == 13) {return false}">
<input type="theme" name="theme" maxlength="30" value="<?php echo $theme; ?>">
</div>
</td></tr><tr><td>
<textarea name="text" id="textarea"><?php echo $text; ?></textarea>
</td></tr><tr><td>
<input type="hidden" name="ID" value=<?php echo $ID; ?>>
</td></tr></table>
<table width="850px">
<tr><td>
</td><td align="right" width="100px">
<input type="button" value="取消編輯" name="cancel" style="width:100px;" onclick="history.go(-1);">
</td><td width="20px">
</td><td align="right" width="100px">
<input type="submit" value="確定編輯" name="submit">
</td></tr></form>
</table>

</body>
</html>

<script type="text/javascript">
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
