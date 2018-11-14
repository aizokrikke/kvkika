<?php  
// formulieren processen

include('components/header.php');

?>
<div id="beheer">
<span class="kop"><a href="?state=admin"><i class="fas fa-cogs fa-sm"></i>  Beheer</a></span>


<?php

$def = true;
if (!empty($go)) {
    $row = db_row("select incl, recht from beheer where sectie = '$go'");
    $inc = $row[0];
    $recht = $row[1];
    $row = db_row("select recht from rechtendef where id='$recht'");
    if ((!empty($user['rechten'][$row[0]])) and ($user['verbannen'] != 'j')) {
          $def = false;
          include($inc);
    }
}
if ($def) {
?>
<hr>
<ul>
	<?php if (!empty($user['rechten']['deelnemers'])) {?>
    	<li><a href="?state=admin&go=deelnemers"><i class="fas fa-users fa-2x fa-fw"></i> Deelnemers</a></li>
    <?php } ?>

	<?php if (!empty($user['rechten']['beheerders'])) {?>
    	<li><a href="?state=admin&go=beheerders"><i class="fas fa-users-cog fa-2x fa-fw"></i> Beheerders</a></li>
    <?php } ?>

	<?php if (!empty($user['rechten']['content'])) {?>
    	<li><a href="?state=admin&go=content"><i class="fas fa-file-alt fa-2x fa-fw"></i> Content</a></li>
    <?php } 
		
		if (!empty($user['rechten']['draaiboek bekijken'])) {?>
    	<li><a href="?state=admin&go=draaiboek"><i class="fas fa-clipboard-list fa-2x fa-fw"></i> Draaiboek</a></li>
    <?php } 

		if (!empty($user['rechten']['draaiboek bekijken'])) {?>
    	<li><a href="?state=admin&go=documenten"><i class="fas fa-folder-open fa-2x fa-fw"></i> Documentenbeheer</a></li>
    <?php } 
	
		if (!empty($user['rechten']['beheer'])) {?>
    	<li><a href="?state=admin&go=settings"><i class="fas fa-user-cog fa-2x fa-fw"></i> Persoonlijke instellingen</a></li>
    <?php } 	

		if (!empty($user['rechten']['generalsettings'])) {?>
    	<li><a href="?state=admin&go=generalsettings"><i class="fas fa-cogs fa-2x fa-fw"></i> Algemene instellingen</a></li>
    <?php } 	


	if (!empty($user['rechten']['database'])) {?>
    	<li><a href="https://<? echo $base_url;?>/phpMyAdmin" target="_blank"><i class="fas fa-database fa-2x fa-fw"></i> Database</a></li>
    <?php }	
    	
    if (!empty($user['rechten']['maillog'])) {?>
    	<li><a href="?state=admin&go=maillog" target="_blank"><i class="fas fa-envelope fa-2x fa-fw"></i> Mail log</a></li>
    <?php } ?>


</ul>
<?php
}
?>	 
</div> <!-- beheer --> 
</div> <!-- inner container -->
</div> <!-- outer container -->
<div id="footer"><div style="margin-left: 15px; padding-top:5px;"><?php if (!empty($user['id'])) {
    ?><a href="?state=logoff"><img src="beheer/img/24x24/actions/exit.png" align="absmiddle"> logout</a> | <?php echo $user['naam'];
} ?></div></div>
</body>
</html>