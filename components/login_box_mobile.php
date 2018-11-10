    <div id="loginbox_mobile">
    <?php

	
	 if ((empty($user['id'])) or (($user['bevestigd']!='j') and ($user['beheerder']!='j')))
	  {
	?>	  
        <form action="https://<?php echo $domein;?>/?" method="post" id="aform">
    	<div class="left">
        <input type="hidden" name="state" value="<?php echo $state; ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
                
        <input type="text" name="login" value="<?php if (empty($login)) { $login='inlognaam'; } echo stripslashes($login);?>" id="login" class="input_disabled" onFocus="activeer_input('login','inlognaam');" onBlur="deactiveer_input('login','inlognaam');" /><br>
        <input <?php if ((empty($password)) or (strtolower($password)=='wachtwoord')) { ?>type="text"<?php } else { ?>type="password"<?php } ?> name="password" value="<?php if (empty($password)) { $password='wachtwoord'; } echo stripslashes($password);?>" id="password" class="input_disabled" onFocus="activeer_ww('password','wachtwoord');" onBlur="deactiveer_input('password','wachtwoord');" />
        <input type="submit" name="do" value="INLOGGEN" class="button_rood_small">
        </div>
        <div class="right">
        <h1>Meedoen</h1>
        <input type="button" name="in" value="INSCHRIJVEN" class="button_rood_small" onClick="call_url('https://<?php echo $domein; ?>/?state=inschrijven');"><br><br>
        <input type="button" name="in" value="DONEER" class="button_rood_small" onClick="call_url('https://<?php echo $domein; ?>/?state=doneer');">
        </div>
        <div style="clear:both"></div>
        </form>
    <?php 
	  }
	  else
	  {

	?> 		
        <form action="?" method="post">
        
        <?php if (!empty($user['pagina'])) {?>
        <input type="button" name="do" value="MIJN PAGINA" class="button_paars_small" onClick="window.location='/deelnemers/<?php echo $user['pagina'];?>';">
        <br><br>
<?php 
		}
    
		if (!empty($user['rechten']['beheer']))
		  {
?>
	<div class="button_paars_small" onclick="window.location='https://<?php echo $domein; ?>?state=admin'" align="center">
    	<img src="https://<?php echo $domein; ?>/beheer/img/eleganticons-png/png/Config.png" alt="beheer" title="beheer" align="absmiddle" width="20" height="20" /> BEHEER
    </div>
	<br />

<?php		  
		  }        
?>       
        <input type="button" name="logoff" value="UITLOGGEN" class="button_rood_small" onClick="window.location='https://<?php echo $domein; ?>?state=logoff';" >
        </form>
    
    <?php 
	  }
	?>  
    </div>