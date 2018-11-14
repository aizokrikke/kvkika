<?php
include_once('libs/is_email.php');
$soort = $_REQUEST['soort'];
$teamnaam = $_REQUEST['teamnaam'];
$voornaam = $_REQUEST['voornaam'];
$tussenvoegsel = $_REQUEST['tussenvoegsel'];
$achternaam = $_REQUEST['achternaam'];
$naam = $achternaam;
$straat = $_REQUEST['straat'];
$nummer = $_REQUEST['nummer'];
$pc = $_REQUEST['pc'];
$plaats = $_REQUEST['plaats'];
$geb_dag = voorloop_nul($_REQUEST['geb_dag'],2);
$geb_maand = voorloop_nul($_REQUEST['geb_maand'],2);
$geb_jaar = $_REQUEST['geb_jaar'];
$geslacht = $_REQUEST['geslacht'];
$email = $_REQUEST['email'];
$telefoon = $_REQUEST['telefoon'];
$mobiel = $_REQUEST['mobiel'];
$school = $_REQUEST['school'];
$pagina = $_REQUEST['pagina'];
$login = $_REQUEST['login'];
$password = $_REQUEST['password'];
$password_check = $_REQUEST['password_check'];
$akkoord = $_REQUEST['akkoord'];
$reg_akkoord = $_REQUEST['reg_akkoord'];
$v_voornaam = $_REQUEST['v_voornaam'];
$v_tussenvoegsel = $_REQUEST['v_tussenvoegsel'];
$v_achternaam = $_REQUEST['v_achternaam'];
$v_email = $_REQUEST['v_email'];
$v_telefoon = $_REQUEST['v_telefoon'];
$v_mobiel = $_REQUEST['v_mobiel'];

switch ($do) {
	case 'INLOGGEN':
		login($login,$password);
		break;
		
	case 'VERSTUUR':
		$bedrag=$_REQUEST['bedrag'];
		
		$bedrag=preg_replace('/,/','.',$bedrag);
		$naam=$_REQUEST['naam'];
		$bankrekening=$_REQUEST['bankrekening'];
		$onderwerp=$_REQUEST['onderwerp'];
		$voorwaarden=$_REQUEST['voorwaarden'];
		
		$bedrag=mysql_real_escape_string($bedrag);
		$naam=mysql_real_escape_string($naam);
		$bankrekening=mysql_real_escape_string($bankrekening);
		$id=mysql_real_escape_string($id);
		
		mysql_query("insert into sponsoring (bedrag, van, rekening, voor) values ('$bedrag','$naam','$bankrekening','$id')") or die(mysql_error());
		
		break;	
	
	case 'INSCHRIJVEN':
		if ($soort!='estafette')
		  { 
			if (!empty($tussenvoegsel)) { $naam=$tussenvoegsel." ".$naam; }
			if (!empty($voornaam)) { $naam=$voornaam." ".$naam; }	

			$v_naam=$v_achternaam;
			if (!empty($v_tussenvoegsel)) { $v_naam=$v_tussenvoegsel." ".$v_naam; }
			if (!empty($v_voornaam)) { $v_naam=$v_voornaam." ".$v_naam; }
			$gebdatum="$geb_jaar-$geb_maand-$geb_dag";	  
		  }
		  else
		  { 
		  	$voornaam='team';
		    $achternaam=$teamnaam;
		  	$gebdatum='2017-01-01';
		  }
				
		$nu=time();
		$grens=$nu-20;
		  	// voorkom dubbele aanmelding, vorige aanmelding moet ouder zijn dan 10 seconden
		if ($dr=mysql_fetch_row(mysql_query("select id from personen where voornaam='$voornaam' and achternaam='$achternaam' and gebdatum='$gebdatum' and (mutatie>'$grens')")))
		  { $fout[]='aanmelding is al verstuurd'; }
		  else
		  {	
			if (empty($soort))
			{ $fout[]="geen onderdeel geselecteerd"; }
			$ec=is_email($v_email,true,true);
			if (!empty($ec))
			  { $fout[]="Geen geldig emailadres van ouder/verzorger opgegeven"; }
			if (empty($v_achternaam))
			  { $fout[]="Geen naam van ouder/verzorger opgegeven"; }
			  
			//$v_gebdatum="$v_geb_jaar-$v_geb_maand-$v_geb_dag";
			//if ((!checkDateFormat($v_gebdatum)) or ($v_gebdatum>"1988-01-01"))
			//  { $fout[]="Ongeldige geboortedatum van ouder/verzorger opgegeven"; }
			//echo $gebdatum.", ".max_geboortedatum().", ".min_geboortedatum() ;  
			  
			if (!checkDateFormat($gebdatum))
			  { $fout[]="Ongeldige geboortedatum van deelnemer opgegeven"; }
			if ($gebdatum>max_geboortedatum())
				{ $fout[]="Je bent nog te jong om deel te nemen aan 'De Berg Op'"; }
			if ($gebdatum<min_geboortedatum())	
				{ $fout[]="Je bent te oud om deel te nemen aan 'De Berg Op'"; }
				
			if (empty($voornaam))
			  { $fout[]="Geen voornaam van de deelnemer ingevuld"; }	
			if (empty($achternaam))
			  { $fout[]="Geen achternaam van de deelnemer ingevuld"; }
			if (empty($straat))
			  { $fout[]="Geen straat van de deelnemer ingevuld"; }
			if (empty($nummer))
			  { $fout[]="Geen huisnummer van de deelnemer ingevuld"; }
			  
			$postcode=strtoupper(str_replace(" ","", $pc));
			if (!preg_match("/^\W*[1-9]{1}[0-9]{3}\W*[a-zA-Z]{2}\W*$/",  $postcode))
			  { $fout[]="Onjuiste postcode van de deelnemer ingevuld"; } 		  		   		  	
			if (empty($plaats))
			  { $fout[]="Geen plaats van de deelnemer ingevuld"; }
			$ec=is_email($email,true,true);  		  		   		  	
			if (!empty($ec))
			  { $fout[]="Geen geldig emailadres van de deelnemer ingevuld"; }
			if (empty($login)) 
				{ 
					$login=$email; 
					if (empty($login)) { $fout[]="Geen login gekozen"; }
				}
			if ($er=mysql_fetch_row(mysql_query("select id from personen where login='$login' and verwijderd!='j'")))
			  { $fout[]="Login is al in gebruik. Kies een andere login"; }  	
			if (empty($pagina))
			  { $fout[]="Geen paginanaam gekozen"; }
			if ($pr=mysql_fetch_row(mysql_query("select id from deelnemers where pagina='$pagina' and verwijderd!='j'")))
				{ $fout[]="De gekozen paginanaam is al in gebruik. Kies een andere paginanaam"; }
			if ($password!=$password_check)
			  { $fout[]="Wachtwoorden zijn niet gelijk"; }	 
			if ($akkoord!='j')
			  { $fout[]="Om mee te doen moet je akkoord gaan met de inschrijfvoorwaarden"; }
			if ($reg_akkoord!='j')
			  { $fout[]="Om mee te doen moet je akkoord gaan met het deelnemersreglement"; }
		  }
		
		if (empty($fout))
		  { // alles is ok, dus in de database
			    $soort=mysql_real_escape_string($soort);
		  		$voornaam=mysql_real_escape_string($voornaam);
		  		$tussenvoegsel=mysql_real_escape_string($tussenvoegsel);
		  		$achternaam=mysql_real_escape_string($achternaam);
		  		$geslacht=mysql_real_escape_string($geslacht);
		  		$email=mysql_real_escape_string($email);
		  		$telefoon=mysql_real_escape_string($telefoon);
		  		$mobiel=mysql_real_escape_string($mobiel);
		  		$straat=mysql_real_escape_string($straat);
		  		$nummer=mysql_real_escape_string($nummer);

				// deelnemer
				//$postcode="$pc_cijfer ".strtoupper($pc_letter);
				$postcode=mysql_real_escape_string($postcode);
				$login_md5=md5($login);
				$login=mysql_real_escape_string($login);
				$password_md5=md5($password);
				$code=generate_code();
				
				mysql_query("insert into personen (voornaam, voorvoegsel, achternaam, geslacht, gebdatum, email, tel, mobiel, adres, adres_nr, postcode, plaats, login, login_md5, password_md5, aangemaakt, mutatie) values  ('$voornaam', '$tussenvoegsel', '$achternaam', '$geslacht', '$gebdatum', '$email', '$telefoon', '$mobiel', '$straat', '$nummer', '$postcode', '$plaats', '$login', '$login_md5', '$password_md5','$nu','$nu')") or die(mysql_error());
				
				$deelnemer=mysql_insert_id();
				$pagina=mysql_real_escape_string($pagina);
				
				mysql_query("insert into deelnemers (persoon, categorie, pagina, berichten_toestaan, show_berichten, show_stand, bevestigd, confirmcode, school) values ('$deelnemer','$soort','$pagina','j','j','j','n','$code','$school')") or die(mysql_error());
				$deelnemer_id=mysql_insert_id();
				
				//ouder/verzorger
				$v_voornaam=mysql_real_escape_string($v_voornaam);
				$v_achternaam=mysql_real_escape_string($v_achternaam);
				$v_tussenvoegsel=mysql_real_escape_string($v_tussenvoegsel);
				$v_email=mysql_real_escape_string($v_email);
				$v_telefoon=mysql_real_escape_string($v_telefoon);
				$v_mobiel=mysql_real_escape_string($v_mobiel);
				mysql_query("insert into personen (voornaam, voorvoegsel, achternaam, email, tel, mobiel, aangemaakt, mutatie) values  ('$v_voornaam', '$v_tussenvoegsel', '$v_achternaam', '$v_email', '$v_telefoon', '$v_mobiel','$nu','$nu')") or die(mysql_error());
				$verzorger=mysql_insert_id();
				mysql_query("insert into verzorgers (deelnemer,verzorger) values ('$deelnemer_id','$verzorger')") or die(mysql_error());
				
				if ($geslacht=='m') { $zd='zoon'; } else { $zd='dochter'; }
				
				$naam=stripslashes($naam);
				$v_naam=stripslashes($v_naam);
			  
			  	$persoon['ouder']=$v_naam;
			    $persoon['geslacht']=$zd;
			    $persoon['kind']=$naam;
			  	$persoon['oudermail']=$v_email;
			  	$persoon['code']=$code;

				verstuur_bevestiging($persoon);
			   
				$status='bedankt';
		  }
		  	
		break;
} // switch

?>