<?php
include("../libs/connect.php");
include("../logon/check_logon.php");

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Document Upload</title>
<meta name = "viewport" content = "initial-scale = 1.0">
<link type="text/css" rel="stylesheet" href="../css/upload.css">
<link type="text/css" rel="stylesheet" href="../css/beheer.css">

<script type="text/javascript">
function do_form() {
	var form=document.getElementById('uploadform');
	
	form.submit();	
}
function close_upload() {
	window.parent.close_upload();
}
</script>
</head>

<body>
<?php
$do = $_REQUEST['do'];
$id = db_esc($_REQUEST['id']);
$bestand = $_REQUEST['bestand'];

if ($do == 'store') {
	 // formulier verwerken
	 $error =  $_FILES["bestand"]["error"];
	 if ($error > 0) {
	     echo "er is iets misgegaan";
	 } else {
		 $tmp_name = $_FILES["bestand"]["tmp_name"];
		 $name = $_FILES["bestand"]["name"];
		 $filetype = $_FILES["bestand"]['type'];
		 $parts = explode('.',$name);
		 $ext = $parts[1];
		 
		 $tr = db_row("select id from doctypes where mime='$filetype' and extensie='$ext' and verwijderd!='j'");
		 if (empty($tr[0]))	{
		     $type_id=1;
		 } else {
		     $type_id=$tr[0];
		 }
		 $nr = db_row("select waarde from system where parameter='last_upload'");
		 $nummer = $nr[0];
		 
		 $filename = $nummer.".".$ext;
		 
		 if (!move_uploaded_file($tmp_name,$attach_store.'/'.$filename)) {
		     echo "kan het bestand niet oplslaan in de map. controleer de rechten.";
		 } else {
			$u = $user['id'];
			$tijd = time();
		   	db_query("insert into docs (naam, bestand, doctype, eigenaar, tijd) values ('$name','$filename','$type_id','$u','$tijd')");
			$doc = db_insert_id();
			db_query("insert into draaiboek_docs (tmp_draaiboek,doc,tmp_verwijderd,lock_id,lock_tijd) values ('$id','$doc','n','$u','$tijd')");
			echo "het bestand " . $name . "<br> is succesvol opgeslagen";
		 }
		 $nummer++;
		 db_query("update system set waarde='$nummer' where parameter='last_upload'");
	   }
?><br /><br />
<form>
	<input type="button" class="db_button" value="sluiten" onclick="close_upload();" />
</form>

<?php	 
} else {
?>
<h3>Bestand uploaden</h3>
<form method="post" action="?" enctype="multipart/form-data" id="uploadform" name="uploadform">
<input type="hidden" name="do" value="store" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
Kies bestand<br />
<input type="file" name="bestand" class="db_button" /><br /><br />
<input type="button" name="button" value="toevoegen" onclick="do_form();" class="db_button" />
</form>
<?php
}
?>  
</body>
</html>