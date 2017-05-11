<?
//fra http://stackoverflow.com/questions/2510434/php-format-bytes-to-kilobytes-megabytes-gigabytes
function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    // Uncomment one of the following alternatives
    $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow)); 

    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 

$target=$_GET['target'];
$d='upload/';
$files=array();
$dir = opendir($d);
while ($f = readdir($dir)) {
	if (substr($f,-4)=='.csv') { 
		array_push($files,"$f"); 
 	}
}

$count=1;
$html='<fieldset><legend>Uploadede CSV-filer</legend>';
foreach ($files as $file) {
	$id=$target.$count;
	$s=filesize($d.$file);
	$size=' ('.formatBytes($s,2).')';
	$html.='<input type="radio" id="'.$id.'" name="'.$target.'" value="'.$file.'"><label for="'.$id.'">'.$file.$size.'</label><br>';
	$count++;
}
$html.='</fieldset>';

echo $html;

?>
