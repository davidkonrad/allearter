<?
error_reporting(E_ALL);
ini_set('display_errors', '1');

class Arter extends Base {

	public function __construct($query) {
		parent::__construct();

		if ($query=='') {
			$this->getError('Søgetekst ikke defineret');
		}

		$type = isset($_GET['type']) ? $_GET['type'] : false;
		switch($type) {
			case 'dk' :
				$this->run_Dk($query);
				break;
			case 'lat' :
				$this->run_Vn($query);
				break;
			default :
				$this->run($query);
				break;
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
			$JSON.=$this->rowToJSON($row);
		}
		$JSON='{ "allearter" : ['.$JSON.'] }';
		echo $JSON;
	}

	protected function run_Dk($query) {
		$SQL='select distinct Videnskabeligt_navn, Dansk_navn from allearter '.
			'where Dansk_navn like "%'.$query.'%" and Dansk="Ja"';

		mysql_set_charset('utf8');
		$result=$this->query($SQL);
		$json=[];
		while ($row = mysql_fetch_assoc($result)) {
			$json[] = $row;
		}
		$json = array('allearter' => $json);
		echo json_encode($json);
	}

	protected function run_Vn($query) {
		$SQL='select distinct Videnskabeligt_navn, Dansk_navn from allearter '.
			'where Videnskabeligt_navn like "%'.$query.'%" and Dansk="Ja"';

		mysql_set_charset('utf8');
		$result=$this->query($SQL);
		$json=[];
		while ($row = mysql_fetch_assoc($result)) {
			$json[] = $row;
		}
		$json = array('allearter' => $json);
		echo json_encode($json);
	}

}

?>
