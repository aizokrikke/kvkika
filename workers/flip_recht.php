<?php
header("Content-Type: text/html; charset=utf-8\n"); 
require('../libs/connect.php');

$user = $_REQUEST['user'];
$recht = $_REQUEST['recht'];

if ($row = db_row("select id, verwijderd from rechten where user='" . db_esc($user) . "' and recht='" . db_esc($recht) . "'")) {
	  $verwijderd = $row[1];
	  if ($verwijderd == 'j') {
	      $verwijderd='n';
	  } else {
	      $verwijderd='j';
	  }
	  db_query("update rechten set verwijderd='$verwijderd' where id='$row[0]'");
  }
  else
  { // recht was nog niet bekend
	db_query("insert into rechten (user,recht,verwijderd) values ('" . db_esc($user) . "','" . db_esc($recht) . "','n')");
  }
include('beheerderslijst.php');
?>