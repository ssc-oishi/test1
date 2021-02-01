<?php
/*************************************************************
 * 共通関数専用　PHP
 *************************************************************/


	/**************************************************************************************************************************
	 * JSON掃き出し | AJAX終了処理
	 *
	 * @param 	Array $JSON_Array 	(格納データ)
	 *
	 * @return 	ログファイルに掃き出し
	 **************************************************************************************************************************/
	function exitAccess($JSON_Array){
		global $dbh;
		//JSONデータ出力
		echo json_encode($JSON_Array,JSON_UNESCAPED_UNICODE);
		//データベースアクセス終了
		$dbh = null;
		//AJAX終了
		Exit;
	}

	/**************************************************************************************************************************
	 * エラーログの掃き出し
	 *
	 * @param 	String $FucNm (Function名)
	 * @param 	String $error (DBエラー時情報)
	 *
	 * @return 	ログファイルに掃き出し
	 **************************************************************************************************************************/
	function error_log_Fnc($FucNm,$error){
		error_log(print_r("[DATE]:".date("Y/m/d h:i:s")." [IP]:".$_SERVER["REMOTE_ADDR"]." [PHP]:".PHPNAME." [FuncNM]:".$FucNm." [Browser]:".$_SERVER['HTTP_USER_AGENT']."\n", TRUE), 3,ERRORLOGSAVE);
		error_log(print_r("[Query]:", TRUE), 3,ERRORLOGSAVE);
		error_log(print_r($error, TRUE), 3,ERRORLOGSAVE);
		error_log(print_r("\n", TRUE), 3,ERRORLOGSAVE);
	}
	
	/**************************************************************************************************************************
	 * エラーメール送信
	 *
	 * @param 	String $FucNm (Function名)
	 * @param 	String $error (DBエラー時情報)
	 *
	 * @return 	エラーメールを送信
	 **************************************************************************************************************************/
	function error_mail_Fnc($ErrFileNm,$ER_FuncNm,$ER_Mess){
		$ER_Mess = print_r($ER_Mess,true);
		$message = file_get_contents('mailtext/ErorrText001.txt',true);
		$message = str_replace("%ER_User",SITENAME,$message);
		$message = str_replace("%ER_Date",date("Y/m/d H:i"),$message);
		$message = str_replace("%ER_FileNm",Trim($ErrFileNm),$message);
		$message = str_replace("%ER_FuncNm",Trim($ER_FuncNm),$message);
		$message = str_replace("%ER_IP",Trim($_SERVER["REMOTE_ADDR"]),$message);
		$message = str_replace("%ER_Mess",$ER_Mess,$message);
		SendMail(ERRORMAILADD,"【重要】".SITENAME."サイトエラー通知",$message,ERRORMAIL_HEADR,ERRORMAIL_OPT);
	}

	/**************************************************************************************************************************
	 * メール送信
	 *
	 * @param 	String $to 		(宛先)
	 * @param 	String $subject (件名)
	 * @param 	String $message (本文)
	 * @param 	String $headers (メールヘッダー情報) 形式: From: メールアドレス
	 * @param 	String $mailopt (メールオプション) 形式: -fメールアドレス
	 *
	 * @return  bool | true:送信成功 / false:送信失敗
	 **************************************************************************************************************************/
	function SendMail($to,$subject,$message,$headers,$mailopt){
		mb_language("Japanese");
		mb_internal_encoding("UTF-8");
		$headers = $headers."\r\n";
		return mb_send_mail($to,$subject,$message,$headers,$mailopt);
	}

	/**************************************************************************************************************************
	 * 時間差分　計算
	 * 
	 * @param 	String $date1 (YYYY/MM/DD HH:mm:ss)
	 * @param 	String $date2 (YYYY/MM/DD HH:mm:ss)
	 *
	 * @return  時間差分
	 **************************************************************************************************************************/
	function difftime($date1,$date2){
		return (strtotime($date2) - strtotime($date1)) / 3600;
	}

	/**************************************************************************************************************************
	 * 時間データ分変換(HH:MM → HH.MM)
	 * @param 	String $time 	(format HH:MM)	
	 * @return  String $time 	(minutes)	 例:8:30 → 510
	 **************************************************************************************************************************/
	function ChangeTimeFormatMinutes($time){
		$Min = 60;
		$timeTmp = null;
		$timeTmp 	= explode(":",$time);
		$timeHour = intval($timeTmp[0]) * $Min;
		$timeMin 	= intval($timeTmp[1]);
		$timeSum	=	$timeHour + $timeMin;
		return $timeSum;
	}

	/**************************************************************************************************************************
	 * 時間フォーマット変換(MM → HH:MM)
	 * @param 	String $time 	(format MM)	
	 * @return  String $time 	(HH:MM)	 例:510 → 8:30
	 **************************************************************************************************************************/
	function ChangeMinutesFormat($time){
		$Min = 60;
		$timeTmp = $time;
		//マイナス値変換
		if($time < 0){$timeTmp = $timeTmp * -1;}
		$timeHour = $timeTmp / $Min;
		$timeMin 	= $timeTmp % $Min;
		$timeMin 	= $timeMin < 10 ? "0".$timeMin : $timeMin;
		$timeSum	=	floor($timeHour).":". $timeMin;
		if($time < 0){$timeSum = "-".$timeSum;}
		return $timeSum;
	}