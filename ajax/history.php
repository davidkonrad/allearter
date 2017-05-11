<?
header('Content-type: application/html');

include('../common/Db.php');

class SearchHistory extends Db {

	public function __construct() {
		parent::__construct();

		$h=$_GET['historik'];
		$html='';
		$historik=explode(',',$h);
		//reverse the array, so latest entry comes first
		$historik=array_reverse($historik);
		//$html='<table>';
		foreach ($historik as $id) {
			//$html.='<tr>'.$this->getSearchHistory($id).'</tr>';
			$html.=$this->getSearchHistory($id);
		}
		//$html.='</table>';
		echo $html;
	}

	private function style($param, $value) {
		if (in_array($param, array('familie', 'raekke', 'klasse', 'orden', 'slaegt', 'rige'))) {
			$ch=strtoupper(substr($param,0,1));
			$html='{'.$ch.':<i style="color:navy;">'.$value.'</i>}&nbsp;';
			return $html;
		}
		if ($param=='lang') {
			if ($value=='da') {
				return '(<span style="color:red;">dansk</span>)&nbsp;';
			} else {
				return '';
			}
		}
		if ($param=='text') {
			return '&#187;<span style="font-size:15px;font-family:courier;color:darkgreen;">'.$value.'</span>&#171;&nbsp;';
		}
		if (in_array($param, array('artsgruppe','artsgruppedk'))) {
			return '<span style="color:maroon;text-transform:uppercase;">'.$value.'</span>&nbsp;';
		}
		if ($param=='arter') {
			switch($value) {
				case '1' : $a='&lt;accept.>'; break;
				case '2' : $a='&lt;ikke accept.>'; break;
				case '3' : $a='&lt;* accept.>'; break;
				//default : $a=''; break;
			}
			return '<span style="color:gray;">'.$a.'</span>&nbsp;';
		}
		if ($param=='textmode') {
			switch($value) {
				case 'alle' : $a='navne'; break;
				case 'arter' : $a='arter'; break;
				case 'referencer' : $a='referencer'; break;
				case 'allefelter' : $a='samtlige felter'; break;
				default : $a=''; break;
			}
			return '{<span style="color:forestgreen;">'.$a.'</span>}&nbsp;';
		}
		if ($param=='forvl') {
			$ret='';
			$array=explode(' ',$value);
			foreach($array as $a) {
				if ($ret!='') $ret.='<span style="color:gray;"> / </span>';
				if ($a=='Den_danske_roedliste') $ret.='r&oslash;dlistede';
				if ($a=='Fredede_arter') $ret.='fredede';
				if ($a=='Habitatdirektivet') $ret.='habitatdir.';
				if ($a=='Fuglebeskyttelsesdirektivet') $ret.='fuglebeskyt.';
				if ($a=='Bern_konventionen') $ret.='Bern';
				if ($a=='Bonn_konventionen') $ret.='Bonn';
				if ($a=='CITES') $ret.='CITES';
				if ($a=='Oevrige') $ret.='&Oslash;vrige';
			}
			return '{<span style="color:magenta;">'.$ret.'</span>}&nbsp;';
		}
		if ($param=='taxon') {
			$ret='';
			$array=explode(' ',$value);
			foreach($array as $a) {
				if ($ret!='') $ret.='<span style="color:gray;"> / </span>';
				if ($a=='Art') $ret.='art';
				if ($a=='Underart') $ret.='underart';
				if ($a=='Varietet') $ret.='varietet';
				if ($a=='Form') $ret.='form';
				if ($a=='Hybrid') $ret.='hybrid';
			}
			return '{<span style="color:#8A2BE2;">'.$ret.'</span>}&nbsp;';
		}


		if ($param=='mode') {
			return '';
		}
		return $value;
	}

	private function getSearchHistory($id) {
		if ($id=='') return 'Du har pt. ingen gemte søgninger.';

		$SQL='select _timestamp, guid from userLog where log_id='.$id;
		$row=$this->getRow($SQL);
		$timestamp=$row['_timestamp'];
		$timestamp=date('j M H.i.',strtotime($timestamp));

		$guid=$row['guid'];

		$SQL='select param, value from userLogRequest where log_id='.$id;
		$result=$this->query($SQL);
		$values='';
		while ($row = mysql_fetch_array($result)) {
			$values.=$this->style($row['param'], $row['value']); //$row['value'].',';
		}
		$values=$this->removeLastChar($values);
		$values=$this->crlf($values);

		$html='<div guid="'.$guid.'" class="history-combo-row"><div class="history-combo-tag">';
		$html.=$timestamp.'</div>';

		$title=strip_tags($values);
		$html.='<div class="history-combo-values" title="'.$title.'">';
		$html.=$values.'</div></div>';

		return $html;
	}

}

$search = new SearchHistory();
?>
