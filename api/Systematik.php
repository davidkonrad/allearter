<?
error_reporting(E_ALL);
ini_set('display_errors', '1');

//query is a artsgruppe
class Systematik extends Base {

	public function __construct($query) {
		parent::__construct();
		if ($query=='') {
			$this->getError('Søgetekst ikke defineret');
		} else {
			$this->run($query);
		}
	}

	protected function run($query) {
	}

}

?>
