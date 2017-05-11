<?
error_reporting(E_ALL);
ini_set('display_errors', '1');

// række, klasse, orden, familie, slægt
// ?get=hierarki&fra=slægt&er=<>&find=familie
// ex :
// ?get=hierarki&fra=orden&er=Lepidoptera&find=slægt
// ?get=hierarki&fra=Familie&er=Salmonidae&find=slægt

class Hierarki extends Base {
	private $from;
	private $is;
	private $find;

	public function __construct() {
		parent::__construct();
		$this->from=$this->getParam('fra');
		$this->is=$this->getParam('er');
		$this->find=$this->getParam('find');

		if ($this->from=='' || $this->is=='' || $this->find=='') {
			$this->getError('Søgetekst ikke defineret');
		} else {
			$this->run();
		}
	}

	protected function nameToField($name, $dk=false) {
		switch ($name) {

			case 'række' : 
				return ($dk) ? 'Raekke' : 'Raekke_dk';
				break;

			case 'klasse' : 
				return ($dk) ? 'Klasse' : 'Klasse_dk';
				break;

			case 'orden' : 
				return ($dk) ? 'Orden' : 'Orden_dk';
				break;

			case 'familie' : 
				return ($dk) ? 'Familie' : 'Familie_dk';
				break;

			case 'slægt' : 
				return ($dk) ? 'Slaegt' : 'Slaegt_dk';
				break;
		
			default :
				$this->abort($name.' er et ugyldigt klasssfikationsnavn');
				break;
		}
	}

	private function run() {
		$SQL='select distinct '.
			$this->nameToField($this->find).','.
			$this->nameToField($this->find, true).' '.
			'from allearter where '.
			$this->nameToField($this->from).'="'.$this->is.'"  or '.
			$this->nameToField($this->from, true).'="'.$this->is.'" '.
			'order by '.$this->nameToField($this->find);
		
		$result=$this->query($SQL);
		$JSON='';
		while ($row = mysql_fetch_assoc($result)) {
			if ($JSON!='') $JSON.=',';
			$JSON.=$this->rowToJSON($row);
		}
		$JSON='{ "allearter" : ['.$JSON.'] }';
		echo $JSON;
	}

}

?>
