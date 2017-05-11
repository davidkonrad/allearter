<?
include('common/StatBase.php');

class ArtsgruppeStatistik extends StatBase {
	private $total = array();

	public function __construct() {
		global $html;

		$this->total['art']=0;
		$this->total['underart']=0;
		$this->total['varietet']=0;
		$this->total['form']=0;
		$this->total['hybrid']=0;
		$this->total['accepteret']=0;

		parent::__construct();

		echo '<div style="padding-left:20px;">';
?>
			<a href="/" id="show-search-simple" style="text-decoration:none;">&#171;&nbsp;Simpel søgning</a>
			&nbsp;>&nbsp;
			<a href="http://allearter.dk/" style="text-decoration:none;">Projekt Allearter Startside</a>
			<br>
<?

		$this->header();
		echo '<div class="stat-cnt">';
		$this->drawStatistik();
		echo '</div>';
		echo '</div>';
	}

	private function header() {
		global $html;
		$html->br();
		$html->h1('Statistik - Artsgrupper');
		$html->br();
		$html->p('Oversigt over antal arter, underarter m.v. fordelt på de enkelte artsgrupper.<br/>'.
			'Bemærk at ikke accepterede arter er angivet særskilt.<br/>'.
			'Underarter m.v. er indtil videre primært indarbejdet for planter og svampe.<br/> ');
/*
			'<br/>Læs mere om <a href="http://allearter.dk/danske_arter.htm">accepterede / ikke accepterede arter</a>.'.
			'<br/>Læs mere om <a href="http://allearter.dk/artsgrupper-generelt.htm">artsgrupper</a>.');
*/
		$html->br();
	}

	private function drawStatistik() {
		global $html;

		echo '<table class="stat"><thead>';
		$this->th('Artsgruppe');
		$this->th('Artsgruppe (dk)');
		$this->th('Arter');
		$this->th('Underarter');
		$this->th('Varieteter');
		$this->th('Former');
		$this->th('Hybrider');
		$this->th('Ikke accepterede arter');
		echo '</thead></tbody>';

		mysql_set_charset('utf8');
		$SQL='select distinct Artsgruppe from allearter order by Artsgruppe';// asc limit 1';
		$result=$this->query($SQL);
		$odd=false;
		while($row=mysql_fetch_array($result)) {
			$this->drawStatistikItem($row['Artsgruppe'], $odd);
			$odd = ($odd==false) ? true : false;
		}
		
		echo '<tr><td colspan="2" class="total">Total</td>';
		echo '<td class="right total">'.$this->total['art'].'</td>';
		echo '<td class="right total">'.$this->total['underart'].'</td>';
		echo '<td class="right total ">'.$this->total['varietet'].'</td>';
		echo '<td class="right total">'.$this->total['form'].'</td>';
		echo '<td class="right total">'.$this->total['hybrid'].'</td>';
		echo '<td class="right total">'.$this->total['accepteret'].'</td>';
		echo '</tr>';

		echo '</tbody></table>';
	}

	private function drawStatistikItem($artsgruppe, $odd) {
		$SQL='select distinct Artsgruppe_dk, '.
			'(select count(*) from allearter where Artsgruppe="'.$artsgruppe.'" and Taxonkategori="Art" and Dansk="Ja") as art, '.
			'(select count(*) from allearter where Artsgruppe="'.$artsgruppe.'" and Taxonkategori="Underart" and Dansk="Ja") as underart, '.
			'(select count(*) from allearter where Artsgruppe="'.$artsgruppe.'" and Taxonkategori="Varietet" and Dansk="Ja") as varietet, '.
			'(select count(*) from allearter where Artsgruppe="'.$artsgruppe.'" and Taxonkategori="Form" and Dansk="Ja") as form, '.
			'(select count(*) from allearter where Artsgruppe="'.$artsgruppe.'" and Taxonkategori="Hybrid" and Dansk="Ja") as hybrid, '.
			'(select count(*) from allearter where Artsgruppe="'.$artsgruppe.'" and Dansk="Nej") as accepteret '.
			'from allearter where artsgruppe="'.$artsgruppe.'"';

		//echo $SQL;
		$classr = ($odd==true) ? ' class="right sodd"' : ' class="right"';
		$class = ($odd==true) ? ' class="sodd"' : '';
		$row=$this->getRow($SQL);

		foreach ($row as $key=>$value) {
			if ($value=='0') {
				$row[$key]='';
			}
		}

		$artlink='<a href="?artsgruppe="'.$artsgruppe.'">'.$artsgruppe.'</a>';
		echo '<tr>';
		//echo '<td '.$class.'>'.$artlink.'</td>';
		echo '<td '.$class.'>'.$this->linkify('artsgruppe',$artsgruppe,'artsgruppen').'</td>';
		//echo '<td '.$class.'>'.$row['Artsgruppe_dk'].'</td>';
		echo '<td '.$class.'>'.$this->linkify('artsgruppedk',$row['Artsgruppe_dk'],'artsgruppen').'</td>';
		echo '<td '.$classr.'>'.$row['art'].'</td>';
		echo '<td '.$classr.'>'.$row['underart'].'</td>';
		echo '<td '.$classr.'>'.$row['varietet'].'</td>';
		echo '<td '.$classr.'>'.$row['form'].'</td>';
		echo '<td '.$classr.'>'.$row['hybrid'].'</td>';
		echo '<td '.$classr.'>'.$row['accepteret'].'</td>';
		echo '</tr>';

		$this->total['art']=$this->total['art']+$row['art'];
		$this->total['underart']=$this->total['underart']+$row['underart'];
		$this->total['varietet']=$this->total['varietet']+$row['varietet'];
		$this->total['form']=$this->total['form']+$row['form'];
		$this->total['hybrid']=$this->total['hybrid']+$row['hybrid'];
		$this->total['accepteret']=$this->total['accepteret']+$row['accepteret'];

	}
}

$statistik= new ArtsgruppeStatistik();

?>


