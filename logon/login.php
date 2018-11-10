<?php
require_once('config.php');	

// echo "state: $state, ret: $ret";	


$password_md5=md5($password);
$password=mysql_real_escape_string($_REQUEST['password']); 
$login=mysql_real_escape_string(strtolower($_REQUEST['login']));
$submit=$_REQUEST['submit'];
$ret=mysql_real_escape_string($_REQUEST['ret']);
$ip=$_SERVER['REMOTE_ADDR'];
$tijdstip=strftime("%F %T",time());

  if ($submit=='aanmelden')
    {  
	  $query = "SELECT id
				FROM personen
				WHERE 
					verwijderd!='j' and
					login = '$login' and
					password_md5 = '$password_md5'
				ORDER BY personen.achternaam";
//    echo $query;
 	  $result=mysql_query($query) or die(mysql_error()); 		
	  if ($row = mysql_fetch_array($result))
	    { // succes	
		  $cookie_val=md5($login)."|".$password_md5."|".$row[0];
		  setcookie($user_cookienaam,$cookie_val,time()+(2*24*60*60),"/",'.'.$hoofddomein,0);
		  header("Location: http://".$domein."/?state=".$ret);
		  $q = "INSERT INTO userlog (user, tijd, ip, state) VALUES ('$row[0]','$tijdstip','$ip','$ret')";
//		  echo $q;
		  mysql_query($q) or die(mysql_error());
		  exit;
		}
	} // if


include('components/header.php');
?>
<div id="beheer">

<div id="login">
<div id="login_header">Inloggen</div>
<div id="login_body">
    <div id="login_icon"><img src="../beheer/img/password.png"></div>
    <div id="login_form">
    <form method="post" action="?state=auth">
    <input type="hidden" name="ret" value="<?php echo $ret; ?>">
    Login<br>
    <input type=text name=login size=30 value="<?php echo $login;?>"><br><br>
    Password<br>
    <input type="password" name="password" size=30 <?php echo $password;?>><br><br>
    <input type=submit name=submit value="aanmelden" class="kika_button">
    </form></div>
    <div style="clear:both"></div>
</div> <!-- login body -->   
</div> <!-- login -->

</div> <!-- beheer --> 
</div> <!-- inner container -->
</div> <!-- outer container -->
<div id="footer"></div>
</body>
</html>
