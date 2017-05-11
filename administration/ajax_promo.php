<?

include('../common/Db.php');

class Promo extends Db {
	private $selected_id;
	
	public function __construct() {
		parent::__construct();
		if (!isset($_GET['action'])) {
			$this->drawTable();
		} else {
			switch ($_GET['action']) {
				case 'create' : $this->createPromo(); break;
				case 'save' : $this->savePromo($_GET['id']); break;
				case 'edit' : $this->drawTableID($_GET['id']); 
						$this->selected_id=$_GET['id'];
						break;
				case 'delete' : $this->deletePromo($_GET['id']); break;
				default : break;
			}
		}
		$this->drawList();	
		$this->CKEditor();
		$this->bottomSpace();
	}

	private function CKEditor() {
?>
<script type="text/javascript">
$(document).ready(function() {
	var instance = CKEDITOR.instances['content'];
	if (instance){
        	CKEDITOR.remove(instance);
	}
	//CKEDITOR.replace(id);
	CKEDITOR.replace('content', {width:"600px",height:"200px"});
});
</script>
<style type="text/css">
tr.selected {
	background-color: silver;
}
</style>
<?
	}

	private function bottomSpace() {
?>
<div style="clear:both;float:left;width:100%;height:50px;"><hr></div>
<?
	}

	private function drawList() {
		$html='<div style="width:100%;clear:both;float:left;height:40px;"><hr></div><table style="clear:both;float:left;">';
		$html.='<thead><th></th><th>Redigeret</th><th>Tekst</th><th>Søgning</th><th>Aktiv</th><th></th></thead><tbody>';
		//mysql_set_charset('Latin1');
		//$SQL='select id, changed, substr(content,1,60) as content, target, active from promos';
		$SQL='select id, changed, content, target, active from promos';
		mysql_set_charset('utf8');
		$result=$this->query($SQL);
		while ($row = mysql_fetch_array($result)) {
			$selected=($row['id']==$this->selected_id) ? ' class="selected"' : '';
			$html.='<tr id="'.$row['id'].'"'.$selected.'>';
			$html.='<td><input type="button" value="Red." style="font-size:11px;" onclick="Promo.edit('.$row['id'].');"/></td>';
			$html.='<td>'.$row['changed'].'</td>';
			$html.='<td><div style="width:440px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">'.strip_tags($row['content']).'</div></td>';
			$html.='<td>'.$row['target'].'</td>';

			$html.='<td style="text-align:center;">';
			if ($row['active']=='1') $html.='&#9679;';
			$html.='</td>';

			$html.='<td><input type="button" value="Slet!" style="font-size:11px;" onclick="Promo.deletePromo('.$row['id'].');"/></td>';
			$html.='</tr>';
		}
		$html.='</tbody></table>';
		echo $html;
	}
		

	private function drawImgCnt($img=null) {
?>
<div style="float:left;clear:none;margin-left:20px;border-left:1px solid #ebebeb;background-color:snow;">
<h5>Billede (resizes til 160px højde) :</h5>
<img src="<? if($img!=null) echo $img;?>" id="img-cnt" style="height:160px;">
</div>
<?	}

	private function deletePromo($id) {
		$SQL='delete from promos where id='.$id;
		$this->exec($SQL);
		$this->drawTable();
	}

	private function createPromo() {
		$SQL='insert into promos (target_id, target, image_url, content, exclusive) values ('.
			$this->q($_GET['target_id']).
			$this->q($_GET['target']).
			$this->q($_GET['image_url']).
			$this->q($_GET['content']).
			$this->q($_GET['exclusive'], false);
		$SQL.=')';
		$this->exec($SQL);
		$id=mysql_insert_id();
		$this->drawTableID($id);		
	}

	private function savePromo($id) {
		$SQL='update promos set '.
			'target_id='.$this->q($_GET['target_id']).
			'target='.$this->q($_GET['target']).
			'image_url='.$this->q($_GET['image_url']).
			'content='.$this->q(stripslashes($_GET['content'])).
			'active='.$this->q($_GET['active']).
			'exclusive='.$this->q($_GET['exclusive'], false);
		$SQL.=' where id='.$id;
		$this->exec($SQL);
		$this->drawTableID($id);		
	}
		
	private function targetSelect($id=-1) {
		$html='<select id="target_id" name="target_id">';
		$SQL='select * from promos_target';
		$result=$this->query($SQL);
		while ($row = mysql_fetch_array($result)) {
			$checked=($id==$row['target_id']) ? ' selected="selected"' : '';
			$html.='<option value="'.$row['target_id'].'"'.$checked.'>'.$row['field_name'].'</option>';
		}
		$html.='<select>';
		return $html;
	}
			
	private	function drawTable() {
?>
<h3>Opret ny promo</h3>
<table style="width:400px;float:left;clear:none;">
<tr>
<td>Databasefelt</td>
<td><? echo $this->targetSelect();?></td>
</tr>
<tr>
<td>Søgning</td>
<td><input type="text" id="target" name="target" value="" size="40"/></td>
<tr>
<td>Billed-URL</td>
<td><input type="text" id="image_url" name="image_url" value="" size="70"/>
</tr>
<tr>
<td>Eksklusiv</td>
<td><input type="checkbox" id="exclusive" name="exclusive" value=""/>
</tr>
<tr>
<td style="vertical-align:top;">Text / indhold</td>
<td>
<textarea name="content" id="content"></textarea>
</td>
</tr>
<tr>
<td></td>
<td><input type="submit" value="Opret" onclick="Promo.create();"/>
</td>
</tr>
</table>
<?
	$this->drawImgCnt();
	}

	//lazy, exact copy paste, but with SQL.ookup
	private	function drawTableID($id) {
		$SQL='select * from promos where id='.$id;
		$row=$this->getRow($SQL);
?>
<h3>Rediger promo</h3>
<table style="width:400px;float:left;clear:none;">
<tr>
<td>Databasefelt</td>
<td><? echo $this->targetSelect($row['target_id']);?></td>
</tr>
<tr>
<td>Søgning</td>
<td><input type="text" id="target" name="target" value="<? echo $row['target'];?>" size="40"/></td>
<tr>
<td>Billed-URL</td>
<td><input type="text" id="image_url" name="image_url" value="<? echo $row['image_url'];?>" size="70"/>
</tr>
<tr>
<td>Aktiv</td>
<? $checked=($row['active']==1) ? ' checked="checked"': ''; ?>
<td><input type="checkbox" id="active" name="active" <? echo $checked;?>/>
</tr>
<tr>
<td>Eksklusiv</td>
<? $checked=($row['exclusive']==1) ? ' checked="checked"': ''; ?>
<td><input type="checkbox" id="exclusive" name="exclusive" <? echo $checked;?>/>
</tr>
<tr>
<td style="vertical-align:top;">Text / indhold</td>
<td>
<textarea name="content" id="content"><? echo stripslashes($row['content']);?></textarea>
</td>
</tr>
<tr>
<td></td>
<td><input type="submit" value="Gem" onclick="Promo.save(<? echo $row['id']?>);"/>
</td>
</tr>
</table>
<?
	$this->drawImgCnt($row['image_url']);
	}
}

$promo = new Promo();
?>
