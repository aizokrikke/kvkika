<?php 
function user_has_right($user, $recht) {
	$suc = ($row = db_row("select id from rechten where user = '$user' and recht = '$recht' and verwijderd <> 'j'"));
	RETURN $suc;
}

$id = $_REQUEST['id'];
$voornaam = $_REQUEST['voornaam'];
$voorvoegsel = $_REQUEST['voorvoegsel'];
$achternaam = $_REQUEST['achternaam'];
$email = $_REQUEST['email'];
$login_naam = $_REQUEST['login_naam'];
$pass1 = $_REQUEST['pass1'];
$pass2 = $_REQUEST['pass2'];
$do = $_REQUEST['do'];
$act = $_REQUEST['act'];

if ($act == 'flip_actief')
  {
	  $r = db_row("select actief from beheerders where persoon = '". db_esc($id) . "'");
	  if ($r[0] == 'j') {
	      $actief='n';
	  } else {
	      $actief='j';
	  }
	  db_query("update beheerders set actief = '$actief' where persoon = '" . db_esc($id) . "'");
	  $act = '';
  }


// formulier afhandelen
	switch ($do) {
		case 'verwijderen':
			db_query("update beheerders set verwijderd = 'j' where persoon = '" . db_esc($id) . "'");
			$act = '';
		break;
		case 'opslaan':
			if (empty($login_naam)) {
			    $err[] = 'geen login ingevoerd';
			}
			if (empty($achternaam)) {
			    $err[] = 'geen achternaam ingevoerd';
			}
			if ((!empty($pass1)) and ($pass1 <> $pass2)) {
			    $err[] = 'passwords zijn niet gelijk';
			}
			$login_naam = strtolower($login_naam);
			$login_md5 = md5($login_naam);
			$password_md5 = md5($pass1);

			if (empty($err)) {
				db_query("update personen set voornaam = '" . db_esc($voornaam) . "', voorvoegsel = '" . db_esc($voorvoegsel) .
                    "', achternaam = '" . db_esc($achternaam) . "', email = '" . db_esc($email) . "', login = '" . db_esc($login_naam) .
                    "', login_md5 = '$login_md5', password_md5 = '$password_md5' where id = '". db_esc($id). "'");
				$act = '';
			  }
		break;
		case 'toevoegen' :
			if (empty($login_naam)) {
			    $err[]='geen login ingevoerd';
			}
			if (empty($achternaam)) {
			    $err[]='geen achternaam ingevoerd';
			}
			if ((!empty($pass1)) and ($pass1 <> $pass2)) {
			    $err[]='passwords zijn niet gelijk';
			}
			$login_naam = strtolower($login_naam);
			$login_md5 = md5($login_naam);
			$password_md5 = md5($pass1);

			if (empty($err)) {
				db_query("insert into personen (voornaam, voorvoegsel, achternaam, email, login, login_md5, password_md5) values ('" .
                    db_esc($voornaam) . "', '" . db_esc($voorvoegsel) . "', '" . db_esc($achternaam) . "', '" . db_esc($email) . "', '" .
                    db_esc($login_naam) . "', '$login_md5', '$password_md5') ");
				$id = db_insert_id();
				db_query("insert into beheerders (persoon, actief) values ('$id','j')");
				$act='';
			  }
		break;
	} // switch
		
	if (!empty($err)) {
		  foreach ($err as $val) {
?>
				<div class="error">Oeps... <?php echo $val; ?></div>				
<?php
			}
	  }

	if (empty($act)) {

?>
	<table>
    	<tr valign="top"><td></td><td></td><td></td><td><small>actief</small></td>
<?php $res = db_query("select recht from rechtendef order by id");
	  while ($row = db_row($res)) {
	      ?><td width="55" align="center"><small><?php echo $row[0]; ?></small></td><?php
	  } ?>
        </tr>
<?php
	$res = db_query("select beheerders.id, voornaam, voorvoegsel, achternaam, actief, personen.id from beheerders, personen where beheerders.persoon=personen.id and personen.verwijderd != 'j' and beheerders.verwijderd!='j' order by achternaam, voornaam");
	
	while ($row = db_row($res)) {
?>
	<tr>
    	<td width="30"><a href="?state=admin&go=beheerders&act=edit&id=<?php echo $row[5]; ?>"><i class="fas fa-user-edit"></i></a></td>
        <td width="30"><a href="?state=admin&go=beheerders&act=del&id=<?php echo $row[5]; ?>"><i class="far fa-trash-alt"></i></a></td>
    	<td width="200"><a href="?state=admin&go=beheerders&act=edit&id=<?php echo $row[5]; ?>">
		<?php echo $row[1]; 
		if (!empty($row[2])) {
		    echo " $row[2]";
		}
		if (!empty($row[3])) {
		    echo " $row[3]";
		}?></a></td>
        <td width="50"><a href="?state=admin&go=beheerders&act=flip_actief&id=<?php echo $row[5]; ?>"><i class="fas fa-check-circle <?php if ($row[4] != 'j') { echo "un"; } ?>selected" title="actief" ></i></a></td>
<?php
        $rres = db_query("select id, recht, beschrijving from rechtendef order by id");
		while ($rrow = db_row($rres)) { ?>
          <td align="center"><a href="javascript:void();" onClick="do_flipRecht(<?php echo $row[5]; ?>,<?php echo $rrow[0]; ?>);">
            <i class="fas fa-check-circle <?php
            if (user_has_right($row[5], $rrow[0])) {?>
                selected <?php
            } else {?>
                unselected <?php
            } ?>" title="<?php echo $rrow[2]; ?>"></i></a>
   		  </td>
<?php 
		  }
?>			  
    </tr>
<?php		  
	  }
?>
	</table>
    <br /><br />
    <a href="?state=admin&go=beheerders&act=new"><i class="fas fa-plus-circle"></i> Nieuwe beheerder toevoegen</a>
    
<?php
	  } else {
        if ((($act == 'edit') or ($act == 'del')) and ($opnieuw != 'j')) {
            $r = db_row("select voornaam, voorvoegsel, achternaam, email, login from personen where id = '" . db_esc($id) . "' and verwijderd != 'j'");
            $voornaam = $r[0];
            $voorvoegsel = $r[1];
            $achternaam = $r[2];
            $email = $r[3];
            $login_naam = $r[4];
        }
			
?>
	<?php if ($act == 'new') {
	    ?><h3>Nieuwe beheerder</h3><?php
	} else {
	    ?><h3>Beheerder<?php
            if ($act == 'del') {
                ?> verwijderen<?php
            }
            ?></h3><?php
	} ?>
	<form action="?state=admin&go=beheerders&act=<?php echo $act; ?>" method="post">
    <input type="hidden" name="opnieuw" value="j" />
    <input type="hidden" name="id" value="<?php echo $id; ?>" />
    <div style="float: left; margin-right:10px;">voornaam<br />
    <input type="text" name="voornaam" value="<?php echo $voornaam; ?>" size="30" />
    </div>
    <div style="float: left; margin-right:10px;">voorvoegsel<br />
   <input type="text" name="voorvoegsel" value="<?php echo $voorvoegsel; ?>" size="10" />
   </div>
    <div style="float: left; margin-right:10px;">achternaam<br />
   <input type="text" name="achternaam" value="<?php echo $achternaam; ?>" size="30" />
   </div>
   <div style="clear:both"></div><br /><br />
   email<br />
   <input type="text" name="email" value="<?php echo $email; ?>" size="30" /><br /><br />   
   login<br />
   <input type="text" name="login_naam" value="<?php echo $login_naam; ?>" size="30" /><br /><br />
   
    <div style="float: left; margin-right:10px;">password<br />
   <input type="password" name="pass1" value="<?php echo $pass1; ?>" />
   </div>
    <div style="float: left; margin-right:10px;">herhaal password<br />
   <input type="password" name="pass2" value="<?php echo $pass2; ?>" />
   </div>
   <div style="clear:both"></div>
       <?php if ($act != 'new') {
           ?>laat leeg om password te behouden<?php
       } ?><br /><br />
<?php 
        switch ($act) {
            case 'edit': ?><input type="submit" name="do" value="opslaan" /><?php break;
            case 'del': ?><input type="submit" name="do" value="verwijderen" /><?php break;
            case 'new': ?><input type="submit" name="do" value="toevoegen" /><?php break;
        }
?> <input type="submit" name="annuleren" value="annuleren" />
	</form>
<?php
	}
?>	  
	  