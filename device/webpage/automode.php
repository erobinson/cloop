<?php

$con=mysqli_connect("localhost","root","raspberry","cloop");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$is_on = $_POST['mode'];
#$is_on = 'off';

# set mode
$is_on_sql = "insert into automode_switch (automode_switch_id, is_on, datetime_recorded) ";
$is_on_sql .= "select max(automode_switch_id)+1 as automode_switch_id, '$is_on' as is_on, ";
$is_on_sql .= "now() as datetime_recorded from automode_switch";
mysqli_query($con, $is_on_sql);

echo "<html><body><h1>Set automode to be ".$is_on.". Redirecting in 10 seconds...</h1>";
echo "<script>setTimeout(\"window.location='/diabetes'\", 10000);</script></body></html>";

?>
