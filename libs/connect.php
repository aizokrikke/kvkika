<?php
$base=dirname(dirname(__FILE__));
include_once($base.'/config.php');

function connect($db_server,$db_username,$db_password, $db_name) {

	$db = mysql_connect($db_server,$db_username,$db_password) or
    	die ("Could not connect to MySQL server");
            mysql_select_db ($db_name, $db) or
        die ("Could not select database");
            
		return $db;
}

$db = connect($db_server,$db_username,$db_password, $db_name);
?>