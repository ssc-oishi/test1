<?php

//PHP名
define("PHPName", "project_regist.php");

//セッション判定
include("common/session_config.php");

//CommonPHPインクルード
include("common/common_function.php");
require_once('common/common_def.php');
header("Content-type: text/html; charset=utf-8");

$timestamp = time(); //タイムスタンプ
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<head>
	<title>新規計画</title>
	<link rel="stylesheet" href="css/reset.css?vs=<?php echo $timestamp ?>" type="text/css" />
	<?php include("common_meta.inc"); ?>

	<?php include("common_style.inc"); ?>
	<link rel="stylesheet" href="css/style_project.css?vs=<?php echo $timestamp ?>" type="text/css" />

	<style>
		.dlg_sub_title {
			width: 100%;
			background: #339cff;
			color: white;
		}

		.kouji_info_tbl th {
			text-align: right;
			font-size: 13px;
			width: 120px;
		}

		.kouji_info_tbl td {
			padding: 3px 15px 0px 0px;
			font-size: 13px;
		}

		.kouji_info_tbl select {
			width: 200px !important;
			font-size: 13px;
		}

		.input_wide {
			width: 100%;
			max-width: 405px;
		}

		.hinmei_radio {
			padding: 5px 10px;
			border: 1px solid #a8a8a8;
			background-color: white;
		}

		.hinmei_radio label {
			padding: 5px;
			cursor: pointer;
		}

		.hinmei_radio input[type="radio"] {
			margin-right: 5px;
		}
	</style>
</head>

<body>
	<div id="container">
		<?php include("header.inc"); ?>
		<div id="contents">
			<div id="menu">
				<?php include("sidemenu.inc"); ?>
			</div>
			<!--menuEND-->
			<div id="main">
				<?php //include("UserInfo.inc"); 
				?>
				<div id="MainInfo">
					<div id="MainTitle">■ 新規計画</div>
					<div id="MainContents" style="min-height:100px;">

						<!--プロジェクト新規登録-->
						<div id="prj_entry_dlg" style="background:lightgray;font-size:15px;">
							<table style="width:98%;margin:5px auto;margin-top:0;background:#e6f3ff;" class="kouji_info_tbl">
								<tbody>
									<tr>
										<th>プロジェクト：</th>
										<td><input type="text" name="prj_name" placeholder="プロジェクト名を入力" class="input_wide"></td>
									</tr>
									<tr>
										<th>マイルストーン：</th>
										<td><input type="text" name="prj_sdate" style="width:100px;" placeholder="9999/99/99">　～　<input type="text" name="prj_edate" style="width:100px;" placeholder="9999/99/99"></td>
									</tr>
									<tr>
										<th>担当部署：</th>
										<td>
											<select name="prj_bu1" id="entry_y_1"></select>
											<select name="prj_bu2" id="entry_y_2"></select>
											<select name="prj_bu3" id="entry_y_3"></select>
										</td>
									</tr>
									<tr>
										<th>担当者：</th>
										<td>
											<input type="text" name="prj_eigyo_username" placeholder="営業担当者名" id="eigyo_select" value="" class="input_wide">
										</td>
									</tr>
									<tr>
										<th>特記事項：</th>
										<td>
											<textarea type="text" value="" placeholder="特記事項があれば入力" name="prj_memo" class="input_wide"></textarea>
										</td>
									</tr>
								<tbody>
							</table>
							<div style="width:98%;margin:5px auto;background:#e6f3ff;">
								<p class="dlg_sub_title">案件情報</p>
								<table style="width:100%;" class="kouji_info_tbl">
									<tbody>
										<tr>
											<th>案件名：</th>
											<td><input type="text" name="anken_name" placeholder="案件名を入力" class="input_wide"></td>
										</tr>
										<tr>
											<th>案件予定期間：</th>
											<td><input type="text" style="width:100px;" name="anken_sdate" placeholder="9999/99/99">　～　<input type="text" style="width:100px;" name="anken_edate" placeholder="9999/99/99"></td>
										</tr>
										<tr>
											<th>特記事項：</th>
											<td>
												<textarea type="text" value="" placeholder="特記事項があれば入力" name="anken_memo" class="input_wide"></textarea>
											</td>
										</tr>
									<tbody>
								</table>
							</div>
							<div style="width:98%;margin:5px auto;background:#e6f3ff;">
								<p class="dlg_sub_title">工事情報</p>
								<table style="width:100%;" class="kouji_info_tbl">
									<tbody>
										<tr>
											<th>工事件名：</th>
											<td colspan="3"><input type="text" name="kouji_name" placeholder="工事件名を入力" class="input_wide"></td>
										</tr>
										<tr>
											<th>受注予定日：</th>
											<td><input type="text" style="width:100px;" name="kouji_sdate" placeholder="9999/99/99"></td>
											<th>売上予定日：</th>
											<td><input type="text" style="width:100px;" name="kouji_edate" placeholder="9999/99/99"></td>
											<th>受注確度：</th>
											<td><input type="text" style="width:100px;" name="kouji_kakudo">％</td>
										</tr>
										<tr>
											<th>受注見込金額：</th>
											<td><input type="text" style="width:100px;" name="kouji_amount1" class="amount">円</td>
											<th>受注未定金額：</th>
											<td><input type="text" style="width:100px;" name="kouji_amount2" class="amount">円</td>
											<th>営業品目：</th>
											<td>
												<div class="hinmei_radio">
													<label><input type="radio" name="kouji_eigyo_himoku1" value="K" checked="checked">K</label>
													<label><input type="radio" name="kouji_eigyo_himoku1" value="M">M</label>
													<label><input type="radio" name="kouji_eigyo_himoku1" value="E">E</label>
												</div>
											</td>
										</tr>
										<tr>
											<th>特記事項：</th>
											<td colspan="3">
												<textarea type="text" value="" placeholder="特記事項があれば入力" name="kouji_memo" class="input_wide"></textarea>
											</td>
										</tr>
									<tbody>
								</table>
							</div>
							<div style="width:98%;margin:5px auto;background:#e6f3ff;">
								<p class="dlg_sub_title">受注先情報</p>
								<table style="width:100%;" class="kouji_info_tbl">
									<tbody>
										<tr>
											<th>種別：</th>
											<td>
												<div class="hinmei_radio">
													<label><input type="radio" name="order_type" value="0" checked="checked">JR</label>
													<label><input type="radio" name="order_type" value="1">私鉄</label>
													<label><input type="radio" name="order_type" value="2">その他</label>
												</div>
											</td>
										</tr>
										<tr>
											<th>受注先名：</th>
											<td>
												<input type="text" class="open_s_order_dlg input_wide" name="order_name" style="background:lightpink;" placeholder="選択受注先名を表示" rel="1">
												<input type="hidden" class="open_s_order_dlg" name="order_id" rel="1">
											</td>
											<th>使用先名：</th>
											<td>
												<input type="text" class="open_s_order_dlg input_wide" name="order_username" style="background:lightpink;" placeholder="選択受注先名を表示" rel="2">
												<input type="hidden" class="open_s_order_dlg" name="order_userid" rel="2">
											</td>
										</tr>
										<tr>
											<th>顧客工事番号：</th>
											<td><input type="text" name="order_koujino" class="input_wide" placeholder="受注先工事番号"></td>
											<th>顧客工事件名：</th>
											<td><input type="text" name="order_koujinm" placeholder="顧客工事件名を入力" class="input_wide"></td>
										</tr>
										<tr>
											<th>郵便番号：</th>
											<td><input type="text" name="order_pcode" placeholder="999-9999" style="width: 100px;"></td>
											<th>都道府県：</th>
											<td>
												<select name="order_addr1" id="">
													<option value="">都道府県を選択</option>
													<option value="北海道">北海道</option>
													<option value="青森県">青森県</option>
													<option value="岩手県">岩手県</option>
													<option value="宮城県">宮城県</option>
													<option value="秋田県">秋田県</option>
													<option value="山形県">山形県</option>
													<option value="福島県">福島県</option>
													<option value="茨城県">茨城県</option>
													<option value="栃木県">栃木県</option>
													<option value="群馬県">群馬県</option>
													<option value="埼玉県">埼玉県</option>
													<option value="千葉県">千葉県</option>
													<option value="東京都">東京都</option>
													<option value="神奈川県">神奈川県</option>
													<option value="新潟県">新潟県</option>
													<option value="富山県">富山県</option>
													<option value="石川県">石川県</option>
													<option value="福井県">福井県</option>
													<option value="山梨県">山梨県</option>
													<option value="長野県">長野県</option>
													<option value="岐阜県">岐阜県</option>
													<option value="静岡県">静岡県</option>
													<option value="愛知県">愛知県</option>
													<option value="三重県">三重県</option>
													<option value="滋賀県">滋賀県</option>
													<option value="京都府">京都府</option>
													<option value="大阪府">大阪府</option>
													<option value="兵庫県">兵庫県</option>
													<option value="奈良県">奈良県</option>
													<option value="和歌山県">和歌山県</option>
													<option value="鳥取県">鳥取県</option>
													<option value="島根県">島根県</option>
													<option value="岡山県">岡山県</option>
													<option value="広島県">広島県</option>
													<option value="山口県">山口県</option>
													<option value="徳島県">徳島県</option>
													<option value="香川県">香川県</option>
													<option value="愛媛県">愛媛県</option>
													<option value="高知県">高知県</option>
													<option value="福岡県">福岡県</option>
													<option value="佐賀県">佐賀県</option>
													<option value="長崎県">長崎県</option>
													<option value="熊本県">熊本県</option>
													<option value="大分県">大分県</option>
													<option value="宮崎県">宮崎県</option>
													<option value="鹿児島県">鹿児島県</option>
													<option value="沖縄県">沖縄県</option>
												</select>
											</td>
										</tr>
										<tr>
											<th>住所（市区町村）：</th>
											<td colspan="3"><input type="text" name="order_addr2" placeholder="市区町村以下を入力" style="width:603px;"></td>
										</tr>
										<tr>
											<th>TEL：</th>
											<td><input type="text" name="order_tel" style="width:100px;"></td>
											<th>受注先担当者：</th>
											<td><input type="text" name="order_user" class="input_wide"></td>
										</tr>
									<tbody>
								</table>
							</div>
							<div style="text-align:center;">
								<button id="prj_entry_btn" class="btn_common_style" style="margin:20px auto;">登録する</button>
							</div>
						</div>
						<!--プロジェクト新規登録 END-->
					</div>
				</div>
			</div>
		</div>
		<!--contentsEND-->
		<?php include("footer.inc"); ?>
	</div>
	<!--containerEND-->

	<input type="hidden" name="edit_prjid">

	<?php include("common_js.inc"); ?>
	<script type="text/javascript" src="js/project_regist.js?vs=<?php echo $timestamp ?>"></script>

</body>

</html>