<span class="kop"><a href="?state=<?php echo $state;?>&go=beheerders"><img src="beheer/img/Basic_set2_Png/Basic_set2_Png/user_info_48.png" align="absmiddle" title="beheerders" alt="beheerders" align="absmiddle" height="24" width="24"> Moderatie</a></span>
<hr />


<?php 
$bericht=mysql_real_escape_string($_REQUEST['bericht']);
$blokkeren=$_REQUEST['blokkeren'];
$ok=$_REQUEST['ok'];
if ($blokkeren=='blokkeren')
  { mysql_query("update berichten set blokkeren='j' where id='$bericht'"); }
if ($ok=='bericht is ok')
  { mysql_query("update berichten set blokkeren='n' where id='$bericht'"); }


$br=mysql_fetch_row(mysql_query("select id, aan, naam, email, kop, bericht, blokkeren from berichten where id='$bericht' and verwijderd!='j'")) or die(mysql_error());

echo "van: ".stripslashes($br[2])." (".stripslashes($br[3]).")<br /><br />";
echo "<strong>".stripslashes($br[4])."</strong><br /><br />";
echo stripslashes($br[5])."<br /><br />";

if ($br[6]=='j') { echo "Bericht geblokkerd<br /><br />"; }

$dr=mysql_fetch_row(mysql_query("select voornaam, voorvoegsel, achternaam, pagina from deelnemers, personen where deelnemers.persoon=personen.id and deelnemers.id='$br[1]' and deelnemers.verwijderd!='j' and personen.verwijderd!='j'")) or die(mysql_error());

?>
Voor: <a href="https://<?php echo $domein;?>/deelnemers/<?php echo stripslashes($dr[3]); ?>" target="_blank"><?php echo $dr[0]; if (!empty($dr[1])) { echo stripslashes($dr[1])." "; } if (!empty($dr[2])) { echo stripslashes($dr[2])." "; }?></a> 
<br />
<br />
<form action="?">
<input type="hidden" name="state" value="<?php echo $state;?>" />
<input type="hidden" name="go" value="<?php echo $go;?>" />
<input type="hidden" name="bericht" value="<?php echo $bericht;?>" />
<input type="submit" name="blokkeren" value="blokkeren" class="db_button"/> <input type="submit" name="ok" value="bericht is ok" class="db_button"/>
</form> 