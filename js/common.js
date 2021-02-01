const AJAXURL 			= "controller/AjaxCon_kintai.php";				//AJAX遷移先
const AJAXADMINURL 	= "controller/AjaxCon_Admin_kintai.php";	//AJAX遷移先
const AJAXTIMEOUT 	= 30000;								//AJAXタイムアウト

/************************************************
	moment.js 曜日 日本語表記 初期化
************************************************/
	moment.lang('ja',{
	    weekdays: ["日曜日","月曜日","火曜日","水曜日","木曜日","金曜日","土曜日"],
	    weekdaysShort: ["日","月","火","水","木","金","土"],
	});

/************************************************
	Loading表示 非表示
	
	@param	int | 1:表示 2:非表示
	@return なし
************************************************/
	function Loading(md){
		if(md == 1){
			$(".js-loading").show();
		}else{
			$(".js-loading").hide();
		}
	}


/************************************************
	時刻計算
	
	@param	無し
	@return text format(HH:mm:ss)
************************************************/
	function returnClock(){
		return moment().format("HH:mm:ss");
	}

/************************************************
	時間フォーマット変換 (09:00 → 9:00)
	
	@param	time 時間データ
	@return text 変換後時間データ
************************************************/
function ChangeFormatTime(Time){
	let Tmp = "";
	let TmpTime1 = "";
	let TmpTime2 = "";
	if(Time === ""){return "";}
	TmpTime1 = String(parseInt(Time.split(":")[0],10));
	TmpTime2 = Time.split(":")[1];
	Tmp = TmpTime1 + ":" + TmpTime2;
	return Tmp;
}

/************************************************
	時間フォーマット変換 (09:00 → 9:00)
	
	@param	time 時間データ
	@return text 変換後時間データ
************************************************/
function ChangeFormatTimeInput(Time){
	let Tmp = "";
	if(Time !== ""){
		Tmp = Time.substr(0,2)+":"+Time.substr(2,2);
	}
	return Tmp;
}

/**************************************************************************************************************************
 * 時間データ分変換(HH:MM → HH.MM)
 * @param 	String $time 	(format HH:MM)	
 * @return  String $time 	(minutes)	 例:8:30 → 510
 **************************************************************************************************************************/
function ChangeTimeFormatMinutes(time){
	let Min = 60;
	let timeTmp = null;
			timeTmp 	= time.split(":");
	let timeHour 	= parseInt(timeTmp[0],10) * Min;
	let timeMin 	= parseInt(timeTmp[1],10);
	let timeSum		=	timeHour + timeMin;
	return timeSum;
}


/**************************************************************************************************************************
 * 時間フォーマット変換(MM → HH:MM)
 * @param 	String $time 	(format MM)	
 * @return  String $time 	(HH:MM)	 例:510 → 8:30
 **************************************************************************************************************************/
function ChangeMinutesFormat(time){
	let Min = 60;
	let timeTmp = time;
	//マイナス値変換
	if(time < 0){timeTmp = timeTmp * -1;}
	timeHour = timeTmp / Min;
	timeMin 	= timeTmp % Min;
	timeMin 	= ((timeMin < 10) ? "0"+timeMin : timeMin);
	timeSum	=	parseInt(timeHour,10)+":"+timeMin;
	if(time < 0){timeSum = "-"+timeSum;}
	return timeSum;
}

/************************************************
	バリデーション　数値
	
	@param	無し
	@return text format(HH:mm:ss)
************************************************/
function isNumber(numVal){
	var pattern = /^[-]?([1-9]\d*|0)(\.\d+)?$/;
	return pattern.test(numVal);
}

/************************************************
	数値金額表示変更
	
	@param	無し
	@return text format(10,000)
************************************************/
function isAmountNumber(numVal){
	numVal = numVal.replace(/,/g, '');
	if(!isNumber(numVal)){
		return 0;
	}
	return parseInt(numVal,10).toLocaleString();
}

/**************************************************************************************************************************
 * 日付フォーマット変換
 * @param 	String date 	(format YYYYMMDD)	
 * @return  String time 	(format YYYY/MM/DD)
 **************************************************************************************************************************/
function ChangeDateFormat(date){
	var dateFormat = "";
	if(date === ""){
		return "";
	}
	dateFormat = date.replace(/\//g, '');
	if(dateFormat.length === 6){
		dateFormat = dateFormat + "01";
		if(moment(dateFormat).format("YYYY/MM") !== "Invalid date"){
			return moment(dateFormat).format("YYYY/MM");
		}else{
			return "";
		}
	}else{
		if(moment(dateFormat).format("YYYY/MM/DD") !== "Invalid date"){
			return moment(dateFormat).format("YYYY/MM/DD");
		}else{
			return "";
		}
	}
}

/**************************************************************************************************************************
 * 日付変換フォーマット
 * @param 	String date 	(format YYYYMMDD)	
 * @return  String time 	(format YYYY/MM/DD)
 **************************************************************************************************************************/
function ChangeDateFormatYYYY(date){
	var dateFormat = "";
	if(date === ""){
		return "";
	}

	if(date.length === 6){
		date = date + "01";
		if(moment(date).format("YYYY/MM") !== "Invalid date"){
			return moment(date).format("YYYY/MM");
		}else{
			return "";
		}
	}

	//YYYYMM00判定
	if(date.slice(-2) === "00"){
		date = date.slice(0,6) + "01";
		return moment(date).format("YYYY/MM")
	}else{
		if(moment(date).format("YYYY/MM/DD") !== "Invalid date"){
			return moment(date).format("YYYY/MM/DD");
		}else{
			return "";
		}
	}
}

/**************************************************************************************************************************
 * 日付変換フォーマット
 * @param 	String date 	(format YYYYMMDD)	
 * @return  String time 	(format YYYY年MM月DD日)
 **************************************************************************************************************************/
function ChangeDateFormatString(date){
	if(date === ""){
		return "";
	}

	if(date.length === 6){
		date = date + "01";
		if(moment(date).format("YYYY/MM") !== "Invalid date"){
			return moment(date).format("YYYY年MM月");
		}else{
			return "";
		}
	}
	
	//YYYYMM00判定
	if(date.slice(-2) === "00"){
		date = date.slice(0,6) + "01";
		return moment(date).format("YYYY/MM")
	}else{
		if(moment(date).format("YYYY/MM/DD") !== "Invalid date"){
			return moment(date).format("YYYY年MM月DD日");
		}else{
			return "";
		}
	}
}

/**************************************************************************************************************************
 * 日付データ登録時変換
 * @param 	String date 	(format YYYY/MM/DD)	
 * @return  String time 	(format YYYYMMDD)
 **************************************************************************************************************************/
function ChangeDateFormatInsert(date){
	var dateFormat = "";
	if(date === ""){
		return "";
	}
	dateFormat = date.replace(/\//g, '');
	if(dateFormat.length === 6){
		dateFormat = dateFormat + "00";
		return dateFormat;
	}else{
		return moment(dateFormat).format("YYYYMMDD");
	}
}

/************************************************
	セッションタイムアウト時 遷移
	
	@param	無し
	@return text format(HH:mm:ss)
************************************************/
	function SessionConfig(dat){
		if(parseInt(dat.SessionConfig.sts,10) === 1){
			return true;
		}else{
			location.href = dat.SessionConfig.URL;
		}
	}