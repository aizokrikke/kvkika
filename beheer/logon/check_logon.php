<?php
session_start();

$ip=$_SERVER['REMOTE_ADDR'];
$gekozen_pagina=$_SERVER['REQUEST_URI'];
$tijdstip=time();
$sessietijd=time()+(2*60*60);

// global vars
$password=$_REQUEST['password'];
$login=$_REQUEST['login'];
$set_cookie=$_REQUEST['set_cookie'];
$doe=$_REQUEST['$doe'];

function login($login,$password,$set_cookie='j') {
  global $user_cookienaam, $user, $hoofddomein;

	if ($row = mysql_fetch_array(mysql_query("SELECT id,voornamen, tussenvoegsel, achternaam, alias FROM personen WHERE login='$login' and password='$password' ORDER BY login")))
	  {  // user wel in database
	  	$found=true;
		$user['id']=$row[0];
		$user['ALIAS']=$row[3]; 
		$user['naam']=$row[1];
		if (!empty($row[2])) { $user['naam'].=" $row[2] "; }
		if (!empty($row[3])) { $user['naam'].=$row[3]; }
		if ($set_cookie=='j')
		  { setcookie($user_cookienaam,$login,time()+(60*60*24*365),'/','.'.$hoofddomein); }
		  else
		  { setcookie($user_cookienaam,$login,0,'/','.'.$hoofddomein); }
	  } 
	  else
	  { $found=false;
	  	$user['user_id']='';
		$user['naam']='';
		$user['rechten']=array(); } 
	  
  RETURN $found;	
   
} // function login

function check_rights($required='', $ret='') {
  global $siteroot, $domein, $sessietijd, $tijdstip, $gekozen_pagina, $ip, $user, $systeem, $user_cookienaam;
  
	$user_cookie=$_COOKIE[$user_cookienaam];
	
//	echo "user_cookie: $user_cookie<br>";

 	$user['user_id']='';
	$user['naam']='';
	$user['redacteur']='';
	$user['rechten']=array();
	
	$cookieparts=explode('|',$user_cookie);
	$cookie_login_md5=$cookieparts[0];
	$cookie_password=$cookieparts[1];
	$cookie_id=$cookieparts[3];
	
		
	if (!empty($user_cookie))
		  { // eerder geaccepteerd -> checken met database
			if ($row = mysql_fetch_array(mysql_query("SELECT id,voornaam, voorvoegsel, achternaam FROM personen WHERE login_md5='$cookie_login_md5' and password=$user_cookie_password ORDER BY login")))
			  {  // user bestaat niet in database
				  $verbanrow=mysql_fetch_row(mysql_query("select tot from verbannen where user='$row[0]'"));
				  if (($tijdstip<$verbanrow[0]) or ($verbanrow[0]=='1')) { $user['verbannen']='j'; }
				  $user['redacteur']=$row[0];
				  $user['user_id']=$row[0];
				  $user['naam']=$row[1];
				  if (!empty($row[1])) { $user['naam'].=" $row[2]"; }
				  // loggen
				  mysql_query("INSERT INTO userlog (id, tijd, ip) VALUES ('$row[0]','$tijdstip','$ip')");
				  // haal rechten van gebruiker op
				  $rechten = array();
				  $query="select rechten.recht, rechtendef.recht
									from rechten, rechtendef 
									where rechten.user='$row[0]'
									and rechtendef.id= rechten.recht
									order by secties.prio";
				  $res=mysql_query($query) or die(mysql_error());
				  while ($rrow=mysql_fetch_row($res))
					{
					  $rechten[$rrow[1]]=$rrow[0];				  
					} // while
	
				  $user['rechten']=$rechten;		
								  
				  setcookie($cookienaam,$user_cookie,$sessietijd,"/",$hoofddomein,0);
			  }
		  }
	
	// staat server in onderhoudsmodus?
	// $row = mysql_fetch_array(mysql_query("SELECT waarde FROM status WHERE parameter='onderhoud_mode'"));
	// $systeem['onderhoud']=$row[0];

	if (empty($ret)) { $ret='index.php'; } // default return
	
    // heeft de user de benodigde rechten?
	if (!empty($required))
	  {
		  if (empty($user['rechten'][$required]))
		    { if (!empty($user['rechten']['beheer']))
			   { header ("location: https://".$domein."/?ret=".$ret); }
			   else
			   { header ("location: https://".$domein."/?ret=".$ret."&state=auth"); }	
			}
	  } // if..

} // check_rights



if ($doe=='login')
  { // user login, voor gewone bezoekers!! dus niet beheerders
  	login($login,$password,$set_cookie); }
if ($doe=='logoff')
  { // user logoff, voor gewone bezoekers!! dus niet beheerders
  	setcookie($user_cookienaam,md5(mktime())); 
  	$user['naam']='';
	$user['user_id']='';
  }
  
check_rights($required, $ret);
   
 
 // maak user global
 $_SESSION['user']=$user;
   
   print_r($user);
?>