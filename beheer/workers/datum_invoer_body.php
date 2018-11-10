<?php 
setlocale(LC_ALL,"nl_NL");

	if (empty($datum_select)) { $datum_select=time(); }
	
	$datum_select=mktime(0,0,0,date("n",$datum_select),date("j", $datum_select), date("Y", $datum_select));
	
//echo "$datum_select ";	
//echo strftime("%d %m %Y %H:%M",$datum_select);

	$datum_invoer_jaar=strftime("%Y", $datum_select);
	$datum_invoer_maand_text=strftime("%B",$datum_select);
	$datum_invoer_maand=strftime("%m",$datum_select);
	$datum_invoer_dag=strftime("%d",$datum_select);

//echo " parts: $datum_invoer_dag $datum_invoer_maand $datum_invoer_jaar<br>"; 	
	
	
	$dag_stamp=mktime(0,0,0,$datum_invoer_maand,1,$datum_invoer_jaar);
	
	$maand_terug=$datum_invoer_maand-1;
	if ($maand_terug<1) { $maand_terug=12; $jaar_terug=$datum_invoer_jaar-1; } 
		else
		{ $jaar_terug=$datum_invoer_jaar; }
	$maand_eerder=mktime(0,0,0,$maand_terug,1,$jaar_terug);	
	$maand_verder=$datum_invoer_maand+1;
	if ($maand_verder>12) { $maand_verder=1; $jaar_verder=$datum_invoer_jaar+1; } 
		else
		{ $jaar_verder=$datum_invoer_jaar; }
	$maand_later=mktime(0,0,0,$maand_verder,1,$jaar_verder);
	$jaar_eerder=mktime(0,0,0,$datum_invoer_maand,1,$datum_invoer_jaar-1);
	$jaar_later=mktime(0,0,0,$datum_invoer_maand,1,$datum_invoer_jaar+1);
	

?>	
<table>
<tr>
	<td colspan="3" align="center" class="datum_invoer_jaar"><div style="float: left; position: relative; left: 50%;"><div style="float: left; position: relative; left: -50%;"><div style="padding-right:5px; width:16px; float:left;" ><a href="javascript:void();" onClick="datum_select('<?php echo $div; ?>','<?php echo $jaar_eerder;?>')"><img src="beheer/img/previous.png" /></a></div><div style="float:left;"><?php echo $datum_invoer_jaar;?></div><div style="padding-left:5px; width:16px; float:left"><a href="javascript:void();" onClick="datum_select('<?php echo $div; ?>','<?php echo $jaar_later;?>')"><img src="beheer/img/next.png" /></a></div><div style="clear:both"></div></div></div></td>
	<td colspan="4" align="center" class="datum_invoer_maand"><div style="float: left; position: relative; left: 50%;"><div style="float: left; position: relative; left: -50%;"><div style="padding-right:5px; width:16px; float:left;" ><a href="javascript:void();" onClick="datum_select('<?php echo $div; ?>','<?php echo $maand_eerder;?>')"><img src="beheer/img/previous.png" /></a></div><div style="float:left;"><?php echo $datum_invoer_maand_text;?></div><div style="padding-left:5px; width:16px; float:left"><a href="javascript:void();" onClick="datum_select('<?php echo $div; ?>','<?php echo $maand_later;?>')"><img src="beheer/img/next.png" /></a></div><div style="clear:both"></div></div></div></td>
</tr>    
<tr>
	<td class="datum_invoer_weekdag">zo</td>
	<td class="datum_invoer_weekdag">ma</td>
	<td class="datum_invoer_weekdag">di</td>
	<td class="datum_invoer_weekdag">wo</td>
	<td class="datum_invoer_weekdag">do</td>
	<td class="datum_invoer_weekdag">vr</td>
	<td class="datum_invoer_weekdag">za</td>
</tr>
<tr>
<?php
	$eendag=60*60*24;
	$datum_invoer_start=strftime("%w", $dag_stamp);
	$maand=strftime("%m", $dag_stamp);
	for ($i=0; $i<$datum_invoer_start; $i++)
	  { 
	  	$temp_stamp=$dag_stamp-(($datum_invoer_start-$i)*24*60*60);
	  ?><td class="datum_invoer_dag_off"><a href="javascript:void();" onClick="datum_select('<?php echo $div; ?>','<?php echo $maand_eerder;?>')"><?php echo strftime("%d",$temp_stamp);?></a></td>
<?php }  // for...
	$dag=1; 
	while (($maand==$datum_invoer_maand) and ($i<7))
	  {
		  $dag_stamp=mktime(0,0,0,$maand,$dag,$datum_invoer_jaar);
?>
	<td width="30" class="datum_invoer_dag<?php if ($datum_select==$dag_stamp) {?>_select<?php }?>">
    	<a href="javascript:void();" onClick="datum_select('<?php echo $div; ?>','<?php echo $dag_stamp;?>')"><?php echo strftime("%d",$dag_stamp);?></a>        
	</td>	  
<?php
		$dag++;
		$dag_stamp=mktime(0,0,0,$maand,$dag,$datum_invoer_jaar);
		$maand=strftime("%m", $dag_stamp);
		$i++;
		if ($i>6)
		  {
			  $i=0; 
?>			  
	</tr>
    <tr>
<?php    	  	
		  } // if i>6
	  } // while
	  
	if ($i>0)
	  {  
		while ($i<=6)
		  {  
	?>		
		<td width="30" class="datum_invoer_dag_off">
			<a href="javascript:void();" onClick="datum_select('<?php echo $div; ?>','<?php echo $maand_later;?>')"><?php echo strftime("%d",$dag_stamp);?></a>
		</td>	  
	<?php
			$dag_stamp=$dag_stamp+(60*60*24);
			$i++;
			  
		  } // while
	  } //  if $i >0
	?>
	</tr>
  </table>
  
 
  <input type="hidden" name="<?php echo $div;?>" value="<?php echo $datum_select; ?>">         