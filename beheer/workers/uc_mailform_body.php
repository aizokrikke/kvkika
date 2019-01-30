<?php
if (!empty($err)) {
    ?>
	<span class="error"><?php echo $err; ?></span><br><br>
<?php
}

if ($succes != 'j') {
?>
    <form method="post" id="mailform">
    <div class="uc_formregel">
    Voornaam:<br>
    <input type="text" size="25" name="voornaam" value="<?php echo $voornaam; ?>"></div>
    <div class="uc_formregel">    
    Achternaam:<br>
    <input type="text" size="25" name="achternaam" value="<?php echo $achternaam; ?>"></div>
    <div class="uc_formregel">    
    Mail:<br>
    <input type="text" size="25" name="email" value="<?php echo $email; ?>"></div>
    <br>
    <input type="button" name="doe" value="aanmelden" class="kika_button" onClick="process_mailform();">
    </form>
<?php
} else {
?>
	Bedankt voor je belangstelling. <br>
<br>
We houden je op de hoogte!
<?php 
}
?>