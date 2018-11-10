<form action="?" method="post" id="dform">
<div id="cms_datum"><?php $div="cms_datum"; $datum_select= mktime(0,0,0,date("n",$r[0]),date("j",$r[0]),date("Y",$r[0])); include('datum_invoer_body.php'); ?></div>

<br />
<input type="button" name="do" value="opslaan" onclick="proc_cms_datum('<?php echo $id; ?>');" class="button_rood_small" /> 
<input type="button" name="annuleren" value="annuleren" onclick="close_cms_datum();" class="button_rood_small" />
</form>

