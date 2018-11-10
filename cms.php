<span class="kop"><a href="?state=admin&go=content"><img src="beheer/img/eleganticons-png/png/Paper.png" alt="content" title="content" align="absmiddle" height="24" width="24"> Content</a></span>

<?php 

$m=$_REQUEST['m'];
$act=$_REQUEST['act'];
$do=$_REQUEST['do'];
$id=mysql_real_escape_string($_REQUEST['id']);
$naam=mysql_real_escape_string($_REQUEST['naam']);
$body=mysql_real_escape_string($_REQUEST['body']);
$lead=mysql_real_escape_string($_REQUEST['lead']);
$foto=mysql_real_escape_string($_REQUEST['foto']);
$boxact=$_REQUEST['boxact'];
$boxid=mysql_real_escape_string($_REQUEST['boxid']);
$annuleren=$_REQUEST['annuleren'];

$aantal_boxen=$_REQUEST['aantal_boxen'];
for ($i=1; $i<$aantal_boxen; $i++)
  { $box_id[$i]=mysql_real_escape_string($_REQUEST['box_id'.$i]); 
  	$box[$i]=mysql_real_escape_string($_REQUEST['box_editor'.$i]);
	$box_datum[$i]=mysql_real_escape_string($_REQUEST['box_datum'.$i]);
  } // for


//print_r($box_id);
//print_r($box); 
//echo "boxact: $boxact, boxid: $boxid<br>";
//echo "naam: $naam<br>";

//echo "body: $body<br>";
 
if ($annuleren=='annuleren')		  
  { $do='';
	$act='';
  }

$nu=time();
$u=$user['id'];

switch ($m) {
	case 'pagina':
?>
		<span class="kop"><a href="?state=admin&go=content&m=pagina"><img src="beheer/img/content.png" alt="pagina's" title="pagina's" align="absmiddle" height="24" width="24"> Pagina's</a></span>	
        <hr>
        <div id="cms_box_editor"></div>
<?php	
	if (($do=='opslaan') or ($do=='zet live') or ($boxact=='newbox') or ($boxact=='delbox'))
		{
			
			mysql_query("update dev_pagina set naam='$naam', lead='$lead', body='$body', foto='$foto', gewijzigd='$nu', door='$u' where id='$id'");
			if (!empty($box))
			  {
				  // boxen verwerken
				  foreach ($box as $key => $val)
				    {
						$bdatum=$box_datum[$key];
						$bid=$box_id[$key];
						if (($bid<>0) and ($br=mysql_fetch_row(mysql_query("select id from dev_boxen where id='$bid' and verwijderd!='j'"))))
						  { // box bestaat 
						  	mysql_query("update dev_boxen set body='$val', datum='$bdatum' where id='$bid'");
						  }
						  else
						  {
							mysql_query("insert into dev_boxen (body, datum, pagina) values ('$val','$bdatum','$id')");
						  }
					}
			   }
			if ($boxact=='delbox')
				 { mysql_query("update dev_boxen set verwijderd='j' where id='$boxid'"); }	
			if (empty($boxact)) { $act=''; }
		 }
	if ($do=='zet live')
	  {
			if (mysql_fetch_row(mysql_query("select id from pagina where id='$id'")))
			  {
				mysql_query("update pagina set naam='$naam', lead='$lead', body='$body', foto='$foto', gewijzigd='$nu', door='$u' where id='$id'");
			  }
			  else
			  {
				mysql_query("insert into pagina (id, naam, lead, body, foto, aangemaakt, gewijzigd, auteur, door) values ('$id', '$naam', '$lead', '$body', '$foto', '$nu','$nu','$u','$u')");
			  }
			if (!empty($box))
			  {
				  // boxen verwerken
				  foreach ($box as $key => $val)
				    {
						$bdatum=$box_datum[$key];
						$bid=$box_id[$key];
						
						if (($bid<>0) and ($br=mysql_fetch_row(mysql_query("select id from boxen where id='$bid' and verwijderd!='j'"))))
						  { // box bestaat 
						  	
						  	mysql_query("update boxen set body='$val', datum='$bdatum' where id='$bid'");
						  }
						  else
						  {
							  
							mysql_query("insert into boxen (id, body, datum, pagina) values ('$bid','$val','$bdatum','$id')") or die(mysql_error());
						  }
					}
			   }
		  
	  }
	if ($do=='verwijderen')
		{
				  $r=mysql_fetch_row(mysql_query("select fixed from dev_pagina where id='$id'"));
				  if ($r[0]!='j')
				    { 
						mysql_query("update dev_pagina set verwijderd='j', gewijzigd='$nu', door='$u' where id='$id'");
						mysql_query("update pagina set verwijderd='j', gewijzigd='$nu', door='$u' where id='$id'");
					}
				  $act=''; 
		}
	if ($do=='toevoegen')
		{
				  mysql_query("insert into dev_pagina (naam, lead, body, foto, aangemaakt, gewijzigd, auteur, door) values ('$naam','$lead','$body', '$foto','$nu,'$nu','$u','$u')");
				  $act='';
		}


		if (!empty($act))
		  {
			//echo "do: $do, act: $act, id: $id, body: $body";
			if (($act=='edit') OR ($act=='del'))
			  {
				$r=mysql_fetch_row(mysql_query("select naam, lead, body, foto, menu, fixed from dev_pagina where id='$id' and verwijderd!='j'"));
			  }
?>
		<form action="?state=admin&go=content&m=pagina&act=edit" method="post" id="bform">
        <input type="hidden" name="id" value="<?php echo $id; ?>" />
        <input type="hidden" name="boxact" value="" />
        <input type="hidden" name="boxid" value="" />
        
        naam<br />
        <input type="text" name="naam" value="<?php echo stripslashes($r[0]); ?>" <?php if ($r[5]=='j') { ?> readonly="readonly"<?php } ?> /><br />
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
			$fres=mysql_query("select id, naam from fotos order by naam");
			while ($fr=mysql_fetch_row($fres))
			  {
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
		
		if ($act!='new')
		  {
			$bres=mysql_query("select id, datum, body from dev_boxen where pagina='$id' and verwijderd!='j'");
			$a=1;
			$t=1;
			while ($br=mysql_fetch_row($bres))
			  {
		?>
        	<div <?php		  
                 if ($a==1)
                   { echo 'class="box_oranje"'; }
                   else
                   { echo 'class="box_paars"'; }?>>
                   
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
                if ($a>2) 
                  { $a=1;
                  }
             }
		 if ($boxact=='newbox')
		   {
		?>	   
        	<div <?php		  
                 if ($a==1)
                   { echo 'class="box_oranje"'; }
                   else
                   { echo 'class="box_paars"'; }?>>
            	<div class="box_datum_edit">
            		<div id="box_datum<?php echo $t; ?>"><?php $div="box_datum".$t; $datum_select=time();; include('beheer/workers/datum_invoer_body.php'); ?></div>
            
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
		  	$i=1;
			$b=1;
			while($i<=$t)
			  {
			?>	  
		  	<script type="text/javascript">
			box_editor<?php echo $i;?> = CKEDITOR.replace( 'box_editor<?php echo $i;?>', { toolbar : [
						{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
						{ name: 'styles', items : [ 'Styles' ] },
						{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
						{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote',
	'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] }, { name: 'links', items : [ 'Link','Unlink' ] }

					], uiColor : '<?php if ($b==1) { ?>#560666<?php } else { ?>#e95005<?php } ?>',  width : 300, height : 200
				});
				

		<?php if ($b=='1') { ?>box_editor<?php echo $i; ?>.config.contentsCss='css/box_oranje.css';  <?php }
				else { ?>box_editor<?php echo $i; ?>.config.contentsCss='css/box_paars.css'; <?php } ?>
			box_editor<?php echo $i;?>.config.stylesSet ='boxStyles';		
			
			
			</script>        
	 	<?php
				$i++;
				$b++;
				if ($b>2) { $b=1;}	
			  } // while
        		
		?> 
        <a href="javascript:void();" onclick="do_newbox()"><img src="beheer/img/24x24/edit_add.png" align="absmiddle" height="16" width="16" /> nieuwe box</a><br />
<br />
       <?php
		  }
		if ($act=='del') {  ?><input type="submit" name="do" value="verwijderen" class="button_rood" /><?php } elseif ($act=='new')  { ?><input type="submit" name="do" value="toevoegen" class="button_rood" /><?php } else { ?><input type="submit" name="do" value="opslaan" class="button_rood" /> <input type="submit" name="do" value="zet live" class="button_rood" /><?php } ?> <input type="submit" name="annuleren" value="annuleren" class="button_rood" /> 
        </form>
<?php		
			  
		  }
		if (empty($act))
		  {
			  $res=mysql_query("select id, naam, lead, body, foto, fixed from dev_pagina where verwijderd!='j' order by naam");
			  while ($r=mysql_fetch_row($res))
			    {
?>
			<div class="cms_regel">
			<div class="cms_table_item"><?php if ($r[5]!='j') {?><a href="?state=admin&go=content&m=pagina&act=del&id=<?php echo $r[0]; ?>"><img src="beheer/img/24x24/editcut.png" title="verwijderen" alt="verwijderen"></a><?php } else { ?><div style="width:24px;">&nbsp;</div><?php } ?></div>
            <div class="cms_table_item"><a href="?state=admin&go=content&m=pagina&act=edit&id=<?php echo $r[0]; ?>"><img src="beheer/img/24x24/edit.png" title="bewerken" alt="bewerken"></a></div>
            <div class="cms_table_naam"><a href="?state=admin&go=content&m=pagina&act=edit&id=<?php echo $r[0]; ?>"><?php echo $r[1]; ?></a>&nbsp;</div>
            <div class="cms_table_txt"><a href="?state=admin&go=content&m=pagina&act=edit&id=<?php echo $r[0]; ?>"><?php echo $r[2]; ?></a></div>
            <div class="cms_table_txt"><a href="?state=admin&go=content&m=pagina&act=edit&id=<?php echo $r[0]; ?>"><?php echo $r[3]; ?></a></div>
			<div style="clear:both"></div>
            </div>
<?php					
				} // while
?>
		<br />
		<a href="?state=admin&go=content&m=pagina&act=new"><img src="beheer/img/24x24/edit_add.png" width="16" height="16" align="absmiddle" alt="pagina toevoegen" title="pagina toevoegen" /> pagina toevoegen</a>
<?php				
				
		   } // if
		  
	break;
	
	case 'nieuws':

?>
		<span class="kop"><a href="?state=admin&go=content&m=nieuws"><img src="beheer/img/news.png" alt="nieuwsberichten" title="nieuwsberichten" align="absmiddle" height="24" width="24"> Nieuws</a></span>	
        <hr>
 
 <?php
 		switch ($act) {
			case 'new':
				$nu=time();
				mysql_query("insert into dev_nieuws (datum) values ('$nu')");
				$act='';
			break;
			case 'del':
				if (empty($do))
				  {
					  $r=mysql_fetch_row(mysql_query("select datum, lead, body from dev_nieuws where verwijderd!='j' and id='$id'"));
?>	
				<form action="?state=admin&go=content&m=nieuws&act=del&id=<?php echo $id;?>" method="post">
                Het volgende bericht echt verwijderen?<br><br>
                <div class="news_lead"><?php echo stripslashes($r[1]); ?></div>
                <div class="news_body"><?php echo stripslashes($r[2]); ?></div>
                <input type="submit" name="do" value="verwijderen" class="button_rood"> <input type="submit" name="annuleren" value="annuleren" class="button_rood"> 
                
                </form>
<?php
				  }
				  else
				  {
					  if ($do=='verwijderen') 
					  	{ mysql_query("update dev_nieuws set verwijderd='j' where id='$id'");
						  mysql_query("update nieuws set verwijderd='j' where id='$id'");
						  $act='';
						}
						
				  }
			break;
		}
?>        
        <div id="cms_txt_editor"></div>
        <div id="cms_datum_editor"></div>
<?php		

		if (empty($act))
		  {
			 $res=mysql_query("select id, lead, body, datum, status from dev_nieuws where verwijderd!='j'");
			 while ($r=mysql_fetch_row($res))
			   {
?>
        
        <div id="<?php echo "nieuws$r[0]";?>" class="cms_regel">
        <div class="cms_table_item"><a href="?state=admin&go=content&m=nieuws&act=del&id=<?php echo $r[0]; ?>"><img src="beheer/img/24x24/editcut.png" title="verwijderen" alt="verwijderen"></a></div>
        <div class="cms_table_datum"><a href="javascript:void();" onclick="cms_datum(<?php echo $r[0]; ?>)"><?php echo strftime("%d-%B-%Y", $r[3]); ?></a></div>
        <div class="cms_table_txt"><a href="javascript:void();" onclick="cms_editor('<?php echo $r[0]; ?>','lead');"><?php if (!empty($r[1])) { echo $r[1]; } else {?><em>-- leeg --</em><?php } ?></a></div>
		<div class="cms_table_txt"><a href="javascript:void();" onclick="cms_editor('<?php echo $r[0]; ?>','body');"><?php if (!empty($r[2])) { echo $r[2]; } else { ?><em>-- leeg --</em><?php } ?></a></div>
        <div class="cms_table_item"><a href="javascript:void();" onClick="doFlipNieuwsStatus('<?php echo $r[0]; ?>');"><img src="beheer/img/<?php if ($r[4]=='draft') { ?>draft.png<?php } else { ?>public.png<?php } ?>" title="<?php echo $r[4]; ?>" alt="<?php echo $r[4];?>"></a></div>        
		<div style="clear:both"></div>
        </div>
<?php				   
			   } // while
?>

		<br>
		<a href="?state=admin&go=content&m=nieuws&act=new"><img src="beheer/img/24x24/edit_add.png" width="16" height="16" align="absmiddle" alt="bericht toevoegen" title="bericht toevoegen"> bericht toevoegen</a>
<?php        		   
			  
		  }	

	break;
	default:
?>	
<ul>
	<li><a href="?state=admin&go=content&m=pagina"><img src="beheer/img/content.png" alt="pagina's bewerken" title="pagina's bewerken" align="absmiddle"> Pagina's bewerken</a></li>
	<li><a href="?state=admin&go=content&m=nieuws"><img src="beheer/img/news.png" alt="nieuws bewerken" title="nieuws bewerken" align="absmiddle"> Nieuws bewerken</a></li>
</ul>    
<?php
	break;
} // switch

?>