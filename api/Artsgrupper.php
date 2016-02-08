<?
error_reporting(E_ALL);
ini_set('display_errors', '1');

class Artsgrupper extends Base {

	public function __construct($query) {
		parent::__construct();
		$this->run($query);
	}

	private function run($query) {
		if ($query=='') {
			$SQL='select distinct Artsgruppe, Artsgruppe_dk from allearter order by Artsgruppe';
		} else {
			$SQL='select distinct Artsgruppe, Artsgruppe_dk from allearter '.
				'where Artsgruppe like "%'.$query.'%" or Artsgruppe_dk like "%'.$query.'%" '.
				'order by Artsgruppe';
		}

		mysql_set_charset('utf8');
		$result=$this->query($SQL);
		$JSON='';
		while ($row = mysql_fetch_assoc($result)) {
			if (($row['Artsgruppe']!='') || ($row['Artsgruppe_dk']!='')) {
				if ($JSON!='') $JSON.=', ';
				$JSON.=$this->rowToJSON($row);
			}
		}
		$JSON='{ "allearter" : ['.$JSON.'] }';
		echo $JSON;
	}

}

?>
