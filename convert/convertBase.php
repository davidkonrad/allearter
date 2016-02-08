<?

//debug
error_reporting(E_ALL);
ini_set('display_errors', '1');

ini_set('memory_limit',-1);
ini_set("auto_detect_line_endings", true);

include('../common/Db.php');

class convertBase extends Db {
	protected $CSVFile = ''; // 
	protected $delimiter = ';';
	protected $fieldNames = array();
	protected $records = array();
	
	public function __construct($CSVFile) {
		parent::__construct();
		$this->CSVFile=$CSVFile;
	}

/*now in Db
	protected function debug($a) {
		echo '<pre>';
		print_r($a);
		echo '</pre>';
	}
*/

	public function removeLastChar($s) {
		return substr_replace($s ,"", -1);
	}

	protected function loadCSV($fieldNamesOnly=false) {
		if (($handle = fopen($this->CSVFile, "r")) !== false) {
			$this->fieldNames = fgetcsv($handle, 0, $this->delimiter);

			if (!$fieldNamesOnly) {
				while (($record = fgetcsv($handle, 0, $this->delimiter)) !== false) {
					$array = array();
					$index = 0;
					foreach ($this->fieldNames as $fieldName) {
						if (isset($record[$index])) {
							$array[$fieldName] = $record[$index];
						} else {
							$array[$fieldName] = '';
						}
						$index++;
					}
					echo '<pre>';
					print_r($array);
					echo '</pre>';
					$this->records[]=$array;
				}
			}
			fclose($handle);
		}
	}

	//generates a simple 'create table xxx based on field names
	protected function getCreateTableSQL($tablename) {
		$count=0;
		$SQL='create table '.$tablename.'(';
		foreach ($this->fieldNames as $fieldName) {
			$SQL.=$fieldName.' varchar(20)';
			$count++;
			if ($count<count($this->fieldNames)) $SQL.=',';
			$SQL.="\n\n";
		}
		$SQL.=')';
		return $SQL;
	}
}

?>
