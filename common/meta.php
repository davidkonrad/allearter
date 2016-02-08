<?

include_once('Db.php');

class Meta extends Db {
	private $tail = ' :: allearter.dk';
	private $artsgruppe = null;

	public function __construct() {
		parent::__construct();
		if (isset($_GET['artsgruppe']) || isset($_GET['artsgruppedk'])) {
			//$this->artsgruppe=$this->getArtsgruppe();
			$this->artsgruppe=(isset($_GET['artsgruppe'])) ? $_GET['artsgruppe'] : $_GET['artsgruppedk'];
		}
	}

/*
	private function getArtsgruppe() {
		$field=(isset($_GET['artsgruppedk'])) ? 'Artsgruppe_dk' : 'Artsgruppe';
		$value=(isset($_GET['artsgruppedk'])) ? $_GET['artsgruppedk'] : $_GET['artsgruppe'];
		$SQL='select * from allearter where '.$field.'="'.mysql_real_escape_string($value).'" order by rand() limit 5';
		echo $SQL;
		mysql_set_charset('utf8');
		$result=$this->query($SQL);
		$artsgruppe=mysql_fetch_array($result);
		//$this->debug($artsgruppe);
		return $artsgruppe;
	}
*/

	public function getTitle() {
		if (isset($_GET['taksonomi'])) {
			return $this->getTaksonomiTitle();

		} elseif (isset($_GET['statistik'])) {
			return 'Statistik'.$this->tail;

		} elseif (isset($_GET['referencer'])) {
			return 'Referencer'.$this->tail;

		} elseif (isset($_GET['artsgruppe-statistik'])) {
			return 'Artsgrupper - statistik'.$this->tail;

		} elseif (isset($_GET['artsgruppedk']) || isset($_GET['artsgruppe'])) {
			return $this->artsgruppe.$this->tail;

		} elseif (isset($_GET['sitemap'])) {
			$sc=($_GET['arter']=='da') ? ' (danske navne)': ' (ikke danske navne)';
			return 'Indholdsfortegnelse '.strtoupper($_GET['index']).$sc.$this->tail;

		} else {
			return 'Allearter.dk - Søgning';
		}
	}

	public function getMetaDesc() {
		if (isset($_GET['taksonomi'])) {
			return $this->getTaksonomiDesc();

		} elseif (isset($_GET['statistik'])) {
			return "Nøgletal for allearter.dk's kortlægning af Danmarks dyr, planter, svampe etc, ".
			"fordelt på artsgrupper, riger, rækker, ordner, familier, slægter mm";

		} elseif (isset($_GET['referencer'])) {
			return "Overblik over referencer benyttet ifm. udarbejdelsen af allearter.dk's database. ".
			"Referencerne dækker over såvel publicerede som upublicerede tekster, hjemmesider, regneark, personlige meddelelser m.v.";

		} elseif (isset($_GET['artsgruppe-statistik'])) {
			return 'Overblik over artsgrupperne. Statistik for hhv. '.
			'arter, underarter, former, hybrider samt ikke accepterede arter for hver artsgruppe.';

		} elseif (isset($_GET['sitemap'])) {
			$cnt = ($_GET['arter']=='da') ? 'arter med danske navne' : 'arter uden et dansk navn';
			$cnt.=' der starter med bogstavet '.strtoupper($_GET['index']);
			return 'Komplet indholdsfortegnelse over alle danske arter. Denne side indeholder '.$cnt;
			
		} else {
			return $this->getDesc();
		}
	}

	private function getTaksonomiTitle() {
		$tax = $_GET['taksonomi'];
		$tax=str_replace('+',' ',$tax);
		mysql_set_charset('utf8');
		$SQL='select Videnskabeligt_navn, Dansk_navn from allearter where Videnskabeligt_navn="'.$tax.'"';
		$row=$this->getRow($SQL);
		
		$html='';
		if ($row['Dansk_navn']!='') {
			$html.=$row['Dansk_navn'].' - '.$row['Videnskabeligt_navn'];
		} else {
			$html.=$row['Videnskabeligt_navn'];
		}
		$html.=$this->tail;
		return $html;
	}

	private function klasMeta($klas, $arr=true) {
		if ($klas!='') {
			if ($arr) return $klas.' > ';
			return $klas;
		}
		return '';
	}

	private function getTaksonomiDesc() {
		$tax = $_GET['taksonomi'];
		$tax=str_replace('+',' ',$tax);
		mysql_set_charset('utf8');
		$SQL='select Videnskabeligt_navn, Dansk_navn, Synonymer, Synonymer_dk, Autor, '.
			'Rige_dk, Raekke_dk, Klasse_dk, Orden_dk, Familie_dk, Slaegt_dk, '.
			'Referencetekst from allearter where Videnskabeligt_navn="'.$tax.'"';
		$row=$this->getRow($SQL);
		
		$html='';
		if ($row['Dansk_navn']!='') {
			$html.=$row['Dansk_navn'].' ('.$row['Videnskabeligt_navn'].'). ';
		} else {
			$html.=$row['Videnskabeligt_navn'];
		}

		if ($row['Synonymer_dk']!='') {
			$html.=$row['Synonymer_dk'].'. ';
		}
		if ($row['Synonymer']!='') {
			$html.=$row['Synonymer'].'. ';
		}

		if ($row['Autor']!='') $html.=$row['Autor'].'. ';

		$html.=$this->klasMeta($row['Rige_dk']);
		$html.=$this->klasMeta($row['Raekke_dk']);
		$html.=$this->klasMeta($row['Klasse_dk']);
		$html.=$this->klasMeta($row['Orden_dk']);
		$html.=$this->klasMeta($row['Familie_dk']);
		$html.=$this->klasMeta($row['Slaegt_dk'], false).'. ';
		$html.=$row['Referencetekst'];

		return $html;
	}

	private function getDesc() {
		return 'Søg i alle danske arter. Allearter.dk er et videnskabeligt projekt hvis formål '.
			'er at udarbejde en samlet oversigt over Danmarks dyr, planter, svampe m.v. '.
			'samt deres systematiske tilhørsforhold';
	}


}
