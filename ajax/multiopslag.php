<?
header('Content-type: application/json');

include('../common/Db.php');

class load extends Db {
	private $param;
	private $ex;

	public function __construct() {
		parent::__construct();
		$this->param=$_GET['opslag'];
		$this->ex=($_GET['lang']=='da') ? '_dk' : '';

		mysql_set_charset('utf8');

		$return='';
		
		$json=$this->lookUp('Rige');
		if ($json!='') {
			if ($return!='') $return.=',';
			$return.=$json;
		}
		$json=$this->lookUp('Raekke');
		if ($json!='') {
			if ($return!='') $return.=',';
			$return.=$json;
		}
		$json=$this->lookUp('Klasse');
		if ($json!='') {
			if ($return!='') $return.=',';
			$return.=$json;
		}
		$json=$this->lookUp('Orden');
		if ($json!='') {
			if ($return!='') $return.=',';
			$return.=$json;
		}
		$json=$this->lookUp('Familie');
		if ($json!='') {
			if ($return!='') $return.=',';
			$return.=$json;
		}
		$json=$this->lookUp('Slaegt');
		if ($json!='') {
			if ($return!='') $return.=',';
			$return.=$json;
		}

		//nye 13.08.2012
		$json=$this->lookUp('Underraekke');
		if ($json!='') {
			if ($return!='') $return.=',';
			$return.=$json;
		}
		$json=$this->lookUp('Infraklasse');
		if ($json!='') {
			if ($return!='') $return.=',';
			$return.=$json;
		}
		$json=$this->lookUp('Underklasse');
		if ($json!='') {
			if ($return!='') $return.=',';
			$return.=$json;
		}
		$json=$this->lookUp('Overklasse');
		if ($json!='') {
			if ($return!='') $return.=',';
			$return.=$json;
		}
		$json=$this->lookUp('Infraorden');
		if ($json!='') {
			if ($return!='') $return.=',';
			$return.=$json;
		}
		$json=$this->lookUp('Underorden');
		if ($json!='') {
			if ($return!='') $return.=',';
			$return.=$json;
		}
		$json=$this->lookUp('Overorden');
		if ($json!='') {
			if ($return!='') $return.=',';
			$return.=$json;
		}
		$json=$this->lookUp('Underfamilie');
		if ($json!='') {
			if ($return!='') $return.=',';
			$return.=$json;
		}
		$json=$this->lookUp('Overfamilie');
		if ($json!='') {
			if ($return!='') $return.=',';
			$return.=$json;
		}
		$json=$this->lookUp('Tribus');
		if ($json!='') {
			if ($return!='') $return.=',';
			$return.=$json;
		}

		$return='['.$return.']';

		echo $return;
	}

	private function lookUp($field) {
		$SQL='select distinct '.$field.$this->ex.' from allearter where '.$field.$this->ex.' like "'.$this->param.'%" order by '.$field.$this->ex.' asc';
		$result=$this->query($SQL);
		$return='';

		$label=$field;
		if ($field=='Raekke') $label='Række';
		if ($field=='Slaegt') $label='Slægt';

		while($row = mysql_fetch_array($result)) {
			if ($return!='') $return.=',';
			$return.='{"label" : "'.$label.': '.$this->crlf($row[$field.$this->ex]).'", "taxon": "'.$label.': '.$this->crlf($row[$field.$this->ex]).'"}';
		}
		return $return;
	}

}

$load = new load();

?>
