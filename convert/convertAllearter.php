<?
include('convertBase.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('memory_limit', '-1');

class ConvertAllearter extends convertBase {

	public function run() {
		//$this->delimiter = ',';
		$this->loadCSV();
	
		/*
		foreach ($this->records as $record) {
			$this->debug($record);
		}
		*/

		//$SQL=$this->getCreateTableSQL('allearter');
		//$this->exec($SQL);
		//echo $SQL;
		//$this->getCreateSQL();		
		$this->insertData();
	}

	protected function getDMY($date) {
		$split = explode('-',$date);
		$result='dato_day='.$split[0].','.
			'dato_month='.$split[1].','.
			'dato_year='.$split[2];
		return $result;
	}

	protected function insertData() {
		$SQL='delete from allearter';
		$this->exec($SQL);

		mysql_set_charset('utf8');
		foreach($this->records as $record) {
			$SQL='insert into allearter set ';
			foreach($this->fieldNames as $field) {
				$SQL.=$field.'='.$this->q($record[$field]);
			}
			$SQL.=$this->getDMY($record['Dato']);
			//$SQL=$this->removeLastChar($SQL);
			$this->debug($SQL);
			$this->exec($SQL);
		}
	}


}

$faroe = new ConvertAllearter('Allearter.dk-csv.csv');
$faroe->run();


?>
