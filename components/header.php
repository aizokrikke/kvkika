<!doctype html>
<html>
<head  prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# 
               article: http://ogp.me/ns/article#">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>DE BERG OP | Kinderen voor KiKa</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<?php 
if (!empty($metatags)) {
	foreach ($metatags as $var) {
	    echo $var . "\n";
	  }
  } else {
?>  
<meta property="og:title" content="Kinderen voor KiKa | De Berg Op" />
<meta property="og:description" content="Op <?php echo event_datum(); ?> rijden kinderen in Amersfoort De Berg Op om geld in te zamelen voor KiKa" />
<?php
}
?>  
<link type="text/css" rel="stylesheet" href="<?php echo $protocol . $domein;?>/css/main.css">
<?php
switch ($state) {
	case 'inschrijven':
?>	
<link type="text/css" rel="stylesheet" href="<?php echo $protocol . $domein;?>/css/inschrijven.css">
<?php 	
	break;
	
	case 'deelnemer':
?>	
<link type="text/css" rel="stylesheet" href="<?php echo $protocol . $domein;?>/css/deelnemer.css">
<?php 
	break;
}
?>
<link type="text/css" rel="stylesheet" href="<?php echo $protocol;?>www.kinderenvoorkika.nl/css/twitter.css">
<link rel="shortcut icon" type="image/x-icon" href="<?php echo $protocol . $domein;?>/favicon.ico">
<script src="<?php echo $protocol . $domein;?>/js/ajax.js" type="text/javascript"></script>
<script src="<?php echo $protocol . $domein;?>/js/main.js" type="text/javascript"></script>
<script src="<?php echo $protocol . $domein;?>/ckeditor/ckeditor.js" type="text/javascript"></script>


<script type="text/javascript">

var logos=[<?php 
$aant=0;
$lres = db_query("select file from sponsorlogo where verwijderd!='j' and live='j' order by volgorde asc");
while ($lr = db_row($lres)) {
    echo "'$lr[0]',"; $aant++;
}
?>''];
var aantal_logos=<?php echo $aant-1; ?>;
var basis_url='<?php echo $base_url; ?>';


  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-29109148-1']);
  _gaq.push(['_setDomainName', 'kinderenvoorkika.nl']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body onload="sponsor_cycle();">


<div id="header_container">
<!-- menu gebied -->
    <div id="header">
      <div id="header_kvkika" onClick="window.location='<?php echo $protocol . $domein."/"?>';"></div>
    <?php
        include($siteroot.'/components/menu.php');
    ?>	
      <div id="header_bergop" onClick="window.location='<?php echo $protocol . $domein."/"?>';"></div>
      <!-- <div id="header_5jaar"></div> -->
    </div>
</div>

<!-- start content -->
<div id="outer_container">
	<div id="inner_container">