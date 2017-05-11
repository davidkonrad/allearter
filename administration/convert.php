<?
include('../convert/convertBase.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '600');

class ConvertAllearter extends convertBase {
	private $action;
	private $COLSTR;

	public function __construct($file, $action) {
		parent::__construct($file);

		$this->COLSTR=
'ID,Artsgruppe,Artsgruppe_dk,Videnskabeligt_navn,Autor,Dansk_navn,Taxonkategori,Taxonstatus,Sortering,'.
'Rige,Rige_dk,Raekke,Raekke_dk,Underraekke,Underraekke_dk,Overklasse,Overklasse_dk,Klasse,Klasse_dk,Underklasse,Underklasse_dk,'.
'Infraklasse,Infraklasse_dk,Overorden,Overorden_dk,Orden,Orden_dk,Underorden,Underorden_dk,Infraorden,Infraorden_dk,Overfamilie,'.
'Overfamilie_dk,Familie,Familie_dk,Underfamilie,Underfamilie_dk,Tribus,Tribus_dk,Slaegt,Slaegt_dk,Synonymer,Synonymer_dk,'.
'Referencenavn,Reference_aar,Referencetekst,Dansk,Noter_systematik,Noter_oekologi,Noter_nye_arter,Dato,Aendringsdato,'.
'Den_danske_roedliste,Fredede_arter,Habitatdirektivet,Fuglebeskyttelsesdirektivet,Bern_konventionen,Bonn_konventionen,CITES,'.
'Oevrige,NOBANIS_arter,NOBANIS_herkomst,NOBANIS_etableringsstatus,NOBANIS_invasiv_optraeden,Licens';


		$this->delimiter='';
		$this->detectDelimiter();

		switch ($action) { 
			case 'test' : $this->test(); break;
			//case 'convert' : $this->convert(); break;
			case 'convert' : $this->newConvert(); break;
			default : break;
		}
	}

	protected function detectDelimiter() {
		if (($handle = fopen($this->CSVFile, "r")) !== false) {
			$line = fgets($handle); //1000
			fclose($handle);
		}
		echo $line;
		if (strpos($line, ',')!==false) {
			$this->delimiter=',';
		}
		if (strpos($line, ',')!==false) {
			$this->delimiter=',';
		}
		if (strpos($line, ';')!==false) {
			$this->delimiter=';';
		}
		echo 'delimiter er : '.$this->delimiter;
	}

	protected function convert() {
		//$this->delimiter = ',';
		$this->loadCSV();
		$this->insertData();
	}

	protected function test() {
		$html='';
		$err= array();
		//$this->delimiter = ',';
		$this->loadCSV(true);
		
		if (!is_array($this->fieldNames)) {
			echo $this->CSVFile.' kan ikke genkendes som en CSV-fil ...';
			exit();
		}
			
		$cols=explode(',',$this->COLSTR);

		/*
		$html='xxxx<br>';
		foreach($this->fieldNames as $field) {
			echo '_'.$field.'_<br>';
		}
		*/

		foreach ($cols as $col) {
			if (in_array($col, $this->fieldNames, true)) {
				$html.='<br>'.$col.' ...OK!';
			} else {
				$html.='<br>'.$col.' FEJL!!';
				$err[]=$col;
			}
		}
		echo $html;
		if (count($err)>0) {
			echo '<br>------------------------------<br>';
			echo 'Der var problemer med følgende kolonner :';
			echo '<br>------------------------------<br>';
			foreach($err as $e) {
				echo $e.'<br>';
			}
		} else {
			echo '<br>------------------------------<br>';
			echo $this->CSVFile.' ser ud til at kunne blive importeret<br>';
			//echo 'Filen indeholder '.count($this->records).' rækker';
			echo '<br>------------------------------<br>';
		}
	}

	protected function getDMY($date) {
		$split = explode('-',$date);
		$result='dato_day='.$split[0].','.
			'dato_month='.$split[1].','.
			'dato_year='.$split[2];
		return $result;
	}

	protected function insertData() {
		//$SQL='delete from allearter';
		//truncate also reset autoincrement
		$SQL='truncate table allearter';
		$this->exec($SQL);

		$cols=explode(',',$this->COLSTR);
		$count=0;

		mysql_set_charset('utf8');
		//mysql_set_charset('Latin1');
		foreach($this->records as $record) {
			$SQL='insert into allearter set ';

			foreach($cols as $col) {
				$SQL.=$col.'='.$this->q($record[$col]);
			}
			/*	
			foreach($this->fieldNames as $field) {
				$SQL.=$field.'='.$this->q($record[$field]);
			}
			*/
			//$SQL.=$this->getDMY($record['Dato']);
			$SQL=$this->removeLastChar($SQL);
			//$this->debug($SQL);
			//echo $SQL.'<br>';
			$this->exec($SQL);
			$count++;
			//if ($count>10) return;
		}
		$SQL='select count(*) from allearter';
		$row=$this->getRow($SQL);
		echo '<br>---------------------------<br>';
		echo 'Indhold i allearter slettet<br>';
		echo $count.' rækker indsat<br>';
		echo $row['count(*)'].' faktiske rækker i tabel<br>';
		echo '<br>---------------------------<br>';
	}

	/*** 09.04.2013 ***/
	protected function insertRow($array) {
		$cols=explode(',',$this->COLSTR);
		$count=0;

		mysql_set_charset('utf8');
		$SQL='insert into allearter set ';

		foreach($cols as $col) {
			$SQL.=$col.'='.$this->q($array[$col]);
		}
		$SQL=$this->removeLastChar($SQL);
		$this->exec($SQL);
	}

	protected function newConvert() {
		$SQL='truncate table allearter';
		$this->exec($SQL);
		$count=0;
		if (($handle = fopen($this->CSVFile, "r")) !== false) {
			$this->fieldNames = fgetcsv($handle, 0, $this->delimiter);
			while (($record = fgetcsv($handle, 0, $this->delimiter)) !== false) {
				$count++;
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
				//$this->records[]=$array;
				$this->insertRow($array);
			}
			fclose($handle);
		}
		$SQL='select count(*) from allearter';
		$row=$this->getRow($SQL);

		echo '<br>---------------------------<br>';
		echo 'Indhold i allearter slettet<br>';
		echo $count.' rækker indsat<br>';
		echo $row['count(*)'].' faktiske rækker i tabel<br>';
		echo '<br>---------------------------<br>';
	}


}

if (!isset($_GET['file'])) {
	exit('CSV-fil mangler ...');
} 
if (!isset($_GET['action'])) {
	exit('Handling ikke specificeret ...');
} 

$file='upload/'.$_GET['file'];
$action=$_GET['action'];

$convert = new ConvertAllearter($file, $action);

?>
