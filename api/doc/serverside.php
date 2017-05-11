<?
$JSON=file_get_contents('http://allearter-databasen.dk/api/?get=artsgruppe-arter&query=fisk');
$data=json_decode($JSON, true);

$HTML='<h2>Danske fiskearter</h2>';
$HTML.='<table>';
$HTML.='<thead><tr><th>Latinsk navn</th><th>Dansk navn</th></tr></thead>';

foreach ($data['allearter'] as $fiskeart) {
	$HTML.='<tr><td>'.$fiskeart['Videnskabeligt_navn'].'</td><td>'.utf8_decode($fiskeart['Dansk_navn']).'</td></tr>';
}

echo $HTML;
?>
