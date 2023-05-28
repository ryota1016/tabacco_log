<?php
include '../../wp-load.php';

$returnArr = array();
if($_POST["command"] == "login"){
/*
	$pas = password_hash("0000", PASSWORD_DEFAULT);
	$query = "UPDATE tabacco_user SET PASSWORD = '". $pas ."'";
	$query = $wpdb->prepare($query);
	$wpdb->query($query);
*/

	//$date = date_i18n("Y/m/d");
	$mail = $_POST["mail"];
	$query = "SELECT tabacco_user.id user_id,";
	$query .= "tabacco_user.password password, ";
	$query .= "tabacco_user.name user_name ";
	$query .= " FROM tabacco_user ";
	$query .= " WHERE tabacco_user.mail = '" .$mail . "'";
	$rows = $wpdb->get_results($query);

	$password="";
	$result=false;
	if($rows) {
		$password = $rows[0]->password;
		$result = password_verify($_POST["password"], $password);
	}

	$returnArr["result"] = $result;
	if($result){
		$returnArr["name"] = $rows[0]->user_name;
		$returnArr["id"] = $rows[0]->user_id;
		setcookie("USER_ID",$rows[0]->user_id,time()+60*60*24*30,"/");
		setcookie("USER_NAME",$rows[0]->user_name,time()+60*60*24*30,"/");
	}
}else if($_POST["command"] == "logout"){
	setcookie("USER_ID","",time()+60*60*24*30,"/");
	setcookie("USER_NAME","",time()+60*60*24*30,"/");
	$returnArr["result"] = true;
}else if($_POST["command"] == "regist"){
	$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
	$name = $_POST["name"];
	//メールアドレス存在確認
	$mail = $_POST["mail"];
	$query = "SELECT tabacco_user.id user_id ";
	$query .= " FROM tabacco_user ";
	$query .= " WHERE tabacco_user.mail = " .$mail;
	$rows = $wpdb->get_results($query);

	$result = true;
	if($rows) {
		$result = false;
	}
	
	//登録処理
	if(result){
		$query = "INSERT INTO tabacco_user(password, mail, name) values( '";
		$query .= $password ."','". $mail ."','" .  $name ."')";
		$query = $wpdb->prepare($query);
		$wpdb->query($query);
	}

	$returnArr["result"] = result;
}

echo json_encode($returnArr);
?>
