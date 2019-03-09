<?php
function get_boxen($pagina) {
	global $sitestatus;
	$res=db_query("select datum, body from " . $sitestatus . "boxen where pagina = '$pagina' and verwijderd != 'j' order by positie asc");
	
	$i=1;
	while ($r=db_row($res))
	 {
		 if ($i == 1) {
		     ?><div class="box_container"><div class="box_oranje"><?php
		 } else {
		     ?><div class="box_paars"><?php
		 }?>
	<div class="box_datum"><?php echo strftime("%d %B %Y",$r[0]); ?></div>
	<div class="box_body"><?php echo $r[1]; ?></div>   	   	 
	</div>
 <?php
 		$i++;
		if ($i > 2) {
		    $i = 1;
		  ?><div style="clear:both"></div></div><?php
		  }
	 }
	
}

function generate_code() {
  $characters = array(
"A","B","C","D","E","F","G","H","I","J","K","L","M",
"N","O","P","Q","R","S","T","U","V","W","X","Y","Z",
"1","2","3","4","5","6","7","8","9","0",
"a","b","c","d","e","f","g","h","i","j","k","L","m",
"n","o","p","q","r","r","s","t","v","w","x","y","z");
  $code = "aaaaaaaaaaaaaaaaaaaa";
  while (db_row("select confirmcode from deelnemers where confirmcode='$code'")) {
	  for ($i = 0; $i < 20; $i++) {
		  $code[$i] = $characters[rand(0,count($characters) - 1)];
	  } // for
	} // while
  return $code;
} // generate_code

function generate_password() {
  $characters = array(
"A","B","C","D","E","F","G","H","I","J","K","L","M",
"N","O","P","Q","R","S","T","U","V","W","X","Y","Z",
"1","2","3","4","5","6","7","8","9","0",
"a","b","c","d","e","f","g","h","i","j","k","L","m",
"n","o","p","q","r","r","s","t","v","w","x","y","z");
  $code="00000000";
	  for ($i = 0; $i < 8; $i++) {
		  $code[$i] = $characters[rand(0,count($characters) - 1)];
		} // for
  return $code;
} // generate_password


function checkDateFormat($date)
{
  //match the format of the date
  if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts)) {
    //check weather the date is valid of not
        if (checkdate($parts[2], $parts[3], $parts[1]))
          return true;
        else
         return false;
  }
  else
    return false;
}


function strip_html($in) {
	$pattern[1] = '/<p>/';
	$pattern[2] = '/<\/p>/';
	$pattern[3] = '/<strong>/';
	$pattern[4] = '/<\/strong>/';
	$pattern[5] = '/<em>/';
	$pattern[6] = '/<\/em>/';
	$pattern[7] = '/<strike>/';
	$pattern[8] = '/<\/strike>/';
	$pattern[9] = '/<br>/';
	$pattern[10] = '/<br\/>/';
	$pattern[11] = '/<br \/>/';
	$pattern[12] = '/<img.{1,}\/>/i';
	
	$replace[1] = '';
	$replace[2] = '';
	$replace[3] = '';
	$replace[4] = '';
	$replace[5] = '';
	$replace[6] = '';
	$replace[7] = '';
	$replace[8] = '';
	$replace[9] = '';
	$replace[10] = '';
	$replace[11] = '';
	$replace[12] = '';
	
	$out = preg_replace($pattern,$replace,$in);
	return $out;
}

function convert_html_to_txt($in) {
	$pattern[1] = '/<p>/';
	$pattern[2] = '/<\/p>/';
	$pattern[3] = '/<strong>/';
	$pattern[4] = '/<\/strong>/';
	$pattern[5] = '/<em>/';
	$pattern[6] = '/<\/em>/';
	$pattern[7] = '/<strike>/';
	$pattern[8] = '/<\/strike>/';
	$pattern[9] = '/<br>/';
	$pattern[10] = '/<br\/>/';
	$pattern[11] = '/<br \/>/';

	$replace[1] = "";
	$replace[2] = "\n";
	$replace[3] = "";
	$replace[4] = "";
	$replace[5] = "";
	$replace[6] = "";
	$replace[7] = "";
	$replace[8] = "";
	$replace[9] = "\n";
	$replace[10] = "\n";
	$replace[11] = "\n";
	
	$out = preg_replace($pattern,$replace,$in);
	return $out;
}


function persoon($id) {
	$r = db_row("select voornaam, voorvoegsel, achternaam from personen where id = '$id'");
	$naam = $r[0];
	if (!empty($r[1])) {
	    $naam .= " $r[1]";
	}
	if (!empty($r[2])) {
	    $naam .= " $r[2]";
	}
	return $naam;
}

function stuur_bericht($aan, $ond, $bericht, $html = "<html><body></body></html>") {
/* LEGACY */	
	$to['naam']=persoon($aan);
	$r = db_row("select email from personen where id = '$aan'");
	$to['email'] = $r[0];
	$from['naam'] = "Kinderen voor Kika Draaiboek";
	$from['email'] = "<no_reply@kinderenvoorkika.nl>";
	$out = stuur_mail($to, $ond, $from, $html, $bericht);
	return $out;
}

function cleanup_mail_naam($in) {
	$out=ereg_replace("/[\,\;\:]",'',$in);
	return $out;
}

function maak_mailadres($in) {
/*	
	$in: emailadres-object of emailadres
*/
	
	if (is_array($in)) {
		if (!is_email($in['email'])) {
			$out='';
		} else {
			if (!empty($in['naam'])) {
					
					$out=cleanup_mail_naam($in['naam'])." <".$in['email'].">"; 
				} else {
			    $out = $in['email'];
			}
		}
	} else {
	  	if (!is_email($in)) {
	  	    $out = '';
	  	} else {
	  	    $out = $in;
	  	}
	}
	return $out;
}


function php_quot_print_encode($str)
{
    $lp = 0;
    $ret = '';
    $hex = "0123456789ABCDEF";
    $length = strlen($str);
    $str_index = 0;
    
    while ($length--) {
        if ((($c = $str[$str_index++]) == "\015") && ($str[$str_index] == "\012") && $length > 0) {
            $ret .= "\015";
            $ret .= $str[$str_index++];
            $length--;
            $lp = 0;
        } else {
            if (ctype_cntrl($c) 
                || (ord($c) == 0x7f) 
                || (ord($c) & 0x80) 
                || ($c == '=') 
                || (($c == ' ') && ($str[$str_index] == "\015")))
            {
                if (($lp += 3) > PHP_QPRINT_MAXL)
                {
                    $ret .= '=';
                    $ret .= "\015";
                    $ret .= "\012";
                    $lp = 3;
                }
                $ret .= '=';
                $ret .= $hex[ord($c) >> 4];
                $ret .= $hex[ord($c) & 0xf];
            } 
            else 
            {
                if ((++$lp) > PHP_QPRINT_MAXL) 
                {
                    $ret .= '=';
                    $ret .= "\015";
                    $ret .= "\012";
                    $lp = 1;
                }
                $ret .= $c;
            }
        }
    }

    return $ret;
}



function show_mail_body ($in) {
	
	preg_match("/(text\/html)((.|\n)*)(--=next_part)/i",$in, $matches);
	$b = $matches[2];
	//echo "$b<br><br>";
	preg_match("/(printable)((.|\n)*)/i",$b, $matches);
	//print_r($matches);
	$b = html_entity_decode(quoted_printable_decode($matches[2]));
	//echo "$b<br><br>";
	preg_match("/(<html><body>)((.|\n)*)(<\/body><\/html>)/i",$b, $matches);
	$b = $matches[2];
	//echo $b;
	return $b;
}

function stuur_mail($aan, $ond, $van, $html = '', $text = '', $type = 'multipart') {
/* wrapper voor mail-functie voor sturen van multipart mailberichten
	$aan, $van: emailadres-object of een emailadres

*/	

	$to = maak_mailadres($aan);
	$from = maak_mailadres($van);
	
	if ((!empty($to)) && (!empty($from))) {
		if (empty($html)) {
		    $html="<html><body></body></html>";
		}
		
		$tijd = strftime("%Y-%m-%d %T");
		$boundary = "=next_part_".md5(time());

		$headers = "From: ".$from . "\r\n";	
		$headers .= "Subject: ".$ond . "\r\n";	
		$headers .= "Reply-To: " . $aan['email'] . "\r\n";
		$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		
		switch($type) {
			default;
			case 'multipart';
			case 'multi':
				$headers .= 'Content-Type: multipart/alternative; ';	
				$headers .= 'boundary="'.$boundary.'"' . "\r\n";
			break;
				
			case 'text';
			case 'txt':
				$headers .= 'Content-type: text/plain; charset=UTF-8' . "\r\n";
				$headers .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n";
			break;	

			case 'html':
				$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
				$headers .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n";
			break;	
		}
			
		switch (strtolower($type)) {
			case 'multipart';
			case 'multi';
			default:
				$html_part = 'Content-type: text/html; charset=UTF-8' . "\r\n";
				$html_part .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
				$html_part .= php_quot_print_encode($html) . "\r\n";

				$text_part = 'Content-type: text/text; charset=UTF-8' . "\r\n";
				$text_part .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
				$text_part .= php_quot_print_encode($text) . "\r\n";

				$multipart_closer = "\r\n\r\n--".$boundary;
				
				$bericht= "This is a Mime encoded message. \r\n\r\n--". $boundary."\r\n".$text_part . $multipart_closer. "\r\n" . $html_part . $multipart_closer . "--";	
			break;
				
			case 'html':
				$bericht = php_quot_print_encode($html);
			break;	

			case 'text';
			case 'txt':
				$bericht = php_quot_print_encode($text) . "\r\n";
			break;		
		}
				
		$out = mail($to, $ond, $bericht, $headers, "-f".$van['email']);
		$bericht = db_esc($bericht);
		$headers = db_esc($headers);
		$to = db_esc($to);
		$from = db_esc($from);
		$ond = db_esc($ond);
		db_query("insert into mail_log (van, aan, onderwerp, headers, body, tijd, succes) values ('$from','$to','$ond','$headers','$bericht','$tijd','$out')");
	}
	else {
	    $out = false;
	}
	return $out;
}


function verstuur_bevestiging($p) {
	global $protocol, $domein; 
	
	
	$message = "<html><body>Geachte heer/mevrouw ".
	$p['ouder'] . ",<br><br>uw ".
	$p['geslacht'] . " " . $p['kind']." is aangemeld voor het evenement 'De Berg Op' op ".event_datum().".<br />
	<br />
	Deelname aan dit evenement kan alleen met toestemming van de ouders/verzorgers. Door op onderstaande link (Ja) te klikken bevestigt u de deelname van uw ".$p['geslacht'].". U geeft daarmee tevens aan dat u op de hoogte bent van de voorwaarden van het evenement. Ook geeft u hierbij toestemming voor het aanmaken en publiceren van de persoonlijke pagina van uw ".$p['geslacht'].".<br />
	<br />
	<span style=\"font-size: 2em; font-weight: bold\"><a href=\"$protocol$domein/c/".$p['code']."\">Ja</a></span>, <br><br>ik ga akkoord met de <a href=\"$protocol$domein/?state=voorwaarden\">inschrijfvoorwaarden</a> en het <a href=\"$protocol$domein?state=deelnamevoorwaarden\">deelnemersreglement</a> en geef hierbij toestemming voor de deelname van mijn ".$p['geslacht']." en voor het pubiceren van de persoonlijke pagina</a>. <br /><br />Met vriendelijke groet,<br />Stichting Kinderen voor KiKa<br/>Stuurgroep De Berg Op
					</body></html>";

	
	$message_txt = "Geachte heer/mevrouw ".
	$p['ouder'].",\r\n\r\nuw ".
	$p['geslacht']." ".$p['kind']." is aangemeld voor het evenement 'De Berg Op' op ".event_datum().".\r\n\r\n
	Deelname aan dit evenement kan alleen met toestemming van de ouders/verzorgers. Door op onderstaande link (Ja) te klikken bevestigt u de deelname van uw ".$p['geslacht'].". U geeft daarmee tevens aan dat u op de hoogte bent van de voorwaarden van het evenement. Ook geeft u hierbij toestemming voor het aanmaken en publiceren van de persoonlijke pagina van uw ".$p['geslacht'].".\r\n\r\n
	Bevestigingslink: ".$protocol.$domein."/c/".$p['code']."<".$protocol.$domein."/c/".$p['code'].">\r\n\r\nik ga akkoord met de inschrijfvoorwaarden <$protocol$domein/?state=voorwaarden> en het deelnemersreglement <$protocol$domein?state=deelnamevoorwaarden> en geef hierbij toestemming voor de deelname van mijn ".$p['geslacht']." en voor het pubiceren van de persoonlijke pagina.\r\n\r\n
	Met vriendelijke groet,\r\nStichting Kinderen voor KiKa\r\nStuurgroep De Berg Op";
	
	$aan['email'] = $p['oudermail'];
	$van['naam'] = 'Kinderen voor KiKa';
	$van['email'] = 'info@kinderenvoorkika.nl';
	stuur_mail($aan,'Bevestiging deelname De Berg Op', $van, $message, $message_txt);
	
} // verstuur_bevestiging



function editor_naar_txt($in) {
	// eerst de eerste <p> en de laatste </p> verwijderen
	$pattern = "/^<p>/";
	$replace = "";
	$out=preg_replace($pattern,$replace,$in);
	$pattern = "/<\/p>$/";
	$replace = "";
	$out = preg_replace($pattern,$replace,$out);
	
	// eerste \n en \r verwijderen
	$pattern = "/^[\n,\r]{0,}/";
	$replace = "";
	$out = preg_replace($pattern,$replace,$out);
	
	// laatste \n en \r verwijderen
	$pattern = "/[\n,\r]{0,}$/";
	$replace = "";
	$out = preg_replace($pattern,$replace,$out);


	// dan alle overige html vervangen
	return convert_html_to_txt($out);
} // editor_naar_txt

function voorloop_nul ($st,$lengte) {
	while (strlen($st) < $lengte) {
	    $st='0'.$st;
	}

	return $st;	
}

function proef11($bankrek){
  $csom = 0;                            // variabele initialiseren
  $pos = 9;                             // het aantal posities waaruit een bankrekeningnr hoort te bestaan
  for ($i = 0; $i < strlen($bankrek); $i++){
    $num = substr($bankrek,$i,1);       // bekijk elk karakter van de ingevoerde string
    if ( is_numeric( $num )){           // controleer of het karakter numeriek is
      $csom += $num * $pos;                        // bereken somproduct van het cijfer en diens positie
      $pos--;                           // naar de volgende positie
    }
  }
  $postb = ($pos > 1) && ($pos < 7);    // True als resterende posities tussen 1 en 7 => Postbank
  $mod = $csom % 11;                                        // bereken restwaarde van somproduct/11.
  return( $postb || !($pos || $mod) );  // True als het een postbanknr is of restwaarde=0 zonder resterende posities
}


function strip_cr($in) {
	$pattern = "/[\r\n]/";
	$replace = "";
	return preg_replace($pattern,$replace,$in);
}


function is_bedrag($in) {
// retourneert een bedrag (real met 2 decimalen) als de string een bedrag is en anders false

	$regexa = "/[€]{0,1}(\s){0,1}[0-9]{0,}([.,]){0,1}([0-9]){0,2}/i";

	$regex = "/^([€\s]{0,}){0,1}(\d{0,})(([.,]{1})(\d{0,})){0,1}$/i";

	$valid = preg_match($regex, $in, $matches);
	
	if (!$valid) {
	    return false;
	} else {
		$bedrag = round($matches[2]+($matches[5]/100),2);
		return $bedrag;
    }
} // is_bedrag

function event_datum() {
	$r = db_row("select waarde from system where parameter='event_date'");
	return $r[0];
} // event_datum

function event_dag() {
	$r = db_row("select waarde from system where parameter='event_dag'");
	return $r[0];
} // event_dag

function min_geboortedatum() {
	$r = db_row("select waarde from system where parameter='min_geboortedatum'");
	return $r[0];
} // min_geboortedatum

function max_geboortedatum() {
	$r = db_row("select waarde from system where parameter='max_geboortedatum'");
	return $r[0];
} // max_geboortedatum

function onderhoud() {
	$r = db_row("select waarde from system where parameter='onderhoud'");
	return ($r[0] == 'j');
} // event_datum

function doneren_toegestaan() {
	global $user;
	$r = db_row("select waarde from system where parameter='doneren_toegestaan'");
	if ((!onderhoud()) || ($user['beheerder'] == 'j')) {
	    return $r[0];
	} else {
	    return 'n';
	}
} // doneren_toegestaan

function in_inschrijfperiode() {
	$t=strftime("%Y-%m-%d",time());
	$r=db_row("select waarde from system where parameter='sluiting_inschrijving'");
	//echo "<!-- $r[0], $t --> ";
	return ($t <= $r[0]);
} // in_inschrijfperiode

function inschrijven_toegestaan() {
	global $user;
	if ((!onderhoud()) || ($user['beheerder'] == 'j')) {
	    return in_inschrijfperiode();
	} else {
	    return false;
	}
} // inschrijven_toegestaan


function rotate_image($file, $hoek) {
	preg_match("/(\/)(\w+)(\.)(\w+)$/i", strtolower($file), $matches);
	$naam = $matches[2];
	$ext = $matches[4];
	
	switch ($ext) {
		case 'jpg';
		case 'jpeg';
		default: $im = imagecreatefromjpeg($file); break;
		
		case 'gif': $im = imagecreatefromgif($file); break;
			
		case 'png': $im = imagecreatefrompng($file); break;
	}
	
	$im = imagerotate($im, $hoek,0);
	
	unlink($file);
	
	switch ($ext) {
		case 'jpg';
		case 'jpeg';
		default: imagejpeg($im, $file); break;
		
		case 'gif': imagegif($im, $file); break;
			
		case 'png': imagepng($im, $file); break;		
	}
	imagedestroy($im);	
}

?>