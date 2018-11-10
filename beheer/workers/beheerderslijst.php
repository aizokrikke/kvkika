<?php 
function user_has_right($user,$recht) {
	$suc=($row=mysql_fetch_row(mysql_query("select id from rechten where user='$user' and recht='$recht' and verwijderd<>'j'")));
	RETURN $suc;
}

?>
	<table>
    	<tr><td></td><td></td><td></td><td><small>actief</small></td>
<?php $res=mysql_query("select recht from rechtendef order by id");
	  while ($row=mysql_fetch_row($res)) { ?><td width="55" align="center"><small><?php echo $row[0]; ?></small></td><?php } ?>        
        
        </tr>
<?php
	$res=mysql_query("select beheerders.id, voornaam, voorvoegsel, achternaam, actief, personen.id from beheerders, personen where beheerders.persoon=personen.id and personen.verwijderd!='j' and beheerders.verwijderd!='j' order by achternaam, voornaam") or die(mysql_error());
	
	while ($row=mysql_fetch_row($res))
	  {
?>
	<tr>
    	<td width="30"><a href="?state=admin&go=personen&call=beheerders&action=edit&id=<?php echo $row[5]; ?>"><img src="beheer/img/24x24/edit.png" alt="bewerken" title="bewerken"></a></td>
    	<td width="200"><a href="?state=admin&go=personen&call=beheerders&action=edit&id=<?php echo $row[5]; ?>">
		<?php echo $row[1]; 
		if (!empty($row[2])) { echo " $row[2]"; } 
		if (!empty($row[3])) { echo " $row[3]"; }?></a></td>
    	<td width="40"><a href="?state=admin&go=personen&call=beheerders&action=delete&id=<?php echo $row[5]; ?>"><img src="beheer/img/24x24/editcut.png" alt="verwijderen" title="verwijderen"></a></td>
    	<td width="50"><?php if ($row[4]=='j') { ?><a href="?state=admin&go=personen&call=beheerders&action=edit&id=<?php echo $row[0]; ?>"><img src="beheer/img/24x24/ok.png" alt="actief" title="actief"><?php } else { ?><img src="beheer/img/24x24/button_cancel.png" alt="inactief" title="inactief"><?php } ?></a></td>
<?php
        $rres=mysql_query("select id, recht, beschrijving from rechtendef order by id");
		while ($rrow=mysql_fetch_row($rres)) 
		  { ?>
          <td align="center"><a href="javascript:void();" onClick="do_flipRecht(<?php echo $row[5]; ?>,<?php echo $rrow[0]; ?>);"><?php			  
			  if (user_has_right($row[5],$rrow[0]))
			  	 { ?><img src="beheer/img/24x24/ok.png" title="<?php echo $rrow[2]; ?>" alt="<?php echo $rrow[2]; ?>"><?php } 
				 else 
				 { ?><img src="beheer/img/24x24/button_cancel.png" alt="<?php echo $rrow[2]; ?>" title="<?php echo $rrow[2]; ?>"><?php } ?></a>
   		  </td>
<?php 
			  } // while
?>			  
    </tr>    
        
<?php		  
	  } // while

?>
	</table>