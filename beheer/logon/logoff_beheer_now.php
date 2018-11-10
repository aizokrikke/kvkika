<?php
// cookie verwijderen -> helemaal uitgelogd...
require_once('../config.php');

setcookie($cookienaam,md5(' '),time()+(2*24*60*60),"/",'.'.$hoofddomein,0);
header("Location: https://".$domein."/".$ret); 
exit;
?>
