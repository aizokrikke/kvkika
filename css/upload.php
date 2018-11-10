<?php

$do=$_REQUEST['do'];
$bestand=$_REQUEST['bestand'];

if ($do=='store')
  {
	 // formulier verwerken
  }
  else
  {
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
	var form=getElementById('uploadform');
	
	
		
	form.submit();
}
</script>
</head>

<body>
<form method="post" action="?" enctype="multipart/form-data" id="uploadform" name="uploadform">
<input type="hidden" name="do" value="store" />
Kies bestand<br />
<input type="file" name="bestand" class="db_button" /><br /><br />
<input type="button" name="button" value="toevoegen" onclick="do_form();" class="db_button" />
</form>

</body>
</html>
<?php
  }
?>  