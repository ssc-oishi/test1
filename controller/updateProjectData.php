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
define("PHPNAME", "updateProjectData.php");		//PHPファイル名
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
 * プロジェクトデータの上書き
 *************************************************************/
$return_data 	= "";
$json_tmp		= "";
$prj_data = $_POST['prj_data'];


$data = array(
	':prj_id' => $prj_data['prj_id'],
	':kubun' => 1,
	':up_userid' => $_SESSION['uID'],
	':up_date' => date("Ymd"),
	':prj_name' => $prj_data['prj_name'],
	':prj_sdate' => $prj_data['prj_sdate'],
	':prj_edate' => $prj_data['prj_edate'],
	':prj_bu1' => $prj_data['prj_bu1'],
	':prj_bu2' => $prj_data['prj_bu2'],
	':prj_bu3' => $prj_data['prj_bu3'],

	':order_type' => $prj_data['order_type'],
	':order_id' => $prj_data['order_id'],
	':order_name' => $prj_data['order_name'],
	':order_userid' => $prj_data['order_userid'],
	':order_username' => $prj_data['order_username'],
	':order_koujino' => $prj_data['order_koujino'],
	':order_koujinm' => $prj_data['order_koujinm'],
	':order_tel' => $prj_data['order_tel'],
	':order_pcode' => $prj_data['order_pcode'],
	':order_addr1' => $prj_data['order_addr1'],
	':order_addr2' => $prj_data['order_addr2'],
	':order_user' => $prj_data['order_user'],
	// ':order_busyo' => $prj_data['order_busyo'],

	':eigyo_userid' => $prj_data['prj_eigyo_userid'],
	':eigyo_username' => $prj_data['prj_eigyo_username'],
	':eigyo_bu1' => $prj_data['eigyo_bu1'],
	':eigyo_bu2' => $prj_data['eigyo_bu2'],
	':eigyo_bu3' => $prj_data['eigyo_bu3'],
	':memo' => $prj_data['prj_memo'],
);


try {

	updateProjectData($data);	

	$json_tmp = [
		'sts' 	=> "1",
		'data'	=> "",
		'Ttl'	=> "更新完了",
		'Mess'	=> "",
	];

} catch (Exception $e) {
	$json_tmp = [
		'sts' 	=> "-1",
		'Ttl'	=> "エラー",
		'Mess'	=> ERR0001,
	];
}
$JSON_Array += array("updateProjectData" => $json_tmp);

//*************************************************************************
// JSONデータ掃き出し処理
//*************************************************************************
//JSONデータ出力
echo json_encode($JSON_Array, JSON_UNESCAPED_UNICODE);
//データベースアクセス終了
$dbh = null;
//JSON.PHP終了
exit;
