<?php
session_start();

$domein = $HTTP_SERVER_VARS['HTTP_HOST'];
$ip = $_SERVER['REMOTE_ADDR'];
$gekozen_pagina = $_SERVER['REQUEST_URI'];
$tijdstip = time();
preg_match('/(\.){1}.+(\.){1}[a-z]+$/',$domein, $domeinparts);
$hoofddomein = $domeinparts[0];

$siteroot = dirname(dirname(__FILE__));
require($siteroot.'/libraries/connect.php');

$cookienaam = "bvva_sec";
$cookie_login = $HTTP_COOKIE_VARS[$cookienaam];

if (!empty($cookie_login)) { // eerder geaccepteerd
    if (!($row = db_row("SELECT * FROM users WHERE login='$cookie_login' ORDER BY login"))) {
         header("Location: https://".$domein."/redactie/noaccess.php");
         exit;
    } // if
} else {
     header("Location: https://".$domein."/logon/login.php");
     exit;
} // if
	   
$_SESSION['redacteur'] = $row[0];
$_SESSION['redacteurnaam'] = $row[3];
$_SESSION['userlevel'] = $row[4];

if ($row[4] < $level_required) {
    header("Location: https://".$domein."/redactie/noaccess2.php");
    exit;
}
$_SESSION['redactiemode'] = '1';
// staat server in onderhoudsmodus?
$onderhoud = db_row("SELECT waarde FROM status WHERE parameter='onderhoud_mode'");
if ($onderhoud[0] == '1') {
    if ($_SESSION['userlevel'] < 100) {
        header("Location: https://".$domein."/redactie/onderhoud.php");
    }
}
// loggen
db_query("INSERT INTO userlog (user_id, tijd, pagina, ip) VALUES ('$row[0]','$tijdstip','$gekozen_pagina','$ip')")
?>