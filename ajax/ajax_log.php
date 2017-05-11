<?
//debug
error_reporting(E_ALL);
ini_set('display_errors', '1');

include('../common/Db.php');

class Log extends Db {
	private $log_id;

	public function __construct() {
		parent::__construct();
		$this->createLog();
		$this->insertRequests();
		//return log_id as response, caller may use it to something ...
		echo $this->log_id;
	}

	private function createLog() {
		$guid=$this->guid();
		$SQL='insert into userLog (_timestamp, guid) values (CURRENT_TIMESTAMP,"'.$guid.'")';
		$this->exec($SQL);
		$this->log_id=mysql_insert_id();
	}

	private function insertRequests() {
		foreach($_GET as $param => $value) {
			if (($value!='') && ($param!='_')) {
				$SQL='insert into userLogRequest (log_id, param, value) values('.
					$this->q($this->log_id).
					$this->q($param).
					$this->q($value, false).
				')';
				$this->exec($SQL);
			}
		}
	}

	//modified http://php.net/manual/en/function.com-create-guid.php
	private function guid(){
		/*
		if (function_exists('com_create_guid')){
			return com_create_guid();
		} else {
			mt_srand((double)microtime()*10000);
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);// "-"
			$uuid = substr($charid, 0, 8).$hyphen
				.substr($charid, 8, 4).$hyphen
				.substr($charid,12, 4).$hyphen
				.substr($charid,16, 4).$hyphen
				.substr($charid,20,8);
			return strtolower($uuid);
		}
		*/
		return uniqid();
	}
}
$log = new Log();

?>
