<?php
	$host = 'localhost';
	$user = 'root';
	$password = 'mysql';
	$database = 'dhvanyaloka';
	$db = new mysqli("$host","$user","$password", "$database");
	mysqli_set_charset($db,"utf8");
	
	if($db->connect_errno > 0)
	{
		echo 'Not connected to the database [' . $db->connect_errno . ']';
		exit(1);
	}
	
?>
