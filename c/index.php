<?php
include_once('../config.php');
include_once('../libs/connect.php');
include_once('../libs/is_email.php');
include_once('../libs/tools.php');
$c=mysql_real_escape_string($_REQUEST['c']);

$r=mysql_fetch_row(mysql_query("select id, pagina, bevestigd, persoon from deelnemers where confirmcode='$c' and verwijderd!='j'"));
$id=$r[0];
$deelnemer=$r[3];
$pag=$r[1];

$dr=mysql_fetch_row(mysql_query("select voornaam, voorvoegsel,achternaam, email from personen where id='$deelnemer' and verwijderd!='j'"));
$naam=$dr[0];
if (!empty($dr[1])) { $naam.=" $dr[1]"; }
if (!empty($dr[2])) { $naam.=" $dr[2]"; }
$naam=stripslashes($naam);
$to['email']=stripslashes($dr[3]);
$to['naam']=$naam;

if ($r[2]!='j')
  {
		$message="<html><body>Beste $naam,<br><br>jouw aanmelding voor 'De Berg Op' is bevestigd door je ouders/verzorgers. Leuk dat je meedoet!<br>
<br>
Vanaf nu is je persoonlijke pagina beschikbaar via <a href=\"$protocol$domein/deelnemers/$pag\">$domein/deelnemers/$pag</a>.<br>
Als je inlogt op jouw persoonlijke pagina met jouw inlognaam en wachtwoord, daarna klikt op \"inloggen\" en vervolgens op \"mijn pagina\" kan je onder \"instellingen\" een foto toevoegen en jouw motivatie en doel opgeven.<br>
<br>
Wij wensen je heel veel plezier en succes! <br><br>Met vriendelijke groet,<br>Stichting Kinderen voor KiKa<br>Stuurgroep De Berg Op
				</body></html>";

	  $message_txt="Beste $naam, \r\n\r\njouw aanmelding voor 'De Berg Op' is bevestigd door je ouders/verzorgers. Leuk dat je meedoet!\r\n\r\nVanaf nu is je persoonlijke pagina beschikbaar via $protocol$domein/deelnemers/$pag\">$domein/deelnemers/$pag<$protocol$domein/deelnemers/$pag\">$domein/deelnemers/$pag>\r\n\r\n
Als je inlogt op jouw persoonlijke pagina met jouw inlognaam en wachtwoord, daarna klikt op \"inloggen\" en vervolgens op \"mijn pagina\" kan je onder \"instellingen\" een foto toevoegen en jouw motivatie en doel opgeven.\r\n\r\nWij wensen je heel veel plezier en succes! \r\n\r\nMet vriendelijke groet,<br>Stichting Kinderen voor KiKa\r\nStuurgroep De Berg Op";	

	  $from['naam'] = "Kinderen voor KiKa";
	  $from['email'] = "info@kinderenvoorkika.nl";
	  stuur_mail($to,'Jouw aanmelding voor De Berg Op is bevestigd',$from, $message, $message_txt);
 } // if...
	  
mysql_query("update deelnemers set bevestigd='j' where confirmcode='$c'") or die(mysql_error());	  
header("location: $protocol$domein/?state=inschrijven&status=bevestig&id=$id");
?>