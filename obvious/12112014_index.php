<?

include('../common/Db.php');

class ObviousFrame extends Db {
	private $artsgruppe;
	private $action;

	public function __construct() {
		parent::__construct();
		$this->action = isset($_GET['vis']) ? $_GET['vis'] : false;
		$this->artsgruppe = isset($_GET['artsgruppe']) ? $_GET['artsgruppe'] : false;
		switch ($this->action) {

			case 'total' :
				$this->total();
				break;

			case 'arter' :
				$this->arter();
				break;

			case 'select' :
				$this->select();
				break;

			case 'klassifikation' :
				$this->klassifikation();
				break;

			case 'referencer' :
				$this->referencer();
				break;

			default :
				break;
		}
	}

	private function header() {
?>
<!doctype html>
<head>
<meta charset="UTF-8">
<link href="http://danbif.dk/css/style.css" rel="stylesheet" type="text/css" media="screen" />
<link href="http://cms.ku.dk/css/units/ku-faelles.css" rel="stylesheet" type="text/css" media="screen" />
<style>
html, body {
	margin: 0px;
	padding: 0px;
	overflow-y: hidden;
}
th {
	margin-right: 5px;
	text-align: left;
	color: #4A4949;
}
th.space {
	margin-left: 8px;
}
td {
	vertical-align: top;
	text-align: left;
	color: #4A4949;
}
td.right {
	text-align: right;
}
td.line {
	border-top: 1px solid #ebebeb;
	min-height: 5px;
}
</style>
<script>
function load() {
	setTimeout(function() {
		var name = "<? echo $this->action;?>";
		var height = 20;
		parent.postMessage(height+'|'+name, '*');
	}, 1);
	setTimeout(function() {
		var name = "<? echo $this->action;?>";
		var height = document.body.scrollHeight + 20;
		parent.postMessage(height+'|'+name, '*');
	}, 50);
}
</script>
</head>
<body onload="load();">
<?
	}

	private function footer() {
?>
</body>
</html>
<?
	}

	private function format($number) {
		$number=number_format($number);
		return str_replace(',','.',$number);
	}

	private function th($caption, $width=false) {
		echo '<th';
		if ($width) echo ' style="width:'.$width.'px;"';
		echo '>';
		echo $caption.'</th>';
	}

	private function linkify($text) {
		//http://stackoverflow.com/questions/507436/how-do-i-linkify-urls-in-a-string-with-php
		$result = preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[A-Z0-9+&@#\/%=~_|]/i', '<a href="\0" target=_blank title="Se reference (åbner i nyt vindue)">\0</a>', $text);
		return $result;
	}

	private function total() {
		$this->header();
		$SQL='select count(*) as c from allearter';
		$antal=$this->getRow($SQL);
		$SQL='select count(*) as c from allearter where Dansk="Ja"';
		$danske=$this->getRow($SQL);
		echo '<table>';
		echo '<tr><td>Antal danske arter&nbsp;&nbsp;&nbsp;</td><td><b>'.
			$this->format($danske['c']).
			'</b></td></tr>';
		echo '<tr><td>Antal poster i databasen&nbsp;</td><td><b>'.
			$this->format($antal['c']).
			'</b></td></tr>';
		echo '</table>';
	}

	private function arterRow($taxonKategori) {
		$SQL='select distinct Artsgruppe,'. 
			'(select count(*) from allearter where Artsgruppe="'.$this->artsgruppe.'" and Taxonkategori="'.$taxonKategori.'") as total, '.
			'(select count(*) from allearter where Artsgruppe="'.$this->artsgruppe.'" and Taxonkategori="'.$taxonKategori.'" and Dansk="Ja") as accepteret, '.
			'(select count(*) from allearter where Artsgruppe="'.$this->artsgruppe.'" and Taxonkategori="'.$taxonKategori.'" and Dansk="Nej") as ejaccepteret '.
			'from allearter where artsgruppe="'.$this->artsgruppe.'"';

		$row=$this->getRow($SQL);

		//vis ikke taxontyper med 0 poster
		if ($row['total']==0) return;

		echo '<tr>'.
			'<td>'.$taxonKategori.'</td>'.
			'<td class="right">'.$row['accepteret'].'</td>'.
			'<td class="right">'.$row['ejaccepteret'].'</td>'.
			'<td class="right">'.$row['total'].'</td>'.
			'</tr>';
	}

	private function arter() {
		$this->header();
		echo '<table>'.
			'<thead><tr>'.
			'<th class="space">Type</th>'.
			'<th class="space">Accept.</th>'.
			'<th class="space">Ej accept.</th>'.
			'<th class="space">Total</th>'.
			'</tr></th>';

		echo '<tbody>';
		$this->arterRow('Art');
		$this->arterRow('Form');
		$this->arterRow('Hybrid');
		$this->arterRow('Underart');
		$this->arterRow('Varietet');
		echo '</tbody></table>';
		$this->footer();
	}

	private function select() {
		$SQL='select distinct Artsgruppe from allearter where Artsgruppe<>"" order by Artsgruppe';
		mysql_set_charset('utf8');
		$result=$this->query($SQL);
		echo '<select id="artsgrupper" size="8">';
		while ($row = mysql_fetch_assoc($result)) {
			$SQL='select distinct Artsgruppe_dk from allearter where Artsgruppe="'.$row['Artsgruppe'].'"';
			$dk=$this->getRow($SQL);
			echo '<option value="'.$row['Artsgruppe'].'">';
			echo $row['Artsgruppe'];
			echo ($dk['Artsgruppe_dk']!='') ? ' ('.$dk['Artsgruppe_dk'].')' : '';
			echo '</option>';
		}
		echo '</select>';
	}

/*
	private function klassifikation() {
		$SQL='select distinct Artsgruppe,'. 
			'(select distinct Rige from allearter where Artsgruppe="'.$this->artsgruppe.'" limit 1) as rige, '.
			'(select distinct Rige_dk from allearter where Artsgruppe="'.$this->artsgruppe.'" limit 1) as rige_dk, '.
			'(select distinct Raekke from allearter where Artsgruppe="'.$this->artsgruppe.'" limit 1) as raekke, '.
			'(select distinct Raekke_dk from allearter where Artsgruppe="'.$this->artsgruppe.'" limit 1) as raekke_dk, '.
			'(select distinct Underraekke from allearter where Artsgruppe="'.$this->artsgruppe.'" limit 1) as underraekke, '.
			'(select distinct Underraekke_dk from allearter where Artsgruppe="'.$this->artsgruppe.'" limit 1) as underraekke_dk, '.
			'(select distinct Klasse from allearter where Artsgruppe="'.$this->artsgruppe.'" limit 1) as klasse, '.
			'(select distinct Klasse_dk from allearter where Artsgruppe="'.$this->artsgruppe.'" limit 1) as klasse_dk, '.
			'(select distinct Orden from allearter where Artsgruppe="'.$this->artsgruppe.'" limit 1) as orden, '.
			'(select distinct Orden_dk from allearter where Artsgruppe="'.$this->artsgruppe.'" limit 1) as orden_dk '.

			'from allearter where artsgruppe="'.$this->artsgruppe.'"';

		mysql_set_charset('utf8');

		$row = $this->getRow($SQL);
		$this->header();
		echo '<table>'.
			'<thead><tr>'.
			'<th>Klassifikation</th>'.
			'<th>Latin</th>'.
			'<th>Dansk</th>'.
			'</tr></th>';

		foreach ($row as $key=>$value) {
			if ($value=='') {
				$row[$key]='-';
			}
		}

		echo '<tbody>';
		echo '<tr><td>Rige</td><td>'.$row['rige'].'</td><td>'.$row['rige_dk'].'</td></tr>';
		echo '<tr><td>Række</td><td>'.$row['raekke'].'</td><td>'.$row['raekke_dk'].'</td></tr>';
		echo '<tr><td>Underrække</td><td>'.$row['underraekke'].'</td><td>'.$row['underraekke_dk'].'</td></tr>';
		echo '<tr><td>Klasse</td><td>'.$row['klasse'].'</td><td>'.$row['klasse_dk'].'</td></tr>';
		echo '<tr><td>Orden</td><td>'.$row['orden'].'</td><td>'.$row['orden_dk'].'</td></tr>';

		echo '</tbody></table>';
		$this->footer();
	}
*/

	private function dash($content) {
		if ($content!='') {
			return $content;
		} else {
			return '-';
		}
	}

	private function klassProcessRow($klass, $row) {
		for ($i=0;$i<count($klass);$i++) {
			$k=$klass[$i];
			if ($k['Rige']==$row['Rige'] && 
				$k['Raekke']==$row['Raekke'] && 
				$k['Klasse']==$row['Klasse']) {
				//check if Orden already exists
				$orden=explode(',', $k['Orden']);
				$ordendk=explode(',', $k['Orden_dk']);
				if (!in_array($row['Orden'], $orden)) {
					$orden[]=$row['Orden'];
					$ordendk[]=$row['Orden_dk'];
					$k['Orden']=implode(',', $orden);
					$k['Orden_dk']=implode(',', $ordendk);
					$klass[$i]=$k;
					return $klass;
				} else {
					return $klass;
				}
			}
		}
		$klass[]=$row;
		return $klass;
	}

	private function klassifikation() {
		$SQL='select Rige,Rige_dk,Raekke,Raekke_dk,Underraekke,Underraekke_dk,Klasse,Klasse_dk,'.
			'Orden,Orden_dk from allearter where artsgruppe="'.$this->artsgruppe.'" '.
			'order by Rige';

		mysql_set_charset('utf8');
		$result = $this->query($SQL);

		$klass = array();
		while ($row = mysql_fetch_assoc($result)) {
			/*
			if (!in_array($row, $klass)) {
				$klass[] = $row;
			} else {
				$klass = $this->klassProcessRow($klass, $row);
			}
			*/
			$klass = $this->klassProcessRow($klass, $row);
		}

		$this->header();
		echo '<table>'.
			'<thead><tr>'.
			'<th style="width:50px;">Niveau</th>'.
			'<th class="space">Latin</th>'.
			'<th class="space">Dansk</th>'.
			'</tr></th>';

		echo '<tbody>';
		$count = count($klass);
		foreach ($klass as $row) {
			$orden=str_replace(',', '<br>', $row['Orden']);
			$ordendk=str_replace("\n", '', $row['Orden_dk']);
			$ordendk=str_replace(',', '<br>', $ordendk);
			$caption=strpos($row['Orden'],',') ? 'Orden&#1645;' : 'Orden';
			echo '<tr><td>Rige</td><td>'.$row['Rige'].'</td><td>'.$this->dash($row['Rige_dk']).'</td></tr>';
			echo '<tr><td>Række</td><td>'.$row['Raekke'].'</td><td>'.$this->dash($row['Raekke_dk']).'</td></tr>';
			echo '<tr><td>Klasse</td><td>'.$row['Klasse'].'</td><td>'.$this->dash($row['Klasse_dk']).'</td></tr>';
			echo '<tr><td>'.$caption.'</td><td>'.$this->dash($orden).'</td><td>'.$this->dash($ordendk).'</td></tr>';
			$count--;
			if ($count>0) {
				echo '<tr><td colspan="4" class="line"></td></tr>';
			}
		}
		echo '</tbody></table>';
		$this->footer();
	}

	private function referencer() {
		$this->header();
		echo '<table style="width:500px;"><thead>';
		$this->th('Navn', 150);
		$this->th('År', 60);
		$this->th('Titel', 240);
		$this->th('Arter', 30);
		echo '</thead><tbody>';

		$SQL='select distinct Referencenavn from allearter '.
			'where Artsgruppe="'.$this->artsgruppe.'" order by Referencenavn asc'; 

		mysql_set_charset('utf8');

		$result=$this->query($SQL);
		while ($r = mysql_fetch_assoc($result)) {

			$ref=mysql_real_escape_string($r['Referencenavn']);
			$SQL='select Referencetekst, Reference_aar, '.
				"(select count(*) from allearter where Artsgruppe='".$this->artsgruppe."' and Referencenavn='".$ref."') as arter ".
				' from allearter where Referencenavn="'.$ref.'"';

			$row=$this->getRow($SQL);
			$tekst=($row['Referencetekst']!='') ? $this->linkify($row['Referencetekst']) : '-';
			$tekst=str_replace('&','&amp;',$tekst);
			$aar=($row['Reference_aar']!='') ? floor($row['Reference_aar']) : '-';
			echo '<tr>';
			echo '<td>'.$r['Referencenavn'].'</td>';
			echo '<td>'.$aar.'</td>';
			echo '<td>'.$tekst.'</td>';
			echo '<td class="right">'.$row['arter'].'</td>';
	
			echo '</tr>';
		}
		echo '</table>';
	}
	
}

$obvious = new ObviousFrame();

?>
