<?
include('../common/Db.php');

class Counter extends Db {

	public function __construct() {
		parent::__construct();
		$this->simple();
	}

	private function simple() {
		echo '<span style="font-size:xx-large;font-family:arial;font-weight:bolder;float:left;">';
		$SQL='select count(*) from allearter';
		$row=$this->getRow($SQL);
		$number=number_format($row['count(*)']);
		$number=str_replace(',','.',$number);
		echo $number; 
		echo '</span>';
	}
}

$counter = new Counter();

?>

	

