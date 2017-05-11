<?

include('Db.php');

class HTML extends Db {

	public function __construct() {
		parent::__construct();
	}

	public function h1($text) {
		echo '<h1>'.$text.'</h1>'."\n";
	}

	public function h2($text) {
		echo '<h2>'.$text.'</h2>'."\n";
	}

	public function h3($text) {
		echo '<h3>'.$text.'</h3>'."\n";
	}

	public function p($text) {
		echo '<p>'.$text.'</p>';
	}

	public function th($text) {
		echo '<th>'.$text.'</th>';
	}

	public function br($count=1) {
		for ($i=1;$i<=$count;$i++) {
			echo '<br/>';
		}
	}
		
	public function divider($height) {
		echo '<div class="divider" style="height:'.$height.'px;"></div>';
	}

	public function getNumberOfSpecies() {
		$SQL='select count(*) from allearter';
		$row=$this->getRow($SQL);
		return $row['count(*)'];
	}

	public function getOptionsVidenskabeligtNavn() {
		$SQL='select distinct Videnskabeligt_navn from allearter order by Videnskabeligt_navn asc';
		$result=$this->query($SQL);
		$HTML='<option value="">Videnskabeligt navn</option>';
		while ($row=mysql_fetch_array($result)) {
			$HTML.='<option value="'.$row['Videnskabeligt_navn'].'">'.$row['Videnskabeligt_navn'].'</option>'."\n";
		}
		return $HTML;
	}

	public function getOptionsArtsgruppe() {
		$SQL='select distinct Artsgruppe from allearter order by Artsgruppe asc';
		$result=$this->query($SQL);
		$HTML='<option value="">[Videnskabeligt navn]</option>';
		while ($row=mysql_fetch_array($result)) {
			$HTML.='<option value="'.$row['Artsgruppe'].'">'.$row['Artsgruppe'].'</option>'."\n";
		}
		return $HTML;
	}

	public function getOptionsColumnNames($column='Videnskabeligt_navn') {
?>
<option value="Artsgruppe">Artsgruppe</option>
<option value="Artsgruppe_dk">Artsgruppe (dk)</option>
<option value="Videnskabeligt_navn" selected="selected">Videnskabeligt navn</option>
<option value="Autor">Autor</option>
<option value="Dansk_navn">Dansk navn</option>
<option value="Dansk">Accepteret</option>
<option value="Taxonkategori">Taxontype</option>
<option value="Taxonstatus">Taxonstatus</option>
<option value="Sortering">Sortering</option>
<option value="Rige">Rige</option>
<option value="Rige_dk">Rige (dk)</option>
<option value="Raekke">Række</option>
<option value="Raekke_dk">Række (dk)</option>
<option value="Underraekke">Underrække</option>
<option value="Underraekke_dk">Underrække (dk)</option>
<option value="Overklasse">Overklasse</option>
<option value="Overklasse_dk">Overklasse (dk)</option>
<option value="Klasse">Klasse</option>
<option value="Klasse_dk">Klasse (dk)</option>
<option value="Underklasse">Underklasse</option>
<option value="Underklasse_dk">Underklasse (dk)</option>
<option value="Infraklasse">Infraklasse</option>
<option value="Infraklasse_dk">Infraklasse (dk)</option>
<option value="Overorden">Overorden</option>
<option value="Overorden_dk">Overorden (dk)</option>
<option value="Orden">Orden</option>
<option value="Orden_dk">Orden (dk)</option>
<option value="Underorden">Underorden</option>
<option value="Underorden_dk">Underorden (dk)</option>
<option value="Infraorden">Infraorden</option>
<option value="Infraorden_dk">Infraorden (dk)</option>
<option value="Overfamilie">Overfamilie</option>
<option value="Overfamilie_dk">Overfamilie (dk)</option>
<option value="Familie">Familie</option>
<option value="Familie_dk">Familie (dk)</option>
<option value="Underfamilie">Underfamilie</option>
<option value="Underfamilie_dk">Underfamilie (dk)</option>
<option value="Tribus">Tribus</option>
<option value="Tribus_dk">Tribus (dk)</option>
<option value="Slaegt">Slægt</option>
<option value="Slaegt_dk">Slægt (dk)</option>
<option value="Synonymer">Synonymer</option>
<option value="Synonymer_dk">Synonymer (dk)</option>
<option value="Referencenavn">Referencenavn</option>
<option value="Reference_aar">Referenceår</option>
<option value="Referencetekst">Referencetekst</option>
<option value="Noter_systematik">Noter (systematik)</option>
<option value="Noter_oekologi">Noter (økologi)</option>
<option value="Noter_nye_arter">Noter (nye arter)</option>
<!--<option value="Dato">Dato</option>-->
<option value="Den_danske_roedliste">Rødlistestatus</option>
<option value="Fredede_arter">Fredningsstatus</option>
<option value="Habitatdirektivet">Habitatdirektivet</option>
<option value="Fuglebeskyttelsesdirektivet">Fuglebeskyttelsesdirektivet</option>
<option value="Bern_konventionen">Bern-konventionen</option>
<option value="Bonn_konventionen">Bonn-konventionen</option>
<option value="CITES">CITES</option>
<option value="Oevrige">Øvrige forvaltningskategorier</option>
<option value="NOBANIS_arter">NOBANIS-arter</option>
<option value="NOBANIS_herkomst">NOBANIS (herkomst)</option>
<option value="NOBANIS_etableringsstatus">NOBANIS (etableringsstatus)</option>
<option value="NOBANIS_invasiv_optraeden">NOBANIS (invasiv optræden)</option>
<?
/*
		$SQL='select column_name from information_schema.columns where table_name="allearter"';
		$result=$this->query($SQL);
		$html='';
		while ($row=mysql_fetch_array($result)) {
			$checked=($row['column_name']==$column) ? ' selected="selected"' : '';
			$html.='<option value="'.$row['column_name'].'"'.$checked.'>'.$row['column_name'].'</option>';
		}
		return $html;
*/
	}	

	public function encodeHTML($text) {
		$text=str_replace('"','&quot;',$text);

		$text=str_replace('Æ','&AElig;',$text);
		$text=str_replace('æ','&aelig;',$text);

		$text=str_replace('Ø','&Oslash;',$text);
		$text=str_replace('ø','&oslash;',$text);

		$text=str_replace('Å','&Aring;',$text);
		$text=str_replace('å','&aring;',$text);

		$text=str_replace('²','&sup2;',$text);

		$text=str_replace('è','&egrave;',$text);
		$text=str_replace('é','&eacute;',$text);

		$text=str_replace('Ä','&Atilde;',$text);
		$text=str_replace('ä','&atilde;',$text);
	
		return $text;
	}

}

?>
