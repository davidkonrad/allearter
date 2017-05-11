<?
//debug
error_reporting(E_ALL);
ini_set('display_errors', '1');

//when called as include
//@include_once('common/Db.php');

//when called via ajax
if (!class_exists('Db')) {
	require_once('../common/Db.php');
}

//we are using a lot of memory
ini_set('memory_limit', '-1');

class Promo extends Db {
	public $row;
	public function __construct() {
		parent::__construct();
		if (!$this->getExclusive()) $this->getRandom();
	}
	private function getExclusive() {
		$SQL='select * from promos where exclusive=1 limit 1';
		if ($this->hasData($SQL)) {
			$this->row=$this->getRow($SQL);
			return true;
		} else {
			return false;
		}
	}
	private function getRandom() {
		$SQL='select * from promos where active=1 order by rand() limit 1';
		$this->row=$this->getRow($SQL);
	}
}

//Search
class Search extends Db {
	private $records = array();
	private $promo;

	public function __construct() {
		parent::__construct();
		$this->drawHeader();
		$this->loadData();
		$this->populate();
	}

	private function drawPromo() {
		echo '<div class="promo">';
		echo '<div class="text">';
		echo $this->promo['content'];
		echo '</div>';
		echo '<div class="image">';
		echo '<img src="'.$this->promo['content'].'"/>';
		echo '</div>';
		echo '<div class="teaser">';
		echo '</div>';
		echo '</div>';
	}

	private function drawHeader() {
		echo '<br/>';
		if (empty($_GET)) {
			echo '<h2>Antal poster i allearter.dk databasen : <span id="recordcount"></span></h2>';
		} else {
			echo '<h2>Søgning, antal fundne poster i allearter.dk databasen : <span id="recordcount"></span></h2>';
		}
?>
<div style="position:relative;display:block;float:left;width:100%;margin-top:5px;margin-bottom:20px;clear:both;font-size:13px;">
<p>Klik på kolonnehovederne for ønsket sortering. 
Man kan trække i kolonnehovederne for at skifte deres rækkefølge. <br>
Klik på "Vis kolonner" for at vise / skjule individuelle kolonner
</p></div>
<table id="search-result" style="width:100%;overflow:visible;margin-top:5px;">
<thead><tr>
<th style="width:240px;">Videnskabeligt navn</th>
<th style="width:150px;">Autor</th>
<th style="width:180px;">Dansk navn</th>
<th style="width:180px;">Familie</th>
<th style="width:180px;">Orden</th>
<th style="width:180px;">Artsgruppe (dk)</th>
<!-- pre hidden -->
<th style="width:130px;">Familie (dk)</th>
<th style="width:130px;">Orden (dk)</th>
<th style="width:130px;">Artsgruppe</th>
<th style="width:130px;">Synonymer</th>
<th style="width:130px;">Synonymer (dk)</th>
<th style="width:130px;">Referencenavn</th>
<th style="width:130px;">Referenceår</th>
<th style="width:130px;">Referencetekst</th>
<th style="width:130px;">Rødlistestatus</th>
<th style="width:130px;">Fredningsstatus</th>
<th style="width:130px;">Accepteret</th>
</tr></thead>
<?
	}

	private function test($param, $field, $like=false) {
		if (isset($_GET[$param])) {
			if ($_GET[$param]!='') {
				if ($like) {
					return $field.' like "%'.$_GET[$param].'%"';
				} else {
					//return $field.'=convert("'.$_GET[$param].'" using utf8_danish_ci)';
					return $field.'="'.$_GET[$param].'"';
					//return 'CONVERT(CAST('.$field.' as BINARY) USING utf8)="'.$_GET[$param].'"';
				}
			}
		}
		return false;
	}

	private function getWhere() {
		$ex=($_GET['lang']=='da') ? '_dk' : '';
		$where=array();

		$p=$this->test('videnskabeligt_navn','Videnskabeligt_navn');
		if ($p) $where[]=$p;

		$p=$this->test('artsgruppedk','Artsgruppe_dk');
		if ($p) $where[]=$p;

		$p=$this->test('artsgruppe','Artsgruppe');
		if ($p) $where[]=$p;

		$p=$this->test('rige','Rige'.$ex);
		if ($p) $where[]=$p;
				
		$p=$this->test('raekke','Raekke'.$ex);
		if ($p) $where[]=$p;

		$p=$this->test('klasse','Klasse'.$ex);
		if ($p) $where[]=$p;

		$p=$this->test('orden','Orden'.$ex);
		if ($p) $where[]=$p;

		$p=$this->test('familie','Familie'.$ex);
		if ($p) $where[]=$p;

		$p=$this->test('slaegt','Slaegt'.$ex);
		if ($p) $where[]=$p;

		if (isset($_GET['extra'])) {
			if ($_GET['extra_value']!='') {
				$p=ucfirst($_GET['extra']).$ex.'="'.$_GET['extra_value'].'"';
				$where[]=$p;
			}
		}	

		if (isset($_GET['taxon'])) {
			$tax=explode(' ',$_GET['taxon']);
			$p='';
			foreach($tax as $t) {
				if ($t!='') {
					if ($p!='') $p.=' or ';
					$p.='Taxonkategori="'.$t.'"';
				}
			}
			$p='('.$p.')';
			$where[]=$p;
		}

		if (isset($_GET['text'])) {
			$text=$_GET['text'];
			switch ($_GET['textmode']) {
				case 'alle' : $fields='Rige,Rige_dk,Raekke,Raekke_dk,Underraekke,Underraekke_dk,
Overklasse,Overklasse_dk,Klasse,Klasse_dk,Underklasse,Underklasse_dk,Infraklasse,Infraklasse_dk,Overorden,Overorden_dk,
Orden,Orden_dk,Underorden,Underorden_dk,Infraorden,Infraorden_dk,Overfamilie,Overfamilie_dk,Familie,Familie_dk,Underfamilie,
Underfamilie_dk,Tribus,Tribus_dk,Slaegt,Slaegt_dk,Videnskabeligt_navn,Dansk_navn,Synonymer,Synonymer_dk,Artsgruppe, 
Artsgruppe_dk'; break;

				case 'referencer' : $fields='Referencenavn,Reference_aar,Referencetekst,Noter_systematik'; break;

				//case 'arter' : $fields='Artsgruppe,Artsgruppe_dk,Videnskabeligt_navn,Dansk_navn,Synonymer,Synonymer_dk'; break;
				case 'arter' : $fields='Videnskabeligt_navn,Dansk_navn,Synonymer,Synonymer_dk'; break;

				case 'allefelter': $fields='Artsgruppe,Artsgruppe_dk,Videnskabeligt_navn,Autor,Dansk_navn,Taxonkategori,
Taxonstatus,Sortering,Rige,Rige_dk,Raekke,Raekke_dk,Underraekke,Underraekke_dk,Overklasse,Overklasse_dk,Klasse,Klasse_dk,Underklasse,
Underklasse_dk,Infraklasse,Infraklasse_dk,Overorden,Overorden_dk,Orden,Orden_dk,Underorden,Underorden_dk,Infraorden,Infraorden_dk,
Overfamilie,Overfamilie_dk,Familie,Familie_dk,Underfamilie,Underfamilie_dk,Tribus,Tribus_dk,Slaegt,Slaegt_dk,Synonymer,Synonymer_dk,
Referencenavn,Reference_aar,Referencetekst,Dansk,Noter_systematik,Noter_oekologi,Noter_status,Dato,Aendringsdato,Den_danske_roedliste,
Fredede_arter,Habitatdirektivet,Fuglebeskyttelsesdirektivet,Bern_konventionen,Bonn_konventionen,CITES,Oevrige,NOBANIS_arter,
NOBANIS_herkomst,NOBANIS_etableringsstatus,NOBANIS_invasiv_optraeden'; break;

				default : $fields='Videnskabeligt_navn'; break;
			}
			
			$ff=explode(',',$fields);
			$p='';			
			foreach($ff as $f) {
				if ($p!='') $p.=' or ';
				$p.=$f.' like "%'.$text.'%"';
			}
			$p='('.$p.')';
			$where[]=$p;
		}

		if (isset($_GET['forvl'])) {
			$mode=$_GET['mode'];
			$forvl=explode(' ',$_GET['forvl']);
			$p='';
			foreach ($forvl as $f) {
				if ($f!='') {
					if ($p!='') $p.=' '.$mode.' ';
					$p.='('.$f.'<>"")';
				}
			}
			if ($p!='') $where[]='('.$p.')';
		}

		if (isset($_GET['arter'])) {
			$p='';
			switch($_GET['arter']) {
				case 1 : $p='Dansk="Ja"'; break;
				case 2 : $p='Dansk="Nej"'; break;
				default : break;
			}
			if ($p!='') $where[]=$p;
		}
				
		return $where;
	}		
					
	private function loadData() {
		$fields='ID, Videnskabeligt_navn, Autor, Dansk_navn, Familie, Familie_dk, Orden, Orden_dk, '.
			'Artsgruppe, Artsgruppe_dk, Slaegt_dk, Referencenavn, Reference_aar, Referencetekst, '.
			'Den_danske_roedliste, Fredede_arter, Dansk, Synonymer, Synonymer_dk';

		//random, art of the day
		if (empty($_GET)) {
			$this->promo=new Promo();
			$SQL='select '.$fields.' from allearter where Dansk_navn<>"" and Dansk="Ja" order by rand() limit 1';
		//all records
		} elseif ((isset($_GET['search']) && $_GET['search']=='all')) {
			$SQL='select '.$fields.' from allearter order by Videnskabeligt_navn';// limit 100';
		//actual search
		} else {
			$where=$this->getWhere();
			$t='where 1=1 ';
			foreach($where as $w) {
				$t.=' and '.$w;
			}
			$SQL='select '.$fields.' from allearter '.$t.' order by Videnskabeligt_navn';
		}

		$this->fileDebug($SQL);

		mysql_set_charset('utf8');
		$result=$this->query($SQL);
		while ($row = mysql_fetch_array($result)) {
			$this->records[]=$row;
		}
	}

	private function populate() {
		echo '<tbody>';

error_reporting(E_ALL);
ini_set('display_errors', '1');

		foreach ($this->records as $record) {
			echo '<tr>';
			echo '<td class="details" artid="'.$record['ID'].'" style="font-size:1.1em;">'.$this->crlf($record['Videnskabeligt_navn']).'</td>';
			echo '<td>'.$this->crlf($record['Autor']).'</td>';
			echo '<td>'.$this->crlf($record['Dansk_navn']).'</td>';
			echo '<td>'.$this->crlf($record['Familie']).'</td>';
			echo '<td>'.$this->crlf($record['Orden']).'</td>';
			echo '<td>'.$this->crlf($record['Artsgruppe_dk']).'</td>';
			//hidden
			echo '<td>'.$this->crlf($record['Familie_dk']).'</td>';
			echo '<td>'.$this->crlf($record['Orden_dk']).'</td>';
			echo '<td>'.$this->crlf($record['Artsgruppe']).'</td>';
			echo '<td>'.$this->crlf($record['Synonymer']).'</td>';
			echo '<td>'.$this->crlf($record['Synonymer_dk']).'</td>';
			echo '<td>'.$this->crlf($record['Referencenavn']).'</td>';
			echo '<td>'.$this->crlf($record['Reference_aar']).'</td>';
			echo '<td>'.$this->crlf($record['Referencetekst']).'</td>';
			echo '<td>'.$this->crlf($record['Den_danske_roedliste']).'</td>';
			echo '<td>'.$this->crlf($record['Fredede_arter']).'</td>';
			echo '<td>'.$this->crlf($record['Dansk']).'</td>';
			echo '</tr>'."\n";
		}
		echo '</tbody></table>';
	}

	private function fileDebug($text) {
		$file = "debug.txt";
		$fh = fopen($file, 'a') or die("can't open file");
		fwrite($fh, 'search : '.$text."\n");
		fclose($fh);
	}

}

$search = new Search();

/*
ID
Artsgruppe
Artsgruppe_dk
Videnskabeligt_navn
Autor
Dansk_navn
Taxonkategori
Taxonstatus
Sortering
Rige
Rige_dk
Raekke
Raekke_dk
Underraekke
Underraekke_dk
Overklasse
Overklasse_dk
Klasse
Klasse_dk
Underklasse
Underklasse_dk
Infraklasse
Infraklasse_dk
Overorden
Overorden_dk
Orden
Orden_dk
Underorden
Underorden_dk
Infraorden
Infraorden_dk
Overfamilie
Overfamilie_dk
Familie
Familie_dk
Underfamilie
Underfamilie_dk
Tribus
Tribus_dk
Slaegt
Slaegt_dk
Synonymer
Synonymer_dk
Referencenavn
Reference_aar
Referencetekst
Dansk
Noter_systematik
Noter_oekologi
Noter_status
Dato
Aendringsdato
Den_danske_roedliste
Fredede_arter
Habitatdirektivet
Fuglebeskyttelsesdirektivet
Bern_konventionen
Bonn_konventionen
CITES
Oevrige
NOBANIS_arter
NOBANIS_herkomst
NOBANIS_etableringsstatus
NOBANIS_invasiv_optraeden
dato_day
dato_month
dato_year
*/
?>
