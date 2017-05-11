<?

include('../common/Db.php');

class PermaLink extends Db {

	public function __construct() {
		parent::__construct();

		$log_id=$_GET['log_id'];
		$SQL='select guid from userLog where log_id='.$log_id;
		$row=$this->getRow($SQL);
	
		//should be changed when going to real domain, eg off /bif/	
		/*
		$ip = getHostByName(php_uname('n')); 
		if ($ip=='127.0.1.1') $ip='localhost';
		$permalink=$ip.'/bif/?perma='.$row['guid'];
		*/
		$permalink='allearter-databasen.dk/?perma='.$row['guid'];
		
		echo $permalink;
	}
}

$perma = new PermaLink();

?>
