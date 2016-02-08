<?
header('Content-type: application/json');

@include_once('../common/Db.php');
@include_once('common/Db.php');

class load extends Db {
	private $master;
	
	public function __construct() {
		parent::__construct();

		//$this->fileDebug(implode(',',$_GET));

		$param=$_GET['name_startsWith'];
		$target=$_GET['target'];
		$ex=($_GET['lang']=='da') ? '_dk' : '';
		switch ($target) {
			case 'klas_rige' : $master='rige';$table='Rige'.$ex; break;
			case 'klas_raekke' : $master='raekke';$table='Raekke'.$ex; break;
			case 'klas_klasse' : $master='klasse';$table='Klasse'.$ex; break;
			case 'klas_familie' : $master='familie';$table='Familie'.$ex; break;
			case 'klas_orden' : $master='orden';$table='Orden'.$ex; break;
			case 'klas_slaegt' : $master='slaegt';$table='Slaegt'.$ex; break;
			default : $master='rige';$table='Rige'.$ex; break;
		}

		$where=' 1=1 ';
		$test=$this->where('rige');
		if ($test!='') $where.=$test;
		$test=$this->where('raekke');
		if ($test!='') $where.=$test;
		$test=$this->where('orden');
		if ($test!='') $where.=$test;
		$test=$this->where('familie');
		if ($test!='') $where.=$test;
		$test=$this->where('klasse');
		if ($test!='') $where.=$test;
		
		if (isset($_GET['extra'])) {
			$where.=' and ('.ucfirst($_GET['extra']).$ex.'="'.$_GET['extra_value'].'") ';
		}			
		//
		//$where='';
		//
		if ($param!=' ') {
			$SQL='select distinct '.$table.' from allearter where '.$table.' like "'.$param.'%" and '.$where.' order by '.$table.' asc';
		} else {
			$SQL='select distinct '.$table.' from allearter where '.$where.' order by '.$table.' asc';
		}

		$this->fileDebug($SQL);

		mysql_set_charset('utf8');
		$result=$this->query($SQL);
		$html='';
		while($row = mysql_fetch_array($result)) {
			if ($html!='') $html.=',';
			//$html.='{"id" : "'.$this->crlf($row[$table]).'", "taxon": "'.$this->crlf($row[$table]).'"}';
			$html.='{"id" : "'.$row[$table].'", "taxon": "'.$row[$table].'"}';
		}
		$html='['.$html.']';

		echo $html;
	}

	private function where($klas) {
		if ($klas==$this->master) return '';
		if (!isset($_GET[$klas])) return '';
		if ($_GET[$klas]=='null') return '';
		if ($_GET[$klas]=='') return '';
		$ex=($_GET['lang']=='da') ? '_dk' : '';
		if ($_GET[$klas]>'') {
			return ' and '.ucfirst($klas).$ex.'="'.$_GET[$klas].'"'; 
		}
		return '';
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
