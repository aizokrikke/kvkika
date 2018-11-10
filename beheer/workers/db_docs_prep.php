<?php
include('../../libs/connect.php');
include('../../logon/check_logon.php');
include('../../libs/tools.php');

$doc=mysql_real_escape_string($_REQUEST['doc']);
$act=mysql_real_escape_string($_REQUEST['act']);
$id=mysql_real_escape_string($_REQUEST['id']);
$u=$user['id'];
$tijd=time();

if ($act=='del')
  { $q="update draaiboek_docs set tmp_verwijderd='j', lock_id='$u', lock_tijd='$tijd' where (draaiboek='$id' or tmp_draaiboek='$id') and doc='$doc'";
  	mysql_query($q) or die(mysql_error()); 
  }
  
if (($act=='add') and ($doc!='new'))  
  { mysql_query("insert into draaiboek_docs  (draaiboek, doc,tmp_verwijderd, tmp_draaiboek, lock_id, lock_tijd) values ('$id','$doc','n','$id','$u','$tijd')"); }

include('db_docs.php');
?>