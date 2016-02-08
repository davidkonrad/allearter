<?
include('common/StatBase.php');

class Statistik extends StatBase {
	private $total = array();

	public function __construct() {
		global $html;
		parent::__construct();

		echo '<div style="padding-left:20px;">';

?>
			<a href="/" id="show-search-simple" style="text-decoration:none;">&#171;&nbsp;Simpel søgning</a>
			&nbsp;>&nbsp;
			<a href="http://allearter.dk/" style="text-decoration:none;">Projekt Allearter Startside</a>
			<br>
<?

		$this->header();
		$this->resetTotal();
		echo '<div class="stat-cnt">';
		$this->rigeArter();
		$this->klassifikationArter('raekke','Række','Rækker','rækken');
		$this->klassifikationArter('klasse','Klasse','Klasser','klassen');
		$this->klassifikationArter('orden','Orden','Ordner','ordenen');
		$this->klassifikationArter('familie','Familie','Familier','familien');
		$this->klassifikationArter('slaegt','Slægt','Slægter','slægten');
		$this->roedlistede();
		echo '</div>';

		echo '</div>'; //left-padding
		$html->divider(2);
	}

	private function resetTotal() {
		$this->total['art']=0;
		$this->total['underart']=0;
		$this->total['varietet']=0;
		$this->total['form']=0;
		$this->total['hybrid']=0;
		$this->total['accepteret']=0;
	}

	private function header() {
		global $html;
		$html->br();
		$html->h1("Statistik - Klassifikation m.v");
		$html->p('<br>Oversigt over:<ul>'.
			'<li>antal arter, underarter m.v. fordelt på de enkelte riger. Bemærk at ikke accepterede arter er angivet særskilt</li>'.
			'<li>de artsrigeste rækker, klasser, ordner, familier og slægter</li>'.
			'<li>antal rødlistede arter fordelt på artsgrupper (NB: Planter er endnu ikke implementeret)</li>'.
			'</ul>');
		//$html->p('Læs mere om <a href="http://allearter.dk/danske_arter.htm">accepterede / ikke accepterede arter</a>.');
		//$html->br();
	}

	private function caption($caption) {
		echo '<h4 style="text-transform:uppercase;">'.$caption.'</h4>';
	}

	private function rigeArterItem($rige, $odd) {
		$SQL='select distinct Rige_dk, '.
			'(select count(*) from allearter where Rige="'.$rige.'" and Taxonkategori="Art" and Dansk="Ja") as art, '.
			'(select count(*) from allearter where Rige="'.$rige.'" and Taxonkategori="Underart" and Dansk="Ja") as underart, '.
			'(select count(*) from allearter where Rige="'.$rige.'" and Taxonkategori="Varietet" and Dansk="Ja") as varietet, '.
			'(select count(*) from allearter where Rige="'.$rige.'" and Taxonkategori="Form" and Dansk="Ja") as form, '.
			'(select count(*) from allearter where Rige="'.$rige.'" and Taxonkategori="Hybrid" and Dansk="Ja") as hybrid, '.
			'(select count(*) from allearter where Rige="'.$rige.'" and Dansk="Nej") as accepteret '.
			'from allearter where Rige="'.$rige.'"';

		//echo $SQL;
		$classr = ($odd==true) ? ' class="right sodd"' : ' class="right"';
		$class = ($odd==true) ? ' class="sodd"' : '';
		$row=$this->getRow($SQL);

		foreach ($row as $key=>$value) {
			if ($value=='0') {
				$row[$key]='';
			}
		}

		echo '<tr>';
		echo '<td '.$class.'>'.$rige.'</td>';
		echo '<td '.$class.'>'.$row['Rige_dk'].'</td>';
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

	private function rigeArter() {
		global $html;
		$html->br();
		$this->caption('Arter fordelt på Riger');

		echo '<table class="stat"><thead>';
		$this->th('Rige');
		$this->th('Rige (dk)');
		$this->th('Arter');
		$this->th('Underarter');
		$this->th('Varietet');
		$this->th('Former');
		$this->th('Hybrid');
		$this->th('Ikke accepterede arter');
		echo '</thead><tbody>';

		mysql_set_charset('utf8');
		$SQL='select distinct rige from allearter order by rige asc';
		$result=$this->query($SQL);
		$odd=false;
		while ($row = mysql_fetch_array($result)) {
			$this->rigeArterItem($row['rige'], $odd);
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

	private function klassifikationArter($klas, $ental, $flertal, $bestemt) {
		global $html;
		echo '<div class="cnt">';
		$html->br(2);
		//$this->caption('De 25 artsrigeste '.$flertal);
		$this->caption('De 25 mest artsrige '.$flertal);

		$SQL='select distinct '.$klas.', '.$klas.'_dk, count(*) '.
			'from allearter '.
			'where Dansk="Ja" '.
			'group by '.$klas.' order by count(*) desc limit 25';
		mysql_set_charset('utf8');
		$result=$this->query($SQL);
		echo '<table class="stat"><thead>';
		$this->th($ental);
		$this->th($ental.' (dk)');
		$this->th('Arter');
		echo '</thead>';
		echo '<tbody>';
		$odd=false;
		while ($row = mysql_fetch_array($result)) {
			$class=($odd==true) ? 'class="sodd"' : '';
			$classr=($odd==true) ? 'class="right sodd"' : 'class="right"';
			echo '<tr>';
			if ($klas=='slaegt') {
				//echo '<td '.$class.'><i>'.$row[$klas].'</i></td>';
				echo '<td '.$class.'><i>'.$this->linkify('slaegt',$row[$klas], $bestemt).'</i></td>';
			} else {
				//echo '<td '.$class.'>'.$row[$klas].'</td>';
				echo '<td '.$class.'>'.$this->linkify($klas,$row[$klas],$bestemt).'</td>';
			}
			echo '<td '.$class.'>'.$row[$klas.'_dk'].'</td>';
			echo '<td '.$classr.'>'.$row['count(*)'].'</td>';
			echo '</tr>';
			$odd = ($odd==false) ? true : false;
		}
		echo' </tbody></table>';
		echo '</div>';
	}

	protected function linkifyR($table, $caption, $scope) {
		$href=urlencode($caption);
		$href='?'.$table.'='.$href.'&forvl=Den_danske_roedliste&mode=or';
		$title='Klik for at se samtlige rødlistede arter indenfor '.$scope.' '.$caption;
		return '<a class="stat-link" href="'.$href.'" title="'.$title.'">'.$caption.'</a>';
	}

	private function roedlistede() {
		global $html;
		echo '<div class="cnt">';
		$html->br(2);
		$this->caption('Rødlistede arter per artsgruppe');

		$SQL='select distinct Artsgruppe, Artsgruppe_dk, count(*) from allearter where Den_danske_roedliste<>"" group by artsgruppe order by count(*) desc';
		$result=$this->query($SQL);
		echo '<table class="stat"><thead>';
		$this->th('Artsgruppe');
		$this->th('Artsgruppe (dk)');
		$this->th('Antal');
		echo '</thead>';
		echo '<tbody>';
		$odd=false;
		while ($row = mysql_fetch_array($result)) {
			$classr = ($odd==true) ? ' class="right sodd"' : ' class="right"';
			$class = ($odd==true) ? ' class="sodd"' : '';
			echo '<tr>';
			//echo '<td'.$class.'>'.$row['Artsgruppe'].'</td>';
			echo '<td '.$class.'>'.$this->linkifyR('artsgruppe',$row['Artsgruppe'],'artsgruppen').'</td>';
			//echo '<td'.$class.'>'.$row['Artsgruppe_dk'].'</td>';
			echo '<td '.$class.'>'.$this->linkifyR('artsgruppedk',$row['Artsgruppe_dk'],'artsgruppen').'</td>';
			echo '<td'.$classr.'>'.$row['count(*)'].'</td>';
			echo '</tr>';
			$odd = ($odd==false) ? true : false;
		}
		echo '</tbody></table>';
		echo '</div>';
	}
}

$statistik= new Statistik();

?>
