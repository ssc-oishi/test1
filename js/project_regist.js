//担当ユーザーID
var eigyo_prop = 0;
var eigyo_id = "";
var edit_eigyo_prop = 0;
var edit_eigyo_id = "";

Loading(1);
getAllProjectData();
getJuchuMstData();

//プロジェクト新規登録 入力欄の初期化
$("#prj_entry_dlg").find("input[type='text']").val("");
$("#prj_entry_dlg").find("select").val("");
$('input[name="kouji_eigyo_himoku1"][value="K"]').prop('checked', true);
$('input[name="order_type"][value="0"]').prop('checked', true);
$("input[name='kouji_amount1']").val(0);
$("input[name='kouji_amount2']").val(0);

//プロジェクト期間
$("input[name='prj_sdate']").focusout(function () {
	$(this).val(ChangeDateFormat($(this).val()));
});
$("input[name='prj_edate']").focusout(function () {
	$(this).val(ChangeDateFormat($(this).val()));
});
//案件期間
$("input[name='anken_sdate']").focusout(function () {
	$(this).val(ChangeDateFormat($(this).val()));
});
$("input[name='anken_edate']").focusout(function () {
	$(this).val(ChangeDateFormat($(this).val()));
});
//工事期間
$("input[name='kouji_sdate']").focusout(function () {
	$(this).val(ChangeDateFormat($(this).val()));
});
$("input[name='kouji_edate']").focusout(function () {
	$(this).val(ChangeDateFormat($(this).val()));
});

//担当部署セレクトボックス作成(新規登録ダイアログ)
//部・室
$("#entry_y_1").on('change', function () {
	hstr = '<option value="">----</option>';
	$("#entry_y_2").html(hstr);
	$("#entry_y_3").html(hstr);
	getSyozokuData(2, $(this).val(), "", "", $("#entry_y_1"), $("#entry_y_2"), $("#entry_y_3"));
});
//課・グループ
$("#entry_y_2").on('change', function () {
	hstr = '<option value="">----</option>';
	$("#entry_y_3").html(hstr);
	getSyozokuData(3, $("#entry_y_1").val(), $(this).val(), "", $("#entry_y_1"), $("#entry_y_2"), $("#entry_y_3"));
});
//担当部署データセット1
getSyozokuData(1, "", "", "", $("#entry_y_1"), $("#entry_y_2"), $("#entry_y_3"));
//担当部署データセット2
getSyozokuData(2, "", "", "", $("#entry_y_1"), $("#entry_y_2"), $("#entry_y_3"));
//担当部署データセット3
getSyozokuData(3, "", "", "", $("#entry_y_1"), $("#entry_y_2"), $("#entry_y_3"));

getEigyoUserData();

//金額カンマ区切りにする
$(".amount").focusout(function () {
	$(this).val(isAmountNumber($(this).val()));
});

//受注先選択ダイアログを開く
btnFlg = "";
$(".open_s_order_dlg").click(function () {
	btnFlg = $(this).attr('rel');
	console.log(btnFlg);
	$("#order_search_dlg").dialog({
		title: "顧客名検索",
		modal: true,
		width: 600,
		height: 500,
		resizable: false,
		open: function (event, ui) {
			let name = $(this).attr('name');
			// $(this).find('input').blur();
		}
	});
});

//プロジェクト新規登録ダイアログの「登録する」ボタンを押したとき
$("#prj_entry_btn").click(function () {
	var ErrTxt = "";
	//入力チェック
	if ($("input[name='prj_name']").val() == "") { ErrTxt += "プロジェクト名を入力して下さい\n"; }
	if ($("input[name='prj_sdate']").val() == "" || $("input[name='prj_edate']").val() == "") { ErrTxt += "マイルストーンを入力して下さい\n"; }
	if ($("input[name='prj_bu1']").val() == "") { ErrTxt += "担当部署を入力して下さい\n"; }
	if (eigyo_id == "") { ErrTxt += "担当者を正しく選択して下さい\n"; }
	if ($("input[name='anken_name']").val() == "") { ErrTxt += "案件名を入力して下さい\n"; }
	if ($("input[name='anken_sdate']").val() == "") { ErrTxt += "案件予定期間（開始）を入力して下さい\n"; }
	if ($("input[name='kouji_name']").val() == "") { ErrTxt += "工事件名を入力して下さい\n"; }
	if ($("input[name='kouji_eigyo_himoku1']").val() == "") { ErrTxt += "営業品目を選択して下さい\n"; }
	if ($("input[name='order_type']").val() == "") { ErrTxt += "種別を入力して下さい\n"; }

	//入力チェッククリアしたら、登録確認して、データ登録functionを実行
	if (ErrTxt == "") {
		jConfirm('プロジェクト情報を新規登録してよろしいですか？', "登録確認", function (r) {
			if (r) {
				Loading(1);
				setTimeout(function () {
					setProjectData();
				}, 1000);
			}
		});
	} else {
		jAlert(ErrTxt);
	}
});






//******************************************************//
//	function 
//******************************************************///

//受注先一覧テーブル生成function
function createJuchuMstTable(dat) {
	var hstr = "";
	hstr += '<table id="juchuMstTbl" style="width:560px;">';
	hstr += '	<thead><th style="width:20px;">No.</th><th>コード</th><th>顧客名</th></thead>';
	hstr += '	<tbody>';
	if (dat != null) {
		for (var i = 0; i < dat.length; i++) {
			hstr += '<tr>';
			hstr += '	<th>' + (i + 1) + '</th>';
			hstr += '	<td><a class="order_user" style="cursor:pointer;color:blue;" rel="' + dat[i].jusaknm + '">' + dat[i].jusakcd + '</a></td>';
			hstr += '	<td>' + dat[i].jusaknm + '</td>';
			hstr += '</tr>';
		}
	} else {
		hstr += '<tr><td colspan="3">※受注先データはありません</td></tr>';
	}
	hstr += '	</tbody>';
	hstr += '</table>';
	$("#juchuMstTblDiv").empty().append(hstr);
	$("#juchuMstTbl").tablesorter({
		theme: 'jui',
		showProcessing: true,
		headerTemplate: '{content} {icon}',
		widgets: ['uitheme', 'zebra', 'filter', "stickyHeaders"],
		widgetOptions: {
			// scroller_height: 150,
			filter_cssFilter: '',
			filter_childRows: false,
			filter_hideFilters: false,
			filter_ignoreCase: true,
			filter_reset: '.reset',
			// filter_reset : '.reset',
			filter_saveFilters: false,
			filter_searchDelay: 300,
			filter_startsWith: false
		},
		headers: {
			0: { filter: false },
			// 1: { filter: false }
		}
	});
	$(".order_user").click(function () {
		const jusakcd = $(this).text();
		const jusaknm = $(this).attr('rel');
		//ここでクリックした顧客データをinputのvalueに入れて、ダイアログ閉じる
		if (btnFlg == '1') {
			$("input[name='order_id']").val(jusakcd);
			$("input[name='order_name']").val(jusaknm);
		}
		if (btnFlg == '2') {
			$("input[name='order_userid']").val(jusakcd);
			$("input[name='order_username']").val(jusaknm);
		}
		if (btnFlg == '3') {
			$("input[name='edit_order_id']").val(jusakcd);
			$("input[name='edit_order_name']").val(jusaknm);
		}
		if (btnFlg == '4') {
			$("input[name='edit_order_userid']").val(jusakcd);
			$("input[name='edit_order_username']").val(jusaknm);
		}
		$("#order_search_dlg").dialog('close');
	});
}

//******************************************************//
//	Ajax通信関連
//******************************************************///
//プロジェクトデータ取得
function getAllProjectData() {
	var fData = {
		"fID": "getAllProjectData",
	}
	$.ajax({
		url: "controller/getProjectData.php",
		type: "POST",
		dataType: 'json',
		data: fData,
		timeout: AJAXTIMEOUT
	})
		.then(
			function (data) {
				if (parseInt(data.getAllProjectData.sts, 10) == 1) {
					// createProjectTable(data.getAllProjectData.data);
				} else {
					swal({ type: "error", title: data.getAllProjectData.Ttl, html: data.getAllProjectData.Mess });
				}
			},
			function () {
				swal({ type: "error", title: "エラー", html: "予期せぬエラーが発生しました。ページを更新して再度お試し下さい。" });
			}
		).always(function (data) {
			Loading(2);
		});
}


//プロジェクトデータ登録function
function setProjectData(kubun) {
	let prj_data = {
		"prj_name": $("input[name='prj_name']").val(),
		"prj_sdate": ChangeDateFormatInsert($("input[name='prj_sdate']").val()),
		"prj_edate": ChangeDateFormatInsert($("input[name='prj_edate']").val()),
		"prj_bu1": $("#entry_y_1").val(),
		"prj_bu2": $("#entry_y_2").val(),
		"prj_bu3": $("#entry_y_3").val(),
		"prj_eigyo_username": $("#eigyo_select").val(),
		"prj_eigyo_userid": eigyo_id,
		"prj_amount1": ($("input[name='kouji_amount1']").val() ? ($("input[name='kouji_amount1']").val()).replace(/,/g, '') : 0),
		"prj_amount2": ($("input[name='kouji_amount2']").val() ? ($("input[name='kouji_amount2']").val()).replace(/,/g, '') : 0),
		"prj_memo": $("textarea[name='prj_memo']").val(),
		"anken_name": $("input[name='anken_name']").val(),
		"anken_sdate": ChangeDateFormatInsert($("input[name='anken_sdate']").val()),
		"anken_edate": ($("input[name='anken_edate']").val() ? ChangeDateFormatInsert($("input[name='anken_edate']").val()) : ""),
		"anken_amount1": ($("input[name='kouji_amount1']").val() ? ($("input[name='kouji_amount1']").val()).replace(/,/g, '') : 0),
		"anken_amount2": ($("input[name='kouji_amount2']").val() ? ($("input[name='kouji_amount2']").val()).replace(/,/g, '') : 0),
		"anken_memo": $("textarea[name='anken_memo']").val(),
		"kouji_name": $("input[name='kouji_name']").val(),
		"kouji_sdate": ($("input[name='kouji_sdate']").val() ? ChangeDateFormatInsert($("input[name='kouji_sdate']").val()) : ""),
		"kouji_edate": ($("input[name='kouji_edate']").val() ? ChangeDateFormatInsert($("input[name='kouji_edate']").val()) : ""),
		"kouji_amount1": ($("input[name='kouji_amount1']").val() ? ($("input[name='kouji_amount1']").val()).replace(/,/g, '') : 0),
		"kouji_amount2": ($("input[name='kouji_amount2']").val() ? ($("input[name='kouji_amount2']").val()).replace(/,/g, '') : 0),
		"eigyo_himoku1": $("input[name='kouji_eigyo_himoku1']").val(),
		"kouji_kakudo": ($("input[name='kouji_kakudo']").val() ? $("input[name='kouji_kakudo']").val() : 0),
		"kouji_memo": $("textarea[name='kouji_memo']").val(),
		"order_type": $("input[name='order_type']").val(),
		"order_id": ($("input[name='order_id']").val() ? $("input[name='order_id']").val() : ""),
		"order_name": ($("input[name='order_name']").val() ? $("input[name='order_name']").val() : ""),
		"order_userid": ($("input[name='order_userid']").val() ? $("input[name='order_userid']").val() : ""),
		"order_username": ($("input[name='order_username']").val() ? $("input[name='order_username']").val() : ""),
		"order_koujino": ($("input[name='order_koujino']").val() ? $("input[name='order_koujino']").val() : ""),
		"order_koujinm": ($("input[name='order_koujinm']").val() ? $("input[name='order_koujinm']").val() : ""),
		"order_pcode": ($("input[name='order_pcode']").val() ? $("input[name='order_pcode']").val() : ""),
		"order_addr1": ($("select[name='order_addr1']").val() ? $("select[name='order_addr1']").val() : ""),
		"order_addr2": ($("input[name='order_addr2']").val() ? $("input[name='order_addr2']").val() : ""),
		"order_tel": ($("input[name='order_tel']").val() ? $("input[name='order_tel']").val() : ""),
		"order_user": ($("input[name='order_user']").val() ? $("input[name='order_user']").val() : ""),
		// "order_busyo": ($("input[name='order_busyo']").val() ? $("input[name='order_busyo']").val() : ""),
	};
	console.log('prj', prj_data);

	var fData = {
		"fID": "createProjectData",
		"prj_data": prj_data,
	}
	$.ajax({
		url: "controller/setProjectData.php",
		type: "POST",
		dataType: 'json',
		data: fData,
		timeout: AJAXTIMEOUT
	})
		.then(
			function (data) {
				if (parseInt(data.setProjectData.sts, 10) == 1) {
					swal({
						type: "success",
						title: data.setProjectData.Ttl,
						html: "プロジェクトを新規登録しました。"
					}).then(function () {
						location.reload();
					});
				} else {
					swal({ type: "error", title: data.setProjectData.Ttl, html: data.setProjectData.Mess });
				}
			},
			function () {
				swal({ type: "error", title: "エラー", html: "予期せぬエラーが発生しました。ページを更新して再度お試し下さい。" });
			}
		).always(function (data) {
			Loading(2);
		});
}


//受注先マスタデータ取得
function getJuchuMstData() {
	var fData = {
		"fID": "getJuchuMstData",
	}
	$.ajax({
		url: "controller/getJuchuMstData.php",
		type: "POST",
		dataType: 'json',
		data: fData,
		timeout: AJAXTIMEOUT
	})
		.then(
			function (data) {
				if (parseInt(data.getJuchuMstData.sts, 10) == 1) {
					createJuchuMstTable(data.getJuchuMstData.data);
				} else {
					swal({ type: "error", title: data.getJuchuMstData.Ttl, html: data.getJuchuMstData.Mess });
				}
			},
			function () {
				swal({ type: "error", title: "エラー", html: "予期せぬエラーが発生しました。ページを更新して再度お試し下さい。" });
			}
		).always(function (data) {
			Loading(2);
		});
}


//******************************************************//
//	人事DB関連
//******************************************************///
//営業担当者データ取得
function getEigyoUserData() {
	var fData = {
		"fID": "getEigyoUserData"
	}
	$.ajax({
		url: "controller/getSyozokuData.php",
		type: "POST",
		dataType: 'json',
		data: fData,
		timeout: AJAXTIMEOUT
	})
		.then(
			function (data) {
				if (parseInt(data._getEigyoUserData.sts, 10) == 1) {
					//担当者登録
					$('#eigyo_select').smSearchInputSelector({
						uniquename: 'selector',
						selector: data._getEigyoUserData.data,
						selected: function (val) {
							eigyo_prop = 1;
							eigyo_id = val;
						}
					});
					$("#eigyo_select").focusout(function () {
						if (eigyo_prop === 0) {
							eigyo_id = "";
						}
					});
					$("#eigyo_select").focus(function () {
						eigyo_prop = 0;
					});

					//担当者編集
					$('#edit_eigyo_select').smSearchInputSelector({
						uniquename: 'selector',
						selector: data._getEigyoUserData.data,
						selected: function (val) {
							edit_eigyo_prop = 1;
							edit_eigyo_id = val;
						}
					});
					$("#edit_eigyo_select").focusout(function () {
						if (edit_eigyo_prop === 0) {
							edit_eigyo_id = "";
						}
					});
					$("#edit_eigyo_select").focus(function () {
						edit_eigyo_prop = 0;
					});

				} else {
					swal({ type: "error", title: data._getEigyoUserData.Ttl, html: data._getEigyoUserData.Mess });
				}
			},
			function () {
				swal({ type: "error", title: "エラー", html: "予期せぬエラーが発生しました。ページを更新して再度お試し下さい。" });
			}
		).always(function (data) {
			Loading(2);
		});
}

//**人事DBから所属部署マスタデータを取得functioon
function getSyozokuData(md, val1, val2, val3, elm1, elm2, elm3) {
	var fData = {
		"fID": "getSyozokuData",
		"_md": md,
		"grp1_name": val1,
		"grp2_name": val2,
		"grp3_name": val3
	}
	$.ajax({
		url: "controller/getSyozokuData.php",
		type: 'POST',
		dataType: 'json',
		data: fData,
		timeout: AJAXTIMEOUT
	})
		.then(
			function (data) {
				if (parseInt(data._getSyozokuData.sts, 10) == 1) {
					//所属マスタからセレクトボックス作成
					hstr = "";
					let grp = data._getSyozokuData.data;
					if (grp != null) {
						hstr += '<option value="">----</option>';
						for (var i = 0; i < grp.length; i++) {
							hstr += '<option value="' + grp[i].data + '">' + grp[i].data + '</option>';
						}
						if (md == 1) {
							elm1.html(hstr);
							if (val1 !== "") {
								elm1.val(val1);
							}
						} else if (md == 2) {
							elm2.html(hstr);
							if (val2 !== "") {
								elm2.val(val2);
							}
						} else if (md == 3) {
							elm3.html(hstr);
							if (val3 !== "") {
								elm3.val(val3);
							}
						}
					}
				} else {
					swal({ type: "error", title: data._getSyozokuData.Ttl, html: data._getSyozokuData.Mess });
				}
			},
			function () {
				swal({ type: "error", title: "エラー", html: "予期せぬエラーが発生しました。ページを更新して再度お試し下さい。" });
			}
		).always(function (data) {
			Loading(2);
		});
}
