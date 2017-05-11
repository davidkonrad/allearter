<?
header('Content-type: application/json');

include('../common/Db.php');

class load extends Db {

	public function __construct() {
		parent::__construct();
		$param=$_GET['name_startsWith'];
		mysql_set_charset('utf8');
		if ($param!=' ') {
			$SQL='select distinct Artsgruppe_dk from allearter where Artsgruppe_dk like "'.$param.'%"';
		} else {
			$SQL='select distinct Artsgruppe_dk from allearter order by Artsgruppe_dk';
		}
		$result=$this->query($SQL);
		$html='';
		while($row = mysql_fetch_array($result)) {
			if ($html!='') $html.=',';
			$html.='{"id" : "'.$row['Artsgruppe_dk'].'", "taxon": "'.$row['Artsgruppe_dk'].'"}';
		}
		$html='['.$html.']';

		echo $html;
	}
}

$load = new load();

?>
