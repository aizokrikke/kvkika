<?php
//documentation on the spreadsheet package is at:
//http://pear.php.net/manual/en/package.fileformats.spreadsheet-excel-writer.php
chdir('libs/phpxls');
require_once 'Writer.php';
chdir('../..');

setlocale(LC_ALL,"nl_NL");
require('libs/connect.php');
require('logon/check_logon.php');
require('libs/tools.php');

$f_soort = db_esc($_REQUEST['f_soort']);
$f_eigenaar = db_esc($_REQUEST['f_eigenaar']);
$f_stuurgroep = db_esc($_REQUEST['f_stuurgroep']);
$f_categorie = db_esc($_REQUEST['f_categorie']);
$f_status = db_esc($_REQUEST['f_status']);
$f_gereed = db_esc($_REQUEST['f_gereed']);
$f_start = db_esc($_REQUEST['f_start']);
$act = db_esc($_REQUEST['act']);
$do = $_REQUEST['do'];
$opnieuw = $_REQUEST['opnieuw'];
$ann = $_REQUEST['ann'];


$q="select 	draaiboek.id, 
			draaiboek.naam, 
			draaiboek.start, 
			draaiboek.gereed, 
			draaiboek.beschrijving, 
			draaiboek.status, 
			draaiboek.prio, 
			draaiboek.resultaat, 
			personen.voornaam,
			personen.voorvoegsel,
			personen.achternaam,
			stuurgroepen.naam, 
			draaiboek.soort 
	from draaiboek, personen, stuurgroepen 
	where 	draaiboek.verwijderd!='j' and 
			draaiboek.soort='$f_soort' and
			personen.id=draaiboek.eigenaar and
			stuurgroepen.id=draaiboek.stuurgroep
	";
if ($f_status == 'niet gestart') {
    $q .= " and (status='niet gestart' ";
}
if ($f_status == 'gestart') {
    $q.=" and (status='niet gestart'  or status='gestart' ";
}
if ($f_gereed == 'j') {
    if (!empty($f_status)) {
        $q.=" or status='gereed') ";
    }
} else {
    if ((!empty($f_status)) and ($f_status != 'gereed')) {
        $q .= ") ";
    }
		$q .= " and status!='gereed ' ";
	}
if (!empty($f_eigenaar)) {
    $q .= " and eigenaar='$f_eigenaar' ";
}
if (!empty($f_stuurgroep)) {
    $q .= " and stuurgroep='$f_stuurgroep' ";
}
if (!empty($f_categorie)) {
    $q .= " and categorie='$f_categorie' ";
}

$q .= " order by gereed asc, prio, start asc";


$res = db_query($q);

$sheet1 =  array(
  array('id','actie','starttijd','gereed','beschrijving','status','prioriteit','resultaat','eigenaar','stuurgroep','soort'));
while ($r = db_row($res)) {
  	$rij = array();
  	$rij[] = $r[0]; // id
	$rij[] = $r[1]; // actie
	$rij[] = strftime('%d %b %Y',$r[2]); // start
	$rij[] = strftime('%d %b %Y',$r[3]); // gereed
	$rij[] = editor_naar_txt($r[4]); // beschrijving
	$rij[] = $r[5]; // status
	$rij[] = $r[6]; // prio
	$rij[] = editor_naar_txt($r[7]); // resultaat
	
	$naam = $r[8];
	if (!empty($r[9])) {
	    $naam .= ' ' . $r[9];
	}
	if (!empty($r[10])) {
	    $naam .= ' ' . $r[10];
	}
	$rij[] = $naam;
	
	$rij[] = $r[11]; // stuurgroep
	$rij[] = $r[12]; // soort
		
  	$sheet1[] = $rij;
  }  


$workbook = new Spreadsheet_Excel_Writer();

$format_und =& $workbook->addFormat();
$format_und->setBottom(2);//thick
$format_und->setBold();
$format_und->setColor('black');
$format_und->setFontFamily('Arial');
$format_und->setSize(8);

$format_reg =& $workbook->addFormat();
$format_reg->setColor('black');
$format_reg->setFontFamily('Arial');
$format_reg->setSize(8);

$arr = array(
      'Draaiboek'=>$sheet1
      );

foreach($arr as $wbname=>$rows) {
    $rowcount = count($rows);
    $colcount = count($rows[0]);

    $worksheet =& $workbook->addWorksheet($wbname);

    $worksheet->setColumn(0,0, 6.14);//setColumn(startcol,endcol,float)
    $worksheet->setColumn(1,3,15.00);
    $worksheet->setColumn(4,4, 8.00);
    
    for( $j = 0; $j < $rowcount; $j++ ) {
        for($i=0; $i<$colcount;$i++) {
            $fmt =& $format_reg;
            if ($j == 0) {
                $fmt =& $format_und;
            }
            if (isset($rows[$j][$i])) {
                $data = $rows[$j][$i];
                $worksheet->write($j, $i, $data, $fmt);
            }
        }
    }
}

$workbook->send('draaiboek_(uitdraai_'.strftime("%d-%m-%Y_%H.%M", time()).').xls');
$workbook->close();
?>

