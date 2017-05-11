<?
include('../common/Db.php');

class Systematik extends Db {

	public function __construct() {
		parent::__construct();
		if (!isset($_GET['a']) && !isset($_GET['ad'])) return '';
		$p = (isset($_GET['a'])) ? 'Artsgruppe="'.$_GET['a'].'"' : 'Artsgruppe_dk="'.$_GET['ad'].'"';

		$SQL='select count(*) from allearter where '.$p.' and Sortering<>""';
		$row=$this->getRow($SQL);
		if ($row['count(*)']>0) {
			echo 'yes';
		} else {
			echo '';
		}
	}
}

$systematik=new Systematik();

?>
