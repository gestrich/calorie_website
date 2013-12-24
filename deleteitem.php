<?php
include 'global.php';
include 'serverOps.php';

//get the q parameter from URL
$q = htmlspecialchars($_POST["q"]);
$qlen = strlen($q);
       $connection = mysql_connect($host, $user, $pass);
        mysql_select_db($database, $connection);
        $command = "Delete from Calories where foodIndex = $q";
        $res = mysql_query($command, $connection) or die(mysql_error());


        # close the connection

echo totalCalories();
?>
