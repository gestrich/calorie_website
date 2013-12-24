<?php
include 'global.php';
include 'serverOps.php';

	$key = htmlspecialchars($_POST["key"]);
	$calories = htmlspecialchars($_POST["calories"]);
        $qlen = strlen($q);

        $connection = mysql_connect($host, $user, $pass);
        mysql_select_db($database, $connection);
        $command = "INSERT INTO Calories (Food, Calories) VALUES( '$key', '$calories' )"; 
        $res = mysql_query($command, $connection) or die(mysql_error());

        $command = "Select foodIndex, timeofadd from Calories ORDER BY timeofadd DESC LIMIT 1";
        $res = mysql_query($command, $connection) or die(mysql_error());
        $row = mysql_fetch_array($res);

	//output the response
	echo totalCalories() . "," . $row[0];
?>
