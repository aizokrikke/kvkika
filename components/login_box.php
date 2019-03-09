<script type="application/javascript">
    function login() {
		document.getElementById('loginform').submit();
    }
</script>


    <div id="loginbox">
    <?php
	 if ((empty($user['id'])) or (($user['bevestigd'] != 'j') and ($user['beheerder']!='j'))) {
	?>	  
    
    	<form action="<?php echo $protocol . $domein;?>/?" method="post" id="loginform" name="loginform">
        <input type="hidden" name="state" value="<?php echo $state; ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="form_inc" value="yes">
        <input type="hidden" name="do" value="INLOGGEN">
        <h1>Inloggen</h1>
        <input type="text" name="login" value="Inlognaam" class="login_input_disabled" onFocus="activeer_login();" onBlur="deactiveer_login();"><br>
        <div id="login_password"><input type="text" name="password" value="Wachtwoord" class="login_input_disabled" onFocus="activeer_password();" onBlur="deactiveer_password();"></div>
        <span class="body"><a href="?state=wachtwoord">Wachtwoord vergeten?</a></span><br />
        <br />
            <div class="button_rood" onclick="login()" align="center">
                <div class="button_icon">
                    <i class="fas fa-sign-in-alt fa-lg"></i>
                </div>
                <div class="button_text">
                    INLOGGEN
                </div>
            </div>
        <br/>
        <h1>Meedoen</h1>
            <div class="button_rood" onClick="call_url('<?php echo $protocol . $domein; ?>/?state=inschrijven');">
                <div class="button_icon">
                    <i class="fas fa-bicycle fa-lg"></i>
                </div>
                <div class="button_text">
                    INSCHRIJVEN
                </div>
            </div>
            <div class="button_rood" onClick="call_url('<?php echo $protocol . $domein; ?>/?state=doneer');">
                <div class="button_icon">
                    <i class="fas fa-euro-sign fa-lg"></i>
                </div>
                <div class="button_text">
                    DONEER
                </div>
            </div>
            <br/>
        </form>
    <?php 
	  }
	  else
	  {

	?>
    	<?php echo strtoupper(stripslashes($user['naam']))	;?><br /><br /> 
        <img src="<?php echo $protocol.$domein; ?>/fotos/<?php if (!empty($user['foto'])) { echo $user['foto']; } else { echo "def.png"; }?>" /><br /><br />
 		<form action="?" method="post">
        
        <?php if (!empty($user['pagina'])) {?>
            <div class="button_paars" onClick="call_url('<?php echo $protocol . $domein; ?>/deelnemers/<?php echo $user['pagina'];?>');">
                <div class="button_icon">
                    <i class="fas fa-user-circle fa-lg"></i>
                </div>
                <div class="button_text">
                    MIJN PAGINA
                </div>
            </div>
        <br><br>
<?php 
		}
    
		if (!empty($user['rechten']['beheer']))
		  {
?>
        <div class="button_paars" onclick="window.location = '<?php echo $protocol . $domein; ?>?state=admin'">
            <div class="button_icon">
                <i class="fas fa-cogs fa-lg"></i>
            </div>
            <div class="button_text">
                BEHEER
            </div>
        </div>
        <br/>
        <div class="button_paars" onclick="window.open('<?php echo $protocol . $domein; ?>/webmail', '_blank');" >
            <div class="button_icon">
                <i class="fas fa-at fa-lg"></i>
            </div>
            <div class="button_text">
                WEBMAIL
            </div>
        </div>
        <br />

<?php		  
		  }        
?>       
        <div name="logoff" value="UITLOGGEN" class="button_rood" onClick="window.location='<?php echo $protocol.$domein; ?>?state=logoff';" >
            <div class="button_icon">
                <i class="fas fa-sign-out-alt fa-lg"></i>
            </div>
            <div class="button_text">
               UITLOGGEN
            </div>
        </div>
        </form>
    
    <?php 
	  }
	?>  
    </div>