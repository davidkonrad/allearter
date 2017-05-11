<?

include('Db.php');

class Extract extends Db {

	public function __construct() {
		parent::__construct();

		$SQL='select * from taxon_image';
		$result=$this->query($SQL);

		while($row = mysql_fetch_array($result)) {
			echo 'insert ignore into taxon_image (taxon, url) values('.
				$this->q($row['taxon']).
				$this->q($row['url'], false).');'."<br><br>";
		}
	}
}

$extract = new Extract();

?>

