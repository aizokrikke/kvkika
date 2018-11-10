<?php

$do=$_REQUEST['do'];
$voornamen=$_REQUEST['voornamen'];
$voorvoegsel=$_REQUEST['voorvoegsel'];
$achternaam=$_REQUEST['achternaam'];
$adres=$_REQUEST['adres'];
$nr=$_REQUEST['nr'];
$postcode=$_REQUEST['postcode'];
$plaats=$_REQUEST['plaats'];
$email=$_REQUEST['email'];
$tel=$_REQUEST['tel'];
$loginnaam=$_REQUEST['loginnaam'];
$pass=$_REQUEST['pass'];
$pass=$_REQUEST['pass2'];
$login_md5=md5($loginnaam);
$pass_md5=md5($pass);

echo $tel;

$err=array();


// formulier verwerken
	if ($do=='verwerken')
	  {
		if (empty($email))
		  { $err[]="Geen emailadres opgegeven"; }
		if ($pass<>$pass2)
		  { $err[]='Passwords niet gelijk'; } 
print_r($err);
		if (empty($err))
		  { 
		  // database updaten
			switch ($action) {
				case 'new':
					mysql_query("insert into personen 	(voornaam, voorvoegesel, achternaam
														adres, adres_nr, postcode, plaats,
														email, tel, login, login_md5, password_md5)
											 values		('$voornaam','$voorvoegsel;,'$achternaam',
														'$adres','$nr','$postcode','$plaats',
														'$email,'$loginnaam','$login_md5','$pass_md5')");
					$id=mysql_insert_id();
					switch ($call) {
						case 'beheerders': mysql_query("insert into beheerders (persoon, actief, verwijderd) values ('$id','j','n'");
						break;											   		
					} // switch
			  	break;
				
				case 'edit':
					$query="update personen set voornaam='$voornamen', voorvoegsel='$voorvoegsel', achternaam='$achternaam',
												adres='$adres', adres_nr='$nr', postcode='$postcode', plaats='$plaats',
												email='$email', tel='$tel', login='$login', login_md5='$login_md5'";
					if (!empty($password))
					  { $query.=" passowrd_md5='$pass_md5'"; }
					$query.=" where id='$id'";  
					mysql_query($query); 							
				break;
				
				case 'delete':
					mysql_query("update personen set verwijderd='j' where id='$id'");
					mysql_query("update beheerders set verwijderd='j' where persoon='$id'");
				breakk;
			} // switch
			$go=$call;			
		  }
	  }
	  else
	  {


if (!empty($err))
  {
//	foreach ($err as $key => $val)
//	  {  
?>
<span class="error"><?php echo $val; ?></span><br>
<?php	
//	  }
  }
?>
<form name="personenform">
<input type="hidden" name="go" value="<?php echo $go; ?>">
<input type="hidden" name="call" value="<?php echo $call; ?>">
<input type="hidden" name="action" value="<?php echo $action; ?>">
<input type="hidden" name="state" value="<?php echo $state; ?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<?php

switch ($action) {
	
	case 'edit':
		if ($row=mysql_fetch_row(mysql_query("select voornaam, voorvoegsel, achternaam, email, tel, adres, adres_nr, postcode, plaats, login from personen where id='$id'")))
		  {
			$voornamen=$row[0];
			$voorvoegsel=$row[1];
			$achternaam=$row[2];
			$email=$row[3];
			$telefoon=$row[4];
			$adres=$row[5];
			$nr=$row[6];
			$postcode=$row[7];
			$plaats=$row[8];
			$login=$row[9]; 
		  }
		
	
	
	?>
    	<h2>Persoon bewerken</h2><br>
        	<div class="formveld">voornamen<br><input type="text" name="voornamen" size="30" value="<?php echo $voornamen; ?>"></div>
        	<div class="formveld">tussenvoegsel<br><input type="text" name="voorvoegsel" size="15" value="<?php echo $voorvoegsel; ?>"></div>
	       	<div class="formveld">achternaam<br><input type="text" name="achternaam" size="30" value="<?php echo $achternaam; ?>"></div>
            <div class="formregeleinde"></div>            
        	<div class="formveld">straat<br><input type="text" name="adres" size="30" value="<?php echo $adres; ?>"></div>
        	<div class="formveld">nr<br><input type="text" name="nr" size="4" value="<?php echo $nr; ?>"></div>
	       	<div class="formveld">postcode<br><input type="text" name="postcode" size="7" value="<?php echo $postcode; ?>"></div>
	       	<div class="formveld">plaats<br><input type="text" name="plaats" size="30" value="<?php echo $plaats; ?>"></div>
            <div class="formregeleinde"></div>   
        	<div class="formveld">telefoon<br><input type="text" name="tel" size="15" value="<?php echo $tel; ?>"></div>
        	<div class="formveld">mail<br><input type="text" name="email" size="40" value="<?php echo $email; ?>"></div>            
            <div class="formregeleinde"></div>
           	<div class="formveld">login<br><input type="text" name="loginnaam" size="30" value="<?php echo $loginnaam; ?>"></div>
            <div class="formregeleinde"></div> 
            <div class="formveld">password<br><input type="password" name="pass" size="30" value="<?php echo $pass; ?>"><br>leeg: behoud bestaande password</div>
            <div class="formveld">herhaal password<br><input type="password" name="pass2" size="30" value="<?php echo $pass2; ?>"></div>
            <div class="formregeleinde"></div> 
            <input type="submit" value="verwerken" name="do" class="kika_button">                          
        
		
<?php	
	break;
	
	
	case 'delete':
		
	
	break;
	
	case 'new':
	
	
	break;
		
	
	
} // switch

$page_end='j';

	  } // else
?>

</form>