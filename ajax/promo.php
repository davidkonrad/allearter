<?

//when called via ajax
if (!class_exists('Db')) {
	require_once('../common/Db.php');
}

class Promo extends Db {
	public $row;
	public $teasers; //string with 3 teasers separated by <br>

	public function __construct() {
		parent::__construct();
		mysql_set_charset('utf8');
		if (!$this->getExclusive()) $this->getRandom();
		$this->getTeasers();
	}
	private function getExclusive() {
		$SQL='select p.content, p.image_url, p.target, t.field_name from promos p, promos_target t where '.
			'p.target_id=t.target_id and exclusive=1 limit 1';
		if ($this->hasData($SQL)) {
			$this->row=$this->getRow($SQL);
			return true;
		} else {
			return false;
		}
	}
	private function getRandom() {
		$SQL='select p.content, p.image_url, p.target, t.field_name from promos p, promos_target t where '.
			'p.active=1 and p.target_id=t.target_id order by rand() limit 1';
		$this->row=$this->getRow($SQL);
	}

	private function getTeasers() {
		$SQL='select teaser_text from teasers order by rand() limit 2';
		mysql_set_charset('Latin1');
		$result=$this->query($SQL);
		$this->teasers='<br/><b>Eksempler på mulige søgninger</b>';
		while ($row = mysql_fetch_array($result)) {
			$this->teasers.='<br/><br/>'.$row['teaser_text'];
		}
		$this->teasers.='<br/><br/> &#9658;&nbsp;';
		$this->teasers.='<a href="http://allearter.dk/soegetips.htm" title="Udnyt allearters søgemuligheder bedre" style="color:teal;text-decoration:none;" style="white-space:nowrap;">Tips&nbsp;til&nbsp;søgning</a>';
	}
}

?>
