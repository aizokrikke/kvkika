<?php 
$s=4;
include('components/header.php'); ?>
<div id="linkerbalk">
<?php include('components/nieuws_box.php'); ?>
<br>
<?php include('components/twitter_box.php'); ?>
</div>


<div id="middenvlak">

<form action="?" method="post">
<input type="hidden" name="state" value="<?php echo $state; ?>" />
<h1>Inschrijven</h1>

<span class="body">De inschrijving is gesloten. Maar je kunt <?php echo event_dag(). " " . event_datum(); ?> natuurlijk nog wel genieten van het programma. Tot <?php echo event_dag(); ?>.<br />
</div>

<div id="rechterbalk">
<?php include('components/login_box.php'); ?>

    <div class="sponsors"><a href="http://www.kika.nl" target="_blank"><img src="img/kika_logo.jpg" alt="KiKa" title="KiKa"></a></div>
    <div id="sponsorbox">Dit evenement is tot <br />
stand gekomen met <br />
de inzet van:<br /><br />
    	<div id="logobox"><img src="img/Continental-Logo_150.png" /></div>
</div>
</div>

<div style="clear: both"></div>



<?php include('components/footer.php'); ?>