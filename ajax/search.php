<?
//debug
error_reporting(E_ALL);
ini_set('display_errors', '1');

//when called via ajax
if (!class_exists('Db')) {
	require_once('../common/Db.php');
}
include('promo.php');

//we are using a lot of memory
ini_set('memory_limit', '-1');

class Search extends Db {
	private $records = array();
	private $promo = null;
	private $artsgruppeLinks;

	public function __construct() {
		parent::__construct();

		if (!isset($_GET['all'])) {
			$file = file_exists('json/artsgruppe-links.json') 
				? 'json/artsgruppe-links.json'
				: '../json/artsgruppe-links.json';

			$JSON = file_get_contents($file);
			$this->artsgruppeLinks = json_decode($JSON);

			$this->loadData();
			$this->drawHeader();
			$this->populate();
		} else {
			$filename=$_GET['filename'];
			header('Content-type: application/text');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			$this->generateCSV();
		}
	}

	function getArtsgruppeLink($artsgruppe_dk) {
		foreach($this->artsgruppeLinks as $artsgruppe) {
			if (trim($artsgruppe->dk)==$artsgruppe_dk) {
				return $artsgruppe->url;
			}
		}
	}

	private function generateCSV() {
		//get the preformatted arrays FIELD_ARRAY and NAME_ARRAY
		include('fieldNames.php');

		$separator=$_GET['separator'];
		$column=$_GET['column'];

		$where=$this->getWhere($_GET);
		$t='where 1=1 ';
		foreach($where as $w) {
			$t.=' and '.$w;
		}
		$SQL='select * from allearter '.$t.' order by '.$column;//Videnskabeligt_navn';

		mysql_set_charset('utf8');
		$result=$this->query($SQL);

		$line='';
		foreach ($NAME_ARRAY as $NAME) {
			$line.='"'.$NAME.'"'.$separator;
		}
		$line=$this->removeLastChar($line);
		echo $line."\n";

		while ($row = mysql_fetch_array($result)) {
			$line='';
			foreach($FIELD_ARRAY as $FIELD) {
				$line.='"'.$row[$FIELD].'"'.$separator;
			}
			$line=$this->removeLastChar($line);
			echo $line."\n";
		}
	}

/*
	private function drawPromo() {
		echo '<div class="promo">';
		echo '<div class="content">';
		echo $this->promo->row['content'];
		echo '</div>';
		echo '<div class="image" style="overflow:hidden;">';
		echo '<img id="promo-image" src="'.$this->promo->row['image_url'].'" style="width:340px;overflow:visible;"/>';
		echo '</div>';
		echo '<div class="teaser"><p style="line-height:13px;">'.$this->promo->teasers.'</p></div>';
		echo '</div>';
	}
*/

	private function drawPromo() {
		echo '<div class="promo">';
		//remove tags, insert linebreak after header
		$content = strip_tags($this->promo->row['content'], '<strong>');
		$content = str_replace('</strong>', '</strong><br>', $content);		
		echo $content;
		echo '</div>';
	}

	private function drawHeader() {
		//echo '<br/>';
		echo '<div style="float:left;padding-left:20px;">';
		if ($this->promo==null) {
			if (empty($_GET)) {
				echo '<h2>Antal poster i allearter.dk databasen : <span id="recordcount"></span></h2>';
			/*
			sunsetting taksonomi, will use taxon in the future
			*/			
			} elseif (isset($_GET['taksonomi']) || isset($_GET['taxon'])) {
				if ($this->records[0]['Dansk_navn']!='') {
					echo '<h1>'.$this->records[0]['Dansk_navn'].'</h1>';
					echo '<h2><i>'.$this->records[0]['Videnskabeligt_navn'].'</i></h2>';
				} else {
					echo '<h1><i>'.$this->records[0]['Videnskabeligt_navn'].'</i></h1>';
				}
				echo '<br><br>';
			} else {
				//echo '<h2>Søgning, antal fundne poster i allearter.dk databasen : <span id="recordcount"></span></h2>';
				echo '<div style="height:45px;clear:noth;float;leaft;"></div>';
				if (count($this->records)!=1) {
					echo '<h2><span id="recordcount"></span> poster fundet i allearter.dk databasen.</h2>';
				} else {
					echo '<h2><span id="recordcount"></span> post fundet i allearter.dk databasen.</h2>';
				}
			}
			if (!isset($_GET['taksonomi']) && !isset($_GET['taxon'])) {
?>
<span style="padding-left:20px;margin-top:5px;margin-bottom:20px;clear:both;font-size:13px;">
<p>
Klik på kolonnehovederne for ønsket sortering. 
Træk og flyt kolonnehovederne for ønsket rækkefølge og bredde. <br/>
Klik på "Vis kolonner" for markering af de kolonner, der ønskes vist.
</p><br>
</span>
<?
			}
		} else {
			$this->drawPromo();
		}
?>
</div>
<table id="search-result" style="width:100%;overflow:visible;">
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

	private function test($GET, $param, $field, $like=false) {
		if (isset($GET[$param])) {
			if ($GET[$param]!='') {
				if ($like) {
					return $field.' like "%'.$GET[$param].'%"';
				} else {
					return $field.'="'.$GET[$param].'"';
					//return $field.'="'.urldecode($GET[$param]).'"';
				}
			}
		}
		return false;
	}

	private function getWhere($GET) {
		if (isset($GET['lang'])) {
			$ex=($GET['lang']=='da') ? '_dk' : '';
		} else {
			$ex='';
		}
		$where=array();

		$p=$this->test($GET, 'videnskabeligt_navn','Videnskabeligt_navn');
		if ($p) $where[]=$p;

		$p=$this->test($GET, 'artsgruppedk','Artsgruppe_dk');
		if ($p) $where[]=$p;

		$p=$this->test($GET, 'artsgruppe','Artsgruppe');
		if ($p) $where[]=$p;

		$p=$this->test($GET, 'rige','Rige'.$ex);
		if ($p) $where[]=$p;
				
		$p=$this->test($GET, 'raekke','Raekke'.$ex);
		if ($p) $where[]=$p;

		$p=$this->test($GET, 'klasse','Klasse'.$ex);
		if ($p) $where[]=$p;

		$p=$this->test($GET, 'orden','Orden'.$ex);
		if ($p) $where[]=$p;

		$p=$this->test($GET, 'familie','Familie'.$ex);
		if ($p) $where[]=$p;

		$p=$this->test($GET, 'slaegt','Slaegt'.$ex);
		if ($p) $where[]=$p;

		if (isset($GET['extra'])) {
			if ($GET['extra_value']!='') {
				$p=ucfirst($GET['extra']).$ex.'="'.$GET['extra_value'].'"';
				$where[]=$p;
			}
		}	

		if (isset($GET['taxon_kategori'])) {
			$tax=explode(' ',$GET['taxon_kategori']);
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

		if (isset($GET['text'])) {
			$text=$GET['text'];
			switch ($GET['textmode']) {
				case 'alle' : $fields='Rige,Rige_dk,Raekke,Raekke_dk,Underraekke,Underraekke_dk,
Overklasse,Overklasse_dk,Klasse,Klasse_dk,Underklasse,Underklasse_dk,Infraklasse,Infraklasse_dk,Overorden,Overorden_dk,
Orden,Orden_dk,Underorden,Underorden_dk,Infraorden,Infraorden_dk,Overfamilie,Overfamilie_dk,Familie,Familie_dk,Underfamilie,
Underfamilie_dk,Tribus,Tribus_dk,Slaegt,Slaegt_dk,Videnskabeligt_navn,Dansk_navn,Synonymer,Synonymer_dk,Artsgruppe, 
Artsgruppe_dk'; break;

				case 'referencer' : $fields='Referencenavn,Reference_aar,Referencetekst,Noter_systematik'; break;

				case 'arter' : $fields='Videnskabeligt_navn,Dansk_navn,Synonymer,Synonymer_dk'; break;

				case 'allefelter': $fields='Artsgruppe,Artsgruppe_dk,Videnskabeligt_navn,Autor,Dansk_navn,Taxonkategori,
Taxonstatus,Sortering,Rige,Rige_dk,Raekke,Raekke_dk,Underraekke,Underraekke_dk,Overklasse,Overklasse_dk,Klasse,Klasse_dk,Underklasse,
Underklasse_dk,Infraklasse,Infraklasse_dk,Overorden,Overorden_dk,Orden,Orden_dk,Underorden,Underorden_dk,Infraorden,Infraorden_dk,
Overfamilie,Overfamilie_dk,Familie,Familie_dk,Underfamilie,Underfamilie_dk,Tribus,Tribus_dk,Slaegt,Slaegt_dk,Synonymer,Synonymer_dk,
Referencenavn,Reference_aar,Referencetekst,Dansk,Noter_systematik,Noter_oekologi,Noter_nye_arter,Dato,Aendringsdato,Den_danske_roedliste,
Fredede_arter,Habitatdirektivet,Fuglebeskyttelsesdirektivet,Bern_konventionen,Bonn_konventionen,CITES,Oevrige,NOBANIS_arter,
NOBANIS_herkomst,NOBANIS_etableringsstatus,NOBANIS_invasiv_optraeden'; break;

				default : $fields='Videnskabeligt_navn'; break;
			}
			
			$ff=explode(',',$fields);
			$p='';			
			foreach($ff as $f) {
				if ($p!='') $p.=' or ';
				$p.=$f.' like "%'.$text.'%"';
				//$p.=' soundex ('.$f.') like soundex("'.$text.'")';
			}
			$p='('.$p.')';
			$where[]=$p;
		}

		if (isset($GET['forvl'])) {
			$mode=$GET['mode'];
			$forvl=explode(' ',$GET['forvl']);
			$p='';
			foreach ($forvl as $f) {
				if ($f!='') {
					if ($p!='') $p.=' '.$mode.' ';
					$p.='('.$f.'<>"")';
				}
			}
			if ($p!='') $where[]='('.$p.')';
		}

		if (isset($GET['arter'])) {
			$p='';
			switch($GET['arter']) {
				case 1 : $p='Dansk="Ja"'; break;
				case 2 : $p='Dansk="Nej"'; break;
				default : break;
			}
			if ($p!='') $where[]=$p;
		}

		if (isset($GET['nyearter'])) {
			$p='Noter_nye_arter<>""';
			$where[]=$p;
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
			$SQL='select '.$fields.' from allearter where '.$this->promo->row['field_name'].'="'.$this->promo->row['target'].'" order by Videnskabeligt_navn';
		//all records
		} elseif ((isset($_GET['search']) && $_GET['search']=='all')) {
			$SQL='select '.$fields.' from allearter order by Videnskabeligt_navn';
		//direct link
		} elseif (isset($_GET['taksonomi']) || isset($_GET['taxon'])) {
			$tax = isset($_GET['taksonomi'])
				? str_replace('+',' ',$_GET['taksonomi'])
				: str_replace('+',' ',$_GET['taxon']);

			$SQL='select '.$fields.' from allearter where Videnskabeligt_navn="'.$tax.'"';
		//perma link
		} elseif (isset($_GET['perma'])) {
			$permaGET=$this->getPermaGet($_GET['perma']);
			$where=$this->getWhere($permaGET);
			$t='where 1=1 ';
			foreach($where as $w) {
				$t.=' and '.$w;
			}
			$orderby = (isset($permaGET['systematik'])) ? 'Sortering+0 asc' : 'Videnskabeligt_navn';			
			$SQL='select '.$fields.' from allearter '.$t.' order by '.$orderby;//Videnskabeligt_navn';
		//actual search
		} else {
			$orderby = (isset($_GET['systematik'])) ? 'Sortering+0 asc' : 'Videnskabeligt_navn';
			$where=$this->getWhere($_GET);
			$t='where 1=1 ';
			foreach($where as $w) {
				$t.=' and '.$w;
			}
			$SQL='select '.$fields.' from allearter '.$t.' order by '.$orderby;//Videnskabeligt_navn';
		}

		//$this->fileDebug($SQL);

		mysql_set_charset('utf8');
		$result=$this->query($SQL);
		while ($row = mysql_fetch_array($result)) {
			$this->records[]=$row;
		}
	}

	private function getPermaGet($guid) {
		$SQL='select r.param, r.value from userLogRequest r, userLog l '.
			'where l.guid="'.$guid.'" and l.log_id=r.log_id';
	
		//mysql_set_charset('utf8');
		$permaGET=array();
		$result=$this->query($SQL);
		while ($row = mysql_fetch_array($result)) {
			$permaGET[$row['param']]=$row['value'];
		}
		return $permaGET;
	}

	private function populate() {
		echo '<tbody>';

error_reporting(E_ALL);
ini_set('display_errors', '1');

		$count = 0;
		foreach ($this->records as $record) {
			$count++;

			//echo $this->getArtsgruppeLink($record['Artsgruppe_dk']);
			/*
			$artsgruppe_link = ($record['Artsgruppe_dk']!='') ? 
				strtolower($record['Artsgruppe_dk']).'.htm' :
				strtolower($record['Artsgruppe']).'.htm';
			$artsgruppe_link=str_replace('Å','aa',$artsgruppe_link);
			$artsgruppe_link=str_replace('æ','ae',$artsgruppe_link);
			$artsgruppe_link=str_replace('å','aa',$artsgruppe_link);
			$artsgruppe_link=str_replace('ø','oe',$artsgruppe_link);
			$artsgruppe_link=str_replace(' ','-',$artsgruppe_link);
			$artsgruppe_link='<a href="http://allearter.dk/'.$artsgruppe_link.'" target=_blank title="Gå til artsgruppens info-side" style="color:teal;text-decoration:none;">';
			*/
			$title='Læs mere om ';
			$title_dk = $record['Artsgruppe_dk']!='' 
				? $title.$record['Artsgruppe_dk'].' ('.$record['Artsgruppe'].')'
				: $title.$record['Artsgruppe'];

			$title_lat = $record['Artsgruppe_dk']!='' 
				? $title.$record['Artsgruppe'].' ('.$record['Artsgruppe_dk'].')'
				: $title.$record['Artsgruppe'];

			$artsgruppe_link_dk='<a href="'.$this->getArtsgruppeLink($record['Artsgruppe_dk']).'" target=_blank title="'.$title_dk.'" style="text-decoration:none;color:#165AAD;">';
			$artsgruppe_link='<a href="'.$this->getArtsgruppeLink($record['Artsgruppe_dk']).'" target=_blank title="'.$title_lat.'" style="text-decoration:none;color:#165AAD;">';

			echo '<tr>';
			echo '<td class="details" artid="'.$record['ID'].'" style="font-size:1.1em;">'.$this->crlf($record['Videnskabeligt_navn']).'</td>';
			echo '<td>'.$this->crlf($record['Autor']).'</td>';
			echo '<td>'.$this->crlf($record['Dansk_navn']).'</td>';
			echo '<td>'.$this->crlf($record['Familie']).'</td>';
			echo '<td>'.$this->crlf($record['Orden']).'</td>';
			//echo '<td>'.$this->crlf($record['Artsgruppe_dk']).'</td>';
			echo '<td>'.$artsgruppe_link_dk.$this->crlf($record['Artsgruppe_dk']).'</a></td>';
			//hidden
			echo '<td>'.$this->crlf($record['Familie_dk']).'</td>';
			echo '<td>'.$this->crlf($record['Orden_dk']).'</td>';

			//28.02.2014
			echo '<td>'.$artsgruppe_link.$this->crlf($record['Artsgruppe']).'</a></td>';
			//echo '<td>'.$this->getArtsgruppeLink($record['Artsgruppe_dk']).$this->crlf($record['Artsgruppe']).'</a></td>';

			echo '<td>'.$this->crlf($record['Synonymer']).'</td>';
			echo '<td>'.$this->crlf($record['Synonymer_dk']).'</td>';
			echo '<td>'.$this->crlf($record['Referencenavn']).'</td>';
			echo '<td>'.$this->crlf(intval($record['Reference_aar'])).'</td>';
			echo '<td>'.$this->crlf($record['Referencetekst']).'</td>';
			echo '<td>'.$this->crlf($record['Den_danske_roedliste']).'</td>';
			echo '<td>'.$this->crlf($record['Fredede_arter']).'</td>';
			echo '<td>'.$this->crlf($record['Dansk']).'</td>';
			echo '</tr>'."\n";
		}

		//is it af taxonomypage (factsheet for a single species)
		/*
		if (isset($_GET['taksonomi']) || isset($_GET['taxon']) || $count==1) { 
			$_GET['id']=$record['ID'];
			$_GET['lang']='da';
			echo '<tr id="details-preload"><td class="details" colspan="6">';
			include('details.php');
			echo '</td></tr>';
		}
		*/

		echo '</tbody></table>';	
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
Noter_nye_arter
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
