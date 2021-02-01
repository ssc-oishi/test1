<?php //DBアクセス

/*************************************************************
 * データベース接続用PHP
 * 使用DB:PosgresSQL
 * バージョン:9.5
 *************************************************************/

//開発DB　接続情報
define("DB_User", "postgres");			// DBユーザ名
define("DB_Pass", "sscpost");			// パスワード
define("DB_Name", "SalesDB");			// DB名
define("DB_Host", "localhost");			// ホスト名
define("DB_Port", "5432");

//DBアクセスFunction
function dbConectSel()
{
	//PosgresSQL専用
	$dsn = "pgsql:dbname=" . DB_Name . " host=" . DB_Host . " port=" . DB_Port;
	$options = array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,		// SQL実行失敗時に例外をスロー
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,	// デフォルトフェッチモードを連想配列形式に設定
	);
	try {
		$dbh = new PDO($dsn, DB_User, DB_Pass, $options);
		return $dbh;
	} catch (PDOException $e) {
		echo "データベース接続エラー";
		exit();
	}
}



//人事DB　接続情報
define("DB_User_JINJI", "postgres");			// DBユーザ名
define("DB_Pass_JINJI", "sscpost");			// パスワード
define("DB_Name_JINJI", "JINJI-TOUGOU-DB");			// DB名
define("DB_Host_JINJI", "localhost");			// ホスト名
define("DB_Port_JINJI", "5432");

//DBアクセスFunction
function dbConectSel_JINJI()
{
	//PosgresSQL専用
	$dsn = "pgsql:dbname=" . DB_Name_JINJI . " host=" . DB_Host_JINJI . " port=" . DB_Port_JINJI;
	$options = array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,		// SQL実行失敗時に例外をスロー
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,	// デフォルトフェッチモードを連想配列形式に設定
	);
	try {
		$dbh_JINJI = new PDO($dsn, DB_User_JINJI, DB_Pass_JINJI, $options);
		return $dbh_JINJI;
	} catch (PDOException $e) {
		echo "データベース接続エラー";
		exit();
	}
}
