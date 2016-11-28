<?php 
	require_once ('database.php');
	// Access config file
	$db_config = parse_ini_file('cms_config.ini');
	$host = $db_config['host'];
	$username = $db_config['username'];
	$password = $db_config['password'];
	$dbname = $db_config['dbname'];
	// Create Connection Object
	$db_conn = new MysqliDb ($host, $username, $password, $dbname);
?>