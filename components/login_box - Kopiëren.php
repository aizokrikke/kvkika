    <div id="loginbox">
    <?php
	 if ((empty($user['id'])) or (($user['bevestigd']!='j') and ($user['beheerder']!='j')))
	  {
	?>	  
    
    	<form action="http://<?php echo $domein;?>/?" method="post" id="loginform">
        <input type="hidden" name="state" value="<?php echo $state; ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <h1>Inloggen</h1>
        <input type="text" name="login" value="Inlognaam" class="login_input_disabled" onFocus="activeer_login();" onBlur="deactiveer_login();"><br>
        <div id="login_password"><input type="text" name="password" value="Wachtwoord" class="login_input_disabled" onFocus="activeer_password();" onBlur="deactiveer_password();"></div>
        <span class="body"><a href="?state=wachtwoord">Wachtwoord vergeten?</a></span><br />
        <br />
        <input type="submit" name="do" value="INLOGGEN" class="button_rood"><br><br>
        <h1>Meedoen</h1>
        <input type="button" name="in" value="INSCHRIJVEN" class="button_rood" onClick="call_url('http://<?php echo $domein; ?>/?state=inschrijven');">
        </form>
    <?php 
	  }
	  else
	  {
	?>
    	<?php echo strtoupper(stripslashes($user['naam']))	;?><br /><br /> 
        <img src="http://<?php echo $base_url; ?>/fotos/<?php if (!empty($user['foto'])) { echo $user['foto']; } else { echo "def.png"; }?>" /><br /><br />
 		<form action="?" method="post">
        
        <?php if (!empty($user['pagina'])) {?>
        <input type="button" name="do" value="MIJN PAGINA" class="button_paars" onClick="window.location='/deelnemers/<?php echo $user['pagina'];?>';">
        <br><br>
<?php        
	if (!empty($user['rechten']['beheer']))
	  {
?>
	<div class="button_paars" onclick="window.location='http://<?php echo $domein; ?>?state=admin'" align="center">
    	<img src="http://<?php echo $domein; ?>/beheer/img/eleganticons-png/png/Config.png" alt="beheer" title="beheer" align="absmiddle" width="20" height="20" /> BEHEER
    </div>
	<br />

<?php		  
	  }        
?>       
        <?php } ?>
        <input type="button" name="logoff" value="UITLOGGEN" class="button_rood" onClick="window.location='http://<?php echo $domein; ?>?state=logoff';" >
        </form>
    
    <?php 
	  }
	?>  
    </div>