<?
include('../common/Db.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('memory_limit', '-1');

class ConvertDKnavne extends Db {

	public function run() {
		$this->update('Klasse');
		$this->update('Familie');
		$this->update('Klasse');
		$this->update('Raekke');
		$this->update('Slaegt');
		$this->update('Orden');
	}

	private function update($field) {
		$SQL='update allearter set '.$field.'_dk='.$field.' where '.$field.'_dk=""';
		$this->exec($SQL);
	}

}

$convert=new ConvertDKnavne();
$convert->run();

?>
		

