<?
error_reporting(E_ALL);
ini_set('display_errors', '1');

class ArtsgruppeArter extends Base {
	private $query;

	public function __construct($query) {
		parent::__construct();
		if ($query=='') {
			$this->getError('Søgetekst ikke defineret');
		} else {
			$this->run($query);
		}
	}

	private function run($query) {
		$SQL='select distinct Videnskabeligt_navn, Dansk_navn from allearter where '.
			'Artsgruppe="'.$query.'" or Artsgruppe_dk="'.$query.'" '.
			'and Dansk="Ja" '.
			'order by Videnskabeligt_navn';

		mysql_set_charset('utf');
		$result=$this->query($SQL);

		$JSON='';
		while ($row = mysql_fetch_assoc($result)) {
			if ($JSON!='') $JSON.=', ';
			$JSON.=$this->rowToJSON($row);
		}
		$JSON='{ "allearter" : ['.$JSON.'] }';
		echo $JSON;
	}

}

?>
