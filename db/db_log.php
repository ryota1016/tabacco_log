<?php
include '../../wp-load.php';

// wpdbオブジェクト
global $wpdb;
//$date = date_i18n("Y/m/d");
$date = $_POST["date"];
$query = "SELECT " .$wpdb->prefix ."tabacco_log.id log_id,";
$query .= $wpdb->prefix ."tabacco_log.time log_time,";
$query .= $wpdb->prefix ."tabacco_log.number log_number,";
$query .= $wpdb->prefix."tabacco_mst.name tabacco_name, ";
$query .= $wpdb->prefix."tabacco_mst.cost tabacco_cost ";
$query .= " FROM " . $wpdb->prefix . "tabacco_log ";
$query .= " JOIN " . $wpdb->prefix . "tabacco_mst " . "ON ";
$query .= $wpdb->prefix ."tabacco_log.tabacco_id = ". $wpdb->prefix ."tabacco_mst.id ";
$query .= " WHERE " .$wpdb->prefix ."tabacco_log.date = DATE('" .$date . "')";
$query .= " ORDER BY time";
$rows = $wpdb->get_results($query);
$number = 0;
$cost = 0;
foreach ($rows as $row) {
	$number += $row->log_number;
	$cost += $row->log_number * $row->tabacco_cost;
}

$returnArr = array();
$returnArr["number"] = $number;
$returnArr["cost"] = $cost;

// 喫煙履歴
$logStr = "";
$logStr .= "<table>";
foreach ($rows as $row) {
	$logStr .= "<tr>";
	$logStr .= "<td>" . date('H:i', strtotime($row->log_time)) . "</td> ";
	$logStr .= "<td>" . $row->tabacco_name . "</td> ";
	$logStr .= "<td>";
	if($row->log_number >= 2){
		$logStr .=  $row->log_number . "本";
	}
	$logStr .= "</td>";
	$logStr .= "<td><input type=\"button\" value=\"削除\" id=\"log_delete_button\" onclick=\"deleteLog(". $row->log_id .")\"></td>";
}
$logStr .= "</tr></table>";

$returnArr["log"] = $logStr;
echo json_encode($returnArr);
?>
