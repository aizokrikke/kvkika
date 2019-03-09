<?php

require('libs/connect.php');
require('logon/check_logon.php');
require('libs/tools.php');
require('libs/php-iban/php-iban.php');
require('libs/is_email.php');

//variablen afvangen
$pag = strtolower($_REQUEST['pag']);
$act = strtolower($_REQUEST['act']);
$go = strtolower($_REQUEST['go']);
$do = strtolower($_REQUEST['do']);
$id = strtolower($_REQUEST['id']);
$opnieuw = strtolower($_REQUEST['opnieuw']);
$berichten_toestaan = strtolower($_REQUEST['berichten_toestaan']);
$show_stand = strtolower($_REQUEST['show_stand']);
$show_berichten = strtolower($_REQUEST['show_berichten']);
$school = strtolower($_REQUEST['school']);
$doel = $_REQUEST['doel'];
$motivatie = $_REQUEST['motivatie'];
$pass1 = $_REQUEST['pass1'];
$pass2 = $_REQUEST['pass2'];
$message = $_REQUEST['message'];


// paginagegevens ophalen
$pagcode = utf8_decode($pag);
$state = 'deelnemer';
$pres = db_query("select personen.voornaam,
							personen.voorvoegsel,
							personen.achternaam,
							deelnemers.foto,
							deelnemers.motivatie,
							deelnemers.doel,
							deelnemers.show_berichten,
							deelnemers.show_stand,
							deelnemers.bevestigd,
							personen.gebdatum,
							personen.plaats,
							deelnemers.school,
							deelnemers.id,
							deelnemers.berichten_toestaan,
							personen.id,
							deelnemers.categorie
							from deelnemers, personen
							where 
								deelnemers.pagina='" . db_esc($pagcode) . "' and 
								deelnemers.persoon = personen.id and 
								deelnemers.verwijderd != 'j' and
								deelnemers.bevestigd != 'n'");
if ($pr = db_row($pres)) {
    if ($pr[8] == 'j') {
    $pagina['deelnemer'] = $pr[0];
    if ($pr[15] != 'estafette') {
        if (!empty($pr[1])) {
            $pagina['deelnemer'] .= " " . $pr[1];
        }
        if (!empty($pr[2])) {
            $pagina['deelnemer'] .= " " . $pr[2];
        }
      } else {
        $pagina['deelnemer'] = $pr[2];
      }
    $pagina['soort'] = $pr[15];
    $pagina['deelnemer'] = stripslashes($pagina['deelnemer']);
    $pagina['voornaam'] = stripslashes($pr[0]);
    $pagina['foto'] = $pr[3];
    $pagina['show'] = 'j';
    $pagina['motivatie'] = stripslashes($pr[4]);
    $pagina['doel'] = stripslashes($pr[5]);
    $pagina['show_berichten'] = $pr[6];
    $pagina['show_stand'] = $pr[7];
    $pagina['gebdatum'] = $pr[9];
    $pagina['plaats'] = $pr[10];
    $sr = db_row("select naam from scholen where id = '$pr[11]'");
    $pagina['school_id'] = $pr[11];
    $pagina['school'] = stripslashes($sr[0]);
    $pagina['id'] = $pr[12];
    $pagina['usr'] = $pr[14];
    $pagina['berichten_toestaan'] = $pr[13];
    $txt['twitter'] = $pagina['deelnemer'] . " doet mee aan 'De Berg Op' op " . event_datum() . " om geld in te zamelen voor KiKa. help je mee? #kindvoorkika ";
    $txt['fb'] = $pagina['deelnemer'] . " doet mee aan 'De Berg Op' op " . event_datum() . " om geld in te zamelen voor KiKa. help je mee? ";

    $metatags[] = '<meta property="og:type" content="article">';
    $metatags[] = '<meta property="og:url" content="' . $protocol.$domein . '/deelnemers/' . rawurlencode($pagcode).'/">';
    $metatags[] = '<meta property="og:site_name" content="Kinderen voor KiKa | De Berg Op">';
    $metatags[] = '<meta property="og:title" content="' . $pagina['deelnemer'] . ' doet mee aan Kinderen voor KiKa" />';
    $metatags[] = '<meta property="og:description" content="' . $txt['fb'].'" />';
    $metatags[] = '<meta property="og:image" content="' . $protocol.$domein . '/fotos/' . $pagina['foto'].'" />';
    } else {
    $pagina['deelnemer'] = 'pagina niet beschikbaar';
    $pagina['foto'] = 'not_found.png';
    $pagina['show'] = 'n';
    }
} else {
    $pagina['deelnemer'] = 'niet gevonden';
    $pagina['foto'] = 'not_found.png';
    $pagina['show'] = 'n';
}


switch ($do) {
	case 'verwijderen':
		db_query("update berichten set verwijderd='j' where id='" .db_esc($id) . "'");
		$go = '';
		$do = '';
	break;
	
	case 'melden':
		//mail('info@kinderenvoorkika.nl','Er is een bericht gemeld dat niet OK is',"het volgende bericht is als niet ok gemeld:\n\nhttps://".$base_url."?state=admin&go=moderatie&bericht=".$id);
		//mail('aizo@kinderenvoorkika.nl','Er is een bericht gemeld dat niet OK is',"het volgende bericht is als niet ok gemeld:\n\nhttps://".$base_url."?state=admin&go=moderatie&bericht=".$id);		
		$go = '';
		$do = '';
		$message = "Het bericht is aan de moderator gemeld. Hartelijk dank.";
	break;
		
	case 'bevestigen':
	// foto upload  
	// 132 x 177 pixels
	
		if ($user['id'] == $pagina['usr']) {
			if (is_uploaded_file($_FILES['foto']['tmp_name'])) {
				$dr = db_row("select alias from deelnemers where id='" . db_esc($id)."' and verwijderd != 'j'");
				$naamparts = explode('.', $_FILES['foto']['name']);
				$ext = strtolower(array_pop($naamparts));
				if (($ext == 'jpg') or ($ext == 'jpeg') or ($ext == 'png') or ($ext == 'gif')) {
					$filenaam = $dr[0].time() . '.png';
					$filenaam_org = $dr[0].time() . '_org.' . $ext;
					if ($sitestatus != "dev_") {
						$dest = $foto_dir.'/'.$filenaam_org;
					   	$resized = $foto_dir.'/'.$filenaam;
					  } else {
						$dest = $foto_dir_dev.'/'.$filenaam_org;
						$resized = $foto_dir_dev.'/'.$filenaam;
					  }
					if (move_uploaded_file($_FILES['foto']['tmp_name'], $dest)) {
						// eerst roteren?
						if (preg_match('@\x12\x01\x03\x00\x01\x00\x00\x00(.)\x00\x00\x00@', file_get_contents($dest), $matches)) {
						    $orientation = ord($matches[1]);
						}
						echo "<!-- orientation: $orientation, file: $dest -->";
						
						switch ($orientation) {
							case 3:
								rotate_image($dest, 180);
							break;
							
							case 6:
								rotate_image($dest, -90);
							break;

							case 8:
								rotate_image($dest, -90);
							break;
						}

						// resizen
						list($width, $height, $type, $attr) = getimagesize($dest);
						
						switch ($ext) {
							case 'jpg';
							case 'jpeg':
								$im = imagecreatefromjpeg($dest);
							break;
							
							case 'gif':
								$im = imagecreatefromgif($dest);
							break;
							
							case 'png':
								$im = imagecreatefrompng($dest);
							break;
						} // switch
						
						$verhouding = 177 / 132;
						
						if ($height >= ($width * $verhouding)) {
							  // hoogte is in verhouding groter
							  $sw = $width;
							  $sh = $width * $verhouding;
							  $sy = round(($height - $sh) / 2);
							  $sx = 0;
						  } else {
						    // breedte is in verhouding groter
							$sh = $height;
							$sw = $height / $verhouding;
							$sx = round(($width - $sw) / 2);
							$sy = 0;
						  }
					
						$im_res = imagecreatetruecolor(132,177);
						imagecopyresampled($im_res, $im,0,0, $sx, $sy,132,177, $sw, $sh);
						imagepng($im_res, $resized);
						imagedestroy($im);
						imagedestroy($im_res);
						
						// opslaan in database
						db_query("update deelnemers set foto='$filenaam' where id='". db_esc($id) . "'");
						$pagina['foto'] = $filenaam;
					  }
				  }
			  } // if is_uploaded_file

			// settings opslaan
			db_query("update deelnemers set berichten_toestaan='" . db_esc($berichten_toestaan) . "', show_stand='" .
                db_esc($show_stand) . "', show_berichten='" . db_esc($show_berichten) . "', doel='" . db_esc($doel) .
                "', motivatie='" . db_esc($motivatie) . "', school='". db_esc($school) . "' where id = '" . db_esc($id). "'");
			$sr = db_row("select naam from scholen where id = '" . db_esc($school) . "'");
			$pagina['school'] = $sr[0];
			$pagina['school_id'] = $school;
			$pagina['berichten_toestaan'] = $berichten_toestaan;
			$pagina['show_stand'] = $show_stand;
			$pagina['show_berichten'] = $show_berichten;
			$r = db_row("select doel, motivatie from deelnemers where id='" . db_esc($id) . "'");
			$pagina['doel'] = stripslashes($r[0]);
			$pagina['motivatie'] = stripslashes($r[1]);
			
			// wachtwoord verwerken
			if (!empty($pass1)) {
				  if ($pass1 != $pass2) {
						$fout[] = "Wachtwoorden zijn niet gelijk";
					} else {
						$pass_md5 = md5($pass1);
						$pr = db_row("select persoon from deelnemers where id = '" . db_esc($id) . "'");
						$persoon = $pr[0];
						db_query("update personen set password_md5 = '$pass_md5' where id='" . db_esc($persoon) . "'");
						$do = '';
						$go = '';
					}
			  } else {
				$do = '';
				$go = '';
			  }
	  } // if...
	break;
	
	case 'verstuur';
	case 'akkoord':
		$bedrag = $_REQUEST['bedrag'];
		if (strtolower($bedrag) == 'bedrag') {
		    $bedrag = 0;
		}
		$bedrag = preg_replace('/,/','.', $bedrag);
		$naam = $_REQUEST['naam'];
		$email = $_REQUEST['email'];
		$bankrekening = $_REQUEST['bankrekening'];
		$onderwerp = $_REQUEST['onderwerp'];
		$bericht = $_REQUEST['bericht'];
		$onderwerp = $_REQUEST['onderwerp'];
		$status = $_REQUEST['status'];
		$voorwaarden = $_REQUEST['voorwaarden'];
		$adres = $_REQUEST['adres'];
		$plaats = $_REQUEST['plaats'];
		$telefoon = $_REQUEST['telefoon'];

		if (!is_bedrag($bedrag)) {
		    $fout[] = 'geen geldig bedrag ingevoerd';
		}
		if (!is_email($email)) {
		    $fout[] = 'Geen geldig emailadres ingevoerd';
		}
		if (empty($naam))  {
		    $fout[] = 'Geen naam ingevoerd';
		}
		if (empty($bedrag)) {
		    $fout[] = 'Geen bedrag ingevoerd';
		}
		if (!verify_iban($bankrekening)) {
		    $fout[] = 'Geen geldige IBAN rekening opgegeven';
		}
		if ($voorwaarden != 'j') {
		    $fout[] = 'U hebt de voorwaarden niet geaccepteerd';
		}
		if (empty($adres)) {
		    $fout[] = 'U hebt geen adres ingevuld';
		}
		if (empty($plaats)) {
		    $fout[] = 'U hebt geen plaats ingevuld';
		}
		$grens = time() - $time_out;

		if ($do == 'akkoord') {
			if (empty($fout)) {
				$rres = db_query("select id from sponsoring where bedrag = '" . db_esc($bedrag) . "' and van = '".
				db_esc($naam) . "' and rekening = '" . db_esc($bankrekening) . "' and email = '" . db_esc($email) .
				"' and adres = '" . db_esc($adres) . "' and plaats = '" . db_esc($plaats) . "' and voor = '" . db_Esc($id) .
				"' and tijd > '$grens'");
				if ($sr = db_row($rres))
				  { $fout[] = "Uw verzoek wordt al verwerkt"; }
			  }


			if (empty($fout)) {
				$nu = time();
				$bedrag = is_bedrag($bedrag);

				db_query("insert into sponsoring (bedrag, van, rekening, voor, email, adres, plaats, telefoon, tijd) 
                            values ('" . db_esc($bedrag) . "','" . db_esc($naam) . "','" . db_esc($bankrekening) .
                            "','" . db_esc($id) . "','" . db_esc($email) . "','" . db_esc($adres) . "','" . db_esc($plaats) .
                            "','" . db_esc($telefoon) . "','$nu')");
				$bericht = stripslashes(urldecode($bericht));
				if ($onderwerp == 'Onderwerp') {
				    $onderwerp='';
				}
				if ((!empty($bericht)) or (!empty($onderwerp))) {
				    db_query("insert into berichten (aan, naam, email, kop, bericht, status) 
                                values ('" . db_esc($id) . "','" . db_esc($naam) . "','" . db_esc($email) . "','" . db_esc($onderwerp) .
                                "','" . db_esc($bericht) . "', '" . db_esc($status) . "')");
				  }

				// bevestigingsmail sturen aan donateur
					$message = "<html><body>Beste " . $naam . ",<br><br>Hartelijk dank voor uw donatie.<br><br>U hebt KiKa gemachtigd om het bedrag &euro; " .
					number_format($bedrag,2,',','.') . " van uw bankrekening " . $bankrekening . " af te schrijven. Deze afschrijving zal plaatsvinden na afloop van het evenement 'De Berg op' op " .
					event_datum() . ". <br><br>Met vriendelijke groet,<br>Stichting Kinderen voor KiKa<br>Stuurgroep De Berg Op</body></html>";

					$message_txt = "Beste " . $naam . ",\r\n\r\nHartelijk dank voor uw donatie.\r\n\r\nU hebt KiKa gemachtigd om het bedrag € " .
					number_format($bedrag,2,',','.') . " van uw bankrekening " . $bankrekening . " af te schrijven. Deze afschrijving zal plaatsvinden na afloop van het evenement 'De Berg op' op " .
					event_datum() . ".\r\n\r\nMet vriendelijke groet,\r\nStichting Kinderen voor KiKa\r\nStuurgroep De Berg Op";

				  	$to['naam'] = $naam;
				  	$to['email'] = $email;
					$from['naam'] = "Kinderen voor KiKa";
				  	$from['email'] = "info@kinderenvoorkika.nl";

				  	stuur_mail($to,'Bevestiging donatie aan De Berg Op', $from, $message, $message_txt);

				// bevestigingsmail sturen aan deelnemer
					$r = db_row("select personen.email, personen.voornaam from personen,deelnemers where deelnemers.id='" .
					db_esc($id) . "' and personen.id = deelnemers.persoon and deelnemers.verwijderd != 'j' and personen.verwijderd != 'j'");
					$email = $r[0];
					$deelnemernaam = $r[1];

					$message = "<html><body>Beste " . $deelnemernaam . ",<br><br>" . $naam . " heeft een donatie gedaan voor jouw actie \"De Berg Op\".<br><br>Veel succes met de verdere voorbereidingen van De Berg Op.<br><br>Met vriendelijke groet,<br>Stichting Kinderen voor KiKa<br>Stuurgroep De Berg Op</body></html>";

				  	$message_txt = "Beste " . $deelnemernaam . ",\r\n\r\n" . $naam . " heeft een donatie gedaan voor jouw actie \"De Berg Op\".\r\n\r\nVeel succes met de verdere voorbereidingen van De Berg Op.\r\n\r\nMet vriendelijke groet,\r\nStichting Kinderen voor KiKa\r\nStuurgroep De Berg Op\r\n";

					$to['naam'] = $deelnemernaam;
				  	$to['email'] = $email;
				  	stuur_mail($to, 'Bevestiging donatie aan De Berg Op', $from, $message, $message_txt);

				$go = '';
				$do = '';
				$message = "hartelijk dank voor uw donatie. Wij hebben een bevestigingsmail verzonden.";
				$redir = $protocol . $domein . '/deelnemers/' . $pag . "&go=" . $go . "&do=" . $do . "&message=" . urlencode($message);
				header("Location: ".$redir);
			  }
		  } else {
			  if (!empty($fout)) {
			      $do = '';
			  }
		  }
	break;
	
	case 'bestellen':
		if ($user['id'] == $pagina['usr'])
		  {
			  db_query("update deelnemer_foto set besteld='j' where id='" . db_Esc($id) . "'");
		  }
	break;

  }

include('components/header.php');
?>

<div id="linkerbalk">
<?php include('components/nieuws_box.php'); ?>
<br>
<?php include('components/twitter_box.php'); ?>
</div>


<div id="middenvlak">
	<div class="top_blocker"></div>
 
<?php
// checken of de usr is ingelog op zijn/haar pagina
if ($go == 'settings') {
	 if ($user['id'] != $pagina['usr']) {
	     $go = '';
	 }
  }
	switch ($go) { 
 		default:
		// mobile versie van de sponsorbox 
		if (doneren_toegestaan() == 'j' && $pagina['show'] == 'j') {
		    include('components/sponsor_box_mobile.php');
        }

		if (!empty($message)) {
		    echo $message;
		}
	$foto_url = $protocol . $domein.'/fotos/';
	$foto_loc = $siteroot.'/fotos/';
	if (!empty($pagina['foto'])) {
	  	if (file_exists($foto_loc.$pagina['foto'])) {
	  	    $foto_url .= $pagina['foto'];
	  	} else {
	  	    $foto_url .= 'def.png';
	  	}
	  } else {
	    $foto_url .= 'def.png';
	}
?>    
    <div id="deelnemer_info">
    <div id="deelnemer_foto"><img src="<?php echo $foto_url;?>"></div>
    <div id="deelnemer_personalia">
    	<h1> <?php echo $pagina['deelnemer']; ?></h1>
        <?php
			if ($pagina['show'] == 'j' && $pagina['soort'] != 'estafette') {
        ?>
        <span class="deelnemer_kopje">Leeftijd</span><br />
        <?php 
		$nu = strftime("%Y-%m-%d", time());
		$jaar_nu = strftime("%Y", time());
		$jaar_geb = substr($pagina['gebdatum'],0,4);
		$leeftijd = $jaar_nu - $jaar_geb;
		if (substr($nu,5,5) < substr($pagina['gebdatum'],5,5)){
			$leeftijd=$leeftijd - 1;
		  }
		 echo $leeftijd; 
		?> jaar<br />
        <span class="deelnemer_kopje">Woonplaats</span><br />
        <?php echo $pagina['plaats']; ?><br />
        <span class="deelnemer_kopje">School</span><br />
        <?php echo $pagina['school']; ?><br />
        <?php
            } ?>
    </div>
	
    <div style="clear:both"></div>


	<?php 
	$id = $pagina['id'];
	if ($pagina['show'] == 'j') {
    	if (($pagina['show_stand'] == 'j') or ($user['id'] == $pagina['usr'])) {
	?>		  
	<div id="deelnemer_score">Totaal bij elkaar gesponsord voor KiKa:<br />
    <span class="deelnemer_bedrag">&euro; <?php $sr=db_row("select sum(bedrag) from sponsoring where voor='" . db_esc($id) . "' and verwijderd != 'j'");
        echo number_format($sr[0],2,',','.'); ?></span>
    </div>
<?php
		  } // if show_stand
		  
?>		  
    <div id="deelnemer_socialmedia"><a href="https://twitter.com/share?text=<?php echo urlencode($txt['twitter']);?>&url=<?php echo $protocol . $domein;?>/deelnemers/<?php echo urlencode($pag);?>" target="_blank"><img src="<?php echo $protocol . $domein;?>/img/twitter_16.png" alt="deel op Twitter" title="deel op Twitter" /></a> <a href="https://www.facebook.com/sharer.php?u=<?php echo $protocol.$domein;?>/deelnemers/<?php echo urlencode($pag);?>&t=<?php echo urlencode($txt['fb']);?>" target="_blank"><img src="<?php echo $protocol.$domein;?>/img/facebook_16.png" alt="deel op Facebook" title="deel op Facebook" /></a><?php if ($user['id']==$pagina['usr']) {?> <a href="/deelnemers/<?php echo $pag;?>&go=settings"><img src="<?php echo $protocol.$domein;?>/img/instellingen_16.png" alt="instellingen" title="instellingen" /></a><?php } ?></div>
    <?php } ?>

	</div>
    
    <?php if ($pagina['show'] == 'j') {
	
		if (!empty($pagina['motivatie'])) {?>
    <div id="deelnemer_motivatie">
    <h2>MOTIVATIE</h2>
    <?php echo $pagina['motivatie']; ?>
    </div>

    <div id="deelnemer_doel">
    <h2>DOEL</h2>
    <?php echo $pagina['doel']; ?>
    </div>
    <?php 	} 	
	
	$res = db_query("select id,naam,besteld from deelnemer_foto where deelnemer = '" . db_esc($pagina['id']) . "' and verwijderd!='j'");
	
	while ($r = db_row($res))
	  {
?>
		<div class="deelnemer_foto"
		    ><img src="../fotos/26mei/<?php echo $r[1];?>">
        </div>
<?php	
	  } // while
if ($user['id'] == $pagina['usr']) {?>
<span class="deelnemer_toelichting">Om jouw eigen pagina op te maken klik je op de knop instellingen <img src="<?php echo $protocol . $domein;?>/img/instellingen_16.png" alt="instellingen" title="instellingen" align="absmiddle" /> rechts bovenin.</span><?php
}
	if (($pagina['show_berichten'] == 'j') or ($user['id'] == $pagina['usr'])) {
	?>
    <div class="deelnemer_bericht_kop"><h2>BERICHTEN</h2></div>
	<?php
		$bres = db_query("select id, naam, email, kop, bericht, status from berichten where aan='" . db_esc($pagina['id']) . "' and blokkeren!='j' and verwijderd!='j'");
		$i = 1;
		while ($br=db_row($bres)) {
			  if (($br[5] != 'prive') or ($user['id'] == $pagina['usr'])) {
	?>
    <a id="bericht<?php echo $i;?>"></a>		  
	<div class="deelnemer_bericht">
    <?php echo stripslashes($br[1]); ?> <span class="deelnemer_bericht_onderwerp"><?php echo stripslashes($br[3]); ?></span><br /><br />
    <?php echo stripslashes($br[4]); ?>
    	<div class="bericht_icons"><a href="https://www.facebook.com/dialog/feed?
  app_id=260569600738136&
  link=<?php echo $protocol . $domein;?>/deelnemers/<?php echo $pag;?>&
  picture=<?php echo $protocol . $domein;?>/fotos/<?php echo $pagina['foto'];?>&
  name=<?php echo urlencode($pagina['deelnemer']." doet mee met De Berg Op.");?>&
  caption=<?php echo urlencode(strip_tags(stripslashes($br[3])));?>&
  description=<?php echo urlencode(strip_tags(stripslashes($br[4])));?>&
  message=<?php echo urlencode(stripslashes($br[4]));?>&
  redirect_uri=<?php echo $protocol . $domein;?>/deelnemers/<?php echo $pag;?>"><img src="<?php echo $protocol . $domein;?>/img/facebook_16.png" alt="deel op facebook" title="deel op facebook" /></a></div>
    	<div class="bericht_icons"><a href="https://twitter.com/share?text=<?php echo strip_cr(substr($pagina['deelnemer'].": " .
    	strip_tags(stripslashes($br[3])) . " ".strip_tags(stripslashes($br[4])),0,80) . " #kindvoorkika ");?>&url=<?php echo $protocol .
    	$domein;?>/deelnemers/<?php echo urlencode($pag);?>" target="_blank"><img src="<?php echo $protocol . $domein;?>/img/twitter_16.png" title="deel op twitter" alt="deel op twitter" /></a></div>
<?php 
  if ($user['id'] == $pagina['usr']) {?>
        <div class="bericht_icons"><a href="/deelnemers/<?php echo $pag;?>?go=deletebericht&id=<?php echo $br[0];?>"><img src="<?php echo $protocol .
        $domein;?>/img/delete_16.png" title="bericht verwijderen" alt="bericht verwijderen" /></a></div>
<?php } ?>    	
        <div style="clear:both;"></div>
        
    </div> <!-- deelnemer_bericht -->
	<?php	  $i++;	 
				}
		  }
	  }
    }
	break;
	
	case 'deletebericht':
?>	
			<h1>Bericht verwijderen</h1><br /><br />
            	
<?php	
			$br = db_row("select id, kop, bericht, naam, email from berichten where id = '" . db_esc($id) . "'");
			echo "<span class=\"deelnemer_bericht_onderwerp\">".stripslashes($br[1]) . "</span><br>" . stripslashes($br[2]) . "<br><br>";
			echo stripslashes($br[3]);
			if (!empty($br[4])) {
			    echo " (" . stripslashes($br[4]) . ")";
			}
			echo "<br><br>Echt verwijderen?<br><br>";
?>
			<form action="/deelnemers/<?php echo $pag;?>">
            <input type="hidden" name="id" value="<?php echo $id;?>" />
            <input type="submit" name="annuleren" value="annuleren" class="button_rood" />
            <input type="submit" name="do" value="verwijderen" class="button_rood" />            
<?php			
	break;
	
	
	case 'donatie':
	
		if ($opnieuw != 'j') {
				$bedrag = '';
				$naam = '';
				$email = '';
				$bankrekening = '';
				$adres = '';
				$plaats = '';
				$telefoon = '';
				$bericht = '';
		  }
	?>	
		<div id="deelnemer_info">
		<div id="deelnemer_foto"><img src="<?php echo $protocol . $domein;?>/fotos/<?php if (!empty($pagina['foto'])) { echo $pagina['foto']; } else { echo 'def.png'; }?>"></div>
		<div id="deelnemer_personalia">
			<h1> <?php echo $pagina['deelnemer']; ?></h1>
			<?php
				if ($pagina['show'] == 'j') {
			?>
			<span class="deelnemer_kopje">Leeftijd</span><br />
			<?php 
			$nu = strftime("%Y-%m-%d",time());
			$jaar_nu = strftime("%Y",time());
			$jaar_geb = substr($pagina['gebdatum'],0,4);
			$leeftijd = $jaar_nu - $jaar_geb;
			if (substr($nu,5,5) < substr($pagina['gebdatum'],5,5)) {
				$leeftijd = $leeftijd-1;
			  }
			 echo $leeftijd; 
			?> jaar<br />
			<span class="deelnemer_kopje">Woonplaats</span><br />
			<?php echo $pagina['plaats']; ?><br />
			<span class="deelnemer_kopje">School</span><br />
			<?php echo $pagina['school']; ?><br />
			<?php
				} ?>
		</div>
		
		<div style="clear:both"></div>
		<br />
		
		<div id="donatie">
	<?php
	
	if ($do != 'verstuur')
	  {
		if (!empty($fout)) {
			  foreach ($fout as $val) {
			      ?><div class="fout"><?php echo $val; ?></div><?php
			  }
			  echo "<br />";			  
		  } // if...
	?>    
			<form action="/deelnemers/<?php echo $pagcode;?>&go=donatie" method="post" id="aform">
			<input type="hidden" name="state" value="<?php echo $state; ?>">
			<input type="hidden" name="id" value="<?php echo $pagina['id']; ?>">
			<input type="hidden" name="pag" value="<?php echo $pag; ?>">
			<input type="hidden" name="opnieuw" value="j">
			<h1>IK WIL <?php echo strtoupper($pagina['voornaam']); ?> STEUNEN MET</h1><br />
			<div class="donatie_veld"><div id="valuta">&euro; </div><div style="float:left; width:200px;"><input type="text" name="bedrag" id="bedrag" value="<?php echo $bedrag;?>" placeholder="bedrag"></div><div style="clear:both;"></div></div>
            
			<div class="donatie_veld"><input type="text" name="bankrekening" id="rekening" value="<?php echo $bankrekening;?>" placeholder="bankrekening"></div>
			<div style="clear:both"></div><br />
					 
			<div class="donatie_veld"><input type="text" name="naam" id="naam" value="<?php echo $naam;?>" placeholder="naam" ></div>
			<div class="donatie_veld"><input type="text" name="email" id="email"value="<?php echo $email;?>" placeholder="email"></div>
			<div style="clear:both"></div><br />
	
			 <div class="donatie_veld"><input type="text" name="adres" id="adres" value="<?php echo $adres;?>" placeholder="adres" ></div>
			<div class="donatie_veld"><input type="text" name="plaats" id="plaats" value="<?php echo $plaats;?>" placeholder="plaats"></div>       
			<div style="clear:both"></div><br />
			<div class="donatie_veld"><input type="text" name="telefoon" id="telefoon" value="<?php echo $telefoon;?>" placeholder="telefoon"></div>       
			<div style="clear:both"></div><br />        
	
			Hierbij machtig ik KiKa &eacute;&eacute;nmalig om het ingevulde bedrag van mijn bankrekening af te schrijven. Deze afschrijving zal plaatsvinden na afloop van het evenement 'De Berg Op' op <?php echo event_datum();?>.<br />
			
	
			
			
			<br />
	<?php if ($pagina['berichten_toestaan'] == 'j') {
	?>	        
			<br />
			<h1>BERICHT AAN <?php echo strtoupper($pagina['voornaam']); ?></h1><br />
			
            <?php echo "<!-- " . stripslashes(urldecode($bericht)) . " -->";?>
			<textarea name="bericht" id="bericht"><?php echo stripslashes(urldecode($bericht));?></textarea>
			<script type="text/javascript">
			editor = CKEDITOR.replace( 'bericht', { toolbar : [
							{ name: 'basicstyles', items : [ 'Bold','Italic','Strike', 'Smiley' ] }	
						], uiColor : '#560666',  width : '95%', height : 150
					});        
			</script>
	
			<input type="radio" name="status" value="publiek" checked="checked" /> mijn bericht en donatie tonen op persoonlijke pagina <br>
	<input type="radio" name="status" value="prive" /> mijn bericht en donatie <span style="font-decoration:underline">niet</span> tonen op persoonlijke pagina<br />
	<?php
	} // if berichten_toestaan
	?>
			<br />
			<div style="float:left; margin-right:10px;"><input type="checkbox" name="voorwaarden" value="j" /></div>
			<div style="float:left;">Ja, ik heb de <a href="/?state=sponsorvoorwaarden" target="_blank">voorwaarden</a> gelezen en accepteer deze.</div>
			<div style="clear:both"></div><br />
	 
		  <input type="submit" name="do" value="VERSTUUR" class="button_rood"><br><br>
          LET OP: Uw donatie is pas definitief na uw bevestiging op de volgende pagina!
		</form>  
<?php
	 } else{
	    // bevestigen
			$bedrag = preg_replace('/[^0-9\.\,]/','',$bedrag);
?>
			<script type="text/javascript">
			var formSubmitting = false;
			var setFormSubmitting = function() { formSubmitting = true; };
			var confirmationMessage = 'Weet u zeker dat u deze pagina wilt verlaten. ' + 'Uw donatie is nog niet definitief.';
			
			window.onload = function() {
				window.addEventListener("beforeunload", function (e) {
					if (formSubmitting) {return undefined; }
					(e || window.event).returnValue = confirmationMessage; //Gecko + IE
					return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
				});
				window.addEventListener("pagehide", function (e) {
					if (formSubmitting) {return undefined; }
					//(e|| window.event).returnValue = confirmationMessage; //Gecko + IE
					//return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
					if (confirm(confirmationMessage)) { e.preventDefault(); }
				});
			};	
			</script>		

			<form action="/deelnemers/<?php echo $pagcode;?>&go=donatie" method="post" id="sponsorform">
			<input type="hidden" name="state" value="<?php echo $state; ?>">
			<input type="hidden" name="id" value="<?php echo $pagina['id']; ?>">
			<input type="hidden" name="pag" value="<?php echo $pag; ?>">
            <input type="hidden" name="naam" value="<?php echo $naam; ?>">
            <input type="hidden" name="bankrekening" value="<?php echo $bankrekening; ?>">
            <input type="hidden" name="bedrag" value="<?php echo $bedrag; ?>">
            <input type="hidden" name="bericht" value="<?php echo urlencode($bericht); ?>">
            <input type="hidden" name="email" value="<?php echo $email; ?>">
            <input type="hidden" name="adres" value="<?php echo $adres; ?>">
            <input type="hidden" name="plaats" value="<?php echo $plaats; ?>">
            <input type="hidden" name="telefoon" value="<?php echo $telefoon; ?>">
            <input type="hidden" name="status" value="<?php echo $status; ?>">
            <input type="hidden" name="voorwaarden" value="<?php echo $voorwaarden; ?>">
			<input type="hidden" name="opnieuw" value="j">
            <input type="hidden" name="do" value="akkoord">
            
<?php
		$r = db_row("select personen.voornaam, personen.voorvoegsel, personen.achternaam from personen, deelnemers where personen.id=deelnemers.persoon and deelnemers.id='" . db_esc($id) . "'");
		$naam = $r[0];
		if (!empty($r1)) {
		    $naam .= " " . $r[1];
		}
		if (!empty($r2)) {
		    $naam .= " " . $r[2];
		}
?>            
	  	U doneert &euro; <?php echo number_format($bedrag,2,',','.'); ?> aan <?php echo $naam; ?><br>
<br>
		Uw donatie is pas definitief als u op de onderstaande JA-knop klikt.<br><br>
		Wilt u dit bevestigen?<br><br><br>
		<input type="button" value="JA" onClick="setFormSubmitting(); sponsorform.submit();" class="button_rood"> ik ga akkoord met deze donatie<br><br><br>
        U ontvangt nog een bevestiging per mail. De deelnemer wordt ook per mail ingelicht dat u hebt gedoneerd.<br><br><br>
		<br>
			</form>

<?php	  		
      }
?>
			</div>
          </div>
<?php
	break;
	
	case 'settings':
	if (!empty($fout)) {
		  foreach ($fout as $val) {
		      ?><div class="fout"><?php echo $val; ?></div><?php
		  }
		  echo "<br />";			  
	  } // if...
?>
	<form method="post" enctype="multipart/form-data" action="/deelnemers/<?php echo $pag;?>&go=settings">
    <input type="hidden" name="id" value="<?php echo $pagina['id'];?>" />	
	<div id="deelnemer_info">
    <div id="deelnemer_foto"><img src="<?php echo $protocol . $domein;?>/fotos/<?php if (!empty($pagina['foto'])) { echo $pagina['foto']; } else { echo 'def.png'; }?>">
	</div>
    <div id="deelnemer_settings">
    	<h1> <?php echo $pagina['deelnemer']; ?></h1>
		<input type="checkbox" name="berichten_toestaan" value="j" <?php if ($pagina['berichten_toestaan']=='j') { ?> checked="checked"<?php } ?> /> Berichten van je sponsors toestaan<br />
     	<input type="checkbox" name="show_berichten" value="j" <?php if ($pagina['show_berichten']=='j') { ?> checked="checked"<?php } ?> /> Berichten van je sponsors zijn voor iedereen zichtbaar<br />
     	<input type="checkbox" name="show_stand" value="j" <?php if ($pagina['show_stand']=='j') { ?> checked="checked"<?php } ?> /> Jouw bedrag laten zien<br /><br>
        School: <select name="school">
        			<option value=""></option>
         <?php           
           $res=db_query("select id, naam from scholen where verwijderd != 'j' order by naam");
		   while ($r=db_row($res)) {
		  ?>
          			<option value="<?php echo $r[0];?>" <?php
          			if ($r[0] == $pagina['school_id']) {
          			    ?>selected<?php
          			} ?>><?php echo $r[1]; ?></option>
         <?php 					 
			  }
		  ?>	  
        </select><br>
       <em> Staat jouw school er niet bij? Mail ons even: <a href="info@kinderenvoorkika.nl">info@kinderenvoorkika.nl</a></em>
        <br>
        <br>
           
        Nieuwe foto: <input type="file" name="foto" /><br /><br />
        Mijn doel:<br />
        <textarea name="doel" id="doel"><?php echo $pagina['doel']; ?></textarea><br /><br />
        Mijn Motivatie:
        <textarea name="motivatie" id="motivatie"><?php echo $pagina['motivatie']; ?></textarea>
        <script type="text/javascript">
		editor1 = CKEDITOR.replace( 'doel', { toolbar : [
						{ name: 'basicstyles', items : [ 'Bold','Italic','Strike', 'Smiley' ] }	
					], uiColor : '#560666',  width : 350, height : 150
				}); 
		editor2 = CKEDITOR.replace( 'motivatie', { toolbar : [
						{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','Smiley' ] }	
					], uiColor : '#560666',  width : 350, height : 150
				}); 				       
        </script>
        
        <br />
        Nieuw wachtwoord (laat leeg om je wachtwoord te behouden)<br />
        <input type="password" name="pass1" value="<?php echo $pass1;?>" /><br /><br />
        Herhaal nieuw wachtwoord <br />
        <input type="password" name="pass2" value="<?php echo $pass2;?>" /><br /><br />
        <br />
<input type="submit" name="do" value="bevestigen" class="button_rood" />
        
    </div>
    
    </div>
    </form>

<?php	
	break;
	}
	?>
    
</div>

<div id="rechterbalk">
<?php 
		// sponsorbox is uitgeschakeld. 
    if (doneren_toegestaan()=='j') {
        if ($pagina['show']=='j') {
            include('components/sponsor_box.php');
        }
    }
	include('components/login_box.php'); ?>

    <div class="sponsors"><a href="http://www.kika.nl" target="_blank"><img src="<?php echo $protocol . $domein;?>/img/kika_logo.jpg" alt="KiKa" title="KiKa"></a></div>
    <div id="sponsorbox">Dit evenement is tot <br />
stand gekomen met <br />
de inzet van:<br /><br />
    	<div id="logobox"><img src="<?php echo $protocol . $domein;?>/img/Continental-Logo_150.png" /></div>
</div>
</div>

<div style="clear: both"></div>


<?php include('components/footer.php'); ?>
