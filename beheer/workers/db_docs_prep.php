<?php
include('../../libs/connect.php');
include('../../logon/check_logon.php');
include('../../libs/tools.php');

$doc = db_esc($_REQUEST['doc']);
$act = db_esc($_REQUEST['act']);
$id = db_esc($_REQUEST['id']);
$u = $user['id'];
$tijd = time();

if ($act == 'del') {
    db_query("update draaiboek_docs set tmp_verwijderd='j', lock_id='$u', lock_tijd='$tijd' where (draaiboek='$id' or tmp_draaiboek='$id') and doc='$doc'");
}
  
if (($act == 'add') and ($doc != 'new')) {
    db_query("insert into draaiboek_docs  (draaiboek, doc,tmp_verwijderd, tmp_draaiboek, lock_id, lock_tijd) values ('$id','$doc','n','$id','$u','$tijd')");
}

include('db_docs.php');
?>