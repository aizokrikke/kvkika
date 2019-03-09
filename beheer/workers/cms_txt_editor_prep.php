<?php
header("Content-Type: text/html; charset=utf-8\n");
setlocale(LC_ALL,"nl_NL");

require('../../libs/connect.php');
require('../../logon/check_logon.php');

$id = db_row($_REQUEST['id']);
$veld = db_row($_REQUEST['veld']);

if ($id == 'nieuws_nieuw') {
    $r[0]='';
} else {
    $r=db_row("select $veld from dev_nieuws where id='$id' and verwijderd!='j'");
}

include('cms_txt_editor.php');
?>