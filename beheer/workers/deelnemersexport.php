<?php
//documentation on the spreadsheet package is at:
//http://pear.php.net/manual/en/package.fileformats.spreadsheet-excel-writer.php


chdir('../../libs/phpxls');
require_once '../../libs/phpxls/Writer.php';
chdir('../../beheer/workers');

setlocale(LC_ALL,"nl_NL");
require('../../libs/connect.php');
require('../../logon/check_logon.php');
require('../../libs/tools.php');

$f_soort=mysql_real_escape_string($_REQUEST['f_soort']);
$f_eigenaar=mysql_real_escape_string($_REQUEST['f_eigenaar']);
$f_stuurgroep=mysql_real_escape_string($_REQUEST['f_stuurgroep']);
$f_categorie=mysql_real_escape_string($_REQUEST['f_categorie']);
$f_status=mysql_real_escape_string($_REQUEST['f_status']);
$f_gereed=mysql_real_escape_string($_REQUEST['f_gereed']);
$f_start=mysql_real_escape_string($_REQUEST['f_start']);
$act=mysql_real_escape_string($_REQUEST['act']);
$do=$_REQUEST['do'];
$opnieuw=$_REQUEST['opnieuw'];
$ann=$_REQUEST['ann'];


switch ($do) {
	default;
	case 'deelnemers':
		$q="select deelnemers.id, 
					deelnemers.pagina,
					personen.voornaam,
					personen.voorvoegsel,
					personen.achternaam,
					personen.geslacht,
					personen.gebdatum,
					personen.email,
					personen.tel,
					personen.mobiel,
					personen.adres,
					personen.adres_nr,
					personen.postcode,
					personen.plaats,
					personen.aangemaakt,
					deelnemers.bevestigd,
					scholen.naam,
					deelnemers.startnummer,
					deelnemers.aanwezig,
					verzorgers.verzorger,
					deelnemers.categorie
			from deelnemers
			left join personen on deelnemers.persoon=personen.id
			left join scholen on scholen.id=deelnemers.school
			left join verzorgers on verzorgers.deelnemer=deelnemers.id
			where 	deelnemers.verwijderd!='j'
			order by personen.achternaam, personen.voornaam
			";


//echo $q;

			$res=mysql_query($q) or die(mysql_error());
			
			$rij=  array();
			$cell['data']='id';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='pagina';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='categorie';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='voornaam';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='tussenvoegsel';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='achternaam';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='geslacht';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='geboortedatum';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='email';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='telefoon';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='mobiel';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='adres';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='nummer';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='postcode';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='plaats';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='inschrijfdatum';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='bevestigd';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='school';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='startnummer';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='aanwezig';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='verzorger voornaam';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='verzorger tussenvoegsel';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='verzorger achternaam';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='verzorger email';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='verzorger telefoon';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='verzorger mobiel';
			$cell['type']='text';
			$rij[]=$cell;
			$cell['data']='sponsorbedrag';
			$cell['type']='text';
			$rij[]=$cell;	
			$sheet1[]=$rij;
		
			while ($r=mysql_fetch_row($res))
			  { 
			  
				$rij=array();
				  
				$cell['data']=$r[0];	//id
				$cell['type']='numeric';
				$rij[]=$cell;  
				$cell['data']=$r[1];	//pagina
				$cell['type']='text';
				$rij[]=$cell;  
				$cell['data']=$r[20];	//categorie
				$cell['type']='text';
				$rij[]=$cell;  
				$cell['data']=$r[2];	//voornaam
				$cell['type']='text';
				$rij[]=$cell;  
				$cell['data']=$r[3];	//tussenvoegsel
				$cell['type']='text';
				$rij[]=$cell;  
				$cell['data']=$r[4];	//achternaam
				$cell['type']='text';
				$rij[]=$cell;  
				$cell['data']=$r[5];	//geslacht
				$cell['type']='text';
				$rij[]=$cell;  
				$cell['data']=substr($r[6],8,2)."-".substr($r[6],5,2)."-".substr($r[6],0,4);	//geboortedatum
				$cell['type']='date';
				$rij[]=$cell;  
				$cell['data']=$r[7];	//email
				$cell['type']='text';
				$rij[]=$cell;  
				$cell['data']=$r[8];	//telefoon
				$cell['type']='text';
				$rij[]=$cell;  
				$cell['data']=$r[9];	//mobiel
				$cell['type']='text';
				$rij[]=$cell;  
				$cell['data']=$r[10];	//adres
				$cell['type']='text';
				$rij[]=$cell;  
				$cell['data']=$r[11];	//nummer
				$cell['type']='text';
				$rij[]=$cell;  
				$cell['data']=$r[12];	//postcode
				$cell['type']='text';
				$rij[]=$cell;  
				$cell['data']=$r[13];	//plaats
				$cell['type']='text';
				$rij[]=$cell;  
				$cell['data']=strftime('%d %b %Y',$r[14]); // inschrijfdatum
				$cell['type']='date';
				$rij[]=$cell;  
				$cell['data']=$r[15];	//bevestigd
				$cell['type']='text';
				$rij[]=$cell;  
				$cell['data']=$r[16];	//school
				$cell['type']='text';
				$rij[]=$cell;  
				$cell['data']=$r[17];	//startnummer
				$cell['type']='numeric';
				$rij[]=$cell;  
				$cell['data']=$r[18];	//aanwezig
				$cell['type']='text';
				$rij[]=$cell;  
				  
				$or=mysql_fetch_row(mysql_query("select voornaam, voorvoegsel,achternaam,email,tel,mobiel from personen where id='$r[19]' and verwijderd!='j'"));

				$cell['data']=$or[0];	//voornaam
				$cell['type']='text';
				$rij[]=$cell;  
				$cell['data']=$or[1];	//tussenvoegsel
				$cell['type']='text';
				$rij[]=$cell;  
				$cell['data']=$or[2];	//achternaam
				$cell['type']='text';
				$rij[]=$cell;  
				$cell['data']=$or[3];	//email
				$cell['type']='text';
				$rij[]=$cell;  
				$cell['data']=$or[4];	//tel
				$cell['type']='text';
				$rij[]=$cell;  
				$cell['data']=$or[5];	//mobiel
				$cell['type']='text';
				$rij[]=$cell;  
				  
				$sr=mysql_fetch_row(mysql_query("select sum(bedrag) from sponsoring where voor='$r[0]' and verwijderd!='j'"));
				$cell['data']=$sr[0];	//sponsorbedrag
				$cell['type']='currency';
				$rij[]=$cell;  
						
				$sheet1[] = $rij;
			  } // while  
			$arr = array('Deelnemers'=>$sheet1);	  
			$filenaam='deelnemers_(uitdraai_'.strftime("%d-%m-%Y_%H.%M", time()).').xls';
  		break;
		
		case 'incasso':
		$q="select 	sponsoring.id, 
					sponsoring.van,
					sponsoring.rekening,
					sponsoring.adres,
					sponsoring.plaats,
					sponsoring.email,
					sponsoring.bedrag					
			from sponsoring
			where 	sponsoring.verwijderd!='j'
			order by sponsoring.rekening
			";


//echo $q;

			$res=mysql_query($q) or die(mysql_error());
			
			$sheet1 =  array(
			  array('id','van','rekening','adres','plaats','email','bedrag'));
			while ($r=mysql_fetch_row($res))
			  { 
			  
				$rij=array();
				$cell['data']=$r[0]; // id
				$cell['type']='numeric';
				$rij[]=$cell;
				$cell['data']=$r[1]; // van
				$cell['type']='txt';
				$rij[]=$cell;
				$cell['data']=$r[2]; // rekening
				$cell['type']='txt';
				$rij[]=$cell;
				$cell['data']=$r[3]; // adres
				$cell['type']='txt';
				$rij[]=$cell;
				$cell['data']=$r[4]; // plaats
				$cell['type']='txt';
				$rij[]=$cell;
				$cell['data']=$r[5]; // email
				$cell['type']='email';
				$rij[]=$cell;
				$cell['data']=$r[6]; // bedrag
				$cell['type']='currency';
				$rij[]=$cell;					
				$sheet1[] = $rij;
			  } // while
			  
			  
		  $arr = array('Incasso'=>$sheet1);
		  $filenaam='incasso(uitdraai_'.strftime("%d-%m-%Y_%H.%M", time()).').xls';  
		break;
} // switch
//print_r ($sheet1);  

$workbook = new Spreadsheet_Excel_Writer();
$workbook->setVersion(8);

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

$date_format =& $workbook->addFormat();
$date_format->setNumFormat('D-MMM-YYYY');

$currency_format =& $workbook->addFormat();
$currency_format->setNumFormat('#,##0.00_);(#,##0.00)');


foreach($arr as $wbname=>$rows)
{
    $rowcount = count($rows);
    $colcount = count($rows[0]);

    $worksheet =& $workbook->addWorksheet($wbname);
	

    $worksheet->setColumn(0,0, 6.14);//setColumn(startcol,endcol,float)
    $worksheet->setColumn(1,3,15.00);
    $worksheet->setColumn(4,4, 8.00);
    
    for( $j=0; $j<$rowcount; $j++ )
    {
        for($i=0; $i<$colcount;$i++)
        {
            $fmt  =& $format_reg;
            if ($j==0)
                $fmt =& $format_und;

            if (isset($rows[$j][$i]))
            {
                $data=$rows[$j][$i]['data'];
				switch (strtolower($rows[$j][$i]['type'])) {
						
				default;
				case 'txt';
				case 'email':
					//echo "txt: $data<br>";
                	$worksheet->writeString($j, $i, $data, $fmt);
				break;
				
				case 'numeric';
				case 'num':
					//echo "number: $data<br>";
					$worksheet->writeNumber($j, $i, $data);	
				break;
						
				case 'currency';
				case 'cur':
					//echo "number: $data<br>";
					$worksheet->write($j, $i, $data, $currency_format);	
				break;		

				case 'date';
				case 'dat':
					//echo "number: $data<br>";
					$worksheet->write($j, $i, $data, $date_format);	
				break;		

				}
            }
        }
    }
}

$workbook->send($filenaam);
$workbook->close();

//-----------------------------------------------------------------------------
?>

