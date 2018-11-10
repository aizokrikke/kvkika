<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

setlocale(LC_ALL,"nl_NL");
require_once('libs/connect.php');
require_once('logon/check_logon.php');
require_once('libs/tools.php'); 
require_once('libs/is_email.php'); 

// switches ophalen
$state=$_REQUEST['state'];
$menu_id=strtolower($_REQUEST['mobile_menu_id']); 
if (!empty($menu_id))
  {
	  $mr=mysql_fetch_row(mysql_query("select actie, extern from menu where id='".mysql_real_escape_string($menu_id)."' and verwijderd!='j' order by id asc"));
	  //echo "mobile menu afhandelen... ";
	  //print_r($mr);
	  preg_match("/(state\=){1}([a-z]{1,})/",$mr[0],$matches);
	  //print_r($matches);
	  $state=$matches[2];
  }
 
if (empty($state))
	{ 
	   if ($subdomein!='dev') 
	 		{ 
			  $row=mysql_fetch_row(mysql_query("select waarde from system where parameter='default_state'")); 
			  $state=$row[0];
			}
			else
	 		{ 
			  $row=mysql_fetch_row(mysql_query("select waarde from system where parameter='default_state_dev'")); 
			  $state=$row[0];
			}
	}

//echo "status: $sitestatus, $protocol <br>";

if (($protocol!='https://') and ($sitestatus!='dev_'))
  { 
  	$loc="HTTPS://".$_SERVER['SERVER_NAME']; 
	header("location: $loc"); 
	exit(); 
  }



//echo "do: $do";
//echo "state: $state, id: $id<br>";


if (!empty($form_inc))
  { include('components/proc_forms.php'); }
if (!empty($pag))
  { header("location: /deelnemers/".$pag); }  


// state opvolgen	
$row=mysql_fetch_row(mysql_query("select incl, beheerder, recht, content_id from states where state='$state' and site='$subdomein' and verwijderd!='j'"));
if (empty($row[0]))
  {
	 if ($subdomein!='dev') 
	 	{ $row=mysql_fetch_row(mysql_query("select waarde from system where parameter='default_state'")); $state=$row[0]; }
		else
	 	{ $row=mysql_fetch_row(mysql_query("select waarde from system where parameter='default_state_dev'")); $state=$row[0]; }
	 $row=mysql_fetch_row(mysql_query("select incl, beheerder, recht, content_id from states where state='$state' and site='$subdomein' and verwijderd!='j'"));
  }

// beheerder?
if ($r=mysql_fetch_row(mysql_query("select id from beheerders where persoon='$user[id]' and actief='j' and verwijderd!='j'")))
	{ $user['beheerder']='j'; }
	else
	{ $user['beheerder']='n'; }  




// rechten afhandelen
$ok=true;
if ($row[1]=='j')
  {
	$ok=false;  
	if ((!empty($user['id'])) and ($user['verbannen']!='j') and ($user['beheerder']=='j')) 
	  {
		if (!empty($row[2])) 
		  {
			  $ok=($rr=mysql_fetch_row(mysql_query("select id from rechten where user='$user[id]' and recht='$row[2]' and verwijderd!='j'")));
		  }
		  else
		  { $ok=true; }
	  }		  
  }
    

  
if ($ok) 
  { 
  	if ($row[3]!=0) { $id=$row[3]; }
  	include($row[0]); 
  }
  else
  { header("location: ?state=auth&ret=".$state); }

$row=mysql_fetch_row(mysql_query("select waarde from system where parameter='debug'")); $debug=$row[0];
if (($sitestatus=='dev_') and ($debug=='j'))
  {
?>
<div id="console">
<?php 
echo "state: $state<br>";
echo "db: $db_name<br>";
echo "id: $id<br>";
?>
<script type="text/javascript">
document.writeln('breedte: '+document.body.clientWidth);
</script>
</div>
<?php	  
  }
?>