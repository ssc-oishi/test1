<?php
//**************************************************************************************************************************************************
// AJAX TEST通信用　PHP
// 
//**************************************************************************************************************************************************

//**************************************************************************************************************************************************
// ファイル読み込み
//**************************************************************************************************************************************************

//共通定義PHP読み込み
require_once('../common/common_def.php');
//共通関数PHP読み込み
require_once('../common/common_function.php');
//DB共通関数PHP読み込み
require_once('../model/db_model_function.php');
//DB接続定義PHP読み込み
require_once('../common/db_config_project.php');

//**************************************************************************************************************************************************
// 初期設定
//**************************************************************************************************************************************************

//*************************************************************************
// 初期定数・変数定義
//*************************************************************************
define("PHPNAME", "getProjectData.php");		//PHPファイル名
$JSON_Array 	= array(); 							//出力JSON全データで使用
$FunctionID 	= "";								//FunctionID
$return_data 	= null;								//データ変換用
$result 		= null;								//DB処理時結果
$json_tmp 		= null;								//jsonデータ一時保管用

//*************************************************************************
// 初期処理
//*************************************************************************
//セッション開始
if (!isset($_SESSION)) {
	session_start();
}

//Header宣言(データをJSON形式で出力)
header('Content-Type: application/json; charset=UTF-8');


// ログイン有無確認
/*
	$json_tmp		= null;
	if(!isset($_SESSION["kintai_admin_uNo"])){
		$json_tmp = [
			'sts' 	=> "-1",
			'URL' 	=> LINK_SESSION_ADMIN_OUT_URL,
		];
		$JSON_Array += array("SessionConfig"=>$json_tmp);
		exitAccess($JSON_Array);
	}else{
		$json_tmp = [
			'sts' 	=> "1",
			'lev' 	=> $_SESSION["kintai_lev"]
		];
		$JSON_Array += array("SessionConfig"=>$json_tmp);
	}
*/
//データベースアクセス
$dbh = dbConectSel();

// FunctionID取得
if (isset($_POST["fID"])) {
	$FunctionID = Trim($_POST['fID']);
}

//**************************************************************************************************************************************************
// Ajax コントローラー
//**************************************************************************************************************************************************

/*************************************************************
 * データ取得（プロジェクト一覧ページに入ったとき）
 *************************************************************/
if ($FunctionID == 'getAllProjectData') {
	$return_data 	= "";
	$json_tmp		= "";
	$where = "";
	try {
		$return_data = getAllProjectData();
		$json_tmp = [
			'sts' 	=> "1",
			'data'	=> $return_data,
			'Mess'	=> "",
		];
	} catch (Exception $e) {
		$json_tmp = [
			'sts' 	=> "-1",
			'Ttl'	=> "エラー",
			'Mess'	=> ERR0001,
		];
	}
	$JSON_Array += array("getAllProjectData" => $json_tmp);
}

/*************************************************************
 * データ取得(検索)
 *************************************************************/
if ($FunctionID == 'findProjectData') {
	$return_data 	= "";
	$json_tmp		= "";
	$where = "";
	try {
		$prj_name = Trim($_POST['prj_name']);
		$prj_sdate = Trim($_POST['prj_sdate']);
		$prj_edate = Trim($_POST['prj_edate']);
		if ($prj_sdate == '') {
			$prj_sdate = '11111111';
		}
		if ($prj_edate == '') {
			$prj_edate = '99999999';
		}

		$return_data = findProjectData($prj_name, $prj_sdate, $prj_edate);

		$json_tmp = [
			'sts' 	=> "1",
			'data'	=> $return_data,
			'Mess'	=> "",
		];
	} catch (Exception $e) {
		$json_tmp = [
			'sts' 	=> "-1",
			'Ttl'	=> "エラー",
			'Mess'	=> ERR0001,
		];
	}
	$JSON_Array += array("findProjectData" => $json_tmp);
}

/*************************************************************
 * 削除データ取得
 *************************************************************/
if ($FunctionID == 'getDeleteProjectData') {
	$return_data 	= "";
	$json_tmp		= "";
	$where = "";
	try {
		$return_data = getDeleteProjectData();
		$json_tmp = [
			'sts' 	=> "1",
			'data'	=> $return_data,
			'Mess'	=> "",
		];
	} catch (Exception $e) {
		$json_tmp = [
			'sts' 	=> "-1",
			'Ttl'	=> "エラー",
			'Mess'	=> ERR0001,
		];
	}
	$JSON_Array += array("getDeleteProjectData" => $json_tmp);
}


/*************************************************************
 * データ取得(案件から見た親プロジェクトデータ)
 *************************************************************/
if ($FunctionID == 'getProjectDataForAnken') {
	$return_data 	= "";
	$json_tmp		= "";
	$where = "";
	try {
		$prj_id = Trim($_POST['prj_id']);
		$return_data = getProjectDataForAnken($prj_id);

		$json_tmp = [
			'sts' 	=> "1",
			'data'	=> $return_data,
			'Mess'	=> "",
		];
	} catch (Exception $e) {
		$json_tmp = [
			'sts' 	=> "-1",
			'Ttl'	=> "エラー",
			'Mess'	=> ERR0001,
		];
	}
	$JSON_Array += array("getProjectDataForAnken" => $json_tmp);
}


//*************************************************************************
// JSONデータ掃き出し処理
//*************************************************************************
//JSONデータ出力
echo json_encode($JSON_Array, JSON_UNESCAPED_UNICODE);
//データベースアクセス終了
$dbh = null;
//JSON.PHP終了
exit;
