<?
error_reporting(E_ALL);
ini_set('display_errors', '1');

//query should be name of artsgruppe
class Referencer extends Base {

	public function __construct($query) {
		parent::__construct();
		if ($query=='') {
			$this->getError('Søgetekst ikke defineret');
		} else {
			$this->run($query);
		}
	}

	protected function run($query) {
		$SQL='select distinct Referencetekst, Referencenavn, Reference_aar from allearter where Artsgruppe="'.$query.'" order by Reference_aar';
		$this->fileDebug($SQL);
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

}

?>
