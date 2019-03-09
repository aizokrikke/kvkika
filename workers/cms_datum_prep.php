<?php
header("Content-Type: text/html; charset=utf-8\n");
setlocale(LC_ALL,"nl_NL");
require('../../libs/connect.php');
require('../../logon/check_logon.php');

$id = $_REQUEST['id'];

if ($id == 'nieuws_nieuw') {
    $r[0] = time();
} else {
    $r = db_row("select datum from dev_nieuws where id='" . db_esc($id) . "' and verwijderd!='j'");
}
include('cms_datum.php');
?>