<?

//when called via ajax
if (!class_exists('Db')) {
	require_once('../common/Db.php');
}

class COF extends Db {

	public function __construct() {
		parent::__construct();
	
		$taxon=(isset($_GET['taxon'])) ? $_GET['taxon'] : '';
		if ($taxon!='') {
			$this->getCOF_URL($taxon);
		}
	}

	protected function getCOF_URL($taxon) {
		$SQL='select url from CatOfLife where taoxn="'.$taxon.'"';
		$row=$this->getRow($SQL);
		if (isset($row['url'])) {
			echo $row['url'];
		} else {
			getByWebservice($taxon);
		}
	}

	protected function getByWebservice($taxon) {
		$url='http://www.catalogueoflife.org/annual-checklist/2012/webservice';
		$url.='?name='.$taxon;
		$url.='&format=php';

		$result=file_get_contents($url);

		echo $result;
	}

}

$cof = new COF();


?>	

