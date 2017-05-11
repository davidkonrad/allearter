<?
header('Content-type: application/text');

include('../common/Db.php');

class Lookup extends Db {
	private $param;
	private $ex;

	public function __construct() {
		parent::__construct();
		$this->target=$_GET['target'];
		$this->prior=$_GET['prior'];
		$this->value=$_GET['value'];
		$ex=($_GET['lang']=='da') ? '_dk' : '';

		mysql_set_charset('utf8');

		$SQL='select distinct '.$this->target.$ex.' from allearter where '.$this->prior.$ex.'="'.$this->value.'"';
		
		$this->fileDebug($SQL);
		$row=$this->getRow($SQL);
		
		echo $row[$this->target.$ex];
	}
}

$lookup= new Lookup();
?>
