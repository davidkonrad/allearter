<?php
error_reporting(E_ALL ^ E_DEPRECATED);

class Db {
	private $database;
	private $hostname;
	private $username;
	private $password;
	private $link;
  
	public static function getInstance(){
		static $db = null;
		if ( $db == null ) $db = new Db();
		return $db;
	}

	public function __construct() { 
		$host = $_SERVER["SERVER_ADDR"]; 
		if (($host=='127.0.0.1') || ($host=='::1')) {
			$this->database = 'allearter';
			$this->hostname = 'localhost';
			$this->username = 'root';
			$this->password = 'dadk';
		
		} else {
			$this->database = 'allearte_allearter';
			$this->hostname = 'db14.meebox.net';
			$this->username = 'allearte';
			$this->password = 'i5e1SgH0j8';
		}

		try {
			$this->link=@mysql_connect($this->hostname,
						  $this->username,
						  $this->password);
			if (!$this->link) {
				header('location: inc/severe_error.html');
				die('Kunne ikke forbinde til database : ' . mysql_error());
			} else {
				mysql_select_db ($this->database);
			}

		} catch (Exception $e){
			throw new Exception('Kunne ikke forbinde til database ...');
			exit;
 		}
	}

	public function setUTF8() {
		mysql_set_charset('UTF8',$this->link);
	}

	public function setLatin1() {
		mysql_set_charset('Latin1',$this->link);
	}

	public function setASCII() {
		mysql_set_charset('ASCII',$this->link);
	}

	public function exec($SQL) {
		mysql_query($SQL);
	}

	public function query($SQL) {
		$result=mysql_query($SQL);
		return $result;
	}

	public function getRow($SQL) {
		$result=mysql_query($SQL);
		$result=mysql_fetch_array($result);
		return $result;
	}

	public function hasData($SQL) {
		$result=mysql_query($SQL);
		return is_array(@mysql_fetch_array($result));
	}

	public function getRecCount($table) {
		$SQL='select count(*) from '.$table;
		$count=$this->getRow($SQL);
		return $count[0];
	}		

	public function q($string, $comma = true) {
		$string=mysql_real_escape_string($string);
		return $comma ? '"'.$string.'",' : '"'.$string.'"';
	}
	
	//	
	// common functions, not nessecarily related to Db
	// implemented here because Db is parent for almost all classes
	//
	public function debug($data) {
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}

	protected function fileDebug($text) {
		$file = "debug.txt";
		$fh = fopen($file, 'a') or die("can't open file");
		fwrite($fh, $text."\n");
		fclose($fh);
	}

	public function crlf($string) {
		return preg_replace('#[\r\n]#', '', $string);
	}

	public function removeLastChar($s) {
		return substr_replace($s ,"", -1);
	}
}

?>
