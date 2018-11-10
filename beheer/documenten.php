<span class="kop"><a href="?state=admin&go=documenten"><img src="beheer/img/eleganticons-png/png/Folder.png" align="absmiddle" title="documenten" alt="documenten" align="absmiddle" height="24" width="24"> Documentenbeheer</a></span>
<hr />

<table>
<?php
$id=mysql_real_escape_string($_REQUEST['id']);
$act=mysql_real_escape_string($_REQUEST['act']);

if ($act=='del')
  { mysql_query("update docs set verwijderd='j' where id='$id'"); }
  


$dres=mysql_query("select docs.id, docs.naam, docs.tijd, doctypes.icon, doctypes.naam from docs, doctypes where docs.doctype=doctypes.id and docs.verwijderd!='j' order by docs.naam, docs.tijd desc") or die(mysql_error);
 
while ($dr=mysql_fetch_row($dres))
  {
?>
	<tr>
    	<td><a href="?state=admin&go=documenten&act=del&id=<?php echo $dr[0]; ?>"><img src="beheer/img/eleganticons-png/png/X.png" width="24" height="24" /></a></td>
        <td><a href="https://<?php echo $base_url; ?>/attachments/index.php?id=<?php echo $dr[0];?>"><img src="beheer/img/mime/<?php echo $dr[3];?>" width="24" height="24" title="<?php echo $dr[4];?>" alt="<?php echo $dr[4];?>" /></a></td>
        <td style="padding-left:10px;"><a href="https://<?php echo $base_url; ?>/attachments/index.php?id=<?php echo $dr[0];?>"><?php echo stripslashes($dr[1]); ?></a></td>
        <td style="padding-left:10px;"><?php echo strftime("%d %B %y %H:%M",$dr[2]); ?></td>
    </tr>
<?php
  }
?>  
</table>



  