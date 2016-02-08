<?
error_reporting(E_ALL);
ini_set('display_errors', '1');

class ArtsgruppeAntal extends Base {

	public function __construct($query) {
		parent::__construct();
		if ($query=='') {
			$this->getError('Søgetekst ikke defineret');
		} else {
			$this->run($query);
		}
	}

	private function run($query) {
		$SQL='select count(*) as antal from allearter where '.
			'Artsgruppe="'.$query.'"';
		$result=$this->query($SQL);
		$row=mysql_fetch_assoc($result);
		$JSON=$this->rowToJSON($row);
		$JSON='{ "allearter" : ['.$JSON.'] }';
		echo $JSON;
	}
}

?>
