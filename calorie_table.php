

<?php

include 'global.php';
include 'serverOps.php';
$connection =& server_connect();
$command = "Select * from Calories order by timeofadd";
$result = mysql_query($command, $connection);
$rows = array();
while($r = mysql_fetch_assoc($result)) {
    $rows[] = $r;
}
print json_encode($rows);

?>
