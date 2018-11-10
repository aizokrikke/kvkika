<span class="kop"><a href="?state=<?php echo $state;?>&go=<?php echo $go; ?>"><img src="beheer/img/eleganticons-png/png/Mail.png"" align="absmiddle" title="Mail log" alt="Mail log" align="absmiddle" height="24" width="24"> Mail log</a></span>
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
	break;
	
	default:
?>
<?php        	
		$res=mysql_query("select id, aan, van, onderwerp, headers, body, tijd, succes from mail_log order by tijd desc") or die(mysql_error());
		while ($r=mysql_fetch_row($res))
		  {
?>
		<div class="table_row">
			<div class="table_cell tijd"><?php echo $r[6]; ?></div>
			<div class="table_cell aan"><?php echo $r[1]; ?></div>
			<div class="table_cell van"><?php echo $r[2]; ?></div>
			<div class="table_cell onderwerp"><?php echo $r[3]; ?></div>
			<div class="table_cell mailbody"><?php echo show_mail_body($r[5]); ?></div>           
			<div class="clearfix"></div>
		</div>
<?php	  
		  }  // while
	break;

} // switch
?>