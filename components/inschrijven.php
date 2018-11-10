<?php 
$s=4;
include('components/header.php'); ?>
<div id="linkerbalk">
<?php include('components/nieuws_box.php'); ?>
<br>
<?php include('components/twitter_box.php'); ?>
</div>


<div id="middenvlak">
<?php 
	if (!inschrijven_toegestaan())
	  {
?>
<h1>Inschrijven</h1>
<?php
		 if (!in_inschrijfperiode())
		 {
?>			 
	<span class="body">De inschrijving is gesloten. Maar je kunt <?php echo event_dag(). " " . event_datum(); ?> natuurlijk nog wel genieten van het programma. Tot <?php echo event_dag(); ?>.</span><br />
<?php
		 }
		 else
		 {
?>
	<span class="body">De inschrijving is tijdelijk gesloten. Probeer het later weer.</span><br />
<?php			 			 
		 }
?>
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

<?php
	  }
	  else
	  {
?>
<form action="?" method="post" id="aform" name="aform">
<input type="hidden" name="state" value="<?php echo $state; ?>" />
<input type="hidden" name="form_inc" value="ja" />

<h1>Inschrijven</h1>

<?php 
if (!empty($fout))
  {
	echo "<br>";  
	foreach ($fout as $val) 
	 {
		 echo "<span class=\"formfout\"><img src=\"img/letop.png\" alt=\"Fout!\" titel=\"Fout!\" align=\"absmiddle\"> $val</span><br />
";
	 }
	 echo "<br>";
  }
   
  
switch ($status) 
  {  
	case 'bedankt':
?>
<span class="body">
<br />
Dank u wel, <br />
<br />
we hebben uw aanmelding ontvangen. Er is een emailbericht gestuurd naar uw emailadres om de aanmelding te bevestigen.<br/><br />
	<em>Heeft u geen bevestigingsmail ontvangen? Check dan ook uw spamfilter!</em><br /><br /> Zodra u op de bevestigingslink <strong>Ja</strong> in het emailbericht heeft geklikt is de aanmelding afgerond en kan uw <?php if ($soort=='estafette') { ?>team<?php } else { ?>kind<?php } ?> beschikken over zijn eigen pagina op kinderenvoorkika.nl.
</span>


<?php
  	break;

	case 'bevestig':
	
	
		$dr=mysql_fetch_row(mysql_query("select personen.voornaam, personen.voorvoegsel, personen.achternaam, personen.geslacht, deelnemers.pagina, deelnemers.categorie from personen, deelnemers where deelnemers.id='$id' and personen.id=deelnemers.persoon and deelnemers.verwijderd!='j' and personen.verwijderd!='j'")) or die(mysql_error());
		$soort=$dr[5];
		if ($soort!='estafette')
		  {
			$naam=$dr[0];
			if (!empty($dr[1])) { $naam.=" ".$dr[1]; }
			if (!empty($dr[2])) { $naam.=" ".$dr[2]; }
			if ($dr[3]=='m') { $zd="zoon"; } else { $zd="dochter"; }
		  }
		  else
		  {
			  $naam=$dr[2];
			  $zd="team";
		  }
		$pagina=$dr[4];
		
		
		$naam=stripslashes($naam);
		$pagina=stripslashes($pagina);
		

		$dr=mysql_fetch_row(mysql_query("select personen.voornaam, personen.voorvoegsel, personen.achternaam 
											from personen, verzorgers 
											where verzorgers.deelnemer='$id' and 
											personen.id=verzorgers.verzorger and 
											verzorgers.verwijderd!='j' and 
											personen.verwijderd!='j'")) or die(mysql_error());								
		$v_naam=$dr[0];
		if (!empty($dr[1])) { $v_naam.=" ".$dr[1]; }
		if (!empty($dr[2])) { $v_naam.=" ".$dr[2]; }
?>
<span class="body">
<br />
Hartelijk dank heer/mevrouw <?php echo $v_naam; ?>, <br />
<br />
voor het bevestigen van de aanmelding van uw <?php echo $zd." ".$naam;?> voor het evenement 'De Berg Op', dat op <?php echo event_datum(); ?> zal plaatsvinden.<br />
<br />
 Vanaf nu is de persoonlijke pagina van uw <?php echo $zd; ?> bereikbaar via <a href="https://<?php echo $domein;?>/deelnemers/<?php echo $pagina; ?>"><?php echo $protocol.$domein;?>/deelnemers/<?php echo $pagina; ?></a></span>


<?php
  	break;

  
	default: 
	  //if (empty($soort)) { $soort='fietsen_8-13'; } 
?>	  
<span class="body">Ja, ik schrijf mijn zoon/dochter in voor 'De Berg Op' voor KiKa op <?php echo event_datum();?>.<br /><br/></span>
<div class="formitem">
    Onderdeel<br />
    <select name="soort" id="soort"  onChange="form.submit();">
       <option value="">Kies onderdeel</option> 
        <option value="fietsen_6-7" <?php if ($soort=='fietsen_6-7') { ?>selected<?php } ?>>Fietsen voor kinderen van 6 en 7 jaar</option>
        <option value="fietsen_8-13" <?php if ($soort=='fietsen_8-13') { ?>selected<?php } ?>>Fietsen voor kinderen van 8 t/m 13 jaar</option>
        <option value="hardlopen" <?php if ($soort=='hardlopen') { ?>selected<?php } ?>>Hardlopen voor kinderen van 8 t/m 13 jaar</option>
    </select>
</div>
<div style="clear:both"></div>
<span class="body">
<?php if ($soort=='estafette') 
  {
?>
<br />Vul hier de gegevens van het team in.<br />
<br />
</span>
<div class="formitem">
<input type="text" name="teamnaam" value="<?php echo stripslashes($teamnaam);?>" id="teamnaam" class="input_active" placeholder="teamnaam"/>
</div> 
<div style="clear:both"></div>
<?php	
  } 
  else
  {
?>		
<br />Vul hier de gegevens van de deelnemer in.<br />
<br />
</span>
<div class="formitem">
<input type="text" name="voornaam" value="<?php echo stripslashes($voornaam);?>" id="voornaam" class="input_active" placeholder="voornaam"/>
</div> 
<div class="formitem">
<input type="text" name="tussenvoegsel" value="<?php echo stripslashes($tussenvoegsel);?>" id="tussenvoegsel" class="input_active" placeholder="tussenvoegsel" />
</div> 
<div class="formitem">
<input type="text" name="achternaam" value="<?php echo stripslashes($achternaam);?>" id="achternaam" class="input_active" placeholder="achternaam" />
</div> 
<div style="clear:both"></div>
<?php 
  }
?>  
<div class="formitem">
<input type="text" name="straat" value="<?php echo stripslashes($straat);?>" id="straat" class="input_active" placeholder="straat" />
</div> 
<div class="formitem">
<input type="text" name="nummer" value="<?php echo stripslashes($nummer);?>" id="nummer" class="input_active" placeholder="nummer" />
</div> 
<div style="clear:both"></div>

<div class="formitem_small">
<input type="text" name="pc" value="<?php echo stripslashes($pc);?>" id="pc" class="input_active" placeholder="postcode" maxlength="7"/></div>

<div class="formitem">
<input type="text" name="plaats" value="<?php echo stripslashes($plaats);?>" id="plaats" class="input_active" placeholder="plaats" />
</div> 
<div style="clear:both"></div>


<?php if ($soort!='estafette')
  {
?>	  
<span class="body">Geboortedatum<br /></span>
<div class="formitem_small">
<input type="text" name="geb_dag" value="<?php echo stripslashes($geb_dag);?>" id="geb_dag" class="input_active" placeholder="dag" maxlength="2" />
</div>

<div class="formitem_small">
<select name="geb_maand" id="geb_maand">
	<option value="">maand</option>
	<option value="1" <?php if ($geb_maand=='1') { ?> selected="selected"<?php } ?>>januari</option>
	<option value="2" <?php if ($geb_maand=='2') { ?> selected="selected"<?php } ?>>februari</option>
	<option value="3" <?php if ($geb_maand=='3') { ?> selected="selected"<?php } ?>>maart</option>
	<option value="4" <?php if ($geb_maand=='4') { ?> selected="selected"<?php } ?>>april</option>
	<option value="5" <?php if ($geb_maand=='5') { ?> selected="selected"<?php } ?>>mei</option>
	<option value="6" <?php if ($geb_maand=='6') { ?> selected="selected"<?php } ?>>juni</option>
	<option value="7" <?php if ($geb_maand=='7') { ?> selected="selected"<?php } ?>>juli</option>
	<option value="8" <?php if ($geb_maand=='8') { ?> selected="selected"<?php } ?>>augustus</option>
	<option value="9" <?php if ($geb_maand=='9') { ?> selected="selected"<?php } ?>>september</option>
	<option value="10" <?php if ($geb_maand=='10') { ?> selected="selected"<?php } ?>>oktober</option>
	<option value="11" <?php if ($geb_maand=='11') { ?> selected="selected"<?php } ?>>november</option>
	<option value="12" <?php if ($geb_maand=='12') { ?> selected="selected"<?php } ?>>december</option>
</select>
</div>

<div class="formitem_small">
<input type="text" name="geb_jaar" value="<?php echo stripslashes($geb_jaar);?>" id="geb_jaar" class="input_active" placeholder="jaar" maxlength="4" />
</div> 
<div class="formitem_small">
<span class="body"><input type="radio" name="geslacht" value="m" <?php if ($geslacht=='m') { ?> checked="checked"<?php } ?> id="geslacht" /> jongen 
<input type="radio" name="geslacht" value="v" <?php if ($geslacht=='v') { ?> checked="checked"<?php } ?> /> meisje </span>
</div>
<div style="clear:both"></div>
<?php
  }
?>  

<div class="formitem">
<input type="text" name="email" value="<?php echo stripslashes($email);?>" id="email" class="input_active" placeholder="email" />
</div> 
<div style="clear:both"></div>

<div class="formitem">
<input type="text" name="telefoon" value="<?php echo stripslashes($telefoon);?>" id="telefoon" class="input_active" placeholder="telefoon" />
</div> 
<div class="formitem">
<input type="text" name="mobiel" value="<?php echo stripslashes($mobiel);?>" id="mobiel" class="input_active" placeholder="mobiel" />
</div> 
<div style="clear:both"></div>
<br />

<?php if ($soort!='estafette') 
  {
?>	  
<div class="formitem">
School<br />
<select name="school" id="school"> 
	<option value=""></option>
    <option value="andere" <?php if ($school=='andere') { ?> selected="selected"<?php } ?>>andere school</option>
<?php
	$sres=mysql_query("select id,naam from scholen where verwijderd!='j' order by naam") or die(mysql_error());
	while ($sr=mysql_fetch_row($sres))
	  {
?>
	<option value="<?php echo $sr[0];?>" <?php if ($sr[0]==$school) { ?> selected="selected"<?php } ?>><?php echo stripslashes($sr[1]); ?></option>
<?php		  
	  } // while
?>	  
</select>
</div> 
<div style="clear:both"></div>
<span class="body"><em>Staat jouw school er niet bij? Mail ons even: <a href="mailto:info@kinderenvoorkika.nl">info@kinderenvoorkika.nl</a></em></span><br>
<?php
  }
?>  
<br>
<div class="formitem">
<span class="body">
<hr />
Als je meedoet aan 'De Berg Op' krijg je een eigen pagina. Deze pagina is voor iedereen te bereiken via www.kinderenvoorkika.nl/deelnemers/<span class="paginanaam">jouw-pagina-naam</span>. Deze paginanaam mag zelf gekozen worden. Mensen die een deelnemers willen sponsoren kunnen dit doen via de eigen deelnemerspagina. De instellingen kunnen worden gewijzigd door in te loggen. Kies hiervoor een login en wachtwoord. <br />
<br /></span>

<span class="body">https://www.kinderenvoorkika.nl/deelnemers/</span><input type="text" name="pagina" value="<?php echo stripslashes($pagina);?>" id="pagina" class="input_active" placeholder="pagina" />
</div>
<div style="clear:both"></div>
<div class="formitem">
<input type="text" name="login" value="<?php echo stripslashes($login);?>" id="login" class="input_active" placeholder="login" />
</div>
<div style="clear:both"></div>

<div class="formitem">
<input type="text" name="password" value="<?php echo stripslashes($password);?>" id="password" class="input_active" placeholder="wachtwoord" />
</div>
<div class="formitem">
<input type="text" name="password_check" value="<?php echo stripslashes($password_check);?>" id="password_check" class="input_active" placeholder="herhaal wachtwoord"/>
</div>
<div style="clear:both"></div>

<div class="formitem">
<span class="body"><input type="checkbox" name="reg_akkoord" value="j" /> Ik ga akkoord met het <a href="?state=deelnamevoorwaarden" target="_blank">deelnemersreglement</a> van 'De Berg Op'. </span>
</div>
<div style="clear:both"></div>
<div class="formitem">
<span class="body"><input type="checkbox" name="akkoord" value="j" /> Ik ga akkoord met de <a href="?state=voorwaarden" target="_blank">inschrijfvoorwaarden</a> van 'De Berg Op'. </span>
</div>
<div style="clear:both"></div>



<hr />
<span class="body">Om mee te kunnen doen aan 'De Berg Op' op <?php echo event_datum(); ?> en daarmee ook te beschikken over je eigen pagina op www.kinderenvoorkika.nl is toestemming van de ouders/verzorgers vereist. Pas nadat de ouders/verzorgers de inschrijving hebben bevestigd door te klikken op de link in de bevestigingsmail die hij of zij ontvangt, is de inschrijving definitief voor het evenement en is de persoonlijke pagina online.<br />
<br />
Vul hieronder de gegevens van de ouder/verzorger in.<br /><br />
</span>
<div class="formitem">
<input type="text" name="v_voornaam" value="<?php echo stripslashes($v_voornaam);?>" id="v_voornaam" class="input_active" placeholder="voornaam"/>
</div>  
<div class="formitem">
<input type="text" name="v_tussenvoegsel" value="<?php echo stripslashes($v_tussenvoegsel);?>" id="v_tussenvoegsel" class="input_active" placeholder="tussenvoegsel" />
</div> 
<div class="formitem">
<input type="text" name="v_achternaam" value="<?php echo stripslashes($v_achternaam);?>" id="v_achternaam" class="input_active" placeholder="achternaam" />
</div> 
<div style="clear:both"></div>


<div class="formitem">
<input type="text" name="v_email" value="<?php echo stripslashes($v_email);?>" id="v_email" class="input_active" placeholder="email" />
</div> 
<div style="clear:both"></div>
<div class="formitem">
<input type="text" name="v_telefoon" value="<?php echo stripslashes($v_telefoon);?>" id="v_telefoon" class="input_active" placeholder="telefoon" />
</div> 
<div class="formitem">
<input type="text" name="v_mobiel" value="<?php if (empty($v_mobiel)) { $v_mobiel='mobiel'; }echo stripslashes($v_mobiel);?>" id="v_mobiel" <?php if ($v_mobiel=='mobiel') { ?>class="input_disabled"<?php } else { ?>class="input_active"<?php } ?> onFocus="activeer_input('v_mobiel','mobiel');" onBlur="deactiveer_input('v_mobiel','mobiel');" />
</div> 
<div style="clear:both"></div>
<br />
<input type="submit" name="do" value="INSCHRIJVEN" class="button_rood" />

</form>
<?php 
	break;
  } // switch
?>  
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
<?php
	  } // if $t>$r[0]
?>	  


<div style="clear: both"></div>



<?php include('components/footer.php'); ?>