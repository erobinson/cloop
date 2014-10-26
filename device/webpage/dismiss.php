<?php

$con=mysqli_connect("localhost","root","raspberry","cloop");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}



# get current mode
$dismiss_sql = "update alerts set datetime_dismissed = now(), src_dismissed = 'webpage' where datetime_dismissed is null";
$result = mysqli_query($con, $dismiss_sql);

echo "<html><body><h1>Dismissed all alerts. Redirecting in 10 seconds...</h1>";
echo "<script>setTimeout(\"window.location='/diabetes'\", 10000);</script></body></html>";

?>
