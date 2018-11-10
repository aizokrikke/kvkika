<?php
header("Content-Type: text/html; charset=utf-8\n");
setlocale(LC_ALL,"nl_NL");

require('../../libs/connect.php');
require('../../logon/check_logon.php');

$id=mysql_real_escape_string($_REQUEST['id']);
$veld=mysql_real_escape_string($_REQUEST['veld']);



if ($id=='nieuws_nieuw')
	{ $r[0]=''; }
	else
	{ 
		$res=mysql_query("select $veld from dev_nieuws where id='$id' and verwijderd!='j'") or die(mysl_error());
		$r=mysql_fetch_row($res) or die(mysql_error()); 
	}

//echo "id: $id | veld: $veld | r[0]: $r[0]<br>";


include('cms_txt_editor.php');
?>