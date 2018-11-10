<?php
// cookie verwijderen -> helemaal uitgelogd...

$domein= $HTTP_SERVER_VARS['HTTP_HOST'];
preg_match('/(\.){1}.+(\.){1}[a-z]+$/',$domein, $domeinparts);
$hoofddomein=$domeinparts[0];

    $cookienaam="bvva_sec";
    setcookie($cookienaam,md5(' '),time()+(2*24*60*60),"/",'.'.$hoofddomein,0);
    header("Location: https://".$domein."/redactie/main_index.php"); 
	exit;
?>
