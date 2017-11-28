<?
error_reporting(E_ALL);
ini_set('display_errors', '1');

class Art extends Base {

	public function __construct($query) {
		parent::__construct();
		if ($query=='') {
			$this->getError('Søgetekst ikke defineret');
		} else {
			mysql_set_charset('utf8');
			$this->run($query);
		}
	}

	protected function getImage($query) {
		$SQL='select * from taxon_image where taxon="'.$query.'"';
		$row=$this->getRow($SQL);
		if (in_array($row['url'], array('FAIL','EXCLUDED'))) return '';
 		if ($row['accepted']==0) return '';
		return $row['url'];
	}

	protected function run($query) {
		$SQL='select distinct Artsgruppe, Artsgruppe_dk, Videnskabeligt_navn, Autor, Dansk_navn, '.
			'Rige, Rige_dk, Raekke, Raekke_dk, Underraekke, Underraekke_dk, Overklasse, Overklasse_dk, '.
			'Klasse, Klasse_dk, Underklasse, Underklasse_dk, Infraklasse, Infraklasse_dk, Overorden, '.
			'Overorden_dk, Orden, Orden_dk, Underorden, Underorden_dk, Infraorden, Infraorden_dk, '.
			'Overfamilie, Overfamilie_dk, Familie, Familie_dk, Underfamilie, Underfamilie_dk, Tribus, '.
			'Tribus_dk, Slaegt, Slaegt_dk, Referencenavn, Reference_aar, Referencetekst '.
			'from allearter '.
			'where Videnskabeligt_navn ="'.$query.'"';

		$result=$this->query($SQL);
		$row = mysql_fetch_assoc($result);
		$JSON='';
		$row['Billede']=$this->getImage($row['Videnskabeligt_navn']);
		$JSON.=$this->rowToJSON($row);
		$JSON='{ "allearter" : ['.$JSON.'] }';
		echo $JSON;
	}
}

?>
