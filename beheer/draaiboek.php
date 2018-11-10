<?php
// draaiboek.php
// afhandeling van de draaiboeken


$f_soort=mysql_real_escape_string($_REQUEST['f_soort']);
$f_eigenaar=mysql_real_escape_string($_REQUEST['f_eigenaar']);
$f_stuurgroep=mysql_real_escape_string($_REQUEST['f_stuurgroep']);
$f_categorie=mysql_real_escape_string($_REQUEST['f_categorie']);
$f_status=mysql_real_escape_string($_REQUEST['f_status']);
$f_gereed=mysql_real_escape_string($_REQUEST['f_gereed']);
$f_start=mysql_real_escape_string($_REQUEST['f_start']);
$act=mysql_real_escape_string($_REQUEST['act']);
$do=$_REQUEST['do'];
$opnieuw=$_REQUEST['opnieuw'];
$ann=$_REQUEST['ann'];

  	$id=mysql_real_escape_string($_REQUEST['id']);
  	$naam=mysql_real_escape_string($_REQUEST['naam']);
	$soort=mysql_real_escape_string($_REQUEST['soort']);
	$eigenaar=mysql_real_escape_string($_REQUEST['eigenaar']);
	$stuurgroep=mysql_real_escape_string($_REQUEST['stuurgroep']);
	$categorie=mysql_real_escape_string($_REQUEST['categorie']);
	$status=mysql_real_escape_string($_REQUEST['status']);
	$gereed=mysql_real_escape_string($_REQUEST['gereed']);	
	$prio=mysql_real_escape_string($_REQUEST['prio']);
  	$db_start=mysql_real_escape_string($_REQUEST['db_start']);
  	$db_gereed=mysql_real_escape_string($_REQUEST['db_gereed']);
  	$db_start_uur=mysql_real_escape_string($_REQUEST['db_start_uur']);
  	$db_start_min=mysql_real_escape_string($_REQUEST['db_start_min']);
  	$db_eind_uur=mysql_real_escape_string($_REQUEST['db_eind_uur']);
  	$db_eind_min=mysql_real_escape_string($_REQUEST['db_eind_min']);
  	$beschrijving=mysql_real_escape_string($_REQUEST['beschrijving']);
  	$resultaat=mysql_real_escape_string($_REQUEST['resultaat']);
	$voortgang=mysql_real_escape_string($_REQUEST['voortgang']);
  	$materialen=mysql_real_escape_string($_REQUEST['materialen']);
  	$externen=mysql_real_escape_string($_REQUEST['externen']);
	$kosten=mysql_real_escape_string($_REQUEST['kosten']);	
	
	$gereed=$db_gereed+($db_eind_uur*3600)+($db_eind_min*60);
	$start=$db_start+($db_start_uur*3600)+($db_start_min*60);

	$u=$user['id'];
	$nu=time();

	
	if (empty($eigenaar)) { $eigenaar=$u; }
	
	$br=mysql_fetch_row(mysql_query("select notify_actie_change from beheerders where persoon='$eigenaar'"));
	$notify=$br[0];



if ($ann=='annuleren')
  { 
  	$act='';
	$do=''; 
	
	// locks verwijderen
	$lr=mysql_fetch_row(mysql_query("select lock_id from draaiboek where id='$id'"));
	if ($u==$lr[0]) 
	  {
		mysql_query("update draaiboek set lock_id='0' where id='$id'");
		mysql_query("update draaiboek_docs set lock_id='0' where id='$id'");
		mysql_query("update draaiboek_docs set tmp_verwijderd=verwijderd, tmp_draaiboek=draaiboek where draaiboek='$id'");
	  }
  }

if (!empty($do))
  { // formulier afwikkelen
	
	// fouten afhandelen
	
	if ($do!='verwijderen')
	  {
		if (empty($naam)) { $err[]="geen actie ingevoerd"; }
	  }
	if (empty($err))
	  {		
		switch ($do) {
			case 'toevoegen':
				mysql_query("insert into draaiboek (naam, soort, eigenaar, stuurgroep, categorie, prio, status, start, gereed, beschrijving, resultaat, voortgang, benodigdheden, externen, kosten, auteur, aangemaakt, mutatie, mutatie_door, lock_id, lock_tijd) values ('$naam', '$soort', '$eigenaar', '$stuurgroep', '$categorie', '$prio', '$status', '$start', '$gereed', '$beschrijving', '$resultaat', '$voortgang','$materialen', '$externen', '$kosten','$u','$nu','$nu','$u','0','0')") or die(mysql_error());
				$id=mysql_insert_id();
				
				mysql_query("update draaiboek_docs set verwijderd=tmp_verwijderd, draaiboek='$id', lock_id='0', lock_tijd='' where tmp_draaiboek='1'") or die(mysql_error());
				
				if (($eigenaar!=$u) and ($notify=='j'))
				   { 
				   	  $tekst="De actie '".$naam."' is voor u aangemaakt in het online draaiboek.\n\nhttps://www.kinderenvoorkika.nl?state=admin&go=draaiboek&id=".$id."&act=edit";
				   	  stuur_bericht($eigenaar,'Er is een actie voor u aangemaakt',$tekst); 
					}
				$act='';
				$do='';
			break;
			
			case 'opslaan':
				mysql_query("update draaiboek set naam='$naam', soort='$soort',eigenaar='$eigenaar', stuurgroep='$stuurgroep', categorie='$categorie', prio='$prio', status='$status', start='$start', gereed='$gereed', beschrijving='$beschrijving', resultaat='$resultaat', voortgang='$voortgang', benodigdheden='$materialen', externen='$externen', kosten='$kosten', mutatie='$nu', mutatie_door='$u', lock_id='0', lock_tijd='0' where id='$id'") or die(mysql_error());
				// update docs
				mysql_query("update draaiboek_docs set verwijderd=tmp_verwijderd, draaiboek=tmp_draaiboek, lock_id='0', lock_tijd='' where tmp_draaiboek='$id'") or die(mysql_error());
				
				if (($eigenaar!=$u) and ($notify=='j'))
				  { 
				   	$tekst="De actie '".$naam."' is gewijzigd in het online draaiboek.\n\nhttps://www.kinderenvoorkika.nl?state=admin&go=draaiboek&id=".$id."&act=edit";
				  
				  	stuur_bericht($eigenaar,'Uw actie is gewijzigd',$tekst); 
				  }
				$act='';
				$do='';
			break;
			
			case 'verwijderen':
				mysql_query("update draaiboek set verwijderd='j', lock_id='0', lock_tijd='0' where id='$id'") or die(mysql_error());
				$act='';
				$do='';
			break;
		} // switch
	  } // if !empty $err
  } // if !empty $do
?>
<span class="kop"><a href="?state=admin&go=draaiboek"><img src="beheer/img/eleganticons-png/png/List.png" alt="draaiboek" title="draaiboek" align="absmiddle" height="24" width="24"> Draaiboek</a></span>
<div id="db_mask"></div>
<div id="db_upload"></div>
</div>
<?php
if (empty($act))
  {
	if (empty($f_soort)) { $f_soort='voorbereiding'; }
?>



<div id="db_menubalk">
<form method="post" action="?">
<input type="hidden" name="go" value="<?php echo $go; ?>">
<input type="hidden" name="state" value="<?php echo $state; ?>">

<input name="f_soort" value="dag" type="radio" <?php if ($f_soort=='dag') { ?> checked="checked" <?php } ?>  onClick="form.submit();"> evenement <input type="radio" name="f_soort" value="voorbereiding" <?php if ($f_soort=='voorbereiding') { ?> checked="checked" <?php } ?>  onClick="form.submit();"> voorbereiding | eigenaar <select name="f_eigenaar" onChange="form.submit();"><option value="">-- alle --</option><?php
$res=mysql_query("select personen.id, personen.voornaam, personen.voorvoegsel, personen.achternaam from personen, beheerders where beheerders.persoon= personen.id and personen.verwijderd!='j' and beheerders.verwijderd!='j' and beheerders.actief='j' order by achternaam") or die(mysql_error());
while ($r=mysql_fetch_row($res))
  {
?>
	<option value="<?php echo $r[0]; ?>" <?php if ($r[0]==$f_eigenaar) { ?> selected="selected" <?php } ?> ><?php echo $r[1]; if (!empty($r[2])) { echo " $r[2]"; } if (!empty($r[3])) { echo " $r[3]"; } ?></option>
<?php	  
  } // while
?></select> | stuurgroep <select name="f_stuurgroep" onChange="form.submit();"><option value="">-- alle --</option>
<?php
$res=mysql_query("select id, naam from stuurgroepen where verwijderd!='j' order by naam") or die(mysql_error());
while ($r=mysql_fetch_row($res))
  {
?>
	<option value="<?php echo $r[0]; ?>" <?php if ($r[0]==$f_stuurgroep) { ?> selected="selected" <?php } ?> ><?php echo $r[1]; ?></option>
<?php	  
  } // while
?>
</select><br />
categorie <select name="f_categorie" onChange="form.submit();"><option value="">-- alle --</option>
<?php
$res=mysql_query("select id, naam from draaiboek_categorie where verwijderd!='j' order by naam") or die(mysql_error());
while ($r=mysql_fetch_row($res))
  {
?>
	<option value="<?php echo $r[0]; ?>" <?php if ($r[0]==$f_categorie) { ?> selected="selected" <?php } ?> ><?php echo $r[1]; ?></option>
<?php	  
  } // while
?>
</select> | status <select name="f_status"><option value="">-- alle --</option><option value="niet gestart" <?php if ($f_status=='niet gestart') { ?> selected="selected" <?php } ?>>niet gestart</option><option value="gestart" <?php if ($f_status=='gestart') { ?> selected="selected" <?php } ?>>gestart</option></select> <input type="checkbox" name="f_gereed" value="j" <?php if ($f_gereed=='j') { ?> checked="checked" <?php } ?>  onClick="form.submit();"> gereed weergeven <input type="checkbox" name="f_start" value="j" <?php if ($f_start=='j') { ?> checked="checked" <?php } ?>  onClick="form.submit();"> start weergeven
</form>
</div>

<?php

$q="select id, naam, start, gereed, beschrijving, status, prio, resultaat, eigenaar, stuurgroep, soort from draaiboek where verwijderd!='j' and soort='$f_soort'";
if ($f_status=='niet gestart') { $q.=" and (status='niet gestart' "; }
if ($f_status=='gestart') { $q.=" and (status='niet gestart'  or status='gestart' "; }
if ($f_gereed=='j') 
	{
		if (!empty($f_status))
		  { $q.=" or status='gereed') "; }
	}
	else
	{
		if ((!empty($f_status)) and ($f_status!='gereed')) { $q.=") "; }
		$q.=" and status!='gereed ' ";
	}
if (!empty($f_eigenaar)) { $q.=" and eigenaar='$f_eigenaar' "; }
if (!empty($f_stuurgroep)) { $q.=" and stuurgroep='$f_stuurgroep' "; }
if (!empty($f_categorie)) { $q.=" and categorie='$f_categorie' "; }

$q.=" order by gereed asc, prio, start asc";

// echo $q;


$res=mysql_query($q) or die(mysql_error());
?>
<br />
<a href="xlsexport.php?f_catergorie=<?php echo $f_categorie; ?>&f_stuurgroep=<?php echo $f_stuurgroep; ?>&f_eigenaar=<?php echo $f_eigenaar; ?>&f_status=<?php echo $f_status; ?>&f_gereed=<?php echo $f_gereed; ?>&f_soort=<?php echo $f_soort; ?>"><img src="beheer/img/excel.png" width="24" height="24" alt="export naar excel" title="export naar excel" align="absmiddle" /> exporteren naar Excel</a><br />

<br>
<table class="db_table">
<tr><td></td><td></td><td></td><td></td><td></td><?php if ($f_start=='j') {?><td></td><td align="center">start</td><?php } ?><td></td><td align="center">gereed</td><td></td><td align="center">status</td><td></td><td>eigenaar</td></tr>
<?php
while ($r=mysql_fetch_row($res))
  {
?>
	<tr <?php if ($r[5]=='gereed') { ?>class="gereed"<?php } else if ($r[5]=='gestart') {?>class="gestart"<?php } ?> >
    	<?php if (!empty($user['rechten']['draaiboek beheer']))
		  {
		?>
    	<td><a href="?state=admin&go=draaiboek&id=<?php echo $r[0]; ?>&act=edit&f_soort=<?php echo $f_soort; ?>&f_eigenaar=<?php echo $f_eigenaar; ?>&f_stuurgroep=<?php echo $f_stuurgroep; ?>&f_categorie=<?php echo $f_f_categorie; ?>&f_status=<?php echo $f_status; ?>&f_gereed=<?php echo $f_gereed; ?>"><img src="beheer/img/eleganticons-png/png/Pencil.png" width="24" height="24" alt="bewerken" title="bewerken"></a></td>
            	<td><a href="?state=admin&go=draaiboek&id=<?php echo $r[0]; ?>&act=copy&f_soort=<?php echo $f_soort; ?>&f_eigenaar=<?php echo $f_eigenaar; ?>&f_stuurgroep=<?php echo $f_stuurgroep; ?>&f_categorie=<?php echo $f_f_categorie; ?>&f_status=<?php echo $f_status; ?>&f_gereed=<?php echo $f_gereed; ?>"><img src="beheer/img/24x24/editcopy.png" width="24" height="24" alt="kopieren" title="kopieren"></a></td>

            	<td><a href="?state=admin&go=draaiboek&id=<?php echo $r[0]; ?>&act=del&f_soort=<?php echo $f_soort; ?>&f_eigenaar=<?php echo $f_eigenaar; ?>&f_stuurgroep=<?php echo $f_stuurgroep; ?>&f_categorie=<?php echo $f_f_categorie; ?>&f_status=<?php echo $f_status; ?>&f_gereed=<?php echo $f_gereed; ?>"><img src="beheer/img/eleganticons-png/png/X.png" width="24" height="24" alt="verwijderen" title="verwijderen"></a></td>
        <td width="10"></td>
    	<td width="450"><a href="?state=admin&go=draaiboek&id=<?php echo $r[0]; ?>&act=edit&f_soort=<?php echo $f_soort; ?>&f_eigenaar=<?php echo $f_eigenaar; ?>&f_stuurgroep=<?php echo $f_stuurgroep; ?>&f_categorie=<?php echo $f_f_categorie; ?>&f_status=<?php echo $f_status; ?>&f_gereed=<?php echo $f_gereed; ?>" alt="<?php echo strip_html(stripslashes($r[4]));?>" title="<?php echo strip_html(stripslashes($r[4]));?>"><?php echo stripslashes($r[1]);?></a></td>                

        <?php 
		  }
		  else
		  {
		?>  
		<td><a href="javascript:void();" alt="<?php echo strip_html(stripslashes($r[4]));?>" title="<?php echo strip_html(stripslashes($r[4]));?>"><?php echo stripslashes($r[1]);?></a></td>
		<?php
		  }
		if ($f_start=='j') 
			{  
		?>  
        <td width="10"></td>
		<td align="center"><?php if ($r[10]=='voorbereiding') {  echo strftime("%d %b %Y",$r[2]); } else { echo strftime("%d %b %Y<br>%H:%M", $r[2]); }?></td>
        <?php 
			} 
		?>
        <td width="10"></td>
		<td><a href="javascript: void();" alt="<?php echo strip_html(stripslashes($r[7]));?>" title="<?php echo strip_html(stripslashes($r[7]));?>"><?php if ($r[10]=='voorbereiding') {  echo strftime("%d %b %Y",$r[3]); } else { echo strftime("%H:%M", $r[3]); }?></a></td>
       	<td width="10"></td>
        <td align="center"><?php echo $r[5]; ?></td>
       	<td width="10"></td>
        <td><?php echo persoon($r[8]); ?></td>
<?php
  }
?> 
</table>
<?php if (!empty($user['rechten']['draaiboek beheer']))
   {
?>			  
<br>
<br>
<a href="?state=admin&go=draaiboek&act=new"><img src="beheer/img/eleganticons-png/png/Plus.png" height="24" width="24" align="absmiddle" alt="toevoegen" title="toevoegen"> toevoegen</a>
<?php
	}
	
  }
  else
  {
	  if ($act=='del')
	    {
			$r=mysql_fetch_row(mysql_query("select id, naam, eigenaar, status, start, gereed, beschrijving from draaiboek where id='$id'")) or die(mysql_error());
					  $naam=stripslashes($r[1]);
					  $eigenaar=stripslashes($r[2]);
					  $status=stripslashes($r[3]);
					  $db_start=stripslashes($r[4]);
					  $db_gereed=stripslashes($r[5]);
					  $beschrijving=stripslashes($r[6]);
					  
?>
	<form method="post" action="?">
    <input type="hidden" name="go" value="<?php echo $go; ?>">
	<input type="hidden" name="state" value="<?php echo $state; ?>">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    	Het actiepunt <br>
        <strong><?php echo $naam; ?></strong> van <?php echo persoon($eigenaar); ?><br>
        start: <?php echo strftime("%d %B %Y",$db_start); ?><br>
        einde: <?php echo strftime("%d %B %Y",$db_gereed); ?><br><br>
        echt verwijderen ?<br><br>
    
    	<input type="submit" name="do" value="verwijderen" class="db_button"> <input type="submit" name="annuleren" value="annuleren" class="db_button"> 
    </form>
<?php					  
			
		}
		else
		{
			
		 	if ($act!='new')
			  {
				if ($opnieuw!='j')  
				  {
					  $r=mysql_fetch_row(mysql_query("select id, naam, eigenaar, stuurgroep, status, prio, categorie, start, gereed, beschrijving, resultaat, voortgang, benodigdheden, kosten, externen, soort, aangemaakt, auteur, mutatie, mutatie_door,lock_id, lock_tijd from draaiboek where id='$id'")) or die(mysql_error());
					  $naam=stripslashes($r[1]);
					  $eigenaar=stripslashes($r[2]);
					  $stuurgroep=stripslashes($r[3]);
					  $status=stripslashes($r[4]);
					  $prio=stripslashes($r[5]);
					  $categorie=stripslashes($r[6]);
					  $db_start=stripslashes($r[7]);
					  $db_gereed=stripslashes($r[8]);
					  $beschrijving=stripslashes($r[9]);
					  $resultaat=stripslashes($r[10]);
					  $voortgang=stripslashes($r[11]);
					  $materialen=stripslashes($r[12]);
					  $kosten=stripslashes($r[13]);
					  $externen=stripslashes($r[14]);
					  $aangemaakt=$r[16];
					  $auteur=$r[17];
					  $mutatie=$r[18];
					  $mutatie_door=$r[19];
					  $soort=stripslashes($r[15]);
					  $db_start_uur=strftime('%H',$db_start);
					  $db_start_min=strftime('%M',$db_start);
					  $db_eind_uur=strftime('%H',$db_gereed);
					  $db_eind_min=strftime('%M',$db_gereed);
					  $lock=$r[20];
					  $lock_tijd=$r[21];
					  $locked=((!empty($lock)) and ($lock<>$u));
					  
					  // lock zetten
					  $u=$user['id'];
					  $tijd=time();
					  mysql_query("update draaiboek_docs set lock_id='$u', lock_tijd='$tijd', tmp_verwijderd=verwijderd, tmp_draaiboek=draaiboek where draaiboek='$id'") or die(mysql_error());
					  
				  }
			  }
			
			if (empty($soort)) { $soort='voorbereiding'; }
			
			if (!empty($err))
			  {
				  foreach ($err as $val) { ?><div class="error">FOUT: <?php echo $val; ?></div><?php }
			  }
			if ($locked) { ?> <div class="error">LET OP: Deze actie is door een andere gebruiker in gebruik. U kunt nu gee wijzigingen opslaan.</div><?php }  
?>		  
	<form method="post" id="aform">
    <input type="hidden" name="go" value="<?php echo $go; ?>">
	<input type="hidden" name="state" value="<?php echo $state; ?>">
    <input type="hidden" name="opnieuw" value="j">
    <input type="hidden" name="id" value="<?php echo $id;?>">
	<input type="hidden" name="f_soort" value="<?php echo $f_soort; ?>">
	<input type="hidden" name="f_stuurgroep" value="<?php echo $f_stuurgroep; ?>">
	<input type="hidden" name="f_eigenaar" value="<?php echo $f_eigenaar; ?>">
	<input type="hidden" name="f_categorie" value="<?php echo $f_categorie; ?>">
	<input type="hidden" name="f_status" value="<?php echo $f_status; ?>">
	<input type="hidden" name="f_gereed" value="<?php echo $f_gereed; ?>">
    
    
    <br />
    <div class="db_in_box">
    actie<br>
    <input type="text" name="naam" value="<?php echo $naam; ?>" class="db_input_naam">
    </div>
    
    <div class="afsluiten"></div>
    
    <div class="db_in_box">
    draaiboek<br>
    <div class="db_input">
    <input type="radio" name="soort" value="voorbereiding" <?php if ($soort=='voorbereiding') { ?> checked="checked"<?php } ?> onClick="flip_draaiboek('voorbereiding');"> voorbereiding <input type="radio" name="soort" value="dag" <?php if ($soort=='dag') { ?> checked="checked"<?php } ?> onClick="flip_draaiboek('dag');"> evenement</div>
	</div>
    
    <div class="db_in_box">
    eigenaar<br>
    <select name="eigenaar" class="db_input">
<?php
	$res=mysql_query("select personen.id, voornaam, voorvoegsel, achternaam from personen, beheerders where beheerders.persoon=personen.id and beheerders.verwijderd!='j' and personen.verwijderd!='j' order by achternaam");
	while ($r=mysql_fetch_row($res))
	  {
?>
	<option value="<?php echo $r[0]; ?>" <?php if ($r[0]==$eigenaar) { ?> selected="selected" <?php } ?>><?php echo $r[1]; if (!empty($r[2])) { echo " $r[2]"; } if (!empty($r[3])) { echo " $r[3]"; } ?></option>
<?php		  
	  } // while
?>    
    
    </select>
   </div>
   <div class="db_in_box">
    stuurgroep<br>
    <select name="stuurgroep" class="db_input">
<?php
	$res=mysql_query("select id, naam from stuurgroepen where verwijderd!='j' order by naam");
	while ($r=mysql_fetch_row($res))
	  {
?>
	<option value="<?php echo $r[0]; ?>" <?php if ($r[0]==$stuurgroep) { ?> selected="selected" <?php } ?>><?php echo $r[1]; ?></option>
<?php		  
	  } // while
?>    
    
    </select>
    </div>
    
    <div class="afsluiten"></div>
        
    <div class="db_in_box">
    beschrijving<br>
    <textarea name="beschrijving"><?php echo $beschrijving; ?></textarea>
    </div>
    
    <div class="db_in_box">
    resultaat<br>
    <textarea name="resultaat"><?php echo $resultaat; ?></textarea>
    </div>    
    <div class="db_in_box">
    voortgang<br>
    <textarea name="voortgang"><?php echo $voortgang; ?></textarea>
    </div>    
    
    <div class="afsluiten"></div>
    
    
    <div class="db_in_box">
    	start
        <div id="db_start"><?php $div="db_start"; $datum_select=$db_start; include('workers/datum_invoer_body.php'); ?></div>
        <div id="db_start_tijd"  <?php if ($soort=='voorbereiding') { ?> style="display:none"<?php } ?>>tijd <input type="text" size="2" maxlength="2" name="db_start_uur" value="<?php echo $db_start_uur; ?>">:<input type="text" size="2" maxlength="2" name="db_start_min" value="<?php echo $db_start_min; ?>"></div>
    </div>
    
     <div class="db_in_box">
    	gereed
        <div id="db_gereed"><?php $div="db_gereed"; $datum_select=$db_gereed; include('workers/datum_invoer_body.php'); ?></div>
        <div id="db_eind_tijd" <?php if ($soort=='voorbereiding') { ?> style="display:none"<?php } ?>>tijd <input type="text" size="2" maxlength="2" name="db_eind_uur" value="<?php echo $db_eind_uur; ?>">:<input type="text" size="2" maxlength="2" name="db_eind_min" value="<?php echo $db_eind_min; ?>"></div>

    </div>   

    <div class="db_in_box">
    categorie<br>
    <select name="categorie" class="db_input">
<?php
	$res=mysql_query("select id, naam from draaiboek_categorie where verwijderd!='j' order by naam");
	while ($r=mysql_fetch_row($res))
	  {
?>
	<option value="<?php echo $r[0]; ?>" <?php if ($r[0]==$categorie) { ?> selected="selected" <?php } ?>><?php echo $r[1]; ?></option>
<?php		  
	  } // while
?>    
    </select><br>
    <br>
    status<br>
    <select name="status" class="db_input">
    	<option value="niet gestart" <?php if ($status=='niet gestart') { ?> selected="selected"<?php } ?>>niet gestart</option>
    	<option value="gestart" <?php if ($status=='gestart') { ?> selected="selected"<?php } ?>>gestart</option>
    	<option value="gereed" <?php if ($status=='gereed') { ?> selected="selected"<?php } ?>>gereed</option>
    </select><br>
   <br>
    prioriteit<br>
    <select name="prio" class="db_input">
    	<option value="showstopper" <?php if ($prio=='showstopper') { ?> selected="selected"<?php } ?>>showstopper</option>
    	<option value="must have" <?php if ($prio=='must have') { ?> selected="selected"<?php } ?>>must have</option>
    	<option value="nice to have" <?php if ($prio=='nice to have') { ?> selected="selected"<?php } ?>>nice to have</option>
    	<option value="laag" <?php if ($prio=='laag') { ?> selected="selected"<?php } ?>>laag</option>
    </select>
    </div>
   
    <div class="afsluiten"></div>
    <br>
    <h3>Benodigdheden</h3>
    
    <div class="db_in_box">
    materialen<br>
    <textarea name="materialen"><?php echo $materialen; ?></textarea>
    </div>
    <div class="db_in_box">
    externe partijen<br>
    <textarea name="externen"><?php echo $externen; ?></textarea>
    </div>
    <div class="db_in_box">
    kosten &amp; sponsors<br>
    <textarea name="kosten"><?php echo $kosten; ?></textarea>
    </div>
    
     <div class="afsluiten"></div>
   
   
	 <div id="docs"><?php include('beheer/workers/db_docs.php'); ?></div>
     
     <br /><br />
     <input type="submit" name="do" value="<?php if (($act=='new') or ($act=='copy')) { ?>toevoegen<?php } else { ?>opslaan<?php } ?>" class="db_button" <?php if ($locked) { ?>disabled="disabled"<?php } ?>> <input type="submit" name="ann" value="annuleren" class="db_button">
     <br /><br /><br />
    
 	<?php if ($act!='new') { ?>   
     <div class="db_small">aangemaakt op <?php echo strftime("%d %B %Y",$aangemaakt); ?> door <?php echo persoon($auteur); ?>, laatste wijziging op <?php echo strftime("%d %B %Y",$mutatie); ?> door <?php echo persoon($mutatie_door); ?></div>
    <?php } ?>
    
    
    <script type="text/javascript">
		editor = CKEDITOR.replace( 'beschrijving', { toolbar : [
						{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
						{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker' ] }	
					], uiColor : '#560666',  width : 304, height : 150
				});
		editor2 = CKEDITOR.replace( 'resultaat', { toolbar : [
						{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
						{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker' ] }	
					], uiColor : '#560666',  width : 304, height : 150
				});	
		editor3 = CKEDITOR.replace( 'materialen', { toolbar : [
						{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
						{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker' ] }	
					], uiColor : '#560666',  width : 304, height : 150
				});	
		editor4 = CKEDITOR.replace( 'externen', { toolbar : [
						{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
						{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker' ] }	
					], uiColor : '#560666',  width : 304, height : 150
				});	
		editor5 = CKEDITOR.replace( 'kosten', { toolbar : [
						{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
						{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker' ] }	
					], uiColor : '#560666',  width : 304, height : 150
				});	
		editor6 = CKEDITOR.replace( 'voortgang', { toolbar : [
						{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
						{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker' ] }	
					], uiColor : '#560666',  width : 304, height : 150
				});                
        </script>			
    
    
    </form>

<?php	 
	} // if act == del
 } //  if empty $act	
	
?>   