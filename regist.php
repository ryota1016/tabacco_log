<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(function () {
  $('#closeModal , #modalBg').click(function(){
    $('#modalArea').fadeOut();
  });
});
function regist(){
  var param = {};
  param["name"] = document.getElementById("tabacco_name").value;
  param["cost"] = document.getElementById("tabacco_cost").value;
  param["mode"] = "insert";

  $.ajax({
    url: "db/db_regist.php",
    type: "POST",
    dataType:"json",
    data: param,
    async: false
  }).done(function (r_data) {
    var result_data = r_data;
   // var result_data = JSON.parse(r_data);
    
    var str = "";
    str = "以下の情報を登録しました。\n";
    str = str + "銘柄名：" + result_data["name"] + "\n";
    str = str + "値段：" + result_data["cost"];
    alert(str);
    window.location.reload();
  }).fail(function (jqXHR, textStatus, errorThrown) {
    alert(errorThrown);
  });
}
function deleteTabacco(id){
  if (confirm('削除してよろしいですか？')) {
  } else {
      return;
  }
  var param = {};
  param["id"] = id;
  param["mode"] = "delete";
  $.ajax({
    url: "db/db_regist.php",
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
function updateModalView(id){
  var name = document.getElementById("name" + id).value;
  var cost = document.getElementById("cost" + id).value;
  document.getElementById("update_name").value = name;
  document.getElementById("update_cost").value = cost;
  document.getElementById("update_id").value = id;
  $('#modalArea').fadeIn();
}
function updateTabacco(id){
  var param = {};
  param["id"] = document.getElementById("update_id").value;
  param["name"] = document.getElementById("update_name").value;
  param["cost"] = document.getElementById("update_cost").value;
  param["mode"] = "update";

  $.ajax({
    url: "db/db_regist.php",
    type: "POST",
    dataType:"json",
    data: param,
    async: false
  }).done(function (r_data) {
    var result_data = r_data;
    
    var str = "";
    str = "以下の情報を登録しました。\n";
    str = str + "銘柄名：" + result_data["name"] + "\n";
    str = str + "値段：" + result_data["cost"];
    alert(str);
    window.location.reload();
  }).fail(function (jqXHR, textStatus, errorThrown) {
    alert(errorThrown);
  });
}
// 値段の自動計算
function calcCost(){
  var number = document.getElementById("one_box_number").value;
  var cost = document.getElementById("one_box_cost").value;
  if(number == "" || cost == ""){
    alert("値段と本数を入力してください。");
    return;
  }else if(number <= 0 || cost <= 0){
    alert("1以上の値を入力してください。");
    return;
  }
  var result = Math.ceil(cost / number);
  document.getElementById("tabacco_cost").value = result;
}
function calcUpdateCost(){
  var number = document.getElementById("update_one_box_number").value;
  var cost = document.getElementById("update_one_box_cost").value;
  if(number == "" || cost == ""){
    alert("値段と本数を入力してください。");
    return;
  }else if(number <= 0 || cost <= 0){
    alert("1以上の値を入力してください。");
    return;
  }
  var result = Math.ceil(cost / number);
  document.getElementById("update_cost").value = result;
}
</script>

<html>
<title>たばこ管理サイト-登録ページ</title>

<h1><a href="index.php">たばこ管理サイト</a></h1>
<h2>たばこ登録ページ</h2>
<div style="text-align:right"><a href="index.php">トップページに戻る</a></div>
銘柄名<input type="text" id="tabacco_name"></input><br>
1本あたりの値段<input type="text" id="tabacco_cost" value="28"></input>円<br>
<input type="button" value="値段の自動計算" onclick="calcCost()"> 1箱の値段<input type="text" id="one_box_cost"> 1箱の本数<input type="text" id="one_box_number" value="20">
<br>
<input type="button" value="登録" onclick="regist();"></input>
<h2>登録済みのたばこ</h2>

<!-- モーダルエリアここから -->
<style>
.modalArea {
  display: none;
  position: fixed;
  z-index: 10; /*サイトによってここの数値は調整 */
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}
.modalBg {
  width: 100%;
  height: 100%;
  background-color: rgba(30,30,30,0.9);
}
.modalWrapper {
  position: absolute;
  top: 50%;
  left: 50%;
  transform:translate(-50%,-50%);
  width: 70%;
  max-width: 500px;
  padding: 10px 30px;
  background-color: #fff;
}

.closeModal {
  position: absolute;
  top: 0.5rem;
  right: 1rem;
  cursor: pointer;
}
</style>
<section id="modalArea" class="modalArea">
  <div id="modalBg" class="modalBg"></div>
  <div class="modalWrapper">
    <div class="modalContents">
      <h1>修正内容入力</h1>
      銘柄名<input type="text" id="update_name"></input><br>
      1本あたりの値段<input type="text" id="update_cost"></input>円<br>
      <input type="button" value="値段の自動計算" onclick="calcUpdateCost()">
      <br>1箱の値段<input type="text" id="update_one_box_cost">
      <br>1箱の本数<input type="text" id="update_one_box_number" value="20">
      <input type="hidden" id="update_id"></input>
      <input type="button" value="登録" onclick="updateTabacco();"></input>
    </div>
    <div id="closeModal" class="closeModal">
      ×
    </div>
  </div>
</section>
<!-- モーダルエリアここまで -->

<?php
include '../wp-load.php';

// wpdbオブジェクト
global $wpdb;
$tabacco_id = array();
$tabacco_name = array();
$tabacco_cost = array();

$query = "SELECT * FROM " . $wpdb->prefix . "tabacco_mst WHERE delete_flg = 0 ";
$query .= "AND user_id =". $_COOKIE["USER_ID"];

$rows = $wpdb->get_results($query);
if($rows){
    foreach ($rows as $row) {
      $tabacco_id[] = $row->ID;
      $tabacco_name[] = $row->name;
      $tabacco_cost[] = $row->cost;
      echo "<table style=\"border-spacing: 8px 0px;\"><tr><td>";
      echo "<table>";
      echo "<tr><td>ID</td><td>".$row->ID."</td></tr>";
      echo "<tr><td>名前</td><td>".$row->name."</td></tr>";
      echo "<tr><td>値段</td><td>".$row->cost."</td></tr>";
      echo "</table>";
      echo "</td>";
      echo "<td><input type=\"button\" value=\"修正\" onclick=\"updateModalView(" . $row->ID .")\"></input></td>";
      echo "<td><input type=\"button\" value=\"削除\" onclick=\"deleteTabacco(" . $row->ID .")\"></input></td>";
      echo "</tr></table><br>";
      echo "<input type=\"hidden\" id = \"id".$row->ID."\" value=\"".$row->ID."\">";
      echo "<input type=\"hidden\" id = \"name".$row->ID."\" value=\"".$row->name."\">";
      echo "<input type=\"hidden\" id = \"cost".$row->ID."\" value=\"".$row->cost."\">";
    }
}
?>

</html>

