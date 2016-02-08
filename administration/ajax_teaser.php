<?

include('../common/Db.php');

class Teasers extends Db {

	public function __construct() {
		parent::__construct();
		switch ($_GET['action']) {
			case 'insert' : $this->insert();break;
			case 'update' : $this->update();break;
			case 'delete' : $this->delete();break;
			default : break;
		}
		$this->drawList();
	}

	private function delete() {
		$SQL='delete from teasers where teaser_id='.$_GET['id'];
		$this->exec($SQL);
	}

	private function update() {
		$SQL='update teasers set teaser_text='.$this->q($_GET['text'], false).' where teaser_id='.$_GET['id'];
		$this->exec($SQL);
	}

	private function insert() {
		$SQL='insert into teasers (teaser_text) values('.$this->q($_GET['text'], false).')';
		$this->exec($SQL);
	}
		
	private function drawItem($caption, $id=false) {
		if (!$id) {
			echo '<input type="button" onclick="Teaser.insert();" class="teaser-button" value="Opret"/>';
		} else {
			echo '<input type="button" onclick="Teaser.update('.$id.');" class="teaser-button" value="Gem"/>';
		}
		echo '<input type="text" style="width:700px;" id="teaser_text'.$id.'" value="'.$caption.'"/>';
		if ($id) {
			echo '<input type="button" onclick="Teaser.Delete('.$id.');" class="teaser-button" value="Slet"/>';
		}
		echo '<br>';
	}
		
	private function drawList() {
		$SQL='select * from teasers';
		mysql_set_charset('Latin1');
		$result = $this->query($SQL);
		while ($row = mysql_fetch_array($result)) {
			$this->drawItem($row['teaser_text'], $row['teaser_id']);
		}
		$this->drawItem('');
	}
		
}
			

$teasers = new Teasers();

?>
