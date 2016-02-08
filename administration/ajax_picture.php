<?

include('../common/Db.php');

class Pictures extends Db {
	private $abc=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','x','y','z');
	private $accepted;

	public function __construct() {
		parent::__construct();
		$this->accepted = (isset($_GET['accepted'])) ? ($_GET['accepted']=='yes') : true;
		switch ($_GET['action']) {
			case 'saveaccept' : $this->saveAccept();break;
			case 'accept' : $this->setAccept();break;
			case 'update' : $this->update();break;
			case 'remove' : $this->remove();break;
			default : break;
		}
		$this->drawList();
		$this->drawScript();
	}

	private function setAccept() {
		$SQL='update taxon_image set accepted="'.$_GET['new_accept'].'" where taxon="'.$_GET['taxon'].'"';
		$this->exec($SQL);
	}
		
	private function remove() {
		$SQL='delete from taxon_image where taxon="'.$_GET['taxon'].'"';
		$this->exec($SQL);
	}
		
	private function update() {
		$SQL='update taxon_image set url="'.$_GET['url'].'" where taxon="'.$_GET['taxon'].'"';
		$this->exec($SQL);
	}

	//reuse functions, assume new_accept is 1, url and taxon is set
	private function saveAccept() {
		$this->update();
		$this->setAccept();
	}

	private function drawItem($row) {
		$taxon=str_replace(' ','-',$row['taxon']);
		$taxon=str_replace('.','-',$taxon); //ssp. etc
		$taxon=str_replace('--','',$taxon); //ssp. etc
		echo '<tr class="list">';
		echo '<td style="width:110px;height:90px;">';

		if (($row['url']=='FAIL') || ($row['url']=='EXCLUDED')) {
			echo '<img id="img-'.$taxon.'" fail="fail" src="../images/cancel.png" style="XXXwidth:100px;margin-top:-5px;position:absolute;z-index:2;overflow:hidden;"/></td>';
		} else {
			echo '<img id="img-'.$taxon.'" src="'.$row['url'].'" style="width:100px;margin-top:-35px;position:absolute;z-index:2;overflow:hidden;"/></td>';
		}

		echo '<td><input type="text" taxon="'.$taxon.'" real-taxon="'.$row['taxon'].'" id="edit-'.$taxon.'" style="width:650px;padding:3px;font-size:1.4em;" value="'.$row['url'].'"/>';
		echo '<input type="button" value="Gem" onclick="Pictures.updatePicture(&quot;'.$taxon.'&quot;);"/>';
		if (!$this->accepted) {
			echo '<input type="button" value="Gem &amp; Godkend" onclick="Pictures.saveAndAccept(&quot;'.$taxon.'&quot;);"/>';
		}
		echo '<br>';
		echo '<i>'.$row['taxon'].'</i><br>'.$row['Dansk_navn'];
		
		if ($row['url']=='FAIL') {
			echo '<br><input type="button" value="Fjern fra liste (nulstil)" style="font-size:0.8em;" onclick="Pictures.removeFromList(&quot;'.$taxon.'&quot;);"/>';
			echo '<input type="button" value="EXCLUDE" title="Fjern fra fremtidige visninger og opslag" style="font-size:0.8em;margin-left:20px;" onclick="Pictures.setEXCLUDED(&quot;'.$taxon.'&quot;);"/>';
		} elseif ($row['url']=='EXCLUDED') {
			echo '<br><input type="button" value="Forfrem til FAIL" title="Billede vil blive vist / forsøgt fundet" style="font-size:0.8em;" onclick="Pictures.setFAIL(&quot;'.$taxon.'&quot;);"/>';
		} else {
			echo '<br><input type="button" value="  FAIL  " title="Nulstil billede" style="font-size:0.8em;" onclick="Pictures.setFAIL(&quot;'.$taxon.'&quot;);"/>';
			echo '<input type="button" value="EXCLUDE" title="Fjern fra fremtidige visninger og opslag" style="font-size:0.8em;margin-left:20px;" onclick="Pictures.setEXCLUDED(&quot;'.$taxon.'&quot;);"/>';
		}
		$checked=($row['accepted']=='1') ? ' checked="checked"': '';
		$title=($row['accepted']=='1') ? 'Ophæv godkendelse': 'Godkend billedet';
		$godkend=($row['accepted']=='1') ? '0': '1';
		echo '<span style="float:right;"><label style="">Godkend</label><input type="checkbox" title="'.$title.'" style="" onchange="Pictures.setAccepted(&quot;'.$taxon.'&quot;,'.$godkend.');"'.$checked.'/></span>';
		echo '</td></tr>';
		echo '<tr><td colspan=2 class="sep">&nbsp;</tr>';
	}
		
	private function drawMenu() {
		$letter = (isset($_GET['letter'])) ? $_GET['letter'] : 'a';
		echo '<input type="hidden" id="letter" value="'.$letter.'"/>';
		foreach ($this->abc as $char) {
			$class = ($char==$letter) ? 'abc abc-selected' : 'abc';
			echo '<span class="'.$class.'" onclick="Pictures.changeLetter(&quot;'.$char.'&quot;);">'.$char.'</span>';
		}
		if (!$this->accepted) {
			echo '&nbsp;&middot;&nbsp;';
			$class = ('FAIL'==$letter) ? 'abc abc-selected smaller' : 'abc smaller';
			echo '<span class="'.$class.'" onclick="Pictures.changeLetter(&quot;FAIL&quot;);">FAIL</span>';

			echo '&nbsp;&middot;&nbsp;&nbsp;';
			$class = ('EXCLUDED'==$letter) ? 'abc abc-selected smaller' : 'abc smaller';
			echo '<span class="'.$class.'" onclick="Pictures.changeLetter(&quot;EXCLUDED&quot;);">EXCLUDED</span>';
		}
	}

	private function drawHeader() {
		$html='<small style="color:gray;float:left;clear:both;">';

		$SQL='select count(*) from taxon_image'; 
		$row=$this->getRow($SQL);
		$html.='<b>'.$row['count(*)'].'</b> billedreferencer i alt :';

		$SQL='select count(*) from taxon_image where accepted="1"'; 
		$row=$this->getRow($SQL);
		$html.='<b>'.$row['count(*)'].'</b> godkendte billeder. ';

		$SQL='select count(*) from taxon_image where accepted="0" and url<>"EXCLUDED"'; 
		$row=$this->getRow($SQL);
		$html.='<b>'.$row['count(*)'].'</b> afventer godkendelse. ';

		$SQL='select count(*) from taxon_image where url="FAIL"';
		$row=$this->getRow($SQL);
		$html.='<b>'.$row['count(*)'].'</b> "FAIL". ';

		$SQL='select count(*) from taxon_image where url="EXCLUDED"';
		$row=$this->getRow($SQL);
		$html.='<b>'.$row['count(*)'].'</b> "EXCLUDED". ';

		$html.='</small><br/>';
		echo $html;

		echo '<div id="accepted" style="float:left;clear:none;">';
		if ($this->accepted) {
			echo '<input type="radio" name="accepted" id="is_accepted" checked="checked" onchange="Pictures.changeAccepted();"/><label id="acceptedLabel" for="is_accepted">Godkendte</label>';
			echo '<input type="radio" name="accepted" id="is_not_accepted" onchange="Pictures.changeAccepted();"/><label for="is_not_accepted" id="notAcceptedLabel">Ikke godkendte</label>';
		} else {
			echo '<input type="radio" name="accepted" id="is_accepted" onclick="Pictures.changeAccepted();" /><label id="acceptedLabel" for="is_accepted">Godkendte</label>';
			echo '<input type="radio" name="accepted" id="is_not_accepted" checked="checked" onclick="Pictures.changeAccepted();" id="notAcceptedLabel"/><label for="is_not_accepted">Ikke godkendte</label>';
		}
		echo '</div>';
	}
		
	private function drawList() {
		$this->drawHeader();
		$this->drawMenu();

		if ($this->accepted) {
			$this->drawListAccepted();
		} else {
			$this->drawListNotAccepted();
		}
	}

	private function drawListAccepted() {
		$letter = (isset($_GET['letter'])) ? $_GET['letter'] : 'a';

		$SQL='select t.taxon, t.url, t.accepted, a.Dansk_navn from taxon_image t, allearter a ';
		$SQL.='where t.url<>"FAIL" and t.url<>"EXCLUDED" and t.accepted="1" and t.taxon=a.Videnskabeligt_navn and t.taxon like "'.$letter.'%"';
		$SQL.=' order by t.taxon';

		mysql_set_charset('utf8');
		$result = $this->query($SQL);
		
		echo '<table>';
		while ($row = mysql_fetch_array($result)) {
			$this->drawItem($row);
		}
		echo '</table>';
	}

	private function drawListNotAccepted() {
		$letter = (isset($_GET['letter'])) ? $_GET['letter'] : 'a';

		$SQL='select t.taxon, t.url, t.accepted, a.Dansk_navn from taxon_image t, allearter a ';
		
		if ($letter=='FAIL') {
			$SQL.='where t.url="FAIL" and t.taxon=a.Videnskabeligt_navn';
		} elseif ($letter=='EXCLUDED') {
			$SQL.='where t.url="EXCLUDED" and t.taxon=a.Videnskabeligt_navn';
		} else {
			$SQL.='where t.url<>"FAIL" and t.url<>"EXCLUDED" and t.accepted="0" and t.taxon=a.Videnskabeligt_navn and t.taxon like "'.$letter.'%"';
		}
		$SQL.=' order by t.taxon';

		mysql_set_charset('utf8');
		$result = $this->query($SQL);
		
		echo '<table>';
		while ($row = mysql_fetch_array($result)) {
			//echo $row['taxon'].'<br>';
			$this->drawItem($row);
		}
		echo '</table>';
	}

	private function drawScript() {
?>
<script type="text/javascript">
$(document).ready(function() {
$("#accepted").buttonset();
$('img').each(function(index) {
	if ($(this).attr('fail')==undefined) {
		$(this).live("mouseover",function() {
			$(this).css('z-index','45');
			$(this).css('width','480px');
			$(this).css('height','auto');
		});
		$(this).live("mouseout",function() {
			$(this).css('z-index','1');
			$(this).css('width','100px');
			$(this).css('height','auto');
		});
	}
});
$("input[type=text]").bind('input propertychange', function(e) {
	var t=$(this).attr('taxon');
	$("#img-"+t).attr('src',$(this).val());
});
});
</script>
<?
	}			
}

$pictures = new Pictures();

?>
