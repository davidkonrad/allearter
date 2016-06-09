<?
error_reporting(E_ALL);
ini_set('display_errors', '1');

class Arter extends Base {

	public function __construct($query) {
		parent::__construct();
		if ($query=='') {
			$this->getError('Søgetekst ikke defineret');
		} else {
			$this->run($query);
		}
	}

	protected function run($query) {
		$SQL='select distinct Videnskabeligt_navn, Dansk_navn from allearter '.
			'where Videnskabeligt_navn like "%'.$query.'%" '.
			'or Dansk_navn like "%'.$query.'%" '.
			'and Dansk="Ja"';

		mysql_set_charset('utf8');

		$result=$this->query($SQL);
		
		$JSON='';
		while ($row = mysql_fetch_assoc($result)) {
			if ($JSON!='') $JSON.=', ';
			//$JSON.='"'.$row['Videnskabeligt_navn'].'"';
			$JSON.=$this->rowToJSON($row);
		}
		$JSON='{ "allearter" : ['.$JSON.'] }';
		echo $JSON;
	}
}

?>
