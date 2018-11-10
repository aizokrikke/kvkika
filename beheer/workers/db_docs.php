     <h3>Bijbehorende document</h3>
     document toevoegen<br />
     <select name="doc" class="db_input" onchange="proc_doc('add');">
     	<option></option>
     	<option value="new" style="font-style:italic">-- nieuw document toevoegen --</option>
<?php
	$ores=mysql_query("select id, naam, tijd from docs where verwijderd!='j' order by naam, tijd desc");
	while ($or=mysql_fetch_row($ores))
	  {
?>
		<option value="<?php echo $or[0];?>"><?php echo $or[1]; ?>&nbsp;&nbsp;&nbsp;(<?php echo strftime("%d %b %y %H:%M",$or[2]);?>)</option>
<?php		  	  
	  } // while
?>        
     </select>
     <br /><br />
     <table>
     <?php
	 	$dres=mysql_query("select docs.id, docs.naam, docs.bestand, docs.doctype, doctypes.icon, docs.eigenaar, docs.tijd from draaiboek_docs, docs, doctypes 
							where (draaiboek_docs.draaiboek='$id' or draaiboek_docs.tmp_draaiboek='$id') and
							docs.id=draaiboek_docs.doc and
							doctypes.id=docs.doctype and
							draaiboek_docs.verwijderd!='j' and
							draaiboek_docs.tmp_verwijderd!='j'
							order by docs.naam") or die(mysql_error());
		while ($dr=mysql_fetch_row($dres))
		  {
	 ?>
     	<tr>
        	<td><a href="javascript:void();" onclick="proc_doc('del','<?php echo $dr[0]; ?>');"><img src="beheer/img/eleganticons-png/png/X.png" width="24" height="24" alt="verwijderen" title="verwijderen" /></a></td>
            <td><?php if (!empty ($dr[4])) { ?><a href="https://<?php echo $base_url; ?>/attachments/index.php?id=<?php echo $dr[0];?>"><img src="beheer/img/mime/<?php echo $dr[4]; ?>" width="24" height="24" /></a><?php } ?></td>
            <td><a href="https://<?php echo $base_url; ?>/attachments/index.php?id=<?php echo $dr[0];?>"><?php echo stripslashes($dr[1]); ?> <span class="db_doc_extra">(<?php echo strftime("%d %b %Y %H:%M", $dr[6]).', '.persoon($dr[5]); ?>)</span></a></td>
        </tr>    
     <?php		  
		  }
     ?>
     </table>