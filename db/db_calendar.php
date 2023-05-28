<?php
include '../../wp-load.php';

$year = $_POST["year"];
$month = $_POST["month"];

//喫煙本数取得部分
$query = "SELECT DATE_FORMAT(date, '%e') smoking_day, SUM(number) sum_number, SUM(cost) sum_cost FROM ";
$query .= $wpdb->prefix ."tabacco_log "; 
$query .=" INNER JOIN " . $wpdb->prefix . "tabacco_mst ON " . $wpdb->prefix ."tabacco_log.tabacco_id = " . $wpdb->prefix . "tabacco_mst.id ";
$query .= " WHERE DATE_FORMAT(date, '%Y') =". $year;
$query .= " AND DATE_FORMAT(date, '%c') =". $month;
$query .= " AND " .$wpdb->prefix ."tabacco_log.user_id =" .$_COOKIE["USER_ID"];
$query .= " GROUP BY DATE_FORMAT(date, '%d')";
$logRows = $wpdb->get_results($query);
$logArray = array();
$monthNumber = 0;
$monthCost = 0;
if($logRows){
    foreach ($logRows as $row) {
		$logArray[$row->smoking_day] = $row->sum_number;
        $monthNumber += $row->sum_number;
        $monthCost += $row->sum_cost;
	}
}
//カレンダー取得部分  
// 月末日を取得
$last_day = date_i18n('j', mktime(0, 0, 0, $month + 1, 0, $year));
$calendar = array();
$j = 0;
// 月末日までループ
for ($i = 1; $i < $last_day + 1; $i++) {
    // 曜日を取得
    $week = date_i18n('w', mktime(0, 0, 0, $month, $i, $year));
    // 1日の場合
    if ($i == 1) {
        // 1日目の曜日までをループ
        for ($s = 1; $s <= $week; $s++) {
            // 前半に空文字をセット
            $calendar[$j]['day'] = '';
            $j++;
        }
    }
    // 配列に日付をセット
    $calendar[$j]['day'] = $i;
    $j++;
    // 月末日の場合
    if ($i == $last_day) {
        // 月末日から残りをループ
        for ($e = 1; $e <= 6 - $week; $e++) {
            // 後半に空文字をセット
            $calendar[$j]['day'] = '';
            $j++;
        }
    }
}

$returnStr = "";
$returnStr .= "<br> ";
$returnStr .= "<br> ";
$returnStr .= "<table id=\"calendarCaptionTable\">";
$returnStr .= " <tr>";
$returnStr .= "  <td width=\"20%\">";
$returnStr .= "   <a href=\"javascript:void(0)\" onclick=\"lastMonth();\"><<前月へ</a>";
$returnStr .= "  </td>";
$returnStr .= "  <td width=\"60%\">";
$returnStr .=     $year . "年 " .$month."月	";
$returnStr .= "  </td>";
$returnStr .= "  <td width=\"20%\">";
$returnStr .= "   <a href=\"javascript:void(0)\" onclick=\"nextMonth();\">次月へ>></a>";
$returnStr .= "  </td>";
$returnStr .= " </tr>";
$returnStr .= "</table>";
$returnStr .= "<table id=\"tabaccoCalendarTable\"> ";
$returnStr .= "    <tr> ";
$returnStr .= "        <th>日</th> ";
$returnStr .= "        <th>月</th> ";
$returnStr .= "        <th>火</th> ";
$returnStr .= "        <th>水</th> ";
$returnStr .= "        <th>木</th> ";
$returnStr .= "        <th>金</th> ";
$returnStr .= "        <th>土</th> ";
$returnStr .= "    </tr>  ";
$returnStr .= "    <tr> ";
$cnt = 0;
foreach ($calendar as $key => $value):
    $scriptStr = "";
    if($value['day'] != ""){
	    $scriptStr = "onclick=\"getLogSelectDay(" . $value['day'] .")\"";
    }
    $returnStr .= "        <td ".$scriptStr."> ";
    $cnt++;
    $returnStr .= $value['day'];
    $returnStr .= "<div id=\"day_tabacco_number\">" . $logArray[$value['day']] . "</div>";
    $returnStr .= "        </td> ";
    if ($cnt == 7):
	    $returnStr .= "    </tr> ";
	    $returnStr .= "    <tr> ";
	    $cnt = 0;
    endif;
endforeach;
$returnStr .= "    </tr> ";
$returnStr .= "</table> ";
$returnStr .= "<input type=\"hidden\" id=\"select_year\" value=\"".$year."\">";
$returnStr .= "<input type=\"hidden\" id=\"select_month\" value=\"".$month."\">";

$result = array();
$result["calendarStr"] = $returnStr;

$returnStr = "";
$returnStr .= "<table id=\"monthTotalTable\">";
$returnStr .= "<tr><td id=\"monthTotalHead\">";
$returnStr .= $year . "年 " .$month."月の合計喫煙本数";
$returnStr .= "</td></tr>";
$returnStr .= "<tr><td>";
$returnStr .= $monthNumber . "本";
$returnStr .= "</td></tr>";
$returnStr .= "<tr><td>";
$returnStr .= $monthCost . "円";
$returnStr .= "</td></tr>"; 
$returnStr .= "</table>";
$result["monthStr"] = $returnStr;

echo json_encode($result);
?>