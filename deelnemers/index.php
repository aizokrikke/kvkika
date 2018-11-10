<?php 
setlocale(LC_ALL,"nl_NL");
require('../libs/connect.php');
require('../logon/check_logon.php');
require('../libs/tools.php'); 
$pag=$_REQUEST['pag'];

include('../components/header.php');
?>

<div id="linkerbalk">
<h1>Nieuws</h1>
<?php include('../components/nieuws_box.php'); ?>
<br>
<?php include('../components/twitter_box.php'); ?>

</div>


<div id="middenvlak"> 

</div>

<div id="rechterbalk">
<?php include('../components/login_box.php'); ?>

    <div class="sponsors"><a href="http://www.kika.nl" target="_blank"><img src="http://<?php echo $domein;?>/img/kika_logo.jpg" alt="KiKa" title="KiKa"></a></div>
    <div id="sponsorbox">Dit evenement is tot <br />
stand gekomen met <br />
de inzet van:<br /><br />
    	<div id="logobox"><img src="http://<?php echo $domein;?>/img/Continental-Logo_150.png" /></div>
</div>
</div>

<div style="clear: both"></div>


<?php include('../components/footer.php'); ?>
