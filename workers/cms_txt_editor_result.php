<?php
header("Content-Type: text/html; charset=utf-8\n");
setlocale(LC_ALL,"nl_NL");
require('../../libs/connect.php');
require('../../logon/check_logon.php');

$id = $_REQUEST['id'];
$veld = $_REQUEST['veld'];
$tekst = $_REQUEST['tekst'];

if ($id == 'nieuws_nieuw') {
    db_query("insert into dev_nieuws (" . db_esc($veld) . ") values ('" . db_esc($tekst) . "')");
} else {
    db_query("update dev_nieuws set $veld = '" . db_esc($tekst) . "' where id='" . db_esc($id) . "'");
}

$r=db_row("select id, lead, body, datum, status from dev_nieuws where id='" . db_esc($id) . "' and verwijderd != 'j'")

?>
        <div class="cms_table_item"><a href="?state=admin&go=content&m=nieuws&act=del&id=<?php echo $r[0]; ?>"><img src="beheer/img/24x24/editcut.png" title="verwijderen" alt="verwijderen"></a></div>
        <div class="cms_table_datum"><a href="javascript:void();" onclick="cms_datum(<?php echo $r[0]; ?>)"><?php echo strftime("%d-%B-%Y", $r[3]); ?></a></div>
        <div class="cms_table_txt"><a href="javascript:void();" onclick="cms_editor('<?php echo $r[0]; ?>','lead');"><?php if (!empty($r[1])) {echo $r[1]; } else { ?><em>-- leeg --</em><?php } ?></a></div>
		<div class="cms_table_txt"><a href="javascript:void();" onclick="cms_editor('<?php echo $r[0]; ?>','body');"><?php if (!empty($r[2])) { echo $r[2]; } else { ?><em>-- leeg --<em><?php } ?></a></div>
        <div class="cms_table_item"><a href="javascript:void();" onClick="doFlipNieuwsStatus('<?php echo $r[0]; ?>');"><img src="beheer/img/<?php if ($r[4]=='draft') { ?>draft.png<?php } else { ?>public.png<?php } ?>" title="<?php echo $r[4]; ?>" alt="<?php echo $r[4];?>"></a></div>        
        
		<div style="clear:both"></div>
