<?

//when called via ajax
if (!class_exists('Db')) {
	require_once('../common/Db.php');
}

class Hierarchy extends Db {
	private $colors=array(
		"Rige"=>"navy",
		"Raekke"=>"forestgreen",
		"Klasse"=>"fuchsia",
		"Orden"=>"darkred",
		"Familie"=>"black",
		"Slaegt"=>"black"
	);

	public function __construct() {
		parent::__construct();
		$get=(isset($_GET['get'])) ? $_GET['get'] : 'Rige';
		$parent=(isset($_GET['parent'])) ? $_GET['parent'] : '';
		$base=(isset($_GET['base'])) ? $_GET['base'] : '';

		$this->load($get, $parent, $base);
	}

	private function nextLevel($scope) {
		switch ($scope) {
			case 'Rige' :
				return 'Raekke'; 
				break;
			case 'Raekke' :
				return 'Klasse'; 
				break;
			case 'Klasse' :
				return 'Orden'; 
				break;
			case 'Orden' :
				return 'Familie'; 
				break;
			case 'Familie' :
				return 'Slaegt'; 
				break;
			default :
				return '';
				break;
		}
	}

	private function getIndent($scope) {
		switch ($scope) {
			case 'Rige' :
				$indent=1;
				break;
			case 'Raekke' :
				$indent=2;
				break;
			case 'Klasse' :
				$indent=3;
				break;
			case 'Orden' :
				$indent=4;
				break;
			case 'Familie' :
				$indent=5;
				break;
			case 'Slaegt' :
				$indent=6;
				break;
			case 'Art' :
				$indent=7;
				break;
			default :
				$indent=1;
				break;
		}
		$html='';
		for ($i=1;$i<$indent;$i++) {
			$html.='<span class="klas-indent">&nbsp;</span>';
		}
		return $html;
	}

	private function getTitle($scope, $name, $dkname) {
		$text='Søg på ';
		switch ($scope) {
			case 'Rige' :
				$text.='riget ';
				break;
			case 'Raekke' :
				$text.='rækken ';
				break;
			case 'Klasse' :
				$text.='klassen ';
				break;
			case 'Orden' :
				$text.='ordnen ';
				break;
			case 'Familie' :
				$text.='familien ';
				break;
			case 'Slaegt' :
				$text.='slægten ';
				break;
			case 'Art' :
				$text.='arten ';
				break;
			default :
				break;
		}
		$text.=$name;
		if ($dkname!='' && $dkname!=$name) $text.=' ('.$dkname.')';
		return $text;
	}
			
	private function row($scope, $parent, $name, $dkname) {
		//$style='style="color:'.$this->colors[$scope].';"';
		$style=($scope=='Art') ? 'style="color:black;"' : '';
		echo '<div class="klas-row" id="'.$name.'" scope="'.$scope.'" parent="'.$parent.'">';
		$title=$this->getTitle($scope, $name, $dkname);
		$nextLevel=$this->nextLevel($scope);
		echo $this->getIndent($scope);
		$click='Hi.expand(&quot;'.$nextLevel.'&quot,&quot;'.$scope.'&quot;,&quot;'.$name.'&quot);';
		echo '<span class="klas-expand"><img src="images/arrow_plus_icon.png" style="cursor:pointer;" title="Udvid" onclick="'.$click.'"></span>';
		echo '<a class="klas-row-search" href="#" title="'.$title.'" onclick="Hi.doSearch(&quot;'.$scope.'&quot;,&quot;'.$name.'&quot);" '.$style.'>';
		echo '<span class="klas-lat">'.$name.'</span>';
		if ($dkname!='') echo '<span class="klas-dan">('.$dkname.')</span>';
		echo '</a>';
		echo '</div>';
	}
		
	private function load($get, $parent, $base) {
		if ($get=='') {
			$this->loadArter($parent, $base);
			return;
		}

		$SQL='select distinct '.$get.', '.$get.'_dk from allearter where '.$get.'<>"" ';
		if ($parent!='' && $base!='') {
			$SQL.='and '.$parent.'="'.$base.'" ';
		}
		$SQL.='order by '.$get;
		mysql_set_charset('utf8');
		$result=$this->query($SQL);
		while ($row = mysql_fetch_assoc($result)) {
			$this->row($get, $base, $row[$get], $row[$get.'_dk']);
		}
	}

	private function loadArter($parent, $base) {
		$SQL='select distinct Videnskabeligt_navn, Dansk_navn from allearter '.
			'where '.$parent.'="'.$base.'" '.
			'order by Videnskabeligt_navn ';
		//echo $SQL;
		mysql_set_charset('utf8');
		$result=$this->query($SQL);
		while ($row = mysql_fetch_assoc($result)) {
			$this->row('Art', $base, $row['Videnskabeligt_navn'], $row['Dansk_navn']);
		}
	}
		
}

$h= new Hierarchy();

?>

