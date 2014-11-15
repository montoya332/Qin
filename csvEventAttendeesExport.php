<?php
require_once("common.php");

header('Content-Type: application/excel');
//header('Content-Disposition: attachment; filename="sample.csv"');

$event_id = filterNumberString($_GET['event_id']);
//$event_id = 4;

openDatabase();

$eventNameResult = mysql_query("SELECT event_name from events where event_id = " . $event_id . ";");
$eventNameArray = mysql_fetch_array($eventNameResult, MYSQL_ASSOC);


$eventName = urldecode($eventNameArray['event_name']);

$result = mysql_query("SELECT sjsu_id, first_name, last_name, is_checked_in From qr_codes Where event_id = " . $event_id .";");
$result_array = array();



header('Content-Disposition: attachment; filename="'.$eventName.'.csv"');


$out = "";

for($i = 0; $i < mysql_num_fields($result); $i++) {
    $field_info = mysql_fetch_field($result, $i);
    $out .= $field_info->name.",";
}

$out .= "\r\n";

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	//$result_array[] = $row;
	$out .= implode(",", $row) . "\r\n";
}
//print_r($result_array)
echo $out;

closeDatabase();

?>
