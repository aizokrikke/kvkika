<?php
// config.php

$sitestatus = "";

$domein = $_SERVER['SERVER_NAME'];
$dparts = explode('.',$domein);
$d_land = array_pop($dparts);
$d_main = array_pop($dparts);
$subdomein = array_pop($dparts);
$hoofddomein = $d_main.'.'.$d_land;
$base_url = 'www.'.$hoofddomein;

if (!empty($_SERVER['HTTPS'])) {
    $protocol="https://";
} else {
    $protocol="http://";
}

if ($subdomein == 'dev')
  {
      $sitestatus = 'dev_';
  }

$db_server = 'localhost';
if ($sitestatus != 'dev_')
  {
      $db_name = 'kvkika_db';
  }
  else
  {
      $db_name = 'kvkika_dev';
  }
$db_username = 'kvkika_gen';
$db_password = 'Z3lfl13fd3';

$siteroot = dirname(__FILE__);

$user_cookienaam = "kvkika_ck";

// locatie voor attachements
$attach_store = '/home/kvkika/domains/kinderenvoorkika.nl/public_html/attachments';

// locatie van de sponsorlogo's
$sponsorlogo_dir = '/home/kvkika/domains/kinderenvoorkika.nl/public_html/img/logos';

// foto's van de deelnemers
$foto_dir = '/home/kvkika/domains/kinderenvoorkika.nl/public_html/fotos';
$foto_dir_dev = '/home/kvkika/domains/kinderenvoorkika.nl/public_html/dev/fotos';

// time-out voor sponsoring

$time_out = 24*60*60;   // 1 dag

?>