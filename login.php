<html>
<title>たばこ管理サイト</title>
<h1 style="text-align:center;">たばこ管理サイト</h1>
<?php
include '../wp-load.php';

// wpdbオブジェクト
global $wpdb;
?>
<div id="main_contents">
<div id="error_message">
IDまたはパスワードが間違っています。
</div>
<div>
メールアドレス:
<input type="text" id="mail"></input>
<br>
パスワード:
<input type="password" id="password"></input>
<br>
<input type="button" onclick="loginCheck();" value="ログイン">
</div>
<div>
<a href="user-regist.php">新規登録</a>
</div>
<?php

?>
<style type="text/css">
#smoking_button{
	width:20%;
	height: 10%;
	font-size: 2em;
}
#error_message{
	visibility:hidden;
}
</style>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</html>


<script>
function loginCheck(){
    var param = {};
	param["mail"] = document.getElementById("mail").value;
	param["password"] = document.getElementById("password").value;
	param["command"] = "login";

	$.ajax({
		url: "db/db_user.php",
		type: "POST",
		dataType:"json",
		data: param,
		async: false
	}).done(function (r_data) {
		if(r_data["result"]){
			window.location = "index.php";
			console.log("うえ");
		}else{
			document.getElementById("error_message").style.visibility = "visible";
			console.log("した");
		}
	}).fail(function (jqXHR, textStatus, errorThrown) {
		console.log(jqXHR);
		console.log(textStatus);
		console.log(errorThrown);
	});
}

</script>