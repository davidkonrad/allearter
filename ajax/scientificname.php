<?
header('Content-type: application/json');

include('../common/Db.php');

class load extends Db {

	public function __construct() {
		parent::__construct();
		$param=$_GET['name_startsWith'];
		if ($param!=' ') {
			$SQL='select distinct Videnskabeligt_navn from allearter '.
				'where Videnskabeligt_navn like "'.$param.'%" order by Videnskabeligt_navn asc';
		} else {
			//$SQL='select distinct Videnskabeligt_navn from allearter order by rand(), Videnskabeligt_navn asc limit 1000';
			$SQL='select * from (select Videnskabeligt_navn from allearter order by rand() limit 300) T1 order by Videnskabeligt_navn asc';
		}
		mysql_set_charset('utf8');
		$result=$this->query($SQL);
		$html='';
		while($row = mysql_fetch_array($result)) {
			if ($html!='') $html.=',';
			$html.='{"id" : "'.$row['Videnskabeligt_navn'].'", "taxon": "'.$row['Videnskabeligt_navn'].'"}';
		}
		$html='['.$html.']';

		echo $html;
	}
}

$load = new load();

/*

[
{ "id": "1", "taxon": "taxerrwer" },
{ "id": "2", "taxon": "aataxerrwer" },
{ "id": "3", "taxon": "bbtaxerrwer" },
{ "id": "4", "taxon": "bbxxtaxerrwer" },
{ "id": "5", "taxon": "aaaaxxxxtaxerrwer" }
]
*/
?>
