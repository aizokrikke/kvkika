<h1>Nieuws</h1>
<?php 

	if (onderhoud()) {
?>
        <div class="blocker"></div>
        <div class="news_datum"><?php echo strftime("%d %B %Y", time()); ?></div>
        <div class="news_lead">Onderhouds-werkzaamheden</div>
        <div class="news_body">Op dit moment vinden er onderhoudswerkzaamheden plaats aan de site. Deze werkzaamheden zijn nodig om ervoor te zorgen dat onze deelnemers en donateur zo optimaal mogelijk gebruik kunnen maken van deze website. Door de werkzaamheden is het tijdelijk niet mogelijk om je in te schrijven of te doneren. De onderhoudswerkzaamheden zullen van korte duur zijn en zodra deze gereed zijn is inschrijven en doneren weer mogelijk. Probeer het dus later weer. Onze excuses voor het ongemak.</div>			
<?php		
	}

	$q = "select datum, lead, body from " . $sitestatus . "nieuws where verwijderd != 'j' ";
	if ($sitestatus != 'dev_') {
	    $q .= " and status='public' ";
	}
	$q .= "order by datum desc limit 0,2";

	$res = db_query($q);
	while ($i = db_row($res))
	  {
?>		  
        <div class="blocker"></div>
        <div class="news_datum"><?php echo strftime("%d %B %Y",$i[0]); ?></div>
        <div class="news_lead"><?php echo $i[1]; ?></div>
        <div class="news_body"><?php echo $i[2]; ?></div>
<?php  } ?>