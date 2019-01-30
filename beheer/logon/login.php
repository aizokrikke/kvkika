<?php
$domein = $HTTP_SERVER_VARS['HTTP_HOST'];
preg_match('/(\.){1}.+(\.){1}[a-z]+$/',$domein, $domeinparts);
$hoofddomein = $domeinparts[0];

require('../libraries/connect.php');	
		
$cookienaam = "bvva_sec";
// password aanpassen: alle quotes eruit, ' or '' = ' bug!
$password = str_replace( "'", "", $password);

if ($submit == 'enter') {
  $query = "SELECT `users`.* 
            FROM `users`
            WHERE ( (`users`.`login`='$login') and 
                    (`users`.`password`='$password'))
            ORDER BY `users`.`login`";
  $result=db_query($query);
  if ($row = db_array($result)) {
      // succes
      setcookie($cookienaam,$login,time()+(2*24*60*60),"/",'.'.$hoofddomein,0);
      header("Location: https://".$domein."/redactie/main_index.php");
      exit;
  } else {
      die ("U bent niet geautoriseerd voor deze sectie");
  } // if
} // if
	
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Bewoners Vereniging Vathorst</title>
<link rel="stylesheet" href="/stylesheets/default.css" type="text/css">
</head>
<body bgcolor="#FFFFCC" background="/images/back_main.jpg" BGPROPERTIES="FIXED" text="#660099" class="normal" >


<table width="100%"><tr valign="top"><td width="10"></td><td>
<br><span class="kop">Logon <?php echo $domein; ?></span>

<br>

<form method="post" action="login.php">
<table class="normal"><tr><td>
Login
</td><td>
<input type=text name=login size=30>
</td></tr>
<tr><td>
Password</td><td>
<input type="password" name="password" size=30>
</td></tr>
<tr><td></td><td align="right"><input type=submit name=submit value=enter></td></tr></table>

</form>
</td><td width="10"></td></tr></table>

</body>
</html>
<?php
exit;
?>
