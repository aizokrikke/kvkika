<?php 
$s=4;

$do=$_REQUEST['do'];


if ($do=='herstellen')
  {
	$loginnaam=$_REQUEST['loginnaam'];
	$email=$_REQUEST['email'];
	
	
	if (!empty($loginnaam))
	  {
		  $l=db_esc($loginnaam);
		  if ($lr=db_row("select id,email, login from personen where login='$l' and verwijderd!='j'")) {
		      // login gevonden dus password resetten
			  $status = 'succes';
			  $to = $lr[1];
			  $id = $lr[0];
			  $login = $lr[2];
			} 
	  }
	if ($status != 'succes') {
		  if (!empty($email)) {
				$e=db_esc($email);
				$lres = db_query("select id, email, login from personen where email = '$e' and verwijderd != 'j'");
				if (db_num_rows($lres) == 1) {
					  $status = 'succes';
					  $lr = db_row($lres);
					  $id = $lr[0];
					  $to = $lr[1];
					  $login = $lr[2];
				  } else {
				    $fout[] = 'Er is geen unieke deelnemer gekoppeld aan het opgegeven emailadres';
				}
			} else {
		      $fout[] = "geen geldige login of emailadres opgegeven";
		  }
	  }
	if ($status=='succes') {
		  $pass = generate_password();
		  $pass_md5 = md5($pass);
		  db_query("update personen set password_md5='$pass_md5' where id = '$id'");
		  $mes = "<html><body>Je wachtwoord is opnieuw ingesteld.<br><br>Vanaf nu kun je inloggen met de volgende gegevens<br>Login: ".$login."<br>Wachtwoord: ".$pass."<br><br>Je kunt weer een eigen wachtwoord kiezen via instellingen op je eigen pagina.<br><br>Kinderen voor KiKa";
		  $headers  = 'MIME-Version: 1.0' . "\r\n";
		  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		  $headers .= 'From: Kinderen voor KiKa <info@kinderenvoorkika.nl>' . "\r\n";
		  mail($to,'Wachtwoord is hersteld', $mes, $headers);
	  } else {
	    $fout[] = "er is iets misgegaan. Wij kunnen jouw wachtwoord niet automatisch herstellen. Neem contact op met Kinderen voor KiKa via info@kinderenvoorkika.nl";
	}
  }
  
  
  
include('components/header.php'); ?>
<div id="linkerbalk">
<?php include('components/nieuws_box.php'); ?>
<br>
<?php include('components/twitter_box.php'); ?>
</div>


<div id="middenvlak">

<form action="?" method="post">
<input type="hidden" name="state" value="<?php echo $state; ?>" />
<h1>Wachtwoord herstellen</h1>

<?php 
if (!empty($fout)) {
	echo "<br>";  
	foreach ($fout as $val) {
		 echo "<span class=\"formfout\"><img src=\"img/letop.png\" alt=\"Fout!\" titel=\"Fout!\" align=\"absmiddle\"> $val</span><br />\n";
	 }
	 echo "<br>";
  }
   
  
switch ($status) {
	case 'succes':
?>
<span class="body">
<br />
Je wachtwoord is hersteld <br />
<br />
Er is een emailbericht naar je verzonden met je nieuwe wachtwoord. Met deze inloggegeven kun je weer inloggen op je de site. Je kunt eventueel zelf weer een nieuw wachtwoord kiezen via de instellingen op je eigen pagina. </span>


<?php
  	break;
  
	default:  
?>	  
<span class="body">
Wanneer je de wachtwoord en/of je login kwijt bent kun je een nieuwe aanvragen door je login of je emailadres in te voeren. Er zal dan een nieuw wachtwoord voor je worden aangemaakt. Met dit wachtwoord kun je dan weer inloggen. Via de instellingen op je eigen pagina kun je dan weer een eigen wachtwoord kiezen. Onthoud dit wachtwoord goed.<br /><br />
Login<br />
<input type="text" name="loginnaam" class="form_midden" value="<?php echo stripslashes($loginnaam);?>" /><br /><br />
E-mail<br />
<input type="text" name="email" class="form_midden" value="<?php echo stripslashes($email);?>" /><br /><br />
<input type="submit" name="do" value="herstellen" class="form_midden" />

<?php 
	break;
  }
?>  
</form>
</div>

<div id="rechterbalk">
<?php include('components/login_box.php'); ?>

    <div class="sponsors"><a href="http://www.kika.nl" target="_blank"><img src="img/kika_logo.jpg" alt="KiKa" title="KiKa"></a></div>
    <div id="sponsorbox">Dit evenement is tot <br />
stand gekomen met <br />
de inzet van:<br /><br />
    	<div id="logobox"><img src="img/Continental-Logo_150.png" /></div>
</div>
</div>

<div style="clear: both"></div>

<?php include('components/footer.php'); ?>