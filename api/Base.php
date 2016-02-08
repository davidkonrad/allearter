<?
/* base class for AJAX and JSON */
header('Content-Type: text/html; charset=utf-8');

class Base extends Db {
	
	public function __construct() {
		parent::__construct();
	}

	protected function getError($error='error') {
		return '{ "error" : "'.$error.'" }';
	}

	protected function abort($error='error') {
		echo $this->getError($error);
		exit();
	}

	protected function getParam($name) {
		return (isset($_GET[$name])) ? $_GET[$name] : '';
	}

	//recursive!!
	protected function rowToJSON($row) {
		$JSON='';
		foreach($row as $key=>$value) {
			if ($JSON!='') $JSON.=', ';

			if (is_array($value)) {
				$JSON.='"'.$key.'" : [ '.$this->rowToJSON($value).' ] ';
			} else {
				$value=str_replace(array("\n","\r"), '', $value);
				$value=utf8_encode($value);
				$JSON.='"'.$key.'" : "'.$value.'"';
			}
		}
		$JSON='{'.$JSON.'}';
		return $JSON;
	}
}
?>
