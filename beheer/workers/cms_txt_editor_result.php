<?php
header("Content-Type: text/html; charset=utf-8\n");
setlocale(LC_ALL,"nl_NL");
require('../../libs/connect.php');
require('../../logon/check_logon.php');

$id = db_esc($_REQUEST['id']);
$veld = db_esc($_REQUEST['veld']);
$tekst = db_esc($_REQUEST['tekst']);


if ($id == 'nieuws_nieuw') {
    db_query("insert into dev_nieuws ($veld) values ('$tekst')");
} else {
  	$r = db_row("select status from nieuws where id='$id'");
	$status = $r[0];
  	db_query("update dev_nieuws set $veld='$tekst' where id='$id'");
  	if ($status == 'public') {
  	    db_query("update nieuws set $veld='$tekst' where id='$id'");
  	}
}

$r = db_row("select id, lead, body, datum, status from dev_nieuws where id='$id' and verwijderd!='j'");

?>
        <div class="cms_table_item"><a href="?state=admin&go=content&m=nieuws&act=del&id=<?php echo $r[0]; ?>"><img src="beheer/img/24x24/editcut.png" title="verwijderen" alt="verwijderen"></a></div>
        <div class="cms_table_datum"><a href="javascript:void();" onclick="cms_datum(<?php echo $r[0]; ?>)"><?php echo strftime("%d-%B-%Y", $r[3]); ?></a></div>
        <div class="cms_table_txt"><a href="javascript:void();" onclick="cms_editor('<?php echo $r[0]; ?>','lead');"><?php if (!empty($r[1])) {echo $r[1]; } else { ?><em>-- leeg --</em><?php } ?></a></div>
		<div class="cms_table_txt"><a href="javascript:void();" onclick="cms_editor('<?php echo $r[0]; ?>','body');"><?php if (!empty($r[2])) { echo $r[2]; } else { ?><em>-- leeg --<em><?php } ?></a></div>
        <div class="cms_table_item"><a href="javascript:void();" onClick="doFlipNieuwsStatus('<?php echo $r[0]; ?>');"><img src="beheer/img/<?php if ($r[4]=='draft') { ?>draft.png<?php } else { ?>public.png<?php } ?>" title="<?php echo $r[4]; ?>" alt="<?php echo $r[4];?>"></a></div>        
        
		<div style="clear:both"></div>
