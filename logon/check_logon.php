<?php 
session_start();
$tijdstip = strftime("%F %T", time());
$sessietijd = time() + (2 * 60 * 60);

// global vars
$password = db_esc($_REQUEST['password']);
$login = strtolower(db_esc($_REQUEST['login']));
$password_md5 = md5($password);
$set_cookie = $_REQUEST['set_cookie'];
$state = db_esc($_REQUEST['state']);
$ret = db_esc($_REQUEST['ret']);
$ip = $_SERVER['REMOTE_ADDR'];
$go = $_REQUEST['go'];
$do = $_REQUEST['do'];
$call = $_REQUEST['call'];
$action = $_REQUEST['action'];
$id = $_REQUEST['id'];
$status = $_REQUEST['status'];
$pag = $_REQUEST['pag'];
$form_inc = $_REQUEST['form_inc'];

//echo "check logon: $login, $password, $do<br>";
 
function do_rights($id) {
    global $user;
    // beheerder?
    if ($rrow = db_row("  select id 
                              from beheerders 
                              where persoon = '$id' 
                              and actief = 'j' and 
                              verwijderd != 'j'")) {
            $user['beheerder']='j';
    }
				  
    // userrechten toevoegen
    $rres = db_query("  select rechtendef.id, rechtendef.recht 
                            from rechtendef, rechten 
                            where rechten.user = '$id' 
                            and rechten.recht = rechtendef.id 
                            and rechten.verwijderd != 'j'");
    while ($rrow = db_row($rres))  {
          $user['rechten'][$rrow[1]] = $rrow[0];
    }
					  
    // deelnemers
    $dres = db_query("select code, pagina, alias, show_berichten, show_stand, foto, bevestigd 
                         from deelnemers 
                         where persoon = '$id' 
                         and bevestigd = 'j' 
                         and verwijderd != 'j'");
    if ($drow = db_row($dres))
    {
        $user['code'] = $drow[0];
        $user['alias'] = $drow[2];
        $user['pagina'] = $drow[1];
        $user['show_berichten'] = $drow[3];
        $user['show_stand'] = $drow[4];
        $user['foto'] = $drow[5];
        $user['bevestigd'] = $drow[6];
    }
}
 
function login($login,$password,$set_cookie = 'j') {
  global $user_cookienaam, $user, $hoofddomein, $tijdstip, $state, $ret, $ip;
    
	
  	$password_md5 = md5($password);
	//echo "login: $login $password $password_md5<br>";
	
	$res = db_query("SELECT id,voornaam, voorvoegsel, achternaam, login_md5 FROM personen WHERE login='$login' and password_md5='$password_md5' and verwijderd!='j' ORDER BY login") or die(mysql_error());
	if ($row = db_row($res)) {  // user wel in database
	  	$found = true;
		$user['id'] = $row[0];
		$user['naam'] = $row[1];
		$user['voornaam'] = $row[1];
		if (!empty($row[2])) {
		    $user['naam'] .= " $row[2]";
		}
		if (!empty($row[3])) {
		    $user['naam'] .= " $row[3]";
		}
		$cookie_val = $row[4] . "|" . $password_md5 . "|" .$row[0];
		if ($set_cookie == 'j') {
		    setcookie($user_cookienaam, $cookie_val,time()+(60*60*24*365),'/','.' . $hoofddomein);
		} else {
		    setcookie($user_cookienaam, $cookie_val,0,'/','.' . $hoofddomein);
		}
		db_query("INSERT INTO userlog (user, tijd, ip, state) VALUES ('$row[0]','$tijdstip','$ip','$ret')");
		
		do_rights($row[0]);
	} else {
	    $found = false;
	  	$user = array();
	}
	  
	  //print_r($user);	
	  
  return $found;
   
} // function login

function check_login() {
  global $sessietijd, $tijdstip, $ip, $user, $user_cookienaam, $state, $hoofddomein;
  
	$user_cookie = $_COOKIE[$user_cookienaam];

 	$user['id'] = '';
	$user['naam'] = '';
	$user['rechten'] = array();

	$cookieparts = explode('|',$user_cookie);
	$cookie_login_md5 = $cookieparts[0];
	$cookie_password_md5 = $cookieparts[1];
	$cookie_id = $cookieparts[2];
		
	if (!empty($user_cookie))
        { // eerder geaccepteerd -> checken met database
        $query = "  SELECT personen.id, voornaam, voorvoegsel, achternaam 
                    FROM personen 
                    WHERE   personen.verwijderd != 'j' and	
                            personen.id = '$cookie_id' and
                            login_md5 = '$cookie_login_md5' and 
                            password_md5 = '$cookie_password_md5' 
                    ORDER BY achternaam";

//        $res = db_query($query);

        if ($row = db_row($query))
          {  // user bestaat in database
              $verbanrow = db_row("select tot from verbannen where user='$row[0]'");
              if (($tijdstip < $verbanrow[0]) or ($verbanrow[0] == '1')) {
                  $user['verbannen'] = 'j';
              }
              $user['id'] = $row[0];
              $user['voornaam'] = $row[1];
              $naam = $row[1];
              if (!empty($row[2])) {
                  $naam .= " $row[2]";
              }
              if (!empty($row[3])) {
                  $naam .= " $row[3]";
              }
              $user['naam'] = $naam;
              //mysql_query("INSERT INTO userlog (id, tijd, ip, state) VALUES ('$row[0]','$tijdstip','$ip','$state')") or die(mysql_error());
              setcookie($user_cookienaam, $user_cookie, $sessietijd,"/", $hoofddomein,0);

              do_rights($row[0]);
          }
        }
	 $_SESSION['user'] = $user;
} // check_login

if (($state!='logoff') and (empty($login))) {
    check_login();
}
 // maak user global

  // print_r($user);
?>