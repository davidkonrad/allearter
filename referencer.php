<?
include('common/StatBase.php');

class Referencer extends StatBase {

	public function __construct() {
		global $html;
		parent::__construct();

		echo '<div style="padding-left:20px;padding-right:20px;">';
?>
			<a href="/" id="show-search-simple" style="text-decoration:none;">&#171;&nbsp;Simpel søgning</a>
			&nbsp;>&nbsp;
			<a href="http://allearter.dk/" style="text-decoration:none;">Projekt Allearter Startside</a>
<?

		echo '<div class="stat-cnt"><br>';
		$html->h1('Oversigt over referencer benyttet i databasen');
		$html->br();
		$html->p('For hver art i databasen bag allearter.dk er der tilknyttet en reference, der angiver pågældende arts forekomst i Danmark.');
		$html->p('<br/>På denne side findes en samlet oversigt over disse referencer. Referencerne dækker over såvel publicerede som upublicerede tekster, hjemmesider, regneark, personlige meddelelser m.v.');
		$html->br();
		$this->drawReferences();
		echo '</div>';
		echo '</div>'; //padding-left
?>
<script type="text/javascript">
$(document).ready(function() {
	var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
	$("#stat-referencer td").each(function(index) {
		var html=$(this).html();
		html=html.replace(exp,"<a target=_blank style='text-decoration:none;' href='$1' target=_blank>$1</a>"); 
		$(this).html(html);
	});
});
</script>
<?	
	}

	private function drawReferences() {
		//$SQL='select distinct Referencenavn from allearter order by Referencenavn asc';
		$SQL='select distinct Referencetekst, Referencenavn from allearter order by Referencenavn asc';  

		echo '<table class="stat" id="stat-referencer"><thead>';
		$this->th('Referencenavn',200);
		$this->th('År', 30);
		$this->th('Referencetitel', 600);
		$this->th('Arter', 30);

		echo '</thead><tbody>';
		mysql_set_charset('utf8');
		$result=$this->query($SQL);
		$odd=false;
		$processed=array();
		while ($row = mysql_fetch_array($result)) {
			//$ref=$row['Referencenavn'];
			$ref=$row['Referencetekst'];
			if (!in_array($ref, $processed)) {
				if ($ref!='') {
					$this->drawRow($ref, $odd);
					$odd=($odd==false) ? true : false;
				}
				$processed[]=$ref;
			}
		}
		echo'</tbody></table>';
	}

	//14032015, $ref er nu referencetekst, ikke referencenavn
	private function drawRow($ref, $odd) {
		$classr = ($odd==true) ? ' class="right sodd"' : ' class="right"';
		$class = ($odd==true) ? ' class="sodd"' : '';

		$rt=$ref;
		$rt=str_replace('&','&amp;',$rt);

		$ref=mysql_real_escape_string($ref);
		/*
		$SQL='select Referencetekst, Reference_aar, '.
			"(select count(*) from allearter where Referencenavn='".$ref."') as arter ".
			' from allearter where Referencenavn="'.$ref.'"';
		*/
		$SQL='select Referencenavn, Reference_aar, '.
			"(select count(*) from allearter where Referencetekst='".$ref."') as arter ".
			' from allearter where Referencetekst="'.$ref.'"';

		$row=$this->getRow($SQL);
		//$tekst=($row['Referencetekst']!='') ? $row['Referencetekst'] : '-';
		//$tekst=str_replace('&','&amp;',$tekst);
		$aar=($row['Reference_aar']!='') ? intval($row['Reference_aar']) : '-';
		echo '<tr>';
		echo '<td '.$class.'>'.$row['Referencenavn'].'</td>';
		echo '<td '.$class.'>'.$aar.'</td>';
		echo '<td '.$class.'>'.$ref.'</td>';
		echo '<td '.$classr.'>'.$row['arter'].'</td>';

		echo '</tr>';
	}

}

$referencer = new Referencer();

?>
