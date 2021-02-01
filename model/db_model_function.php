<?php

/*************************************************************
 * DB接続共通関数
 *************************************************************/
session_start();
if (!isset($_SESSION["uID"])) {
	header('Location:SessionOut.php');
}

/*************************************************************
 * プレースホルダーDBクエリ発行
 *
 * @param String $dbh
 * @param String $sql
 * @param Array  $data
 * @return DB実行結果
 *************************************************************/
function queryPost($dbh, $sql, $data)
{
	$stmt = $dbh->prepare($sql);
	$stmt->execute($data);
	return $stmt;
}

/*************************************************************
 * 新規ID発行　※numbering_tblへ事前に登録する必要有
 *
 * @param String $n_name
 * @param String $ID_Style
 * @return String $ID(※新規発行ID)
 *************************************************************/
function CreateID($n_name, $ID_Style)
{
	global $dbh;
	try {
		$sql = "select sp_numbering(:n_name,:ID_Style) as id";
		$data = array(':n_name' => $n_name, ':ID_Style' => $ID_Style);
		$stmt = queryPost($dbh, $sql, $data);
		$row = 	$stmt->fetch(PDO::FETCH_ASSOC);
		return $row["id"];
	} catch (Exception $e) {
		error_log_Fnc('CreateID', $e->getMessage());
		throw new Exception('ErrorExeption発生: ' . $e->getMessage());
	}
}

//**************************************************************************************************************************************************
// DB接続共通関数 データ取得
//**************************************************************************************************************************************************

/*************************************************************
 * ユーザーログイン有無確認
 *
 * @param String $eNo 	(kt_user_tbl : emp_no)
 * @param String $Pass 	(cm_usertbl : passwd_12)
 * @return bool | true:ログイン可 false:ログイン不可
 *************************************************************/
function DoUserLogin($eNo, $Pass)
{
	global $dbh;
	try {
		$sql = "select * from( 
					select ku.*,cu.cd2 as eno,cu.passwd_12,cu.passwd_11 
					from kt_user_tbl ku 
					left outer join cm_usertbl cu on cu.cd2 = ku.emp_no 
					where (cu.edate_02 < :edate_02 or cu.edate_02 = '' or cu.edate_02 is null) 
					and (cu.edate_05 > :edate_05 or cu.edate_05 = '' or cu.edate_05 is null) and cu.edate_05 <> '99999999' 
					and (cu.edate_06 > :edate_06 or cu.edate_06 = '' or cu.edate_06 is null) and cu.edate_06 <> '99999999' 
					) l 
					where l.eno is not null and l.emp_no = :eNo and l.passwd_12 = :Pass";
		$data = array(':edate_02' => date("Ymd"), ':edate_05' => date("Ymd"), ':edate_06' => date("Ymd"), ':eNo' => $eNo, ':Pass' => $Pass);
		$stmt = queryPost($dbh, $sql, $data);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!empty($row)) {
			return true;
		} else {
			return false;
		}
	} catch (Exception $e) {
		error_log_Fnc("DoUserLogin", $e->getMessage());
		throw new Exception('ErrorExeption発生: ' . $e->getMessage());
	}
}

/*************************************************************
 * TESTデータ取得
 *
 * @param String $ 	(sd_projtbl : projid)
 * @return array (連想配列) | データ無し：null
 *************************************************************/
function getTestData($projid)
{
	global $dbh;
	try {
		$sql = "select * from sd_projtbl where projid =:projid;";
		$data = array(':projid' => $projid);
		$stmt = queryPost($dbh, $sql, $data);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!empty($row)) {
			return $row;
		} else {
			return null;
		}
	} catch (Exception $e) {
		error_log_Fnc("getTestData", $e->getMessage());
		throw new Exception('ErrorExeption発生: ' . $e->getMessage());
	}
}


/*************************************************************
 * プロジェクトデータ一覧取得（全てのプロジェクトデータ、kubun = 1）
 *
 * @param String  	
 * @return array (連想配列) | データ無し：null
 *************************************************************/
function getAllProjectData()
{
	global $dbh;
	try {
		$return_data = null;
		$sql = "select * from sd_projtbl where kubun = '1' and del_date is null";
		$stmt = $dbh->query($sql);
		if ($stmt) {
			foreach ($stmt as $row) {
				$return_data[] = array(
					'id'				=> $row["projid"],
					'dataname' 			=> $row["dataname"],
					'sdate' 			=> $row["sdate"],
					'edate'				=> $row["edate"],
					'process_sts'		=> $row["process_sts"],
					'prj_bu1'			=> $row["prj_bu1"],
					'prj_bu2'			=> $row["prj_bu2"],
					'prj_bu3'			=> $row["prj_bu3"],
					'order_type'		=> $row["order_type"],
					'order_id'			=> $row["order_id"],
					'order_name'		=> $row["order_name"],
					'order_userid'		=> $row["order_userid"],
					'order_username'	=> $row["order_username"],
					'order_koujino'		=> $row["order_koujino"],
					'order_koujiname'	=> $row["order_koujiname"],
					'order_tel'			=> $row["order_tel"],
					'order_pcode'		=> $row["order_pcode"],
					'order_addr1'		=> $row["order_addr1"],
					'order_addr2'		=> $row["order_addr2"],
					'order_user'		=> $row["order_user"],
					'eigyo_userid'		=> $row["eigyo_userid"],
					'eigyo_username'	=> $row["eigyo_username"],
					'eigyo_bu1'			=> $row["eigyo_bu1"],
					'eigyo_bu2'			=> $row["eigyo_bu2"],
					'eigyo_bu3'			=> $row["eigyo_bu3"],
					'memo'				=> $row["memo"],
					'amount1'			=> $row["amount1"],
					'amount2'			=> $row["amount2"],
				);
			}
			return $return_data;
		} else {
			return false;
		}
	} catch (Exception $e) {
		error_log_Fnc("getAllProjectData", $e->getMessage());
		throw new Exception('ErrorExeption発生: ' . $e->getMessage());
	}
}

/*************************************************************
 * プロジェクトデータ取得(プロジェクト検索時)
 *
 * @param String $prj_name, $prj_sdate, $prj_edate 	
 * @return array (連想配列) | データ無し：null
 *************************************************************/
function findProjectData($prj_name, $prj_sdate, $prj_edate)
{
	global $dbh;
	try {
		$return_data = null;
		$sql = "select * from sd_projtbl where kubun = '1' and dataname like :prj_name and :prj_sdate <= sdate and edate <= :prj_edate and del_date is null;";
		$data = array(':prj_name' => "%$prj_name%", ':prj_sdate' => $prj_sdate, ':prj_edate' => $prj_edate);
		$stmt = queryPost($dbh, $sql, $data);
		if ($stmt) {
			foreach ($stmt as $row) {
				$return_data[] = array(
					'id'			=> $row["projid"],
					'dataname' 		=> $row["dataname"],
					'sdate' 		=> $row["sdate"],
					'edate'			=> $row["edate"],
					'process_sts'	=> $row["process_sts"],
					'prj_bu1'			=> $row["prj_bu1"],
					'prj_bu2'			=> $row["prj_bu2"],
					'prj_bu3'			=> $row["prj_bu3"],
					'order_id'			=> $row["order_id"],
					'order_name'			=> $row["order_name"],
					'amount1'			=> $row["amount1"],
					'amount2'			=> $row["amount2"],
				);
			}
			return $return_data;
		} else {
			return false;
		}
	} catch (Exception $e) {
		error_log_Fnc("findProjectData", $e->getMessage());
		throw new Exception('ErrorExeption発生: ' . $e->getMessage());
	}
}



/*************************************************************
 * プロジェクトデータ取得
 *
 * @param String $ 	(sd_projtbl : projid)
 * @return array (連想配列) | データ無し：null
 *************************************************************/
function getProjectDataByProjectID($projid)
{
	global $dbh;
	try {
		$sql = "select * from sd_projtbl 
						where projid = :projid
						and kubun = 1
						and del_date is null;";
		$data = array(':projid' => $projid);
		$stmt = queryPost($dbh, $sql, $data);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!empty($row)) {
			return $row;
		} else {
			return null;
		}
	} catch (Exception $e) {
		error_log_Fnc("getProjectDataByProjectID", $e->getMessage());
		throw new Exception('ErrorExeption発生: ' . $e->getMessage());
	}
}


/*************************************************************
 * 新規プロジェクトデータ登録
 *
 * @param  {$prj_data}
 * @return 
 *************************************************************/
function setProjectData($data)
{
	global $dbh;
	try {
		$sql = "insert into sd_projtbl (projid, kubun, cr_userid, cr_date, process_sts, dataname, sdate, edate, prj_bu1, prj_bu2, prj_bu3, order_type, order_id, order_name, order_userid, order_username, order_koujino, order_koujiname, order_tel, order_pcode, order_addr1, order_addr2, order_user, eigyo_tehai, eigyo_userid, eigyo_username, eigyo_bu1, eigyo_bu2, eigyo_bu3, memo, amount1, amount2)" . "\n";
		$sql .= "values(
			 :prj_id,
			 :kubun,
			 :cr_userid,
			 :cr_date,
			 '0',
			 :prj_name,
			 :prj_sdate,
			 :prj_edate,
			 :prj_bu1,
			 :prj_bu2,
			 :prj_bu3,
			 :order_type,
			 :order_id,
			 :order_name,
			 :order_userid,
			 :order_username,
			 :order_koujino,
			 :order_koujinm,
			 :order_tel,
			 :order_pcode,
			 :order_addr1,
			 :order_addr2,
			 :order_user,
			 '通常',
			 :prj_eigyo_userid,
			 :prj_eigyo_username,
			 :prj_bu1,
			 :prj_bu2,
			 :prj_bu3,
			 :prj_memo,
			 :prj_amount1,
			 :prj_amount2
		)";
		$stmt = queryPost($dbh, $sql, $data);
	} catch (Exception $e) {
		error_log_Fnc("setProjectData", $e->getMessage());
		throw new Exception('ErrorExeption発生: ' . $e->getMessage());
	}
}

/*************************************************************
 * 案件登録（新規プロジェクトデータ登録時）
 *
 * @param  $prj_id, $prj_data
 * @return 
 *************************************************************/
function setAnkenData($data)
{
	global $dbh;
	try {
		$sql = "insert into sd_plan_ankentbl (projid, anken_id, kubun, cr_userid, cr_date, process_sts, dataname, sdate, edate, eigyo_himoku1, eigyo_tehai, eigyo_userid, eigyo_username, eigyo_bu1, eigyo_bu2, eigyo_bu3, memo, amount1, amount2, order_date1, order_date2, order_amount)" . "\n";
		$sql .= "values(
			 :prj_id,
			 :anken_id,
			 :kubun,
			 :cr_userid,
			 :cr_date,
			 '0',
			 :anken_name,
			 :anken_sdate,
			 :anken_edate,
			 :eigyo_himoku1,
			 '通常',
			 :prj_eigyo_userid,
			 :prj_eigyo_username,
			 :eigyo_bu1,
			 :eigyo_bu2,
			 :eigyo_bu3,
			 :anken_memo,
			 :anken_amount1,
			 :anken_amount2,
			 '',
			 '',
			 ''
		);";
		$stmt = queryPost($dbh, $sql, $data);
	} catch (Exception $e) {
		error_log_Fnc("setAnkenData", $e->getMessage());
		throw new Exception('ErrorExeption発生: ' . $e->getMessage());
	}
}

/*************************************************************
 * 工事情報登録（新規プロジェクトデータ登録時）
 *
 * @param  
 * @return 
 *************************************************************/
function setKoujiData($data)
{
	global $dbh;
	try {
		$sql = "insert into sd_plan_koujitbl (projid, anken_id, kouji_id, kubun, cr_userid, cr_date, process_sts, dataname, sdate, edate, eigyo_himoku1, eigyo_tehai, eigyo_userid, eigyo_username, eigyo_bu1, eigyo_bu2, eigyo_bu3, memo, amount1, amount2, order_date1, order_date2, kakudo, order_amount)" . "\n";
		$sql .= "values(
			 :prj_id,
			 :anken_id,
			 :kouji_id,
			 :kubun,
			 :cr_userid,
			 :cr_date,
			 '0',
			 :kouji_name,
			 :kouji_sdate,
			 :kouji_edate,
			 :eigyo_himoku1,
			 '通常',
			 :prj_eigyo_userid,
			 :prj_eigyo_username,
			 :eigyo_bu1,
			 :eigyo_bu2,
			 :eigyo_bu3,
			 :kouji_memo,
			 :kouji_amount1,
			 :kouji_amount2,
			 '',
			 '',
			 :kakudo,
			 ''
		);";
		$stmt = queryPost($dbh, $sql, $data);
	} catch (Exception $e) {
		error_log_Fnc("setKoujiData", $e->getMessage());
		throw new Exception('ErrorExeption発生: ' . $e->getMessage());
	}
}

/*************************************************************
 * プロジェクトデータ削除
 *
 * @param String prj_id,$del_memo
 * @return 
 *************************************************************/
function deleteProjectData($data)
{
	global $dbh;
	try {
		$sql = "update sd_projtbl set del_date = :del_date, del_userid = :del_userid, del_memo = :del_memo where projid = :prj_id and del_date is null";
		$sql2 = "update sd_plan_ankentbl set del_date = :del_date, del_userid = :del_userid, del_memo = :del_memo where projid = :prj_id and del_date is null";
		$sql3 = "update sd_plan_koujitbl set del_date = :del_date, del_userid = :del_userid, del_memo = :del_memo where projid = :prj_id and del_date is null";
		$sql4 = "update sd_plan_arrangetbl set del_date = :del_date, del_userid = :del_userid, del_memo = :del_memo where projid = :prj_id and del_date is null";
		$sql5 = "update sd_plan_arrange_dtltbl set del_date = :del_date, del_userid = :del_userid, del_memo = :del_memo where projid = :prj_id and del_date is null";
		$stmt = queryPost($dbh, $sql, $data);
		$stmt = queryPost($dbh, $sql2, $data);
		$stmt = queryPost($dbh, $sql3, $data);
		$stmt = queryPost($dbh, $sql4, $data);
		$stmt = queryPost($dbh, $sql5, $data);
	} catch (Exception $e) {
		error_log_Fnc("deleteProjectData", $e->getMessage());
		throw new Exception('ErrorExeption発生: ' . $e->getMessage());
	}
}

/*************************************************************
 * 案件データ削除
 *
 * @param String prj_id,$del_memo
 * @return 
 *************************************************************/
function deleteAnkenData($data)
{
	global $dbh;
	try {
		$sql = "update sd_plan_ankentbl set del_date = :del_date, del_userid = :del_userid, del_memo = :del_memo where projid = :prj_id and anken_id = :anken_id and del_date is null";
		$sql2 = "update sd_plan_koujitbl set del_date = :del_date, del_userid = :del_userid, del_memo = :del_memo where projid = :prj_id and anken_id = :anken_id and del_date is null";
		$sql3 = "update sd_plan_arrangetbl set del_date = :del_date, del_userid = :del_userid, del_memo = :del_memo where projid = :prj_id and anken_id = :anken_id and del_date is null";
		$sql4 = "update sd_plan_arrange_dtltbl set del_date = :del_date, del_userid = :del_userid, del_memo = :del_memo where projid = :prj_id and anken_id = :anken_id and del_date is null";
		$stmt = queryPost($dbh, $sql, $data);
		$stmt = queryPost($dbh, $sql2, $data);
		$stmt = queryPost($dbh, $sql3, $data);
		$stmt = queryPost($dbh, $sql4, $data);
	} catch (Exception $e) {
		error_log_Fnc("deleteAnkenData", $e->getMessage());
		throw new Exception('ErrorExeption発生: ' . $e->getMessage());
	}
}
/*************************************************************
 * 工事データ削除（プロジェクト配下すべて）
 *
 * @param String prj_id,$del_memo
 * @return 
 *************************************************************/
function deleteKoujiData($data)
{
	global $dbh;
	try {
		$sql = "update sd_plan_koujitbl set del_date = :del_date, del_userid = :del_userid, del_memo = :del_memo where projid = :prj_id and anken_id = :anken_id and kouji_id = :kouji_id and del_date is null";
		$sql2 = "update sd_plan_arrangetbl set del_date = :del_date, del_userid = :del_userid, del_memo = :del_memo where projid = :prj_id and anken_id = :anken_id and kouji_id = :kouji_id and del_date is null";
		$sql3 = "update sd_plan_arrange_dtltbl set del_date = :del_date, del_userid = :del_userid, del_memo = :del_memo where projid = :prj_id and anken_id = :anken_id and kouji_id = :kouji_id and del_date is null";
		$stmt = queryPost($dbh, $sql, $data);
		$stmt = queryPost($dbh, $sql2, $data);
		$stmt = queryPost($dbh, $sql3, $data);
	} catch (Exception $e) {
		error_log_Fnc("deleteKoujiData", $e->getMessage());
		throw new Exception('ErrorExeption発生: ' . $e->getMessage());
	}
}


//************************************************************ */
//ここから追加分
//************************************************************ */
/*************************************************************
 * プロジェクトデータ一覧取得（全てのプロジェクトデータ、kubun = 1）
 *
 * @param String  	
 * @return array (連想配列) | データ無し：null
 *************************************************************/
function getDeleteProjectData()
{
	global $dbh;
	try {
		$return_data = null;
		$sql = "select * from sd_projtbl 
				where kubun = '1' 
				and del_date is not null";
		$stmt = $dbh->query($sql);
		if ($stmt) {
			foreach ($stmt as $row) {
				$return_data[] = array(
					'id'			=> $row["projid"],
					'dataname' 		=> $row["dataname"],
					'sdate' 		=> $row["sdate"],
					'edate'			=> $row["edate"],
					'process_sts'	=> $row["process_sts"],
					'prj_bu1'			=> $row["prj_bu1"],
					'prj_bu2'			=> $row["prj_bu2"],
					'prj_bu3'			=> $row["prj_bu3"],
					'order_id'			=> $row["order_id"],
					'order_name'			=> $row["order_name"],
					'amount1'			=> $row["amount1"],
					'amount2'			=> $row["amount2"],
				);
			}
			return $return_data;
		} else {
			return false;
		}
	} catch (Exception $e) {
		error_log_Fnc("getDeleteProjectData", $e->getMessage());
		throw new Exception('ErrorExeption発生: ' . $e->getMessage());
	}
}
