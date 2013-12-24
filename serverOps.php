<?php

include 'global.php';

function &server_connect()
{
# connect to MySQL server
	global $host, $user, $pass, $database;
	$connection = mysql_connect($host, $user, $pass);
	if (!$connection) {
	    die('Could not connect: ' . mysql_error());
	}
	# select the database
	mysql_select_db($database, $connection);
	
	return $connection;	
}

function totalCalories()
{
	$connection =& server_connect();
	$command = "Select sum(Calories) from Calories order by timeofadd";
	$result = mysql_query($command, $connection);
	$row = mysql_fetch_array($result);

	return $row[0];

}


?>
