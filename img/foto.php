<?php require('../libs/connect.php');

$id = $_REQUEST['id'];

$r = db_row("select foto, ext from fotos where id='".db_esc($id)."' and verwijderd != 'j'");

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
}
echo $r[0];
?>