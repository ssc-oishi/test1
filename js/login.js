var jsnModule = "JSON_Admin_JINJI.php"; //ajaxデータ取得PHPファイル名
var ajaxTimeOutLng = 30000; //ajaxタイムアウトLong
var ajaxTimeOutMdl = 10000; //ajaxタイムアウトMiddle
var ajaxTimeOutSrt = 5000; //ajaxタイムアウトShort
var ajaxTimeOutNon = 0; //ajaxタイムアウト無し

var jsn_getHousingData = ""; //筐体データGlobal
var jsn_getHousingDtlData = ""; //明細データGlobal

var JumpURL = "project_info.php"; //ログイン時遷移先URL

if ($.cookie("LoginID")) {
  $("#loginID").val($.cookie("LoginID"));
  $("#loginChk").prop("checked", true);
}
if ($.cookie("LoginPass")) {
  $("#loginPass").val($.cookie("LoginPass"));
  $("#loginChk").prop("checked", true);
}
$("#LoginBtn").on("click", function () {
  if ($("#loginID").val() != "" || $("#loginPass").val() != "") {
    var LoginID = $("#loginID").val();
    var LoginPass = $("#loginPass").val();
    if ($("#loginChk").prop("checked")) {
      $.cookie("LoginID", LoginID, { expires: 999 });
      $.cookie("LoginPass", LoginPass, { expires: 999 });
    } else {
      $.cookie("LoginID", $("#loginID").val(), { expires: -1 });
      $.cookie("LoginPass", $("#loginPass").val(), { expires: -1 });
    }
    _DoLogin(LoginID, LoginPass);
  } else {
    alert("ログインIDとパスワードを入力してください。");
  }
});
$(".loginInp").keypress(function (e) {
  if (e.which == 13) {
    $("#LoginBtn").click();
  }
});

//******************************************************//
//	function
//******************************************************///
function _DoLogin(LoginID, Password) {
  var fData = {
    fID: "doLogin",
    LID: encodeURIComponent(LoginID), //ログインID
    Pass: encodeURIComponent(Password), //パスワード
  };

  $.ajax({
    url: "controller/doLogin.php",
    type: "POST",
    dataType: "json",
    data: fData,
    timeout: AJAXTIMEOUT,
  })
    .then(
      function (data) {
        console.log(data);
        if (parseInt(data._doLogin.data[0].sts, 10) == 1) {
          location.href = JumpURL;
        } else {
          alert("有効なIDまたはパスワードを入力してください。");
        }
      },
      function () {
        swal({
          type: "error",
          title: "エラー",
          html:
            "予期せぬエラーが発生しました。ページを更新して再度お試し下さい。",
        });
      }
    )
    .always(function (data) {
      Loading(2);
    });
}
