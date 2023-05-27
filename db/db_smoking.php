<?php
include '../../wp-load.php';

// POST値
$registData = array();
$returnData = array();
if($_POST["mode"] == "addSmoking"){
  $registData["id"] = $_POST["id"];
  
  $query = "INSERT INTO ";
  $query .= $wpdb->prefix ."tabacco_log "; 
  $query .= "(tabacco_id, date, time) ";
  $query .= "values( ";
  $query .= "'" . $registData["id"] ."',";
  $query .= "sysdate(),";
  $query .= "curtime()";
  $query .=")";
}else if($_POST["mode"] == "addSmokingSelectDate"){
  $registData["id"] = $_POST["id"];
  $registData["date"] = $_POST["date"];
  $registData["time"] = $_POST["time"];
  $registData["number"] = $_POST["number"];

  $query = "INSERT INTO ";
  $query .= $wpdb->prefix ."tabacco_log "; 
  $query .= "(tabacco_id, date, time, number) ";
  $query .= "values( ";
  $query .= "'" . $registData["id"] ."',";
  $query .= "'" . $registData["date"] . "',";
  $query .= "'" . $registData["time"] . "',";
  $query .= $registData["number"];
  $query .=")";
}else if($_POST["mode"] == "delete"){
	$registData["id"] = $_POST["id"];
	$query = "DELETE FROM ";
	$query .= $wpdb->prefix ."tabacco_log "; 
	$query .= " WHERE ";
	$query .= " id =" . $registData["id"];
  }

// wpdbオブジェクト
global $wpdb;

$query = $wpdb->prepare($query);
$wpdb->query($query);

$returnData = json_encode($registData);
echo $returnData;
?>