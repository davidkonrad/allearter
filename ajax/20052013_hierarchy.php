<?

//when called via ajax
if (!class_exists('Db')) {
	require_once('../common/Db.php');
}

class Hierarchy extends Db {

		public function __construct() {
			parent::__construct();
			if (count($_GET)<=0) {
				$this->loadRige();
			} else {
				switch ($_GET['scope']) {
					case 'Raekke' : $this->loadRaekke(); break;
					case 'Klasse' : $this->loadKlasse(); break;
					case 'Orden' : $this->loadOrden(); break;
					case 'Familie' : $this->loadFamilie(); break;
					case 'Slaegt' : $this->loadSlaegt(); break;
					default : break;
				}
			}
		}

		private function getIndent($indent) {
			$html='';
			for ($i=0;$i<$indent;$i++) {
				$html.='<span class="klas-indent">&nbsp;</span>';
			}
			return $html;
		}

		private function row($type, $id, $lat, $dan, $click, $indent=0) {
				echo '<div class="klas-row" id="'.$id.'">';
				echo $this->getIndent($indent);
				//$click='Hi.expand(&quot;Raekke&quot,&quot;'.$row['Rige'].'&quot;,4,&quot;#'.$id.'&quot);';
				if ($click!='') {
					echo '<span class="klas-expand"><img src="images/arrow_plus_icon.png" style="cursor:pointer;" title="Udvid" onclick="'.$click.'"></span>';
				} else {
					echo '<span class="klas-expand"><img src="images/arrow_minus_icon.png" style="cursor:pointer;" title="Udvid"></span>';
				}
				echo '<a class="klas-row-search" href="#" title="Søg" onclick="Hi.doSearch(&quot;'.$type.'&quot;,&quot;'.$lat.'&quot,&quot;'.$id.'&quot;);">';
				echo '<span class="klas-lat">'.$lat.'</span>';
				if ($dan!='') echo '<span class="klas-dan">('.$dan.')</span>';
				echo '</a>';
				echo '</div>';
		}
		
		private function loadRige() {
			$SQL='select distinct Rige, Rige_dk from allearter where Rige<>"" order by Rige';
			$result=$this->query($SQL);
			$count=1;
			while ($row = mysql_fetch_array($result)) {
				$id='r'.$count;
				$click='Hi.expand(&quot;Raekke&quot,&quot;'.$row['Rige'].'&quot;,3,&quot;#'.$id.'&quot);';
				$this->row('klas_rige',$id, $row['Rige'], $row['Rige_dk'], $click);
				$count++;
			}
		}

		private function loadRaekke() {
			$SQL='select distinct Raekke, Raekke_dk from allearter where Rige="'.$_GET['name'].'" order by Raekke';
			mysql_set_charset('utf8');
			$result=$this->query($SQL);
			$count=1;
			while ($row = mysql_fetch_array($result)) {
				$id='rk'.$count;
				/*
				echo '<div class="klas-row" id="'.$id.'">';
				echo $this->getIndent($_GET['indent']);
				$click='Hi.expand(&quot;Raekke&quot,&quot;'.$row['Raekke'].'&quot;,1,&quot;#'.$id.'&quot);';
				echo '<span class="klas-expand"><img src="images/arrow_plus_icon.png" style="cursor:pointer;" title="Udvid" onclick="'.$click.'"></span>';
				echo '<span class="klas-lat">'.$row['Raekke'].'</span>';
				echo '<span class="klas-dan">('.$row['Raekke_dk'].')</span>';
				echo '</div>';
				*/
				$click='Hi.expand(&quot;Klasse&quot,&quot;'.$row['Raekke'].'&quot;,6,&quot;#'.$id.'&quot);';
				$this->row('klas_raekke',$id, $row['Raekke'], $row['Raekke_dk'], $click, $_GET['indent']);
				$count++;
			}
		}

		private function loadKlasse() {
			$SQL='select distinct Klasse, Klasse_dk from allearter where Raekke="'.$_GET['name'].'" order by Klasse';
			mysql_set_charset('utf8');
			$result=$this->query($SQL);
			$count=1;
			while ($row = mysql_fetch_array($result)) {
				$id='k'.$_GET['name'].$count;
				$click='Hi.expand(&quot;Orden&quot,&quot;'.$row['Klasse'].'&quot;,9,&quot;#'.$id.'&quot);';
				$this->row('klas_klasse',$id, $row['Klasse'], $row['Klasse_dk'], $click, $_GET['indent']);
				$count++;
			}
		}

		private function loadOrden() {
			$SQL='select distinct Orden, Orden_dk from allearter where Klasse="'.$_GET['name'].'" order by Orden';
			mysql_set_charset('utf8');
			$result=$this->query($SQL);
			$count=1;
			while ($row = mysql_fetch_array($result)) {
				$id='or'.$_GET['name'].$count;
				$click='Hi.expand(&quot;Familie&quot,&quot;'.$row['Orden'].'&quot;,12,&quot;#'.$id.'&quot);';
				$this->row('klas_orden',$id, $row['Orden'], $row['Orden_dk'], $click, $_GET['indent']);
				$count++;
			}
		}

		private function loadFamilie() {
			$SQL='select distinct Familie, Familie_dk from allearter where Orden="'.$_GET['name'].'" order by Orden';
			mysql_set_charset('utf8');
			$result=$this->query($SQL);
			$count=1;
			while ($row = mysql_fetch_array($result)) {
				$id='fl'.$_GET['name'].$count;
				$click='Hi.expand(&quot;Slaegt&quot,&quot;'.$row['Familie'].'&quot;,15,&quot;#'.$id.'&quot);';
				$this->row('klas_familie',$id, $row['Familie'], $row['Familie_dk'], $click, $_GET['indent']);
				$count++;
			}
		}

		private function loadSlaegt() {
			$SQL='select distinct Slaegt, Slaegt_dk from allearter where Familie="'.$_GET['name'].'" order by Slaegt';
			mysql_set_charset('utf8');
			$result=$this->query($SQL);
			$count=1;
			while ($row = mysql_fetch_array($result)) {
				$id='sl'.$_GET['name'].$count;
				//$click='Hi.expand(&quot;Slaegt&quot,&quot;'.$row['Familie'].'&quot;,15,&quot;#'.$id.'&quot);';
				$click='';
				$this->row('klas_slaegt',$id, $row['Slaegt'], $row['Slaegt_dk'], $click, $_GET['indent']);
				$count++;
			}
		}

}

$h= new Hierarchy();

?>

