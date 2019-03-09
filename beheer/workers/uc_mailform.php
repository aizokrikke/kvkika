<?php
header("Content-Type: text/html; charset=utf-8\n"); 
require('../libs/connect.php');
require('../libs/is_email.php');

$voornaam = $_REQUEST['voornaam'];
$achternaam = $_REQUEST['achternaam'];
$email = $_REQUEST['email'];

if (empty($email)) {
  	$err='Geen email ingevuld!';
} else {
	if (!is_email($email)) {
	    $err='Ongeldig email adres ingevuld';
	}
}
  
if (empty($err)) {
	// in database
	$voorvoegsels = '';
	$a_parts = explode(' ',$achternaam);
	$achternaam = array_pop($a_parts);
	while (!empty($a_parts)) {
		$voorvoegsel = array_pop($a_parts) . ' ' . $voorvoegsel;
	  }
	db_query("insert into personen (voornaam, voorvoegsel,achternaam, email) values ('$voornaam','$voorvoegsel','$achternaam','$email')");
	$succes = 'j';
}

include('uc_mailform_body.php');

?>