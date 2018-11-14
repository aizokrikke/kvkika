<?php
$zoek = $_REQUEST['zoek'];
if (empty($id)) {
    $id = 1;
}
if(!$p = bd_row("select foto, lead, body, views, menu from " . $sitestatus . "pagina where id = '$id' and verwijderd != 'j'")) {
    $id = strtolower($id);
    $p = bd_row("select foto, lead, body, views, menu from " . $sitestatus .
        "pagina where naam like '$id' and verwijderd != 'j'");
}
if (!empty($p)) {
	$foto = $p[0];
	$lead = stripslashes($p[1]);
	$body = stripslashes($p[2]);
	$views = $p[3];
	$views++;
	db_query("update " . $sitestatus . "pagina set views = '$views' where id = '" . db_esc($id) .
	"'");
	$s = $p[4];
  } else {
    $foto = 1;
   	$lead = "Geselecteerde pagina is niet gevonden";
   	$body = '';
	$s = 1;
  }
if ($subdomein == 'dev') {
    $s = $s + 10; }
  
include('components/header.php'); ?>

<div id="linkerbalk">
<?php include('components/nieuws_box.php'); ?>
<br>
<?php include('components/twitter_box.php'); ?>
</div>


<div id="middenvlak">
	<div class="foto"><img src="img/foto.php?id=<?php echo $foto; ?>">
    	<div id="oranje_balk"></div>
    </div>
    
    <div class="lead"><h1>Deelnemers</h1></div>
    <div class="body">
    &bullet; Om een deelnemer te zoeken typt u in onderstaande balk de naam van de deelnemer en klikt u op "zoek"<br>
    &bullet; Als er meerdere namen verschijnen, klikt u op de naam van de deelnemer, die u zoekt<br>
    &bullet; Daarna komt u op de persoonlijke pagina van de deelnemer<br><br>
    <form action="?">
    <input type="hidden" name="state" value="deelnemers"/> 
    <input name="zoek" size="40" value="<?php echo $zoek;?>" class="zoek"  /> <Input type="submit" name="do" value="Zoek" class="button_rood" />
    </form><br /><br />
    <table>
<?php
	if (!empty($zoek)) {
	
		$q = "select deelnemers.pagina, personen.voornaam, personen.voorvoegsel, personen.achternaam from personen, deelnemers where deelnemers.persoon = personen.id and deelnemers.verwijderd != 'j' and personen.verwijderd != 'j' and deelnemers.bevestigd = 'j' ";
		$termen = explode(' ',$zoek);
		foreach ($termen as $val) {
		    $e=db_esc($val);
		  	$q .= "and (personen.voornaam like '%$val%' or personen.achternaam like '%$val%' or deelnemers.pagina like '%$e%') ";
		}
		$q .=" order by personen.achternaam, personen.voornaam";
		
		$dres = db_query($q);
		
		while ($dr = db_row($dres)) {
?>
		<tr>
			<td><a href="<?php echo $protocol . $domein; ?>/deelnemers/<?php echo $dr[0];?>"><?php if (!empty($dr[1])) {
			    echo $dr[1]." ";
			}
			if (!empty($dr[2])) {
			    echo $dr[2]." ";
			}
			echo $dr[3];?></a></td>
		 </tr>   
<?php    
		  }
	  }
?>	
	</table>
    </div>    
</div>

<div id="rechterbalk">
<?php include('components/login_box.php'); ?>

    <div class="sponsors"><a href="https://www.kika.nl" target="_blank"><img src="img/kika_logo.jpg" alt="KiKa" title="KiKa"></a></div>
    <div id="sponsorbox">Dit evenement is tot <br />
stand gekomen met <br />
de inzet van:<br /><br />
    	<div id="logobox"><img src="img/Continental-Logo_150.png" /></div>
</div>
</div>

<div style="clear: both"></div>


<?php include('components/footer.php'); ?>

