<html>
<title>たばこ管理サイト</title>
<h1 style="text-align:center;">たばこ管理サイト</h1>
<div style="text-align:right;"><a href="regist.php">たばこ銘柄登録画面</a></div>
<?php
include '../wp-load.php';

// wpdbオブジェクト
global $wpdb;
?>
<div id="main_contents">
<div>
<h3>本日の喫煙本数</h3>
<div id="today_sum_number"></div>
</div>
<?php
// たばこの銘柄セレクトボックス
$tabacco_id = array();
$tabacco_name = array();
$tabacco_cost = array();
$query = "SELECT * FROM " . $wpdb->prefix . "tabacco_mst WHERE delete_flg = 0 ";
$tabaccoRows = $wpdb->get_results($query);
if($tabaccoRows){
	echo "<select id=\"tabacco_select\">";
    foreach ($tabaccoRows as $row) {
		echo "<option value=\"" .$row->ID. "\">".$row->name."</option>";
	}
	echo "</select>";
}
?>
<style type="text/css">
#smoking_button{
	width:20%;
	height: 10%;
	font-size: 2em;
}
#tabacco_select{
	height: 10%;
	font-size: 1.5em;
	padding: 5px 10px;
}
#today_tabacco_log{
	margin-top: 30px;
	text-align:center;
}
#today_tabacco_log table{
	margin: auto;
	border-collapse: separate;
  	border-spacing: 10px;
}
#select_day_tabacco_log table{
	margin: auto;
	border-collapse: separate;
  	border-spacing: 10px;
}
#main_contents{
	text-align:center;
}
#today_sum_number{
	font-size: 2.5em;
	font-weight: bold;
	margin-bottom: 20px;
}
</style>
<input type="button" id="smoking_button" value="吸った" onclick="addSmoking();">

<div id="today_tabacco_log"></div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<div id="select_smoking_date_section">
<h3>時間を指定して記録</h3>
<?php
if($tabaccoRows){
	echo "<select id=\"select_date_id\">";
    foreach ($tabaccoRows as $row) {
		echo "<option value=\"" .$row->ID. "\">".$row->name."</option>";
	}
	echo "</select>";
}
?>
<input type="date" id="select_date">
<input type="time" id="select_time" value="00:00">
<input type="text" id="select_number" value="1" style="text-align:right;">本</input>
<br>
<input type="button" id="select_smoking_button" value="吸った" onclick="addSmokingSelectDate();">
</div>

<style type="text/css">
#select_date_id,#select_date,#select_time,#select_number{
	width: 18%;
	height: 50px;
	margin: 5px;
}
#select_smoking_button{
	width:20%;
	height: 5%;
	font-size: 1.5em;
}
#tabaccoCalendarTable{
    width: 100%;
	margin-bottom:20px;
}
#tabaccoCalendarTable th {
    background: #FFAD90;
}
#tabaccoCalendarTable th {
	border: 1px solid #CCCCCC;
    text-align: center;
    padding: 5px;
}
#tabaccoCalendarTable td {
    border: 1px solid #CCCCCC;
    text-align: left;
    padding: 5px;
	height: 80px;
	vertical-align: top;
}
#tabaccoCalendarTable td:hover{
    background: #66FFCC;
}
#calendarCaptionTable{
	border: 0px;
    text-align: center;
	width: 100%;
	margin-top: 20px;
}
#tabacco_calendar #day_tabacco_number{
    text-align: center;
	vertical-align: center;
}
#select_day_log{
	margin-bottom:50px;
}
#day_tabacco_number{
	font-weight:bold;
	font-size: 1.5em;
}
#monthTotalTable{
	border: 0px;
    text-align: center;
	width: 100%;
	margin-top: 20px;
	font-size: 1.5em;
}
#monthTotalHead{
	font-weight:bold;
}
</style>
<div id="tabacco_calendar"></div>

<div id="select_day_log">
	<div id = "select_day_head"></div>
	<div id = "select_day_number"></div>
	<div id = "select_day_cost"></div>
	<div id = "select_day_tabacco_log"></div>
</div>
<div id="month_total"></div>
</div>
</html>


<script>
(function(){
	var today = new Date();
    today.setDate(today.getDate());
    var yyyy = today.getFullYear();
    var mm = ("0"+(today.getMonth()+1)).slice(-2);
    var dd = ("0"+today.getDate()).slice(-2);
    document.getElementById("select_date").value=yyyy+'-'+mm+'-'+dd;
	getTodayLog();
    getTodayCalendar();
}());
function getTodayCalendar(){
    var today = new Date();
	var year = today.getFullYear();
	var month = today.getMonth()+1;
    getCalendar(year, month);
}
function getCalendar(year, month){
    var param = {};
	param["year"] = year;
	param["month"] = month;

	$.ajax({
		url: "db/db_calendar.php",
		type: "POST",
		dataType:"json",
		data: param,
		async: false
	}).done(function (r_data) {
		document.getElementById("tabacco_calendar").innerHTML = r_data.calendarStr;
		document.getElementById("month_total").innerHTML = r_data.monthStr;
	}).fail(function (jqXHR, textStatus, errorThrown) {
		alert(errorThrown);
	});
}
function addSmoking(){
	var param = {};
	param["id"] = document.getElementById("tabacco_select").value;
	param["mode"] = "addSmoking";

	$.ajax({
		url: "db/db_smoking.php",
		type: "POST",
		dataType:"json",
		data: param,
		async: false
	}).done(function (r_data) {
		window.location.reload();
	}).fail(function (jqXHR, textStatus, errorThrown) {
		alert(errorThrown);
	});
}
function addSmokingSelectDate(){
	var param = {};
	param["id"] = document.getElementById("select_date_id").value;
	param["number"] = document.getElementById("select_number").value;
    var date = document.getElementById("select_date").value;
    var time = document.getElementById("select_time").value;
	param["date"] = date;
	param["time"] = time;
 	param["mode"] = "addSmokingSelectDate";

	$.ajax({
		url: "db/db_smoking.php",
		type: "POST",
		dataType:"json",
		data: param,
		async: false
	}).done(function (r_data) {
        alert("時間を指定して吸った");
		window.location.reload();
	}).fail(function (jqXHR, textStatus, errorThrown) {
		alert(errorThrown);
	});
}
function initDate(){
	var target = document.getElementById("input_date");
	var nowDate = new Date();
	var year = nowDate.getFullYear();
    var month = nowDate.getMonth()+1;
    var date = nowDate.getDate();
	var hour = nowDate.getHours();
    var min = nowDate.getMinutes();

    target.value = year + "/" + month + "/" + date + " " + hour + ":" + min;
}
function getTodayLog(){
	var date = document.getElementById("select_date").value;
	var returnData = getTabaccoLog(date);
	document.getElementById("today_sum_number").innerHTML = returnData.number + "本";
	document.getElementById("today_tabacco_log").innerHTML = returnData.log;
}
function getLogSelectDay(day){
	var year = document.getElementById("select_year").value;
	var month = document.getElementById("select_month").value;
	var date = year + "-" + month + "-" + day;console.log(date);
	var returnData = getTabaccoLog(date);
	document.getElementById("select_day_head").innerHTML = year + "年" + month + "月" + day + "日の喫煙ログ";
	document.getElementById("select_day_number").innerHTML = "喫煙本数：" + returnData.number + "本";
	document.getElementById("select_day_cost").innerHTML = "合計金額：" + returnData.cost + "円";
	document.getElementById("select_day_tabacco_log").innerHTML = returnData.log;
}
function getTabaccoLog(date){
	var result;
	var param = {};
	param["date"] = date;

	$.ajax({
		url: "db/db_log.php",
		type: "POST",
		dataType:"json",
		data: param,
		async: false
	}).done(function (r_data) {
		result = r_data;
	}).fail(function (jqXHR, textStatus, errorThrown) {
		alert(errorThrown);
	});
	return result;
}
function deleteLog(id){
	if (confirm('削除してよろしいですか？')) {
	} else {
		return;
	}
	var param = {};
	param["id"] = id;
	param["mode"] = "delete";

	$.ajax({
		url: "db/db_smoking.php",
		type: "POST",
		dataType:"json",
		data: param,
		async: false
	}).done(function (r_data) {
		alert("削除しました。");
		window.location.reload();
	}).fail(function (jqXHR, textStatus, errorThrown) {
		alert(errorThrown);
	});
}
function lastMonth(){
	var year = document.getElementById("select_year").value;
	var month = document.getElementById("select_month").value - 1;
	if(month == 0){
		year = year - 1;
		month = 12;
	}
	getCalendar(year, month);
}
function nextMonth(){
	var year = document.getElementById("select_year").value;
	var month = +document.getElementById("select_month").value + 1;
	if(month == 13){
		year = +year + 1;
		month = 1;
	}
	getCalendar(year, month);
}
</script>