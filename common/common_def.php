<?php

/*************************************************************
 * 定数・変数定義用PHP
 *************************************************************/
  //基本設定
	ini_set('display_errors', "On");
	//東京時刻指定
	ini_set('date.timezone', 'Asia/Tokyo');
	
	//基本情報
	define("SITENAME","新陽社 プロジェクト管理");				//サイト名
	define("TIMESTAMP",time());									//タイムスタンプ
	define("TESTMODE", 1);										//テストモード　0:未使用 1:使用 テストモード使用時JSONにSQL文を載せて返す。
	
	//エラー時情報
	define("ERRORMODE",1);										//エラーメール送信有無 0:送信なし 1:送信あり
	define("ERRORLOGSAVE", "../log/db_error.log");				//エラーログファイル場所
	define("ERRORMAILADD", "error@drv-rsv.com");				//エラー時送信先メールアドレス
	define("ERRORMAIL_HEADR","From: ".ERRORMAILADD."\n Reply-to: ".ERRORMAILADD."\n X-Mailer: PHP/".phpversion());	//エラーメールヘッダー情報
	define("ERRORMAIL_OPT","-f".ERRORMAILADD);					//送信エラー時返すアドレス(エラーメール専用)

	//遷移先URL
	define("LINK_LOGIN_URL","login.php");									//ログイン先URL
	define("LINK_ADMIN_LOGIN_URL","admin_login.php");						//管理者ログイン先URL
	define("LINK_SESSION_OUT_URL","SessionOut.php");						//Session失効時遷移先URL
	define("LINK_SESSION_ADMIN_OUT_URL","SessionAdminOut.php");				//Session失効時遷移先URL(管理者)

	//共通メッセージPHP読み込み
	require_once('common_message.php');
