<?php
header("Content-Type: text/html; charset=utf-8\n"); 
echo "FLIP!";
require('../libs/connect.php');

$user = $_REQUEST['user'];
$recht = $_REQUEST['recht'];

echo "$user|$recht";

if ($row = db_row("select id, verwijderd from rechten where user='$user' and recht='$recht'")) {
	  $verwijderd = $row[1];
	  if ($verwijderd == 'j') {
	      $verwijderd='n';
	  } else {
	      $verwijderd='j';
	  }
	  db_query("update rechten set verwijderd='$verwijderd' where id='$row[0]'");
  } else {
    // recht was nog niet bekend
	db_query("insert into rechten (user,recht,verwijderd) values ('$user','$recht','n')");
  }

include('beheerderslijst.php');
?>