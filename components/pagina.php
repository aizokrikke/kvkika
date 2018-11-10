<?php

if (empty($id)) { $id=1; }
$id=strtolower($id);
$q="select foto, lead, body, views, menu, inschrijfbutton from ".$sitestatus."pagina where (id='$id' or naam like '$id') and verwijderd!='j'";
//echo $q;
$p=mysql_fetch_row(mysql_query($q)); 
if (!empty($p))
  {	
	$foto=$p[0];
	$lead=stripslashes($p[1]);
	$body=stripslashes($p[2]);
	$views=$p[3];
	$views++;
	mysql_query("update ".$sitestatus."pagina set views='$views' where id='$id'");
	$s=$p[4];
	//$button=$p[5];
	$button='n';
  }
  else
  { $foto=1;
   	$lead="Geselecteerde pagina is niet gevonden";
   	$body='';
	$s=1;
	$button='n';
  }
if ($subdomein=='dev') { $s=$s+10; }  
  
include('components/header.php');
?>

<div id="linkerbalk">
<?php include('components/nieuws_box.php'); ?>
<br>
<?php include('components/twitter_box.php'); ?>
</div>


<div id="middenvlak">
<?php include('components/login_box_mobile.php'); ?>	
	<div class="foto"><img src="img/foto.php?id=<?php echo $foto; ?>">
    	<?php if ($button=='j') { ?><div class="inschrijven"><a href="?state=inschrijven"><img src="img/button_inschrijven.png"></a></div><?php } ?>
        <div id="oranje_balk"></div>
    </div>
    <div class="lead"><?php echo $lead; ?></div>
    <div class="body"><?php echo $body; ?></div>
	<?php get_boxen($id); ?>    


    
</div>

<div id="rechterbalk">
<?php include('components/login_box.php'); ?>
	<div class="sponsors">
    	<a href="https://www.facebook.com/KvKK033" target="_blank"><img src="img/KvKlike.jpg" alt="Like ons op Facebook" title="Like ons op Facebook"></a>
    </div>

    <div class="sponsors"><a href="http://www.kika.nl" target="_blank"><img src="img/kika_logo.jpg" alt="KiKa" title="KiKa"></a></div>
    <div id="sponsorbox">Dit evenement is tot <br />
stand gekomen met <br />
de inzet van:<br /><br />
		<div id="sponsorlogos">
    	<div id="logobox"><img src="img/Continental-Logo_150.png" /></div>
       </div> 
        <br><br>
         <center><a href="../img/KinderenvoorKiKa-Jaarverslag 2016.pdf" target="_blank"><img src="../img/anbilogo.jpg"></a></center>
	</div>
   
</div>

<div style="clear: both"></div>


<?php include('components/footer.php'); ?>

