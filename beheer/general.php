<span class="kop"><a href="?state=<?php echo $state;?>&go=<?php echo $go; ?>"><img src="beheer/img/Basic_set2_Png/Basic_set2_Png/settings_48.png" align="absmiddle" title="beheerders" alt="beheerders" align="absmiddle" height="24" width="24"> Algemene instelling</a></span>
<hr />


<?php 
setlocale(LC_ALL,'nl_NL');

$do=$_REQUEST['do'];
$act=$_REQUEST['act'];

if ($do=='bevestigen')
  {
	$waarde=$_REQUEST['waarde'];
	$soort=$_REQUEST['soort'];
	$id=$_REQUEST['id'];
	  
	switch ($soort) {
	  case 'jn';
	  case 'text';
	  default:
	  	$err=array();
	  break;
	  
	  case 'date':
	  	$dag=$_REQUEST['dag'];
	  	$maand=$_REQUEST['maand'];
	  	$jaar=$_REQUEST['jaar'];
		if ($dag<0) { $err[]='dag is ongeldig, te laag'; }
		if ($dag>31) { $err[]='dag is ongeldig, te hoog'; }
		if ($maand<0) { $err[]='maand is ongeldig, te laag'; }
		if ($maand>12) { $err[]='maand is ongeldig, te hoog'; }
		if (!checkdate($maand,$dag,$jaar)) { $err[]='ongeldige datum'; }
		if (empty($err)) { $waarde=$jaar.'-'.$maand.'-'.$dag; }
	  break;
		
	} // switch

	if (empty($err))
	  {
	  	mysql_query("update system set waarde='".mysql_real_escape_string($waarde)."' where id='".mysql_real_escape_string($id)."'");
		$act='';
	  }
  }

if (!empty($err))
  {
	foreach($err as $val) { echo "FOUT: $val<br>"; }
  } // if..
  
switch ($act) {
	
	case 'edit':
		if ($opnieuw!='j')
		  {
		$id=$_REQUEST['id'];
			$r=mysql_fetch_row(mysql_query("select id, waarde, soort, beschrijving from system where id='".mysql_real_escape_string($id)."' and verwijderd!='j'"));
			$waarde=$r[1];
			$soort=$r[2];
			$beschrijving=$r[3];
		  }// if 
?>
		<form action="?">
        <input type="hidden" name="id" value="<?php echo $id;?>">	
        <input type="hidden" name="act" value="<?php echo $act;?>">	
        <input type="hidden" name="go" value="<?php echo $go;?>">
        <input type="hidden" name="state" value="<?php echo $state;?>">
        <input type="hidden" name="soort" value="<?php echo $soort;?>">
        <input type="hidden" name="opnieuw" value="j">
        
        <table>
        	<tr><td><?php echo $beschrijving; ?></td><td width="10">&nbsp;</td>
<?php		
		switch ($soort) {
		  case 'jn':
?>
			<td><input type="radio" name="waarde" value="j" <?php if ($waarde=='j') { ?>checked<?php } ?> >ja <input type="radio" name="waarde" value="n" <?php if ($waarde=='n') { ?>checked<?php } ?> >nee </td>
<?php
		  break;
		  
		  case 'date':
		  	$d=strtotime($waarde);
			$dag=strftime("%d",$d);
			$maand=strftime("%m",$d);
			$jaar=strftime("%Y",$d);

?>
			<td><input type="text" name="dag" value="<?php echo $dag;?>" size="2" maxlength="2"> <input type="text" name="maand" value="<?php echo $maand;?>" size="2" maxlength="2"> <input type="text" name="jaar" value="<?php echo $jaar;?>" size="4" maxlength="4"> </td>
<?php
		  break;
		  
		  default:
?>
			<td><input type="text" size="40" name="waarde" value="<?php echo $waarde; ?>"></td>
<?php
		  break;
		} // switch
?>
			</tr>
            <tr><td colspan="3" align="right"><input type="submit" name="do" value="bevestigen"></td></tr>
            </table>	
		</form>
	
<?php
	
	break;
	
	default:
?>
		<table>
<?php        	
		$res=mysql_query("select id, beschrijving, waarde, soort from system where verwijderd!='j' and editable='j'");
		while ($r=mysql_fetch_row($res))
		  {
?>
			<tr><td><?php echo $r[1]; ?></td><td width="10">&nbsp;</td><td><a href="?state=<?php echo $state; ?>&go=<?php echo $go; ?>&id=<?php echo $r[0];?>&act=edit"><?php
            if ($r[3]!='date') { echo $r[2]; } else { echo strftime("%d %B %Y",strtotime($r[2])); } ?></a>
<?php	
			
		  }  // while
?>
		</table>
<?php        
	break;

} // switch
?>