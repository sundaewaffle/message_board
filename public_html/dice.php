<html>
<head>
<title>Dice Online</title>
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
		font-size:16px;
		font-weight: 400;
		font-family: "Microsoft YaHei";
	}
	red {
		color:#aa3434;
                font-size:20px;
                font-weight: 400;
		font-family: "Microsoft YaHei";
	}
	textarea[type=command] {
		max-width:400px;
		width:400px;
		color:#343434;
                font-size:16px;
                font-weight: 400;
                font-family: "Microsoft YaHei";
	}
	img {
		max-width: 80px;
	}
	input[type=button] {
		font-size:16px;
                border-radius:5px;
                box-shadow: 0px 0px 5px #a7a7a7;
                border: #a7a7a7 1px solid;
		font-family: "Microsoft YaHei";
	}
	input[type=help] {
		font-size:16px;
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
<T>線上擲骰程式</T>
</td></tr>
</table>

<table align="center">
<tr><td style="vertical-align:top">
<word>請輸入指令：</word>
</td><td>
<textarea rows="4" type="command" id="command"></textarea>
</td></tr>

<tr><td>
</td><td>
<div style="text-align: center">
<input type="button" value="確定" onclick="dice_execute()">
</div>
</td></tr>

<tr><td style="vertical-align:top">
<word>結果：</word>
</td><td>
<textarea rows="4" type="command" id="result" disabled></textarea>
</td></tr>

<tr><td>
</td><td>
<input type="button" value="使用說明" onclick="show_help()">
</td></tr>
<tr id="help" style="display: none;"><td>
</td><td width="400px">
<word>dice：擲骰指令，空格後用「mdn」來表示擲m個n面的骰子，「mdn」不可分離，如果要複數種不同面數地的骰子，可以直接空格後繼續以「mdn」格式增加。<br>
random：隨機指令，空格後輸入隨機數量「n」，會隨機生成一個數字介於0~n-1之間。<br>
P：隨機排列指令，空格後輸入要排列的東西物件，每種物件用空格分開，即可得到隨機排列的物件。</word>
</td></tr>
</table>

</body>
</html>

<script>
function dice(command) {
	var result=0;
	for (var i=1; i<command.length; i++) {
		var temp_dice = command[i].split("d");
		var num = parseInt(temp_dice[0],10);
		var face = parseInt(temp_dice[1],10);
		for (var j=0; j<num; j++) {
			result += Math.floor((Math.random() * face) + 1);
		}
	}
	return result;
}

function rand_sort(a,b) {
	return Math.random()>.5? -1: 1;
}

function rand_P(command) {
	command.splice(0,1);
	command.sort(rand_sort);
	return command;
}

function dice_execute() {
	var input_command = document.getElementById("command").value;
	var split_command = input_command.split(' ');
	switch (split_command[0]) {
		case "dice":
			//document.getElementById("result").value = "骰子";
			document.getElementById("result").value = dice(split_command);
			break;
		case "random":
			var max = parseInt(split_command[1]);
                        document.getElementById("result").value = Math.floor(Math.random() * max);
                        break;
		case "P":
                        document.getElementById("result").value = rand_P(split_command);
                        break;
		default:
			document.getElementById("result").value = "指令錯誤";
	}
}

function show_help() {
	document.getElementById("help").style = "";
}
</script>
