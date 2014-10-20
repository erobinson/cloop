<?php

$con=mysqli_connect("localhost","root","raspberry","cloop");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


$hbBody = file_get_contents('./index-body.hb');

# get current mode
$mode_sql = "select is_on from automode_switch order by datetime_recorded desc limit 1";
$result = mysqli_query($con, $mode_sql);
$mode_array = mysqli_fetch_array($result);
$mode = $mode_array['is_on'];


# get last alert
$last_action_sql = "select title, message, datetime_to_alert, type from alerts 
	where datetime_dismissed is null and
	datetime_to_alert = (select max(datetime_to_alert) from alerts where datetime_to_alert is not null) and
	datetime_to_alert > now() - interval 12 hour";
$result = mysqli_query($con, $last_action_sql);
$last_action = mysqli_fetch_array($result);
$last_action_html = "{is_action: false}";
if(mysqli_num_rows($result) > 0) {
	$last_action_html = "{is_action: true, ";
	$color = 'white';
	if($last_action['type'] != 'info') {
		$color = 'red';
	}
	$last_action_html .= "color: '$color', ";
	$last_action_html .= "datetime_to_alert: '".$last_action['datetime_to_alert']."', ";
	$last_action_html .= "title: '".$last_action['title']."', ";
	$last_action_html .= "message: '".$last_action['message']."'}";
}

# get current bg
$cur_bg_sql = "select datetime_recorded, sgv from sgvs order by datetime_recorded desc limit 1";
$result = mysqli_query($con, $cur_bg_sql);
$cur_bg = mysqli_fetch_array($result);

$color = 'green';
date_default_timezone_set('America/New_York');
if(strtotime($cur_bg['datetime_recorded']) < time() - 25 * 60 ) {
	$color = 'grey';
} elseif($cur_bg['sgv'] > 160) {
	$color = 'orange';
} elseif($cur_bg['sgv'] < 90) {
	$color = 'red';
}

$values = "{curBG: {color: '".$color."', value: '".$cur_bg['sgv']."', time: '".$cur_bg['datetime_recorded']."'},
	    action: ".$last_action_html.", mode: '".$mode."'}";
#$values = json_encode(array('item' => $post_data), JSON_FORCE_OBJECT);
#echo $values;

mysqli_close($con);

$html = "<html><head>";
$html .= "<meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1'>";
$html .= "<script src='handlebars-v2.0.0.js'></script>";
$html .= "<script src='loadTemplates.js'></script>";
$html .= "<script src='jquery-1.11.1.min.js'></script>";
$html .= $hbBody."</head><body bgcolor='black'>";
$html .= "<div id='page_content'></div>";
$html .= "<script>";
$html .= "  loadTemplate('mainTemplate');";
$html .= "  var ctx = ".$values.";";
$html .= "  var html = HandleTmpls.mainTemplate(ctx);";
$html .= "  document.getElementById('page_content').innerHTML = html;";
$html .= "  setTimeout('location.reload()', 10000)";
$html .= "</script></body></html>";

echo $html;

?>
