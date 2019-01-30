<span class="kop"><a href="?state=<?php echo $state;?>&go=<?php echo $go;?>"><img src="beheer/img/Basic_set2_Png/Basic_set2_Png/user_settings_48.png" align="absmiddle" title="beheerders" alt="beheerders" align="absmiddle" height="24" width="24"> Persoonlijke instellingen</a></span>
<hr />

<?php

$do = db_esc($_REQUEST['do']);
$voornaam = db_esc($_REQUEST['voornaam']);
$tussenvoegsel = db_esc($_REQUEST['tussenvoegsel']);
$achternaam = db_esc($_REQUEST['achternaam']);
$email = db_esc($_REQUEST['email']);
$notify = db_esc($_REQUEST['notify']);
$reminder2 = db_esc($_REQUEST['reminder2']);
$reminder7 = db_esc($_REQUEST['reminder7']);


	$u=$user['id'];
	
	
	if (!empty($do)) {
		  db_query("update personen set voornaam='$voornaam',voorvoegsel='$tussenvoegsel', achternaam='$achternaam', email='$email' where id='$u'");
		  db_query("update beheerders set notify_actie_change='$notify', reminder2='$reminder2', reminder7='$reminder7' where persoon='$u'");
?>
	<br />
Wijzigingen zijn opgeslagen.
<?php		  
    } else {
	$r = db_row("select voornaam, voorvoegsel, achternaam, email from personen where id='$u' and verwijderd!='j'");
	$voornaam = $r[0];
	$tussenvoegsel = $r[1];
	$achternaam = $r[2];
	$email = $r[3];
	$r = db_row("select notify_actie_change, reminder2, reminder7 from beheerders where persoon='$u' and verwijderd!='j'");
	$notify = $r[0];
	$reminder2 = $r[1];
	$reminder7 = $r[2];
?>	

<form action="?" method="post">
<input type="hidden" name="state" value="<?php echo $state; ?>" />
<input type="hidden" name="go" value="<?php echo $go; ?>" />

<div class="formveld">
voornaam<br />
<input name="voornaam" value="<?php echo $voornaam;?>" />
</div>

<div class="formveld">
tussenvoegsel<br />
<input name="tussenvoegsel" value="<?php echo $tussenvoegsel;?>" />
</div>

<div class="formveld">
achternaam<br />
<input name="achternaam" value="<?php echo $achternaam;?>" />
</div>

<div class="formregeleinde"></div>


<div class="formveld">
email<br />
<input name="email" value="<?php echo $email;?>" />
</div>

<div class="formregeleinde"></div><br />

<div class="formveld">
notificatie bij wijziging actie<br />
<input type="radio" name="notify" value="j" <?php if ($notify=='j') {
    ?>checked="checked"<?php
} ?> /> ja <input type="radio" name="notify" value="n" <?php if ($notify!='j') {
    ?>checked="checked"<?php
} ?> /> nee
</div>

<div class="formregeleinde"></div><br />

<div class="formveld">
waarschuwing deadline actie<br />
<input type="checkbox" name="reminder2" value="j" <?php if ($reminder2=='j') {
    ?>checked="checked"<?php
} ?> /> 2 dagen vooraf <input type="checkbox" name="reminder7" value="j" <?php if ($reminder7=='j') {
    ?>checked="checked"<?php
} ?> /> 7 dagen vooraf
</div>

<div class="formregeleinde"></div><br />
<input type="submit" name="do" value="opslaan" class="db_button" /> <input type="button" name="ann" value="annuleren" class="db_button" onclick="window.location='?state=admin';" />

</form>
 <?php
    }
?>	  