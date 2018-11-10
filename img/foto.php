<?php require('../libs/connect.php');

$id=$_REQUEST['id'];

$r=mysql_fetch_row(mysql_query("select foto, ext from fotos where id='".mysql_real_escape_string($id)."' and verwijderd!='j'"));

switch ($r[1]) {
	case 'jpg';
	default:
		header('Content-type: image/jpg');
	break;

	case 'png':
		header('Content-type: image/png');
	break;

	case 'gif':
		header('Content-type: image/gif');
	break;
} // switch
echo $r[0];
?>