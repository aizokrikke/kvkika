<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>DE BERG OP | Kinderen voor KiKa</title>
<link type="text/css" rel="stylesheet" href="css/uc.css"> 
<script src="js/ajax.js" type="text/javascript"></script>
<script type="text/javascript">
	function swap_img(name,pic) {
		document[name].src=pic;
	}
</script>

<script type="text/javascript">

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
<body>
<div id="container">
	<div id="uc_header">
    	<div id="uc_kop">UNDER CONSTRUCTION</div>     
    </div>

    <div id="uc_mail_ons">
    	<a href="mailto: info@kinderenvoorkika.nl" ><img src="img/Kika_button_mail_ons.png" name="mail_button" onMouseOver="swap_img('mail_button','img/Kika_button_mail_ons_hover.png');" onMouseOut="swap_img('mail_button','img/Kika_button_mail_ons.png');" alt="Mail ons" title="Mail ons"></a>
    </div>

	<div id="uc_tekstblok">
    	<span class="kop">HET EVENEMENT</span><br><br>
De Berg Op/Kinderen voor KiKa is een initiatief van Amersfoorter Hein van Wegen. Hij verloor zijn zoon Paul, op jonge leeftijd aan kanker. Paul van Wegen zette zich in voor de jeugd, als scoutingleider bij Mondriaan. Met name daarom zocht Hein een mogelijkheid om, middels de schooljeugd en het Amersfoortse bedrijfsleven, geld te gaan genereren voor onderzoek naar de ziekte kanker. Hij werd geïnspireerd door evenementen als Alpe d'HuZes, Terry Fox Run en Roparun. 
    </div>

	<div id="uc_mailform">
    <span class="kop">HOUD MIJ OP DE HOOGTE</span><br><br>
		<div id="ajax_mailform"><?php include('workers/uc_mailform_body.php'); ?></div>
    </div>
    
    <div id="uc_twitter"><a href="https://twitter.com/kindvoorkika" target="_blank"><img src="img/kika_twitter.png" alt="twitter" title="twitter"></a></div>
    <div id="uc_fb"><a href="http://www.facebook.com/profile.php?id=100003546489302" target="_blank"><img src="img/kika_fb.png" alt="facebook" title="facebook"></a></div>
    <div id="uc_linkedin"><a href="http://www.linkedin.com/pub/kinderen-voor-kika/48/b6/230" target="_blank"><img src="img/kika_linkedin.png" alt="linkedin" title="linkedin"></a></div>

    
 <?php if (!empty($user['rechten']['beheer'])) { ?>   
    <div id="uc_beheer"><a href="?state=admin" target="_blank"><img src="img/beheer.png" alt="beheer" title="beheer"></a></div>
<?php } ?>


    <div id="colofon">Concept en design Concreet geeft vorm | Realisatie Reditus</div>
</div>


</body>

</html>
