<?php  
// formulieren processen

include('components/header.php');

?>
<div id="beheer">
<span class="kop"><a href="?state=admin"><img src="beheer/img/eleganticons-png/png/Config.png" align="absmiddle" height="24" width="24"> Beheer</a></span>


<?php

	$def=true;
	if (!empty($go))
	  {
		$row=mysql_fetch_row(mysql_query("select incl, recht from beheer where sectie='$go'"));
		$inc=$row[0];
		$recht=$row[1];
		$row=mysql_fetch_row(mysql_query("select recht from rechtendef where id='$recht'"));
		if ((!empty($user['rechten'][$row[0]])) and ($user['verbannen']!='j'))
		  {
			  
			  $def=false;
			  include($inc);
		  }

	  }
	if ($def)
	  {
?>
<hr>
<ul>
	<?php if (!empty($user['rechten']['deelnemers'])) {?>
    	<li><a href="?state=admin&go=deelnemers"><img src="beheer/img/Basic_set2_Png/Basic_set2_Png/user4_48.png" align="absmiddle"> Deelnemers</a></li>
    <?php } ?>

	<?php if (!empty($user['rechten']['beheerders'])) {?>
    	<li><a href="?state=admin&go=beheerders"><img src="beheer/img/Basic_set2_Png/Basic_set2_Png/user_info_48.png" align="absmiddle" alt="beheerers" title="beheerders""> Beheerders</a></li>
    <?php } ?>

	<?php if (!empty($user['rechten']['content'])) {?>
    	<li><a href="?state=admin&go=content"><img src="beheer/img/eleganticons-png/png/Paper.png" align="absmiddle" alt="content" title="content"> Content</a></li>
    <?php } 
		
		if (!empty($user['rechten']['draaiboek bekijken'])) {?>
    	<li><a href="?state=admin&go=draaiboek"><img src="beheer/img/eleganticons-png/png/List.png" align="absmiddle" alt="draaiboek" title="draaiboek"> Draaiboek</a></li>
    <?php } 

		if (!empty($user['rechten']['draaiboek bekijken'])) {?>
    	<li><a href="?state=admin&go=documenten"><img src="beheer/img/eleganticons-png/png/Folder.png" align="absmiddle" alt="documenten" title="documenten"> Documentenbeheer</a></li>
    <?php } 
	
		if (!empty($user['rechten']['beheer'])) {?>
    	<li><a href="?state=admin&go=settings"><img src="beheer/img/Basic_set2_Png/Basic_set2_Png/user_settings_48.png" align="absmiddle" alt="persoonlijke instellingen" title="persoonlijke instellingen"> Persoonlijke instellingen</a></li>
    <?php } 	

		if (!empty($user['rechten']['generalsettings'])) {?>
    	<li><a href="?state=admin&go=generalsettings"><img src="beheer/img/Basic_set2_Png/Basic_set2_Png/settings_48.png" align="absmiddle" alt="algemene instellingen" title="algemene instellingen"> Algemene instellingen</a></li>
    <?php } 	


	if (!empty($user['rechten']['database'])) {?>
    	<li><a href="https://<? echo $base_url;?>/phpMyAdmin" target="_blank"><img src="beheer/img/eleganticons-png/png/Database.png" align="absmiddle"> Database</a></li>	
    <?php }	
    	
    if (!empty($user['rechten']['maillog'])) {?>
    	<li><a href="?state=admin&go=maillog" target="_blank"><img src="beheer/img/eleganticons-png/png/Mail.png" align="absmiddle"> Mail log</a></li>
    <?php } ?>


</ul>
<?php
	  }
?>	 
</div> <!-- beheer --> 
</div> <!-- inner container -->
</div> <!-- outer container -->
<div id="footer"><div style="margin-left: 15px; padding-top:5px;"><?php if (!empty($user['id'])) { ?><a href="?state=logoff"><img src="beheer/img/24x24/actions/exit.png" align="absmiddle"> logout</a> | <?php echo $user['naam']; } ?></div></div>
</body>
</html>