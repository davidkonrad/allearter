<?
header('Content-type: application/json');

include('../common/Db.php');

class Load extends Db {

	public function __construct() {
		parent::__construct();
		$param=$_GET['name_startsWith'];
		$field=($_GET['target']=='artsgruppedk') ? 'Artsgruppe_dk' : 'Artsgruppe';
		mysql_set_charset('utf8');
		if ($param!=' ') {
			$SQL='select distinct '.$field.' from allearter where '.$field.' like "'.$param.'%" order by '.$field;
		} else {
			$SQL='select distinct '.$field.' from allearter order by '.$field;
		}
		$result=$this->query($SQL);
		$html='';
		while($row = mysql_fetch_array($result)) {
			if ($html!='') $html.=',';
			$html.='{"id" : "'.$row[$field].'", "taxon": "'.$row[$field].'"}';
		}
		$html='['.$html.']';

		echo $html;
	}
}

$load = new Load();

?>
