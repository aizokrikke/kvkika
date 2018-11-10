<?php
$base="/home/kvkika/domains/kinderenvoorkika.nl/public_html/";
require($base.'config.php');
require($base.'/libs/connect.php');
require($base.'/libs/tools.php');

$vandaag=mktime(0,0,0,date("n"),date("j"),date("Y"));
$einde=$vandaag+(23*60*60)+(59*60)+59;
$dag=24*60*60;

// notify 2 daagse berichten
$onder=$vandaag+($dag*2);
$boven=$einde+($dag*2);

$res=mysql_query("select id,naam,eigenaar from draaiboek where gereed>='$onder' and gereed<'$boven' and verwijderd!='j' and status!='gereed'");
while ($r=mysql_fetch_row($res))
  {
	  $er=mysql_fetch_row(mysql_query("select reminder2 from beheerders where persoon='$r[2]'"));
	  if ($er[0]=='j') 
	    {
			$tekst="Over 2 dagen is de deadline van de actie '$r[1]'\n\nhttps://www.kinderenvoorkika.nl/?state=admin&go=draaiboek&id=$r[0]&act=edit";	
			stuur_bericht($r[2],'Notificatie deadline over 2 dagen',$tekst);
		}
  } // while

// notify 7 daagse berichten
$onder=$vandaag+($dag*7);
$boven=$einde+($dag*7);

$res=mysql_query("select id,naam,eigenaar from draaiboek where gereed>='$onder' and gereed<'$boven' and verwijderd!='j' and status!='gereed'");
while ($r=mysql_fetch_row($res))
  {
	  $er=mysql_fetch_row(mysql_query("select reminder7 from beheerders where persoon='$r[2]'"));
	  if ($er[0]=='j') 
	    {
			$tekst="Over 7 dagen is de deadline van de actie '$r[1]'\n\nhttps://www.kinderenvoorkika.nl/?state=admin&go=draaiboek&id=$r[0]&act=edit";	
			stuur_bericht($r[2],'Notificatie deadline over 2 dagen',$tekst);
		}
	  
  }
?>
