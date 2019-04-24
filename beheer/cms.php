<span class="kop"><a href="?state=<?php echo $state;?>&go=<?php echo $go;?>"><i class="fas fa-file-alt fa-fw"></i> Content</a></span>

<?php
setlocale(LC_ALL,"nl_NL,", 'nld_NLD');

$m = $_REQUEST['m'];
$act = $_REQUEST['act'];
$do=$_REQUEST['do'];
$opnieuw = $_REQUEST['opnieuw'];
$id = db_esc($_REQUEST['id']);
$naam = db_esc($_REQUEST['naam']);
$body = db_esc($_REQUEST['body']);
$lead = db_esc($_REQUEST['lead']);
$foto = db_esc($_REQUEST['foto']);
$boxact = $_REQUEST['boxact'];
$boxid = db_esc($_REQUEST['boxid']);
$annuleren = $_REQUEST['annuleren'];
$volgorde = db_esc($_REQUEST['volgorde']);
$oldfile = db_esc($_REQUEST['oldfile']);
$live = db_esc($_REQUEST['live']);
$actie = db_esc($_REQUEST['actie']);
$menu_state = db_esc($_REQUEST['menu_state']);
$tekst = db_esc($_REQUEST['tekst']);
$extern = db_esc($_REQUEST['extern']);
$aantal_boxen = $_REQUEST['aantal_boxen'];

for ($i = 1; $i < $aantal_boxen; $i++) {
    $box_id[$i] = db_esc($_REQUEST['box_id'.$i]);
  	$box[$i] = db_esc($_REQUEST['box_editor'.$i]);
	$box_datum[$i] = db_esc($_REQUEST['box_datum'.$i]);
}

if ($annuleren == 'annuleren') {
    $do = '';
	$act = '';
}

$nu = time();
$u = $user['id'];

switch ($m) {

	case 'pagina':
?>
		<span class="kop"><a href="?state=admin&go=content&m=pagina"><i class="far fa-file-alt fa-fw"></i> Pagina's</a></span>
        <hr>
        <div id="cms_box_editor"></div>
<?php	
	if (($do == 'opslaan') or ($do == 'zet live') or ($boxact == 'newbox') or ($boxact == 'delbox')) {
			db_query("update dev_pagina set naam='$naam', lead='$lead', body='$body', foto='$foto', gewijzigd='$nu', door='$u' where id='$id'");
			if (!empty($box)) {
				  // boxen verwerken
				  foreach ($box as $key => $val) {
						$bdatum = $box_datum[$key];
						$bid = $box_id[$key];
						if (($bid <> 0) and ($br = db_row("select id from dev_boxen where id='$bid' and verwijderd!='j'"))) {
						    // box bestaat
						  	db_query("update dev_boxen set body='$val', datum='$bdatum' where id='$bid'");
                        } else {
							db_query("insert into dev_boxen (body, datum, pagina) values ('$val','$bdatum','$id')");
                        }
				  }
			}
			if ($boxact == 'delbox') {
			    db_query("update dev_boxen set verwijderd='j' where id='$boxid'");
			}
			if (empty($boxact)) {
			    $act = '';
			}
	}
	if ($do == 'zet live') {
			if (db_row("select id from pagina where id='$id'")) {
				db_query("update pagina set naam='$naam', lead='$lead', body='$body', foto='$foto', gewijzigd='$nu', door='$u' where id='$id'");
            } else {
				db_query("insert into pagina (id, naam, lead, body, foto, aangemaakt, gewijzigd, auteur, door) values ('$id', '$naam', '$lead', '$body', '$foto', '$nu','$nu','$u','$u')");
            }
			// boxen staus vanuit dev_boxen updaten
			
			// eerst alle boxen op verwijderd zetten
			db_query("update boxen set verwijderd='j' where pagina='".db_esc($id)."'");
			
			// daarna boxen vanuit dev_boxen updaten
			$res = db_query("select id,body,datum, verwijderd from dev_boxen where pagina='".db_esc($id)."'");
			while ($dbr = db_row($res)) {
				  if ($br = db_row("select id from boxen where id='$dbr[0]'")) { // box bestaat
					  db_query("update boxen set body='$dbr[1]', datum='$dbr[2]', verwijderd='$dbr[3]' where id='$dbr[0]'");
				  } else {
					  db_query("insert into boxen (id, body, datum, verwijderd, pagina) values ('$dbr[0]','$dbr[1]','$dbr[2]','$dbr[3]','".db_esc($id)."')");
				  }
			}
		  
	}
	if ($do == 'verwijderen') {
          $r = db_row("select fixed from dev_pagina where id='$id'");
          if ($r[0] != 'j')
          {
                db_query("update dev_pagina set verwijderd='j', gewijzigd='$nu', door='$u' where id='$id'");
                db_query("update pagina set verwijderd='j', gewijzigd='$nu', door='$u' where id='$id'");
          }
          $act = '';
	}
	if ($do == 'toevoegen') {
          db_query("insert into dev_pagina (naam, lead, body, foto, aangemaakt, gewijzigd, auteur, door) values ('$naam','$lead','$body', '$foto','$nu','$nu','$u','$u')");
          $act = '';
	}

    $recht_fixed = 'n';
    if ((!empty($user['rechten']['fixedpagina'])) and ($user['verbannen'] != 'j')) {
        $recht_fixed = 'j';
    }

    if (!empty($act)) {
        if (($act == 'edit') OR ($act == 'del')) {
            $r = db_row("select naam, lead, body, foto, menu, fixed from dev_pagina where id='$id' and verwijderd!='j'");
        } else {
          $r[0] = '';
          $r[1] = '';
          $r[2] = '';
          $r[3] = 0;
          $r[4] = 0;
          $r[5] = 'n';
        }
?>
		<form action="?state=<?php echo $state;?>&go=<?php echo $go;?>&m=<?php echo $m;?>&act=edit" method="post" id="bform">
        <input type="hidden" name="id" value="<?php echo $id; ?>" />
        <input type="hidden" name="boxact" value="" />
        <input type="hidden" name="boxid" value="" />
        
        naam<br />
        <input type="text" name="naam" value="<?php echo stripslashes($r[0]); ?>" <?php if (($r[5]=='j') and ($recht_fixed!='j')) {
            ?> readonly<?php
        } ?> /><br />
        <br />
        lead<br />
        <textarea name="lead"><?php echo stripslashes($r[1]); ?></textarea><br />
         body<br />
        <textarea name="body"><?php echo stripslashes($r[2]); ?></textarea><br />
        <script type="text/javascript">
		CKEDITOR.stylesSet.add( 'boxStyles', [{ name : 'Kop', element : 'h2', attributes : {  } },
    												 { name : 'Kop alt', element : 'h3', attributes : {  } },
													 { name : 'lead', element : 'span', attributes : { 'class' : 'box_lead' } } ]);

		editor = CKEDITOR.replace( 'lead', { toolbar : [
						{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
						{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
						{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker' ] },
						{ name: 'styles', items : [ 'Format' ] }	
					], uiColor : '#560666',  width : 580, height : 150
				});
		editor.config.contentsCss='css/news_lead.css';				
		editor2 = CKEDITOR.replace( 'body', { toolbar : [
						{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
						{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
						{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
						{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote',
	'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
						{ name: 'links', items : [ 'Link','Unlink' ] }
					], uiColor : '#560666',  width : 580, height : 150
				});	
		editor2.config.contentsCss='css/news_body.css';	
		</script>
        foto<br />
        <select name="foto" onchange="cms_foto_change();">
        <?php
			$fres=db_query("select id, naam from fotos order by naam");
			while ($fr = db_row($fres)) {
		?>
        	<option value="<?php echo $fr[0];?>" <?php if ($fr[0]==$r[3]) { ?> selected="selected"<?php } ?>><?php echo $fr[1]; ?></option> 
        <?php				  
            }
		?>	  
        </select><br />
        <div id="cms_foto"><img src="img/foto.php?id=<?php echo $r[3];?>" /></div>
        <br />
		<br />
        <?php
		
		if ($act != 'new') {
			$bres = db_query("select id, datum, body from dev_boxen where pagina='$id' and verwijderd!='j'");
			$a = 1;
			$t = 1;
			while ($br = db_row($bres)) {
		?>
        	<div <?php		  
                 if ($a == 1) {
                     echo 'class="box_oranje"';
                 } else {
                     echo 'class="box_paars"';
                 }?>>
                   
                <div class="box_delete"><a href="javascript: void();" onclick="delete_box('<?php echo $br[0];?>');"><img src="beheer/img/close_red.png" name="box<?php echo $br[0];?>"  onmouseover="swap_img('box<?php echo $br[0]; ?>','beheer/img/close_grey.png');" onmouseout="swap_img('box<?php echo $br[0]; ?>','beheer/img/close_red.png');" alt="verwijderen" title="verwijderen"/></a></div>   
            	<div class="box_datum_edit">
            		<div id="box_datum<?php echo $t; ?>"><?php $div="box_datum".$t; $datum_select=$br[1]; include('beheer/workers/datum_invoer_body.php'); ?></div>
            
            	</div>
                <div class="box_edit">
            		<textarea name="box_editor<?php echo $t; ?>"><?php echo $br[2]; ?></textarea>
            	</div>
            <input type="hidden" name="box_id<?php echo $t;?>" value="<?php echo $br[0];?>">
            
   				
            </div>
            <div style="clear:both"></div>
         <?php
                $a++;
				$t++;
                if ($a > 2) {
                    $a=1;
                }
            }
		    if ($boxact == 'newbox') {
		?>	   
        	<div <?php		  
                 if ($a==1) {
                     echo 'class="box_oranje"';
                 } else {
                     echo 'class="box_paars"';
                 }?>>
            	<div class="box_datum_edit">
            		<div id="box_datum<?php echo $t; ?>"><?php
                        $div = "box_datum".$t;
                        $datum_select = time();
                        include('beheer/workers/datum_invoer_body.php');
                    ?></div>
            
            	</div>
                <div class="box_edit">
            		<textarea name="box_editor<?php echo $t; ?>"></textarea>
            	</div>
            <input type="hidden" name="box_id<?php echo $t;?>" value="0">
            
   				
            </div>
            <div style="clear:both"></div>
		<?php
                $t++;
               }
		 ?>  
         <input type="hidden" name="aantal_boxen" value="<?php echo $t; ?>" />				 
		<?php	 
		  // editors activeren
		  	$i = 1;
			$b = 1;
			while ($i <= $t) {
			?>	  
		  	<script type="text/javascript">
			box_editor<?php echo $i;?> = CKEDITOR.replace( 'box_editor<?php echo $i;?>', { toolbar : [
						{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
						{ name: 'styles', items : [ 'Styles' ] },
						{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
						{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote',
	'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] }, { name: 'links', items : [ 'Link','Unlink' ] }

					], uiColor : '<?php if ($b == 1) {
					    ?>#560666<?php
					} else {
					    ?>#e95005<?php
					} ?>',  width : 300, height : 200
				});
				

		<?php
            if ($b == '1') {
		    ?>box_editor<?php echo $i; ?>.config.contentsCss='css/box_oranje.css';  <?php
		    } else {
                ?>box_editor<?php echo $i; ?>.config.contentsCss='css/box_paars.css'; <?php
            } ?>
			box_editor<?php echo $i;?>.config.stylesSet ='boxStyles';		
			
			
			</script>        
	 	<?php
				$i++;
				$b++;
				if ($b > 2) {
				    $b = 1;
				}
			  } // while
        		
		?> 
        <a href="javascript:void();" onclick="do_newbox()"><img src="beheer/img/24x24/edit_add.png" align="absmiddle" height="16" width="16" /> nieuwe box</a><br />
<br />
       <?php
		  }
		if ($act == 'del')  {
		    if (($r[5] != 'j') or ($recht_fixed == 'j')) {
			    if ($r[5] == 'j') {
?>
	<img src="img/24x24/alert.png" alt="WAARSCHUWING" title="WAARSCHUWING" align="absmiddle"> LET OP: Dit is een systeempagina, verwijderen kan tot problemen leiden <br><br>
<?php			
				}
			?><input type="submit" name="do" value="verwijderen" class="button_rood" /><?php }
		    } elseif ($act == 'new') {
		    ?><input type="submit" name="do" value="toevoegen" class="button_rood" /><?php
		    } else {
		    ?><input type="submit" name="do" value="opslaan" class="button_rood" /> <input type="submit" name="do" value="zet live" class="button_rood" /><?php
		    } ?> <input type="submit" name="annuleren" value="annuleren" class="button_rood" />
        </form>
<?php		
			  
    }
    if (empty($act)) {
        $res = db_query("select id, naam, lead, body, foto, fixed from dev_pagina where verwijderd!='j' order by naam");
        while ($r = db_row($res)) {
?>
			<div class="cms_regel">
			<div class="cms_table_item"><?php if (($r[5]!='j') or ($recht_fixed=='j')) {
			    ?><a href="?state=<?php echo $state;?>&go=<?php echo $go;?>&m=<?php echo $m;?>&act=del&id=<?php echo $r[0]; ?>"><i class="far fa-trash-alt"></i></a><?php
			    } else { ?><div style="width:24px;">&nbsp;</div><?php
			    } ?></div>
            <div class="cms_table_item"><a href="?state=<?php echo $state;?>&go=<?php echo $go;?>&m=<?php echo $m;?>&act=edit&id=<?php echo $r[0]; ?>"><i class="fas fa-pencil-alt"></i></a></div>
            <div class="cms_table_naam"><a href="?state=<?php echo $state;?>&go=<?php echo $go;?>&m=<?php echo $m;?>&act=edit&id=<?php echo $r[0]; ?>"><?php echo $r[1]; ?></a>&nbsp;</div>
            <div class="cms_table_txt"><a href="?state=<?php echo $state;?>&go=<?php echo $go;?>&m=<?php echo $m;?>&act=edit&id=<?php echo $r[0]; ?>"><?php echo $r[2]; ?></a></div>
            <div class="cms_table_txt"><a href="?state=<?php echo $state;?>&go=<?php echo $go;?>&m=<?php echo $m;?>&act=edit&id=<?php echo $r[0]; ?>"><?php echo $r[3]; ?></a></div>
			<div style="clear:both"></div>
            </div>
<?php					
        }
?>
		<br />
		<a href="?state=<?php echo $state;?>&go=<?php echo $go;?>&m=<?php echo $m;?>&act=new"><i class="fas fa-plus-circle"></i> pagina toevoegen</a>
<?php				
				
    }
		  
	break;
	
	case 'nieuws':
?>
		<span class="kop"><a href="?state=<?php echo $state;?>&go=<?php echo $go;?>&m=<?php echo $m;?>"><i class="fas fa-bullhorn fa-fw"></i> Nieuws</a></span>
        <hr>
 
 <?php
 		switch ($act) {
			case 'new':
				$nu = time();
				db_query("insert into dev_nieuws (datum) values ('$nu')");
				$act = '';
			break;
			case 'del':
				if (empty($do)) {
                    $r = db_row("select datum, lead, body from dev_nieuws where verwijderd!='j' and id='$id'");
?>	
				<form action="?state=<?php echo $state;?>&go=<?php echo $go;?>&m=<?php echo $m;?>&act=del&id=<?php echo $id;?>" method="post">
                Het volgende bericht echt verwijderen?<br><br>
                <div class="news_lead"><?php echo stripslashes($r[1]); ?></div>
                <div class="news_body"><?php echo stripslashes($r[2]); ?></div>
                <input type="submit" name="do" value="verwijderen" class="button_rood"> <input type="submit" name="annuleren" value="annuleren" class="button_rood"> 
                
                </form>
<?php
                } else {
                    if ($do == 'verwijderen') {
                        db_query("update dev_nieuws set verwijderd='j' where id='$id'");
                        db_query("update nieuws set verwijderd='j' where id='$id'");
                        $act = '';
                    }
						
                }
			break;
		}
?>        
        <div id="cms_txt_editor"></div>
        <div id="cms_datum_editor"></div>
<?php		

		if (empty($act)) {
			 $res = db_query("select id, lead, body, datum, status from dev_nieuws where verwijderd!='j'");
			 while ($r = db_row($res)) {
?>
        
        <div id="<?php echo "nieuws$r[0]";?>" class="cms_regel">
        <div class="cms_table_item"><a href="?state=<?php echo $state;?>&go=<?php echo $go;?>&m=<?php echo $m;?>&act=del&id=<?php echo $r[0]; ?>"><i class="far fa-trash-alt"></i></div>
        <div class="cms_table_datum"><a href="javascript:void();" onclick="cms_datum(<?php echo $r[0]; ?>)"><?php echo strftime("%d %B %Y", $r[3]); ?></a></div>
        <div class="cms_table_txt"><a href="javascript:void();" onclick="cms_editor('<?php echo $r[0]; ?>','lead');"><?php if (!empty($r[1])) { echo $r[1]; } else {?><em>-- leeg --</em><?php } ?></a></div>
		<div class="cms_table_txt"><a href="javascript:void();" onclick="cms_editor('<?php echo $r[0]; ?>','body');"><?php if (!empty($r[2])) { echo $r[2]; } else { ?><em>-- leeg --</em><?php } ?></a></div>
        <div class="cms_table_item"><a href="javascript:void();" onClick="doFlipNieuwsStatus('<?php echo $r[0]; ?>');"><img src="beheer/img/<?php if ($r[4]=='draft') { ?>draft.png<?php } else { ?>public.png<?php } ?>" title="<?php echo $r[4]; ?>" alt="<?php echo $r[4];?>"></a></div>        
		<div style="clear:both"></div>
        </div>
<?php				   
            }
?>

		<br>
		<a href="?state=<?php echo $state;?>&go=<?php echo $go;?>&m=<?php echo $m;?>&act=new"><i class="fas fa-plus-circle"></i> bericht toevoegen</a>
<?php        		   
			  
        }

	break;
	
	case 'logo':
?>
	    		<span class="kop"><a href="?state=admin&go=content&m=logo"><i class="fas fa-money-bill-wave fa-fw"></i> Sponsorlogo's</a></span>
        <hr>
<?php
		if (!empty($do)) {
		    // formulier afhandelen
		  	switch ($act) {
			case 'del':
				if ($do == 'verwijderen') {
				    db_query("update sponsorlogo set verwijderd='j' where id='$id'");
				}
				$act = '';
				$do = '';
			break;
			
			case 'edit':
				// file verplaatsen
				$filenaam = '';
				$dest = $sponsorlogo_dir . '/' . $_FILES['bestand']['name'];
				$naamparts = explode('.',$_FILES['bestand']['name']);
				$ext = strtolower(array_pop($naamparts));
				if (($ext == 'jpg') or ($ext == 'jpeg') or ($ext == 'png') or ($ext == 'gif')) {
					if (move_uploaded_file($_FILES['bestand']['tmp_name'],$dest)) {
					    $filenaam=$_FILES['bestand']['name']; echo $filenaam;
					}
                }
				if (empty($filenaam)) {
				    $filenaam = $oldfile;
				} // als er geen bestand is geupload...
				db_query("update sponsorlogo set naam='$naam', volgorde='$volgorde', file='$filenaam', live='$live' where id='$id'");
		  		$act = '';
				$do = '';
			break;

			case 'new':
				// file verplaatsen
				$filenaam = '';
				$dest = $sponsorlogo_dir.'/'.$_FILES['bestand']['name'];
				if (($ext == 'jpg') or ($ext == 'jpeg') or ($ext == 'png') or ($ext == 'gif')) {
					if (move_uploaded_file($_FILES['bestand']['tmp_name'],$dest)) {
					    $filenaam = $_FILES['bestand']['name']; echo $filenaam;
					}
                }
				if (empty($filenaam)) {
				    $filenaam = $oldfile;
				} // als er geen bestand is geupload...
				db_query("insert into sponsorlogo (naam, volgorde, file, live) values ('$naam', '$volgorde', '$filenaam', '$live')");
		  		$act = '';
				$do = '';
			break;
			
			case 'fliplive':
				$r = db_row("select live from sponsorlogo where id='$id'");
				if ($r[0] == 'n') {
				    $live = 'j';
				} else {
				    $live = 'n';
				}
				db_query("update sponsorlogo set live='$live' where id='$id'");
				$act = '';
				$do = '';
			break;	
			}
		  }
	
		switch ($act) {
			case 'del':
				$r = db_row("select id, naam from sponsorlogo where id='$id'");
?>
				<form action="?state=admin&go=content&m=logo&act=del&id=<?php echo $id;?>" method="post">
                Het logo van <?php echo $r[1]; ?> echt verwijderen?<br /><br />
                <input type="submit" name="do" value="verwijderen" class="button_rood"> <input type="submit" name="annuleren" value="annuleren" class="button_rood"> 
                
                </form>
<?php				
			break;
			
			case 'new':
				$naam = '';
				$file = '';
				$volgorde = '';
?>			
				<form action="?state=admin&go=content&m=logo&act=new&id=<?php echo $id;?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="opnieuw" value="j" />
                <input type="hidden" name="live" value="j" />
                <input type="hidden" name="oldfile" value="<?php file; ?>" />
                <br /><br />
                <table>
                	<tr><td>Sponsor</td><td width="10"></td><td><input type="text" name="naam" value="<?php echo $naam;?>" /></td></tr>
                	<tr><td>Logo</td><td width="10"></td><td><input type="file" name="bestand" /></td></tr>
                	<tr><td></td><td width="10"></td><td><?php if(!empty($file)) {
                	    ?><img src="https://<?php echo $base_url; ?>/img/logos/<?php echo $file; ?>" width="150" height="69" /><?php
                	    } ?></td></tr>
                    <tr><td>Volgorde</td><td width="10"></td><td><input type="text" name="volgorde" value="<?php echo $volgorde;?>" maxlength="3" size="3" /></td></tr>
                    <tr><td colspan="3"></td></tr>
                    <tr><td colspan="3" align="right"><input type="submit" name="do" value="bevestigen" /></td></tr>
                    
                </table>
                </form>
<?php
			break;
			
			case 'edit':
				if ($opnieuw != 'j')
				  {
					  $r = db_row("select id, naam, file, volgorde, live from sponsorlogo where id='$id'");
					  $naam = $r[1];
					  $file = $r[2];
					  $volgorde = $r[3];
					  $live = $r[4];
				  }
?>
				<form action="?state=admin&go=content&m=logo&act=edit&id=<?php echo $id;?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="opnieuw" value="j" />
                <input type="hidden" name="oldfile" value="<?php echo $file; ?>" />
                <br /><br />
                <table>
                	<tr><td>Sponsor</td><td width="10"></td><td><input type="text" name="naam" value="<?php echo $naam;?>" /></td></tr>
                	<tr><td>Logo</td><td width="10"></td><td><input type="file" name="bestand" /></td></tr>
                	<tr><td></td><td width="10"></td><td><?php if (!empty($file)) {
                	    ?><img src="https://<?php echo $base_url; ?>/img/logos/<?php echo $file; ?>" width="150" height="69" /><?php
                	    } ?></td></tr>
                    <tr><td>Volgorde</td><td width="10"></td><td><input type="text" name="volgorde" value="<?php echo $volgorde;?>" maxlength="3" size="3" /></td></tr>
                    <tr><td colspan="3"><input type="checkbox" name="live" value="j" <?php if ($live=='j') {
                        ?> checked="checked"<?php
                        } ?> />live </td></tr>
                    <tr><td colspan="3"></td></tr>
                    <tr><td colspan="3" align="right"><input type="submit" name="do" value="bevestigen" /></td></tr>
                    
                </table>
                </form>
<?php				
			break;
			
			default:
?>
				<table>
<?php			
				$res = db_query("select id, naam, volgorde, live from sponsorlogo where verwijderd!='j'");
			 	while ($r = db_row($res)) {
?>
					<tr>
                    	<td><a href="?state=admin&go=content&m=logo&act=del&id=<?php echo $r[0]; ?>"><i class="far fa-trash-alt"></i></a></td>
                        <td width="10"></td>
                        <td><a href="?state=admin&go=content&m=logo&act=edit&id=<?php echo $r[0]; ?>"><?php echo $r[1]; ?></a></td>
                        <td width="10"></td>
                        <td><a href="?state=admin&go=content&m=logo&act=edit&id=<?php echo $r[0]; ?>"><?php echo $r[2]; ?></a></td>
                        <td width="10"></td>
                        <td><a href="?state=admin&go=content&m=logo&act=fliplive&id=<?php echo $r[0]; ?>&do=bevestigen"><input type="checkbox" value="j" <?php if ($r[3]=='j') { ?> checked="checked" <?php } ?> /></a></td>
                        
                    </tr>
<?php				  
                } // while
?>
				</table><br />
                <br />
                <a href="?state=admin&go=content&m=logo&act=new"><i class="fas fa-plus-circle"></i> toevoegen</a>
<?php				  
			break;
		}
	break;
	
	case 'menu':
		if ($do == 'bevestigen') {
		    if ($extern != 'j')  {
              $actie = $menu_state;
            }
            db_query("update menu set naam='$naam', tekst='$tekst', actie='$actie', extern='$extern' where id='$id'");
            $act = '';
        }
	
?>	
		<span class="kop"><a href="?state=admin&go=content&m=menu"><i class="fas fa-bars fa-fw"></i> Menu</a></span>
        <hr>
<?php
		switch ($act) {
		  default:
			  $res=db_query("select id, naam, tekst from menu where verwijderd!='j' and site='$subdomein' order by id");
			  while ($r = db_row($res)) {
?>
			<div class="cms_regel">
                <div class="cms_table_item"><a href="?state=<?php echo $state;?>&go=<?php echo $go;?>&m=<?php echo $m;?>&act=edit&id=<?php echo $r[0]; ?>"><i class="fas fa-pencil-alt"></i></a></div>
            <div class="cms_table_naam"><a href="?state=<?php echo $state;?>&go=<?php echo $go;?>&m=<?php echo $m;?>&act=edit&id=<?php echo $r[0]; ?>"><?php echo $r[1]; ?></a>&nbsp;</div>
            <div class="cms_table_txt"><a href="?state=<?php echo $state;?>&go=<?php echo $go;?>&m=<?php echo $m;?>&act=edit&id=<?php echo $r[0]; ?>"><?php echo $r[2]; ?></a></div>
			<div style="clear:both"></div>
            </div>
<?php
             } // while
?>
		<br />
<?php		
		break;
			
		case 'edit':
				if ($opnieuw != 'j') {
					  $r = db_row("select id, naam, tekst, actie, extern from menu where id='$id'");
					  $naam = $r[1];
					  $tekst = $r[2];
					  $actie = $r[3];
					  $extern = $r[4];
                }
?>
				<form action="?state=admin&go=content&m=menu&act=edit&id=<?php echo $id;?>" method="post" enctype="multipart/form-data" id="menu_form">
                <input type="hidden" name="opnieuw" value="j" />
                <input type="hidden" name="oldfile" value="<?php echo $file; ?>" />
                <br /><br />
                <table>
                	<tr><td>Naam</td><td width="10"></td><td><input type="text" name="naam" value="<?php echo $naam;?>" /></td></tr>
                	<tr><td>Tekst</td><td width="10"></td><td><input type="text" name="tekst" value="<?php echo $tekst; ?>" /></td></tr>
                    <tr><td >Actie</td><td></td><td><input type="checkbox" name="extern" value="j" <?php if ($extern=='j') {
                            ?> checked="checked"<?php
                            } ?> onClick="menu_flip_actie()" /> extern </td></tr>
                    <tr id="actie_veld" style="display:<?php if ($extern=='j') { ?>table-row<?php } else { ?>none<?php } ?>"><td></td><td width="10"></td><td><input type="text" name="actie" value="<?php echo $actie;?>" size="40" /></td></tr>
        			 <tr id="actie_lijst" style="display:<?php if ($extern!='j') { ?>table-row<?php } else { ?>none<?php } ?>"><td></td><td width="10"></td><td><select name="menu_state">
<?php
						$sres = db_query("select id, state from states where site='www' and verwijderd!='j'");
						while ($sr = db_row($sres)) {
?>
							<option value="?state=<?php echo $sr[1]?>" <?php $st="?state=".$sr[1]; if ($actie==$st) { ?> selected<?php } ?>><?php echo $sr[1]; ?></option>
<?php							  
                        } // while
?>						  
                     </select></td></tr>                                
                    <tr><td colspan="3"></td></tr>
                    <tr><td colspan="3" align="right"><input type="submit" name="do" value="bevestigen" /></td></tr>
                    
                </table>
                </form>
<?php			
		break;	
	  } // switch
		  

	break;
	
	
	case 'foto':
		if (!empty($do)) { // formulier afhandelen
		  	switch ($act) {
			case 'del':
				if ($do == 'verwijderen')
				  { db_query("update fotos set verwijderd='j' where id='$id'"); }
				$act = '';
				$do = '';
			break;

			case 'new':
				// file verplaatsen
				$filenaam = '';
				$naamparts = explode('.',$_FILES['bestand']['name']);
				$ext = strtolower(array_pop($naamparts));
				
				switch ($ext) {
					case 'jpg';
					case 'jpeg':
						$ext = 'jpg';
					break;
					
					case 'gif':
						$ext = 'gif';
					break;
					
					case 'png':
						$ext = 'png';
					break;
					
					default:
						$ext = '';
					break;					
				} // switch
				
				if (empty($ext)) {
				    $err[]='Verkeerde bestandsformaat';
				}
				  
				$dest = $sponsorlogo_dir . '/tempfoto.' . $ext;
				if (move_uploaded_file($_FILES['bestand']['tmp_name'],$dest)) {
					list($width, $height, $type, $attr) = getimagesize($dest);
					if ($width != '585') {
					    $err[] = "Breedte ($width pixels) niet correct (moet 585 pixels zijn)";
					}
					if ($height != '260') {
					    $err[] = "Hoogte ($height pixels) niet correct (moet 260 pixels zijn)";
					}
					
					if (empty($err)) {
						$fp = fopen($dest,'r');
						$data = fread($fp, filesize($dest));
						fclose($fp);
						unlink($dest);
						$data = addslashes($data);
						$filenaam = $_FILES['bestand']['name'];
						if (empty($naam)) {
						    $naam = $filenaam;
						}

						db_query("insert into fotos (naam, foto, filenaam, ext) values ('$naam', '$data', '$filenaam','$ext')");
						$act = '';
						$do = '';
                    }
                } else {
				    $err[] = 'Bestand kan niet worden verwerkt';
				}
			break;
			
			}
        }

		if (!empty($err)) {
			echo "<br><br>";  
			foreach ($err as $val) {
			    echo "FOUT: $val<br>";
			}
        }
		
			
		switch ($act) {
			case 'del':
				$r = db_row("select id, naam from fotos where id='$id'");
?>
				<form action="?state=<?php echo $state; ?>&go=<?php echo $go;?>&m=<?php echo $m; ?>&act=<?php echo $act; ?>&id=<?php echo $id;?>" method="post">
				<br>
<img src="img/foto.php?id=<?php echo $id; ?>">
                <br>
<br>
De foto <?php echo $r[1]; ?> echt verwijderen?<br /><br />
                <input type="submit" name="do" value="verwijderen" class="button_rood"> <input type="submit" name="annuleren" value="annuleren" class="button_rood"> 
                
                </form>
<?php				
			break;
			
			case 'new':
				$naam = '';
				$file = '';
				$volgorde = '';
?>			
				<form action="?state=<?php echo $state; ?>&go=<?php echo $go;?>&m=<?php echo $m; ?>&act=<?php echo $act; ?>&id=<?php echo $id;?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="opnieuw" value="j" />
                <input type="hidden" name="oldfile" value="<?php file; ?>" />
                <br /><br />
                <table>
                	<tr><td>Naam</td><td width="10"></td><td><input type="text" name="naam" value="<?php echo $naam;?>" /></td></tr>
                	<tr><td>Foto</td><td width="10"></td><td><input type="file" name="bestand" /></td></tr>
                    <tr><td colspan="3"></td></tr>
                    <tr><td colspan="3" align="right"><input type="submit" name="do" value="bevestigen" /></td></tr>
                    
                </table>
                </form>
<?php
			break;
			
			
			default:
?>
				<br /><br />
				<h1>Foto's</h1>
				<table>
<?php			
				$res = db_query("select id, naam from fotos where verwijderd!='j'");
			 	while ($r = db_row($res)) {
?>
					<tr valign="top">
                    	<td><a href="?state=<?php echo $state;?>&go=<?php echo $go;?>&m=<?php echo $m;?>&act=del&id=<?php echo $r[0]; ?>"><img src="img/24x24/editcut.png" /></a></td>
                        <td width="10"></td>
                        <td><?php echo $r[1]; ?></td>
                        <td width="10"></td>
                        <td><?php echo $r[2]; ?></td>
                        <td width="10"></td>
                        <td><img src="img/foto.php?id=<?php echo $r[0]; ?> width="118" height="52"></td>
                        
                    </tr>
<?php				  
                }
?>
				</table><br />
                <br />
                <a href="?state=<?php echo $state;?>&go=<?php echo $go;?>&m=<?php echo $m;?>&act=new"><img src="img/24x24/edit_add.png" align="absmiddle"/> toevoegen</a>
<?php				  
			break;
		}
	break;
	
	
	default:
?>	
<ul>
	<li><a href="?state=admin&go=content&m=pagina"><i class="far fa-file-alt fa-2x fa-fw"></i> Pagina's bewerken</a></li>
	<li><a href="?state=admin&go=content&m=menu"><i class="fas fa-bars fa-2x fa-fw"></i> Menu bewerken</a></li>
	<li><a href="?state=admin&go=content&m=nieuws"><i class="fas fa-bullhorn fa-2x fa-fw"></i> Nieuws bewerken</a></li>
	<li><a href="?state=admin&go=content&m=logo"><i class="fas fa-money-bill-wave fa-2x fa-fw"></i> Sponsorlogo's bewerken</a></li>
	<li><a href="?state=admin&go=content&m=foto"><i class="fas fa-camera-retro fa-2x fa-fw"></i> Foto's bewerken</a></li>
</ul>    
<?php
	break;
}
?>
