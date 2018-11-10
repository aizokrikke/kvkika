<?php
include_once('../libs/tools.php');
include_once('../libs/is_email.php');
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Test rotate_image</title>
</head>

<body>
<img src="../fotos/1492779659_org.jpg" width="400"><br><br>
<?php 
	rotate_image('/home/kvkika/domains/kinderenvoorkika.nl/public_html/fotos/1492779659_org.jpg',90);
?>	
<br><br>
<img src="../fotos/1492779659_org.jpg" width="400"><br><br>

</body>
</html>