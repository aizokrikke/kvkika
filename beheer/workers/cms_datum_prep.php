<?php
header("Content-Type: text/html; charset=utf-8\n");
setlocale(LC_ALL,"nl_NL");
require('../../libs/connect.php');
require('../../logon/check_logon.php');

$id=$_REQUEST['id'];

if ($id=='nieuws_nieuw')
	{ $r[0]=time();}
	else
	{ $r=mysql_fetch_row(mysql_query("select datum from dev_nieuws where id='$id' and verwijderd!='j'")) or die(mysql_error()); }



include('cms_datum.php');
?>