<form action="?" method="post" id="eform">
<textarea name="txt_editor"><?php echo $r[0]; ?></textarea>

<br />
<input type="button" name="do" value="opslaan" onclick="proc_cms_txt_editor('<?php echo $id; ?>','<?php echo $veld; ?>');" class="button_rood" /> 
<input type="button" name="annuleren" value="annuleren" onclick="close_cms_txt_editor();" class="button_rood" />
</form>

