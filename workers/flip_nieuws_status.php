<?php
header("Content-Type: text/html; charset=utf-8\n"); 
setlocale(LC_ALL,"nl_NL");
require('../../libs/connect.php');

$id = $_REQUEST['id'];

 $r = db_row("select status, lead, body, datum from dev_nieuws where id = '" . db_esc($id) . "' and verwijderd != 'j'");
 if ($r[0] == 'draft') {
     $status = 'public';
 } else {
     $status = 'draft';
 }
  db_query("update dev_nieuws set status='$status' where id='" . db_esc($id) . "'");
  // live site bijwerken 
  if ($rl = db_row("select id from nieuws where id='" . db_esc($id) . "'")) {
      // bericht bestaat al
		db_query("update nieuws set lead='$r[1]', body='$r[2]', status='$status', datum='$r[3]' where id='" . db_esc($id) . "'");
	} else {
		db_query("insert into nieuws (id, lead, body, status, datum) values ('" . db_esc($id) . "', '$r[1]', '$r[2]', '$status','$r[3]')");
	}


$r = db_row("select id, lead, body, datum, status from dev_nieuws where id='" . db_esc($id) . "' and verwijderd != 'j'")

?>
        <div class="cms_table_item"><a href="?state=admin&go=content&m=nieuws&act=del&id=<?php echo $r[0]; ?>"><img src="beheer/img/24x24/editcut.png" title="verwijderen" alt="verwijderen"></a></div>
        <div class="cms_table_datum"><a href="javascript:void();" onclick="cms_datum(<?php echo $r[0]; ?>)"><?php echo strftime("%d-%B-%Y", $r[3]); ?></a></div>
        <div class="cms_table_txt"><a href="javascript:void();" onclick="cms_editor('<?php echo $r[0]; ?>','lead');"><?php echo $r[1]; ?></a></div>
		<div class="cms_table_txt"><a href="javascript:void();" onclick="cms_editor('<?php echo $r[0]; ?>','body');"><?php echo $r[2]; ?></a></div>
        <div class="cms_table_item"><a href="javascript:void();" onClick="doFlipNieuwsStatus('<?php echo $r[0]; ?>');"><img src="beheer/img/<?php if ($r[4]=='draft') { ?>draft.png<?php } else { ?>public.png<?php } ?>" title="<?php echo $r[4]; ?>" alt="<?php echo $r[4];?>"></a></div>        
        
		<div style="clear:both"></div>