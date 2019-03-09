<?php header("Content-Type: text/html; charset=utf-8\n");
setlocale(LC_ALL,"nl_NL");
require('../../libs/connect.php');
require('../../logon/check_logon.php');

$id = $_REQUEST['id'];

$r = db_row("select datum, body from boxen where id = '" . db_esc($id) . "'");
?>

<input type="text" name="box_dag[<?php echo $id; ?>]" value="<?php echo strftime("%d",$r[0]);?>" size="2" maxlength="2"> <select name="box_maand[<?php echo $id; ?>]">
<option value="1" <?php if (strftime("%m",$r[0]) == '01') { ?>selected<?php }?> >januari</option>
<option value="2" <?php if (strftime("%m",$r[0]) == '02') { ?>selected<?php }?> >februari</option>
<option value="3" <?php if (strftime("%m",$r[0]) == '03') { ?>selected<?php }?> >maart</option>
<option value="4" <?php if (strftime("%m",$r[0]) == '04') { ?>selected<?php }?> >april</option>
<option value="5" <?php if (strftime("%m",$r[0]) == '05') { ?>selected<?php }?> >mei</option>
<option value="6" <?php if (strftime("%m",$r[0]) == '06') { ?>selected<?php }?> >juni</option>
<option value="7" <?php if (strftime("%m",$r[0]) == '07') { ?>selected<?php }?> >juli</option>
<option value="8" <?php if (strftime("%m",$r[0]) == '08') { ?>selected<?php }?> >augustus</option>
<option value="9" <?php if (strftime("%m",$r[0]) == '09') { ?>selected<?php }?> >september</option>
<option value="10" <?php if (strftime("%m",$r[0]) == '10') { ?>selected<?php }?> >oktober</option>
<option value="11" <?php if (strftime("%m",$r[0]) == '11') { ?>selected<?php }?> >november</option>
<option value="12" <?php if (strftime("%m",$r[0]) == '12') { ?>selected<?php }?> >december</option>
</select> <input type="text" name="box_jaar[<?php echo $id;?>]" value="<?php echo strftime("%Y", $r[0]);?>" size="4" maxlength="4"><br><br>
 
<div id="box_editor<?php echo $id; ?>" style="height:120px; width:265px; position:relative;"><?php echo $r[1]; ?></div>
