<?php
include '../../wp-load.php';

// POST値
$registData = array();
$returnData = array();
if($_POST["mode"] == "insert"){
  $registData["name"] = $_POST["name"];
  $registData["cost"] = $_POST["cost"];
  
  $query = "INSERT INTO ";
  $query .= $wpdb->prefix ."tabacco_mst "; 
  $query .= "(name, cost) ";
  $query .= "values( ";
  $query .= "'" . $registData["name"] ."',";
  $query .= $registData["cost"];
  $query .=")";
}else if($_POST["mode"] == "update"){
  $registData["id"] = $_POST["id"];
  $registData["name"] = $_POST["name"];
  $registData["cost"] = $_POST["cost"];

  $query = "UPDATE ";
  $query .= $wpdb->prefix ."tabacco_mst ";
  $query .= "SET "; 
  $query .= "name ='" . $registData["name"] ."',";
  $query .= "cost = " . $registData["cost"];
  $query .=" WHERE ";
  $query .="id = " . $registData["id"];
}else if($_POST["mode"] == "delete"){

  $registData["id"] = $_POST["id"];
  $query = "UPDATE ";
  $query .= $wpdb->prefix ."tabacco_mst "; 
  $query .= "SET "; 
  $query .= "delete_flg = 1 ";
  $query .="WHERE ";
  $query .="id = " . $registData["id"];

}

// wpdbオブジェクト
global $wpdb;

$query = $wpdb->prepare($query);
$wpdb->query($query);

$returnData = json_encode($registData);
echo $returnData;
?>