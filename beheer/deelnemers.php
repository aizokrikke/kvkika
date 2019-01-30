<?php
// deelnemers.php
// versie 2.0.2 (10 april 2017)
// - opnieuw sturen van bevestigingsmail aan deelnmerslijst toegevoegd
// - handmatig besvestigen aandeelnemerslijst toegevoegd
// - kleurcodering regels in deelnemerslijst bevestigd/nietbevestigd toegevoegd
// - stripslashes in school in deelnemersliojst toegevoegd
//
// versie 2.0.1 (28 maart 2015)
// - verwijderen van deelnemers toegevoegd
//
// versie 2.0.0 (1 juni 2013)
// - toevoegen deelnemerfoto's toegevoegd

$act = $_REQUEST['act'];
$do = $_REQUEST['do'];
$id = $_REQUEST['id'];
$anu = $_REQUEST['anu'];
$anu2 = $_REQUEST['anu2'];

if ($anu == 'terug') {
    $act='lijst';
}
if ($anu2 == 'terug') {
    $act='';
}

switch ($do) {
  case 'uploaden':
	  
	switch ($act) {
		case 'foto':
			if (file_exists("$siteroot/beheer/tmp/temp.jpg")) {
			    unlink("$siteroot/beheer/tmp/temp.jpg");
			}
			$tmp_name = $_FILES["foto"]["tmp_name"];
			$naam = $_FILES["foto"]["name"];
			if (move_uploaded_file($tmp_name, "$siteroot/beheer/tmp/temp.jpg")) {
				list($width, $height, $type, $attr) = getimagesize("$siteroot/beheer/tmp/temp.jpg");
				
				$factor = $width/$height;
				$nw = 550;
				$nh = round($nw/$factor,0);
				
				$im_out = imagecreatetruecolor($nw,$nh);
				$im_in = imagecreatefromjpeg("$siteroot/beheer/tmp/temp.jpg");
				
				imagecopyresampled($im_out,$im_in,0,0,0,0, $nw, $nh, $width, $height);
				imagejpeg($im_out,"$siteroot/fotos/26mei/$naam",90);
				imagedestroy($im_out);
				imagedestroy($im_in);
				
				db_query("insert into deelnemer_foto (deelnemer,naam, besteld) values ('" . db_esc($id) . "', '$naam','n')");
			  } else {
			    echo "bestand uploaden mislukt<br><br>";
			}
			$act = '';
		break;
	  }
	break;
	
	case 'opslaan':
	  switch ($act) {
		case 'edit':  
			$pw1 = $_REQUEST['pw1'];
			$pw2 = $_REQUEST['pw2'];
			$voornaam = $_REQUEST['voornaam'];
			$voorvoegsel = $_REQUEST['voorvoegsel'];
			$achternaam = $_REQUEST['achternaam'];
			$adres = $_REQUEST['adres'];
			$adres_nr = $_REQUEST['adres_nr'];
			$plaats = $_REQUEST['plaats'];
			$postcode = $_REQUEST['postcode'];
			$deelnemerlogin = $_REQUEST['deelnemerlogin'];
			$school = $_REQUEST['school'];
			$persoon_id = $_REQUEST['persoon_id'];
			$pagina = $_REQUEST['pagina'];
			$passmd5 = md5($pw1);
			$loginmd5 = md5($deelnemerlogin);
			$email = $_REQUEST['email'];
			
			if (!empty($pw1)) {
			    if ($pw1 != $pw2) {
			        $err[] = "Wachtwoorden zijn niet gelijk";
			    }
			  } else {
				$r = db_row("select password_md5 from personen where id='".mysql_real_escape_string($persoon_id)."' and verwijderd!='j'");
				$passmd5 = $r[0];
			  }
		  
			if (db_row("select id from personen where login='" . db_row($deelnemerlogin) . "' and id!='" . db_row($persoon_id) . "' and verwijderd != 'j'")) {
			    $err[] = "Login is al in gebruik";
			}
				
			if (empty($err)) {
				//echo "deelnemer_id: $id<br>";
				db_query("update deelnemers set school='" . db_esc($school) . "',pagina='" . db_esc($pagina) . "' where id = '" . db_esc($id) . "'");
				//echo "persoon_id: $persoon_id<br>";
				db_query("update personen set voornaam='" . db_esc($voornaam) . "', voorvoegsel ='" . db_esc($voorvoegsel) . "', achternaam='" .
                    db_esc($achternaam) . "', email = '" . db_esc($email)."', login = '".db_esc($deelnemerlogin) . "', login_md5 = '$loginmd5', 
                    password_md5 = '$passmd5', adres = '" . db_esc($adres)."', adres_nr = '" . db_esc($adres_nr) . "',postcode = '" .
                    db_esc($postcode)."',plaats = '" . db_esc($plaats) . "' where id = '" . db_esc($persoon_id) . "'");
				$act = 'lijst';
			  }
		break;
		
		case 'editschool';
		case 'addschool':
			$naam = $_REQUEST['naam'];
			$adres = $_REQUEST['adres'];
			$nr = $_REQUEST['nr'];
			$postcode = $_REQUEST['postcode'];
			$plaats = $_REQUEST['plaats'];
			
			if (empty($naam)) {
			    $err[]="Geen naam ingevoerd";
			}
			if (empty($err)) {
			  	if ($act != 'addschool') {
					  db_query("update scholen set naam ='" . db_esc($naam) . "', straat = '" . db_esc($adres) . "', nr = '" .
                          db_esc($nr) . "', postcode = '" . db_esc($postcode) . "', plaats = '" . db_esc($plaats) . "' where id = '" .
                          db_esc($id)."'");
				  } else {
					  db_esc_query("insert into scholen (naam, straat, nr, postcode, plaats) values ('" . db_esc($naam) . "','" .
                          db_esc($adres) . "','" . db_esc($nr) . "','" . db_esc($postcode) . "','" . db_esc($plaats) . "')");
				  }
				$act = '';
			  }
		break;
		
	  }
	break;
	
	case 'verwijder':
		db_query("update deelnemers set verwijderd = 'j' where id = '" . db_esc($id) . "'");
		$act = '';
		$id = '';
	break;
  }
  
  
if (!empty($err)) {
    echo "<br><br>";
    foreach ($err as $val) {
        echo $val."<br>";
    }
}
  
// einde verwerking formulieren  
  
?>
<span class="kop"><a href="?state=<?php echo $state;?>&go=<?php echo $go; ?>"><i class="fas fa-users fa-sm fa-fw"></i> Deelnemers</a></span>
<hr />
<?php  
  
switch ($act) {
	default:
		$r = db_row("select count(id) from deelnemers where verwijderd != 'j'");
		?><br><br>
        <a href="?state=<?php echo $state;?>&go=<?php echo $go;?>&act=foto"><i class="fas fa-plus-circle fa-lg fa-fw"></i> deelnemerfoto's toevoegen</a>
        <br><br>
        <a href="beheer/workers/deelnemersexport.php"><i class="far fa-file-excel fa-lg fa-fw"></i> exporteren naar Excel</a>
		<br><br><br>
		<table>
		<tr height="28"><td>Aantal <a href="?state=<?php echo $state; ?>&go=<?php echo $go; ?>&act=lijst">deelnemers</a></td><td width="10"></td><td><a href="?state=<?php echo $state; ?>&go=<?php echo $go; ?>&act=lijst"><?php echo $r[0]; ?> <i class="fas fa-user-edit fa-sm fa-fw"></i></a></td></tr>
		<?php
		$r = db_row("select count(id) from deelnemers where verwijderd != 'j' and bevestigd = 'j'");?>
		<tr valign="top" height="28"><td>Aantal deelnemers<br>
		<em>(afgeronde inschrijving)</em></td><td width="10"></td><td><a href="?state=<?php echo $state;?>&go=<?php echo $go;?>&act=lijst"><?php echo $r[0]; ?></a></td></tr>
		<?php
		$r = db_row("select sum(bedrag) from sponsoring where verwijderd != 'j'");
		?>
        <tr height="28"><td colspan="2">&nbsp;</td></tr>
		<tr height="28"><td>Sponsorbedrag</td><td width="10"></td><td><a href="?state=<?php echo $state;?>&go=<?php echo $go;?>&act=lijst">&euro; <?php echo number_format($r[0],2,',','.'); ?></a></td></tr>
        <?php if (!empty($user['rechten']['incasso'])) {?>
        <tr height="28"><td></td><td ></td><td><a href="beheer/workers/deelnemersexport.php?do=incasso"><i class="far fa-file-excel fa-lg fa-fw"></i> incassobestand</a></td></tr>
        <?php } ?>
		<tr height="28"><td colspan="3">&nbsp;</td></tr>
		
		<?php
		$res = db_query("select scholen.naam, count(deelnemers.id), scholen.id from deelnemers left join scholen on scholen.id=deelnemers.school where deelnemers.verwijderd!='j' group by scholen.id order by count(deelnemers.id) DESC");
		
		while ($r = db_row($res))
		  {
		?>
		<tr height="28"><td><?php if (!empty($r[0])) {
		    ?><a href="?state=<?php echo $state; ?>&go=<?php echo $go; ?>&act=editschool&id=<?php echo $r[2]; ?>"><i class="far fa-edit fa-sm fa-fw"></i> <?php echo stripslashes($r[0]); ?></a><?php
		} else {
		    echo '-- ongekoppeld --'; }?></td><td width="10"></td><td><?php echo $r[1]; ?></td></tr>
		<?php	   
		  }
	
		// scholen zonder deelnemers
	
		$res = db_query("select scholen.naam, scholen.id from scholen where NOT EXISTS(select id from deelnemers where verwijderd!='j' and deelnemers.school=scholen.id) order by scholen.naam DESC") or die(mysql_error());
		
		while ($r = db_row($res))
		  {
		?>
		<tr><td><?php if (!empty($r[0])) { ?><a href="?state=<?php echo $state; ?>&go=<?php echo $go; ?>&act=editschool&id=<?php echo $r[1]; ?>"><i class="far fa-edit fa-sm fa-fw"></i> <?php echo $r[0]; ?></a><?php } else { echo '-- ongekoppeld --'; }?></td><td width="10"></td><td>0</td></tr>
		<?php	   
		  }
		?>        
		</table>  <br><br>
        <a href="?state=<?php echo $state; ?>&go=<?php echo $go; ?>&act=addschool"><i class="fas fa-plus-circle fa-lg fa-fw"></i> school toevoegen</a>
<?php
	break;
	
	case 'edit';
	case 'del':
		if ($opnieuw != 'j') {
			if (!$r = DB_ROW("select personen.id,personen.voornaam, personen.voorvoegsel, personen.achternaam, personen.geslacht, personen.login, personen.adres, personen.adres_nr, personen.postcode, personen.plaats, deelnemers.pagina, deelnemers.school, personen.email from personen, deelnemers 
                                  where deelnemers.persoon=personen.id and deelnemers.id='" . DB_ESC($id) . "' and personen.verwijderd != 'j' and deelnemers.verwijderd != 'j'"))
			{
			    $err[] = "Deelnemer niet gevonden...";
			} else {
			    $persoon_id = $r[0];
				$voornaam = $r[1];
				$voorvoegsel = $r[2];
				$achternaam = $r[3];
				$geslacht = $r[4];
				$deelnemerlogin = $r[5];
				$adres = $r[6];
				$adres_nr = $r[7];
				$postcode = $r[8];
				$plaats = $r[9];
				$pagina = $r[10];
				$school = $r[11];
				$email = $r[12];
			}
		  }
		  
	if (empty($err)) {
?>			
<form action="?" method="post">
    <input type="hidden" name="state" value="<?php echo $state; ?>"> 
    <input type="hidden" name="go" value="<?php echo $go; ?>"> 
    <input type="hidden" name="act" value="<?php echo $act; ?>"> 
    <input type="hidden" name="opnieuw" value="j">
    <input type="hidden" name="id" value="<?php echo $id; ?>"> 
    <input type="hidden" name="persoon_id" value="<?php echo $persoon_id; ?>"> 			
	
    <table>
    <tr><td>voornaam</td><td>tussenvoegsel</td><td>achternaam</td></tr>
    <tr>
    	<td><input type="text" name="voornaam" value="<?php echo $voornaam; ?>" size="30"></td> 
    	<td><input type="text" name="voorvoegsel" value="<?php echo $voorvoegsel; ?>" size="10"></td> 
    	<td><input type="text" name="achternaam" value="<?php echo $achternaam; ?>" size="30"></td>
    </tr>
    <tr><td>Adres</td><td>Nummer</td><td></td></tr>
    <tr>
    	<td><input type="text" name="adres" value="<?php echo $adres; ?>" size="30"></td> 
    	<td><input type="text" name="adres_nr" value="<?php echo $adres_nr; ?>" size="10"></td> 
    	<td></td>
    </tr>
    </table>
    <table>
    <tr><td>Postcode</td><td>Plaats</td><td></td></tr>
    <tr>
    	<td><input type="text" name="postcode" value="<?php echo $postcode; ?>" size="10"></td> 
    	<td><input type="text" name="plaats" value="<?php echo $plaats; ?>" size="30"></td> 
    	<td></td>
    </tr> 
    </table>
    <br>
    <table>
    	<tr><td>School</td><td>
        	<select name="school">
            	<option value="0"></option>
<?php
			$sres = db_query("select id, naam from scholen where verwijderd != 'j' order by naam");
			while ($sr = db_row($sres)) {
?>
				<option value="<?php echo $sr[0]; ?>" <?php
                if ($sr[0] == $school) {
				    ?>selected<?php
				} ?>><?php echo $sr[1]; ?></option>
<?php				  
			  } // while
?>                
            </select>
        	</td></tr>
        <tr><td>Emailadres</td><td><input type="text" size="40" name="email" value="<?php echo $email; ?>"></td></tr>
        <tr><td>Pagina</td><td><input type="text" size="40" name="pagina" value="<?php echo $pagina; ?>"></td></tr>    
    	<tr><td>Login</td><td><input type="text" size="40" name="deelnemerlogin" value="<?php echo $deelnemerlogin; ?>"></td></tr> 
        <tr><td>Wachtwoord</td><td><input type="password" size="40" name="pw1"> <em>laat leeg om wachtwoord ongewijzigd te laten</em></td></tr>    
        <tr><td>Herhaal Wachtwoord</td><td><input type="password" size="40" name="pw2"></td></tr>    
    </table><br>
    <?php if ($act == 'del') {
	?>
    Echt verwijderen?<br><br>
    <?php } ?>	  
    <input type="submit" name="anu" value="terug"> <input type="submit" name="do" value="<?php
    if ($act == 'del') {
        ?>verwijder<?php
    } else {
        ?>opslaan<?php
    } ?>">
</form>    
<?php   
	  }
	break;
	
	case 'editschool';
	case 'addschool':
		if ($opnieuw != 'j') {
			if ($act != 'addschool') {
				if ($r = db_row("select id, naam, straat, nr, postcode, plaats from scholen where id='" . db_esc($id) . "' and verwijderd != 'j'")) {
					$naam = $r[1];
					$straat = $r[2];
					$nr = $r[3];
					$postcode = $r[4];
					$plaats = $r[5];
				} else {
					$err[] = 'School niet gevonden';
				}
			  }
		  }
		if (empty($err)) {
?>
<form action="?" method="post">
    <input type="hidden" name="state" value="<?php echo $state; ?>"> 
    <input type="hidden" name="go" value="<?php echo $go; ?>"> 
    <input type="hidden" name="act" value="<?php echo $act; ?>"> 
    <input type="hidden" name="opnieuw" value="j">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    
    <table>
    <tr><td>Naam</td><td><input type="text" name="naam" value="<?php echo $naam; ?>" size="40"></td></tr>
    <tr><td>Adres Nummer</td><td><input type="text" name="adres" value="<?php echo $adres; ?>" size="30"> <input type="text" name="nr" value="<?php echo $nr; ?>" size="10"></td></tr>
    <tr><td>Postcode Plaats</td><td><input type="text" name="postcode" value="<?php echo $postcode; ?>" size="10"> <input type="text" name="plaats" value="<?php echo $plaats; ?>" size="30"></td></tr>
    </table><br>
    
    
	<input type="submit" name="anu2" value="terug"> <input type="submit" name="do" value="opslaan">     
</form>    
<?			  
		  }
	break;
	
		
	case 'resendconfirmation':
		if (!$r = db_row("select personen.id,personen.voornaam, personen.voorvoegsel, personen.achternaam, personen.geslacht, deelnemers.confirmcode, personen.email from personen, deelnemers where deelnemers.persoon=personen.id and deelnemers.id='" .
            db_esc($id) . "' and personen.verwijderd != 'j' and deelnemers.verwijderd != 'j'"))	{
		    $err[]="Deelnemer niet gevonden...";
		} else {
		    $persoon_id = $r[0];
            $voornaam = $r[1];
            $tussenvoegsel = $r[2];
            $achternaam = $r[3];
            $naam = $achternaam;
            if (!empty($tussenvoegsel)) {
                $naam = $tussenvoegsel . " " . $naam;
            }
            if (!empty($voornaam)) {
                $naam = $voornaam . " " . $naam;
            }
            $geslacht = $r[4];
            $code = $r[5];
            $email = $r[6];
        }
		if (!$r = db_row("select personen.id,personen.voornaam, personen.voorvoegsel, personen.achternaam, personen.email from personen, verzorgers 
                            where verzorgers.verzorger=personen.id and verzorgers.deelnemer='" . db_esc($id) . "' and personen.verwijderd != 'j' and verzorgers.verwijderd != 'j'")) {
		    $err[] = "Ouder niet gevonden...";
		} else {
			$v_voornaam = $r[1];
			$v_tussenvoegsel = $r[2];
			$v_achternaam = $r[3];
			$v_naam = $v_achternaam;
			if (!empty($v_tussenvoegsel)) {
			    $v_naam = $v_tussenvoegsel . " " . $v_naam;
			}
			if (!empty($v_voornaam)) {
			    $v_naam = $v_voornaam . " " . $v_naam;
			}
			$v_email = $r[4];
		}
		if (empty($err)) {
			$p['kind'] = $naam;
			$p['oudermail'] = $v_email;
			$p['code'] = $code;
			$p['geslacht'] = $geslacht;
			$p['ouder'] = $v_naam;
			verstuur_bevestiging($p);
?>
	De bevestiging is opnieuw verstuurd.<br><br>
	<a href="?state=<?php echo $state; ?>&go=deelnemers&act=lijst">terug</a>
<?php				
		}
		
	break;

				
	case 'confirm':
		if (!$r = db_row("select personen.id,personen.voornaam, personen.voorvoegsel, personen.achternaam, personen.geslacht, deelnemers.confirmcode, personen.email 
                              from personen, deelnemers where deelnemers.persoon=personen.id and deelnemers.id='" . db_esc($id) . "' and personen.verwijderd!='j' and deelnemers.verwijderd!='j'")) {
		    $err[] = "Deelnemer niet gevonden...";
		} else {
		    $persoon_id = $r[0];
				$voornaam = $r[1];
				$tussenvoegsel = $r[2];
				$achternaam = $r[3];
			 	$naam = $achternaam;
			    if (!empty($tussenvoegsel)) {
			        $naam = $tussenvoegsel . " " . $naam;
			    }
				if (!empty($voornaam)) {
				    $naam = $voornaam . " " . $naam;
				}
				$geslacht = $r[4];
				$code = $r[5];
				$email = $r[6];
			}
		if (empty($err)) {
			db_query("update deelnemers set bevestigd = 'j' where id= '" . db_esc($id) . "'");
?>
	Deelnemer <?php echo $naam; ?> is bevestigd.<br><br>
	<a href="?state=<?php echo $state; ?>&go=deelnemers&act=lijst">terug</a>
<?php				
		}
		
	break;
	
	case 'lijst':
?><br><br>
		<table border="0" cellpadding="2" cellspacing="0">
			<tr><?php if (!empty($user['rechten']['verwijderen'])) {
			    ?><td></td><?php
			} ?><td></td><td></td><td></td><td>Naam</td><td></td><td>bev.</td><td></td><td align="right">bedrag</td><td></td><td>email</td><td></td><td>school</td></tr>
<?php
		$res = db_query("select personen.voornaam, personen.voorvoegsel, personen.achternaam, deelnemers.bevestigd, deelnemers.id, personen.email, scholen.naam from deelnemers
		left join personen on personen.id = deelnemers.persoon 
		left join scholen on scholen.id = deelnemers.school
		where personen.id=deelnemers.persoon and deelnemers.verwijderd!='j'");
		$bg='#eeeeee';
		$bg_count = 1;
		while ($r = db_row($res))
		  {
			 $bg = "listrow_";
			 if ($r[3] == 'j') {
			     $bg .= "confirmed_";
			 } else {
			     $bg .= "not_confirmed_";
			 }
			 $bg .= $bg_count;
?>
		<tr class="<?php echo $bg; ?>">
        	<?php
            if (!empty($user['rechten']['verwijderen'])) {
        	    ?>
            <td><a href="?state=<?php echo $state; ?>&go=<?php echo $go; ?>&act=del&id=<?php echo $r[4]; ?>"><img src="../img/24x24/editcut.png"></a></td>
            <?php
        	} ?>
            <td><a href="?state=<?php echo $state; ?>&go=<?php echo $go; ?>&act=edit&id=<?php echo $r[4]; ?>"><img src="../img/24x24/edit.png"></a></td>
            <td><?php
                if ($r[3] != 'j') {
                    ?><a href="?state=<?php echo $state; ?>&go=<?php echo $go; ?>&act=resendconfirmation&id=<?php echo $r[4]; ?>"><img src="../img/24x24/resend.png" width="24" alt="bevestiging opnieuw sturen" title="bevestiging opnieuw sturen"></a><?php
                } ?></td>
            <td width="20">&nbsp;</td>
        	<td><a href="?state=<?php echo $state; ?>&go=<?php echo $go; ?>&act=edit&id=<?php echo $r[4]; ?>"><?php
            echo $r[0];
        	if (!empty($r[1])) {
        	    echo " $r[1]";
        	}
        	echo " $r[2]";
        	?></a></td>
        	<td width="20">&nbsp;</td>
			<td><?php
                if ($r[3]=='j') {
                    ?><img src="../img/24x24/ok.png"><?php
                } else {
                    ?><a href="?state=<?php echo $state; ?>&go=<?php echo $go; ?>&act=confirm&id=<?php echo $r[4]; ?>"><img src="../img/24x24/not_ok.png" alt="bevestigen" title="bevestigen"></a<?php
                } ?></td>
           <td width="20">&nbsp;</td> 
           <td align="right" width="80"><?php
               $sr = db_row("select sum(bedrag) from sponsoring where voor='$r[4]' and verwijderd != 'j'");
               if (!empty($sr[0])) {
                   echo "&euro; " . number_format($sr[0],2,',','.');
               } ?></td>
           <td width="20">&nbsp;</td>
           <td><?php
               if ($r[3] != 'j') {
                   echo $r[5];
               } ?></td>
           <td width="20">&nbsp;</td>
           <td><?php echo stripslashes($r[6]); ?></td>
       </tr>
        
<?php
			$bg_count++;
			if ($bg_count > 2) {
			    $bg_count=1;
			}
		  }
?>
		</table>
<?php		  
	break;
	
	
	case 'foto':
?>
	<form enctype="multipart/form-data" action="?" method="post">
    <input type="hidden" name="state" value="<?php echo $state; ?>"> 
    <input type="hidden" name="go" value="<?php echo $go; ?>"> 
    <input type="hidden" name="act" value="<?php echo $act; ?>"> 
   
	<table>
    <tr><td>Deelnemer id</td><td><input name="id" size="4"></td></tr>
    <tr><td>Foto</td><td><input type="file" name="foto"></td></tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr><td colspan="2" align="right"><input type="submit" name="do" value="uploaden"></td></tr>
    </table>
<?php	
	break;
} // switch