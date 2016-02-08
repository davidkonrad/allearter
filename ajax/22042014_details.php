<?
//if false, it is a lookup trough ajax
if (!headers_sent()) {
	header('Content-type: application/html');
}
if (!class_exists('Db')) {
	include('../common/Db.php');
}

class Details extends Db {
	private $row;
	private $ex;
	private $klassifikation;
	
	public function __construct() {
		parent::__construct();

		$this->klassifikation=array(
			'Rige'=>'Rige',
			'Række'=>'Raekke',
			'Underrække'=>'Underraekke',
			'Overklasse'=>'Overklasse',
			'Klasse'=>'Klasse',
			'Underklasse'=>'Underklasse',
			'Infraklasse'=>'Infraklasse',
			'Overorden'=>'Overorden',
			'Orden'=>'Orden',
			'Underorden'=>'Underorden',
			'Infraorden'=>'Infraorden',
			'Overfamilie'=>'Overfamilie',
			'Familie'=>'Familie',
			'Underfamilie'=>'Underfamilie',
			'Tribus'=>'Tribus',
			'Slægt'=>'Slaegt'
		);
	
		$ex=($_GET['lang']=='da') ? '_dk' : '';
		$this->ex=$ex;
		$html='<div class="details-cnt">';
		$SQL='select * from allearter where ID='.$_GET['id'];
		mysql_set_charset('utf8');
		$result=$this->query($SQL);
		$row=mysql_fetch_array($result);
		$this->row=$row;

		$artid=$row['ID'];

		$html.='<div class="details-item">';

		//$html.=$this->item('<span id="eol-image-'.$artid.'"><img src="images/eol-load.gif"></span>','');
		$html.=$this->Item($this->getImage($artid, $row['Videnskabeligt_navn']), '');

		$html.=$this->header('');
		$html.=$this->item('Artsgruppe :',$row['Artsgruppe'.$ex]);
		$html.=$this->item('Videnskabeligt navn :',$this->crlf($row['Videnskabeligt_navn']), true);
		$html.=$this->itemLong('Autor :',$row['Autor']);
		$html.=$this->item('Dansk navn :',$row['Dansk_navn']);
		$html.=$this->itemLong('Synonymer :',$this->crlf($row['Synonymer']), true);
		$html.=$this->itemLong('Danske synonymer :',$this->crlf($row['Synonymer_dk']), false);
		$html.=$this->item('Accepteret :',$row['Dansk']);
		$html.=$this->item('Taxontype :',$row['Taxonkategori']);
		$html.=$this->item('Taxonstatus :',$row['Taxonstatus']);
		
		$html.=$this->header('Klassifikation');

		/*
		$html.=$this->itemTitle('Rige :',$row['Rige'.$ex], 'Rige');
		$html.=$this->itemTitle('Række :',$row['Raekke'.$ex], 'Raekke');
		$html.=$this->itemTitle('Underrække :',$row['Underraekke'.$ex], 'Underraekke');
		$html.=$this->itemTitle('Overklasse :',$row['Overklasse'.$ex], 'Overklasse');
		$html.=$this->itemTitle('Klasse :',$row['Klasse'.$ex], 'Klasse');
		$html.=$this->itemTitle('Underklasse :',$row['Underklasse'.$ex], 'Underklasse');
		$html.=$this->itemTitle('Infraklasse :',$row['Infraklasse'.$ex], 'Infraklasse');
		$html.=$this->itemTitle('Overorden :',$row['Overorden'.$ex], 'Overorden');
		$html.=$this->itemTitle('Orden :',$row['Orden'.$ex], 'Orden');
		$html.=$this->itemTitle('Underorden :',$row['Underorden'.$ex], 'Underorden');
		$html.=$this->itemTitle('Infraorden :',$row['Infraorden'.$ex], 'Infraorden');
		$html.=$this->itemTitle('Overfamilie :',$row['Overfamilie'.$ex], 'Overfamilie');
		$html.=$this->itemTitle('Familie :',$row['Familie'.$ex], 'Familie');
		$html.=$this->itemTitle('Underfamilie :',$row['Underfamilie'.$ex], 'Underfamilie');
		$html.=$this->itemTitle('Tribus :',$row['Tribus'.$ex], 'Tribus');
		$html.=$this->itemTitle('Slægt :', '<i>'.$row['Slaegt'.$ex].'</i>', 'Slaegt');
		*/

		foreach ($this->klassifikation as $field=>$value) {
			if ($row[$value]!='') {
				$k='<em>'.$row[$value].'</em>';
				if ($row[$value.'_dk']!='' && $row[$value.'_dk']!=$k) {
					$k.='&nbsp;('.$row[$value.'_dk'].')';
				}
				$html.=$this->itemKlassifikation($field.' :', $k);
			}
		}
		$html.='</div>';

		$html.='<div class="details-item">';

		$tax=$this->crlf($row['Videnskabeligt_navn']);
		$tax=str_replace(' ','+',$tax);
		$href='<a id="eol-link-'.$artid.'" taxon="'.$tax.'" href="" target=_blank style="float:left;color:teal;margin-top:10px;" title="Søger efter '.$row['Videnskabeligt_navn'].' på eol.org (åbner i nyt vindue/tab)">Se arten på Encyclopedia of Life</a>';
		$html.=$this->item($href,''); 

		$href=$this->getCatOfLife($tax);
		if ($href!='') {
			$href='<a href="'.$href.'" target=_blank style="float:left;color:teal;margin-top:3px;" title="Arten på Catalog of Lifes seneste tjekliste">Se arten på Catalog of Life</a>';
			$html.=$this->item($href,'');
		}

		$href=$this->getNobanis($row['Videnskabeligt_navn']);
		if ($href!='') {
			$href='<a href="'.$href.'" target=_blank style="float:left;color:teal;margin-top:3px;" title="Arten på Nobanis - The European Network on Invasive Alien Species">Se arten på Nobanis</a>';
			$html.=$this->item($href,'');
		}

		$href=$this->getNobanisFactSheet($row['Videnskabeligt_navn']);
		if ($href!='') {
			$href='<a href="'.$href.'" target=_blank style="float:left;color:teal;margin-top:3px;" title="Nobanis Factsheet - The European Network on Invasive Alien Species">Nobanis factsheet</a>';
			$html.=$this->item($href,'');
		}
		
		$html.=$this->header('');
		$html.=$this->item('Dato :', $this->getDato($row['Dato']));
		$html.=$this->item('Sortering :',$this->crlf($row['Sortering']));

		$html.=$this->header('Noter');
		$html.=$this->item('Systematik :',$row['Noter_systematik']);
		$html.=$this->item('Økologi :',$row['Noter_oekologi']);
		$html.=$this->item('Nye arter :',$row['Noter_nye_arter']);

		$html.=$this->header('Forvaltningskategorier');
		$html.=$this->item('Den danske rødliste :',$this->crlf($row['Den_danske_roedliste']));
		$html.=$this->item('Fredede arter :',$row['Fredede_arter']);	
		$html.=$this->item('EF-habitatdirektivet :',$row['Habitatdirektivet']);
		$html.=$this->item('EF-fuglebeskyttelsesdirektivet :',$row['Fuglebeskyttelsesdirektivet']);
		$html.=$this->item('Bern-konventionen :',$row['Bern_konventionen']);
		$html.=$this->item('Bonn-konventionen :',$row['Bonn_konventionen']);
		$html.=$this->item('CITES :',$row['CITES']);
		$html.=$this->item('Øvrige :',$row['Oevrige']);

		$html.=$this->header('NOBANIS');
		$html.=$this->item('NOBANIS-arter :',$row['NOBANIS_arter']);
		$html.=$this->item('Herkomst :',$row['NOBANIS_herkomst']);
		$html.=$this->item('Etableringsstatus :',$row['NOBANIS_etableringsstatus']);
		$html.=$this->item('Invasiv optræden :',$row['NOBANIS_invasiv_optraeden']);

		$html.=$this->header('Referencer');
		$html.=$this->itemLong('Referencenavn :',$this->crlf($row['Referencenavn']));
		$html.=$this->item('Referenceår :',$row['Reference_aar']);
		
		$text=$this->linkify($row['Referencetekst']);
		//$text=wordwrap($text, 40, '<br>', true);
		$html.=$this->itemLong('Referencetekst :', $text );
		
		$html.='</div>';
		echo $html;
	}

	private function header($caption) {
		return '<span class="details-header">'.$caption.'</span>'."\n";
	}

	private function item($caption, $value, $italic=false) {
		if ($italic) {
			return '<span class="details-caption">'.$caption.'</span><span class="details-value" style="font-style:italic;">'.$value.'</span>'."\n";
		} else {
			return '<span class="details-caption">'.$caption.'</span><span class="details-value">'.$value.'</span>'."\n";
		}
	}

	private function itemLong($caption, $value, $italic=false) {
		if ($italic) {
			return '<span class="details-caption">'.$caption.'</span><span class="details-value-long" style="font-style:italic;">'.$value.'</span>'."\n";
		} else {
			return '<span class="details-caption">'.$caption.'</span><span class="details-value-long">'.$value.'</span>'."\n";
		}
	}

	private function isImage($image) {
		return !in_array($image, array('','FAIL','EXCLUDED'));
	}

	private function correctImage($image) {
		//echo $image;

		$image = str_replace('content60','content70',$image);
		$image = str_replace('content61','content71',$image);
		$image = str_replace('content62','content72',$image);
		$image = str_replace('content63','content73',$image);
		$image = str_replace('content64','content74',$image);
		$image = str_replace('content65','content75',$image);
		$image = str_replace('content66','content76',$image);
		$image = str_replace('content67','content77',$image);
		$image = str_replace('content68','content78',$image);

		//echo $image;

		return $image;
	}

	private function getImage($artid, $taxon) {
		$ret = '<span id="eol-image-'.$artid.'" class="eol-image">';

		$SQL='select * from taxon_image where taxon="'.$taxon.'"';
		$row=$this->getRow($SQL);
		if (is_array($row) && $this->isImage($row['url'])) {
			//$ret.='<img src="'.$this->correctImage($row['url']).'"/></span>';
			$ret.='<img src="'.$row['url'].'"></span>';
		} else {
			$ret.='<img src="images/eol-load.gif"></span>';
		}
		return $ret;
	}

	private function itemKlassifikation($caption, $value) {
		return '<span class="details-caption">'.$caption.'</span><span class="details-value">'.$value.'</span>'."\n";
	}

	private function itemTitle($caption, $value, $field) {
		if ($this->ex!='') {
			$title=$this->row[$field];
		} else {
			$title=$this->row[$field.'_dk'];
		}
		$title= ' title="'.$title.'"';		
		return '<span class="details-caption">'.$caption.'</span><span class="details-value"'.$title.'">'.$value.'</span>'."\n";
	}

	//05.05.2013
	private function getCatOfLife($taxon) {
		$SQL='select url from CatOfLife where taxon="'.$taxon.'"';
		$row=$this->getRow($SQL);
		if (isset($row['url'])) {
			if ($row['url']!='') {
				return $row['url'];
			} else {
				return '';
			}
		} else {
			$url='http://www.catalogueoflife.org/annual-checklist/2012/webservice';
			$url.='?name='.$taxon;
			$url.='&format=php';

			$result=file_get_contents($url);
			$result=unserialize($result);

			$url=(isset($result['results'][0]['url'])) ? $result['results'][0]['url'] : '';

			$SQL='insert into CatOfLife set '.
				'taxon="'.$taxon.'", '.
				'url="'.$url.'"';
			$this->exec($SQL);

			return $url;
		}
	}

	private function linkify($text) {
		//http://stackoverflow.com/questions/507436/how-do-i-linkify-urls-in-a-string-with-php
		$result = preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[A-Z0-9+&@#\/%=~_|]/i', '<a href="\0" target=_blank title="Se reference (åbner i nyt vindue)" style="color:teal;">\0</a>', $text);
		return $result;
	}

	//15.05.2013
	private function getNobanis($taxon) {
		$SQL='select * from Nobanis where speciesName="'.$taxon.'"';
		$row=$this->getRow($SQL);
		if (isset($row['taxaID'])) {
			//return 'http://www.nobanis.org/speciesInfo.asp?taxaID='.$row['taxaID'];
			return 'http://www.nobanis.org/NationalInfo.asp?countryID=DK&taxaID='.$row['taxaID'];
		}
		return '';
	}

	private function getNobanisFactSheet($taxon) {
		$SQL='select factSheet from Nobanis where speciesName="'.$taxon.'"';
		$row=$this->getRow($SQL);
		if (isset($row['factSheet']) && $row['factSheet']!='') {
			$f=str_replace(' ', '%20', $row['factSheet']);
			return 'http://www.nobanis.org/files/factsheets/'.$f;
		}
		return '';
	}

	//23.05.2013
	private function getDato($dato) {
		//come in form 9-2-2010 00:00:00, strip h:m:s
		$dato=explode(' ',$dato);
		return $dato[0];
	}
}

$details = new Details();

?>
