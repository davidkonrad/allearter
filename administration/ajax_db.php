<?

include('../common/Db.php');

class Ajax extends Db {

	public function __construct() {
		parent::__construct();

		if (!isset($_GET['action'])) {
			echo 'Programfejl ..';
			return;
		}

		switch ($_GET['action']) {
			case 'backup' : $this->createBackup(); break;
			case 'showtables' : $this->showTables(); break;
			case 'updateDK' : $this->updateDK(); break;
			case 'updateCRLF' : $this->updateCRLF(); break;
			case 'restoreBackup' : $this->restoreBackup(); break;
			default: return;
		}
	}

	private function createBackup() {
		$t=strftime("%F-%H%m");
		$t=str_replace('-','_',$t);
		$t.='_allearter';

		$SQL='drop table '.$t;
		$this->exec($SQL);

		$SQL='create table '.$t.' select * from allearter';
		$this->exec($SQL);

		$SQL='ALTER TABLE '.$t.' ADD PRIMARY KEY(`ID`)';
		$this->exec($SQL);

		echo 'Backup '.$t.' oprettet';
	}

	private function restoreBackup() {
		if (!isset($_GET['table'])) {
			echo 'backup-tabel ikke defineret ...<br>';
			exit();
		}
		echo '<br>---------------------------<br>';
		$SQL='truncate table allearter';
		$this->exec($SQL);
		echo mysql_affected_rows().' poster slettet<br>';
		$SQL='insert into allearter select * from '.$_GET['table'];
		$this->exec($SQL);
		echo mysql_affected_rows().' poster genskabt fra '.$_GET['table'].'<br>';
		echo '<br>---------------------------<br>';
	}	

	private function showTables() {
		$SQL='show tables';
		$result=$this->query($SQL);
		$t=array();
		while ($row = mysql_fetch_row($result)) {
			$table=$row[0];//'Tables_in_bif'];
			if (substr($table,-10)=='_allearter') {
				$t[]=$table;
			}
		}
		$html='';
		$count=1;
		foreach ($t as $table) {
			$html.='<input type="radio" name="table" id="table'.$count.'" value="'.$table.'"/>';
			$html.='<label for="table'.$count.'">'.$table.'</label><br>';
			$count++;
		}
		$html='<fieldset><legend>Eksisterende backups</legend>'.$html.'</fieldset>';
		echo $html;
	}

	private function updateDK() {
		$this->updateDKTable('Rige');
		$this->updateDKTable('Raekke');
		$this->updateDKTable('Underraekke');
		$this->updateDKTable('Overklasse');
		$this->updateDKTable('Klasse');
		$this->updateDKTable('Underklasse');
		$this->updateDKTable('Infraklasse');
		$this->updateDKTable('Overorden');
		$this->updateDKTable('Orden');
		$this->updateDKTable('Underorden');
		$this->updateDKTable('Infraorden');
		$this->updateDKTable('Overfamilie');
		$this->updateDKTable('Familie');
		$this->updateDKTable('Underfamilie');
		$this->updateDKTable('Tribus');
		$this->updateDKTable('Slaegt');
	}

	private function updateDKTable($field) {
		$SQL='update allearter set '.$field.'_dk='.$field.' where '.$field.'_dk=""';
		$this->exec($SQL);
		echo $field.'->'.$field.'_dk ('.mysql_affected_rows().' rækker påvirket)<br>';
	}

	private function updateCRLF() {
		$this->updateFieldCRLF('Rige');
		$this->updateFieldCRLF('Raekke');
		$this->updateFieldCRLF('Underraekke');
		$this->updateFieldCRLF('Overklasse');
		$this->updateFieldCRLF('Klasse');
		$this->updateFieldCRLF('Underklasse');
		$this->updateFieldCRLF('Infraklasse');
		$this->updateFieldCRLF('Overorden');
		$this->updateFieldCRLF('Orden');
		$this->updateFieldCRLF('Underorden');
		$this->updateFieldCRLF('Infraorden');
		$this->updateFieldCRLF('Overfamilie');
		$this->updateFieldCRLF('Familie');
		$this->updateFieldCRLF('Underfamilie');
		$this->updateFieldCRLF('Tribus');
		$this->updateFieldCRLF('Slaegt');
	}

	private function updateFieldCRLF($field) {
		$html='';
		$count=0;
		$SQL='update allearter set '.$field.' = replace('.$field.', "\n", "")';
		$this->exec($SQL);
		$count=mysql_affected_rows();
		$SQL='update allearter set '.$field.' = replace('.$field.', "\r", "")';
		$this->exec($SQL);
		$count=$count+mysql_affected_rows();
		if ($count>0) $html.=$field.', '.$count.' rækker opdateret<br>';

		$count=0;
		$SQL='update allearter set '.$field.'_dk = replace('.$field.'_dk, "\n", "")';
		$this->exec($SQL);
		$count=mysql_affected_rows();
		$SQL='update allearter set '.$field.'_dk = replace('.$field.'_dk, "\r", "")';
		$this->exec($SQL);
		$count=$count+mysql_affected_rows();
		if ($count>0) $html.=$field.'_dk, '.$count.' rækker opdateret<br>';

		echo $html;
	}
}

$ajax = new Ajax();

?>
