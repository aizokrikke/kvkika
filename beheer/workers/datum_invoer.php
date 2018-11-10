<?php
header("Content-Type: text/html; charset=iso-8859-1\n");
setlocale(LC_ALL,"nl_NL");

require_once('../../libs/connect.php');
require_once('../../logon/check_logon.php');

$div=$_REQUEST['div'];
$datum_select=$_REQUEST['datum_select'];



//echo "div: $div, datum_select: $datum_select <br>";

include('datum_invoer_body.php');
?>