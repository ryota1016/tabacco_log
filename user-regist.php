<html>
<title>たばこ管理サイト</title>
<h1 style="text-align:center;">たばこ管理サイト 新規登録画面</h1>
<?php
include '../wp-load.php';

// wpdbオブジェクト
global $wpdb;
?>
<div id="main_contents">
<div id="error_message">
そのメールアドレスは既に登録されています。
</div>
<div>
名前:
<input type="text" id="name"></input>
<br>
メールアドレス:
<input type="text" id="mail"></input>
<br>
パスワード:
<input type="password" id="password1"></input>
<br>
パスワード(再確認):
<input type="password" id="password2"></input>
<br>
<input type="button" onclick="regist();">
</div>
<div>
<a href="login.php">ログイン画面へ</a>
</div>
<?php

?>
<style type="text/css">
#error_message{
	visibility:hidden;
}
</style>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</html>


<script>
function regist(){
	if(!document.getElementById("name") || document.getElementById("name").value == ""){
		alert("名前を入力してください");
		return;
	}
	if(!document.getElementById("mail") || document.getElementById("mail").value == ""){
		alert("メールアドレスを入力してください");
		return;
	}
	if(!document.getElementById("password1") || document.getElementById("password1").value == ""){
		alert("パスワードを入力してください");
		return;
	}
	if(!document.getElementById("password2") || document.getElementById("password2").value == ""){
		alert("パスワード(再確認)を入力してください");
		return;
	}
	name = document.getElementById("name").value;
	password1 = document.getElementById("password1").value;
	password2 = document.getElementById("password2").value;
	mail = document.getElementById("mail").value;
	if(password1 != password2){
		alert("確認用パスワードが一致していません。");
		return;
	}
    var param = {};
	param["name"] = name;
	param["mail"] = mail;
	param["password"] = password1;
	param["command"] = "regist";

	$.ajax({
		url: "db/db_user.php",
		type: "POST",
		dataType:"json",
		data: param,
		async: false
	}).done(function (r_data) {
		if(r_data["result"]){
			window.location = "login.php";
			console.log("うえ");
			alert("会員登録が完了しました。");
		}else{
			document.getElementById("error_message").style.visibility = "visible";
		}
	}).fail(function (jqXHR, textStatus, errorThrown) {
		console.log(jqXHR);
		console.log(textStatus);
		console.log(errorThrown);
	});
}

</script>