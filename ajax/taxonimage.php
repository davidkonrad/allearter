<?
header('Content-type: application/text');

include('../common/Db.php');

class Image extends Db {

	public function __construct() {
		parent::__construct();
		if (isset($_GET['action'])) switch ($_GET['action']) {
			case 'get' : echo $this->get(); break;
			case 'put' : $this->put(); break;
			default : return;
		}
	}

	/*
	private function get() {
		$taxon=(isset($_GET['taxon']))? $_GET['taxon'] :'';
		if ($taxon=='') return;
		$SQL='select url from taxon_image where taxon="'.$taxon.'"';
		$row=$this->getRow($SQL);
		if (($row['url']!='') && ($row['url']!='FAIL')) {
			return $row['url'];
		} else {
			if ($row['url']!='FAIL') $this->createFail();
			return '';
		}
	}
	*/

	private function get() {
		$taxon=(isset($_GET['taxon']))? $_GET['taxon'] :'';
		if ($taxon=='') return;
		$SQL='select * from taxon_image where taxon="'.$taxon.'"';
		if ($this->hasData($SQL)) {
			$row = $row=$this->getRow($SQL);
			if ($row['accepted']=='0') return 'EXCLUDED';
			if ($row['url']=='EXCLUDED') return 'EXCLUDED';
			if ($row['url']=='FAIL') return 'FAIL';
			if ($row['url']=='') return 'FAIL';

			//09.12.2015, correct bad eol.org server names, correct contentXX to media
			$url = $row['url'];			
			if (preg_match('/content\d+/', $url, $match)) {
				$url = str_replace($match[0], 'media', $url);
			}
			return $url;
		} else {
			$this->createFail();
			return 'FAIL';
		}
	}

	private function put() {
		$taxon=(isset($_GET['taxon']))? $_GET['taxon'] :'';
		$url=(isset($_GET['url']))? $_GET['url'] :'';
		if ($taxon=='') return;

		$SQL='select * from taxon_image where taxon="'.$taxon.'"';
		if (!$this->hasData($SQL)) {
			$SQL='insert into taxon_image (taxon, url) values("'.$taxon.'", "'.$url.'")';
		} else {
			$SQL='update taxon_image set url="'.$url.'" where taxon="'.$taxon.'"';
		}
		$this->exec($SQL);
	}

	//any taxon get a FAIL-value, if they are not found, 
	//then the administration knows, the image could not be found, 
	//- or something failed
	private function createFail() {
		$taxon=(isset($_GET['taxon']))? $_GET['taxon'] :'FAIL'; //should NEVER become FAIL
		$SQL='insert into taxon_image (taxon, url) values("'.$taxon.'", "FAIL")';
		$this->exec($SQL);
	}
}

$image = new Image()
?>
