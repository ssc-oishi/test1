<?php
//タイムスタンプ
$timestamp = time();
//$INFO_URL2 = file_get_contents('Control/info/jikoshin_info.html',true);

//CommonPHPインクルード
include("common/common_function.php");
require_once('common/common_def.php');
header("Content-type: text/html; charset=utf-8");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<head>
	<title>新A伝プロトタイプ版</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />

	<?php include("common_meta.inc"); ?>



	<style>
		body {
			background: #FFFFF0;
			color: 696969;
		}

		.header_login {
			margin: 0 20px;
			display: flex;

		}

		.header_title {
			display: flex;
			justify-content: center;
			align-items: center;
		}

		.header_title h1 p {
			position: relative;

		}

		.header_date {
			margin: 5px 0 0 auto;
			width: 300px;
			text-align: right;
		}

		.header_date p {
			margin: 0px;
		}

		.login_container_user {
			border-style: solid;
			border-width: thin;
			background: #ffd54f;
			margin: 10px auto;
			width: 94.5%;
			padding: 20px 0;
			text-align: center;
			box-shadow: 2px 2px 4px gray;
		}

		.login_input {
			margin: 0 auto;
			width: 300px;
			text-align: right;
			margin-bottom: 10px;
		}

		.login input {
			margin: 5px;
		}

		.login p {
			margin: 0;
		}

		.btn_login {
			margin: 0 auto;
			width: 100px;
			height: 30px;
			margin: auto;
			padding: auto;
			border-radius: 5px;
			background: #FFFFFF;
			border: 0;
			position: relative;
			cursor: pointer;
			border: 1px solid #696969;
			display: inline-block;
			box-shadow: 2px 2px 4px gray;
		}

		.contents {
			display: flex;
			justify-content: space-between;
			margin: 30px auto;
			width: 94.5%;
		}

		.menu_user {
			border-style: solid;
			border-width: thin;
			background: #ffd54f;
			/* margin:15px 15px 15px 0; */
			width: 30.5%;
			height: 500px;
			color: 696969;
			padding: 10px;
			box-shadow: 2px 2px 4px gray;
		}

		.info {
			background: #FFFFFF;
			border-style: solid;
			border-width: thin;
			width: 64%;
			height: 500px;
			padding: 10px;
			box-shadow: 2px 2px 4px gray;
			/* margin:15px 0 15px 15px; */
		}

		.info_admin {
			background: #CFF1FC !important;
		}
	</style>
</head>

<body>
	<header>
		<div class="header_login">
			<div class="header_title">
				<!-- <h1 style="color:#446abb;font-size:23px;">eJIn&nbsp;</h1> -->
				<h1 style="font-size:23px;">ログイン&nbsp;</h1>
				<p>新A伝プロトタイプ版</p>
			</div>
			<div class="header_date">
				<!-- <p>新陽社 総務人事育成</p> -->
				<!-- <p>Date.2020/03/19 12:06</p> -->
			</div>
		</div>
	</header>
	<div class="login_container_user">
		<div class="login">
			<div class="login_input">
				Shin-yosha ID<input type="text" id="loginID" class="loginInp"><br>
				PassWord<input type="password" id="loginPass" class="loginInp" placeholder="(半角英数記号8～20桁)"><br><br>

			</div>
			<label style="cursor:pointer;font-size:14px;"><input type="checkbox" id="loginChk">ログイン情報を保持する</label><br>
			<button class="btn_login" id="LoginBtn">ログインする</button>
		</div>
	</div>
	<div class="contents">
		<div class="menu_user">
			<ul>
				<li>プロジェクト情報</li>
				<li>案件・工事情報</li>
				<li>工事情報</li>
			</ul>
		</div>
		<div class="info">
			<!-- <iframe src="http://192.168.20.153/Control/info/juser_info.html" style="width:98%;height:98%;"></iframe> -->
		</div>
	</div>

	<?php include("common_js.inc"); ?>
	<script type="text/javascript" src="js/login.js?vs=<?php echo $timestamp ?>"></script>
</body>

</html>