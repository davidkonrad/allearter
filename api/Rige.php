<?
error_reporting(E_ALL);
ini_set('display_errors', '1');

class Rige extends Base {

	public function __construct($query) {
		parent::__construct();
		if ($query=='') {
			$this->getError('Søgetekst ikke defineret');
		} else {
			$this->run($query);
		}
	}

	protected function run($query) {
		$SQL='select distinct Rige, Rige_dk from allearter '.
			'where Videnskabeligt_navn ="'.$query.'" ';

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
