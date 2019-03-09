<?php
include('../libs/connect.php');
$id = db_esc($_REQUEST['id']);

if ($r = db_row("select docs.naam, docs.bestand, doctypes.mime from docs, doctypes where doctypes.id=docs.doctype and docs.id='$id'")) {
    header("content-type: ".$r[2]);
    header('Content-Disposition: attachment; filename="'.$r[0].'"');
    readfile($siteroot.'/attachments/'.$r[1]);
} else {
    echo "document niet gevonden";
}
?>