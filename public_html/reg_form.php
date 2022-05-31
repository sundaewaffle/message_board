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
?>

<html>
<head>
<title>註冊中......</title>
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
<table align="center">
<tr><td>
<T>歡迎加入互遊行列</T>
</td></tr>

<table align="center">
<form method="POST" action="reg.php"><tr>
<td><word>請輸入帳號：</word></td>
<td><input type="uid" name="uid" maxlength="20" placeholder="中英皆可，二十字以內" pattern="[^ ]*"></td>
</tr><tr>
<td><word>請輸入密碼：</word></td>
<td><input type="password" name="password" maxlength="255" placeholder="限英文數字"></td></td>
</tr><tr>
<td><word>請確認密碼：</word></td>
<td><input type="password" name="pw_con" maxlength="255" placeholder="再次輸入密碼確認"></td></td>
</tr><tr>
<td><word>請輸入頭像網址：</word></td>
<td><input type="uid" name="addr" maxlength="255" placeholder="預設頭像：PA.jpg"></td></td>
</tr><tr>
<td><input type="submit" value="確定註冊" name="submit"></td>
<td><input type="button" value="返回" onclick="location.href='index.php'"></td>
</tr></form>
</table>
