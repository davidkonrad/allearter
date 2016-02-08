<?
	$destination_path = 'upload/';
 
	$result = 'Upload mislykkedes ...';
 
	$target_path = $destination_path . basename( $_FILES['file']['name']);
	//$target_path = $destination_path . 'allearter.csv';//basename( $_FILES['file']['name']);
 
   	if(@move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
		$result=$_FILES['file']['name'].' er blevet uploadet!';
		if (chmod($target_path, 0777)) {
			$result.='<br>Permission->0777 lykkedes!';
		} else {
			$result.='<br>Reset af permission mislykkedes ...';
		}
		$zip = new ZipArchive();
		$x = $zip->open($target_path);
		if ($x === true) {
			$zip->extractTo($destination_path);
			$zip->close();
			$result.='<br>Udpakning af zip lykkedes!';
		} else {
			$result.='<br>Zip-udpakning mislykkedes ...';
		}
	}

	sleep(1);
?>
<script type="text/javascript">
window.top.window.Db.end('<? echo $result;?>');
window.top.window.Db.readCSVfiles('konv');
window.top.window.Db.readCSVfiles('test');
</script>
<?

?>
