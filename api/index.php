<?
include('../common/Db.php');
include('Base.php');
include('Artsgrupper.php');
include('ArtsgruppeArter.php');
include('ArtsgruppeAntal.php');
include('Arter.php');
include('Art.php');
include('Rige.php');
include('Hierarki.php');
include('Referencer.php');

$get = (isset($_GET['get'])) ? $_GET['get'] : '';
$query = (isset($_GET['query'])) ? urldecode($_GET['query']) : '';

header('Content-type: application/json');

switch ($get) {

	case 'artsgrupper' :
		$run = new Artsgrupper($query);
		break;

	case 'artsgruppe-arter' :
		$run = new ArtsgruppeArter($query);
		break;

	case 'artsgruppe-antal' :
		$run = new ArtsgruppeAntal($query);
		break;

	case 'arter' :
		$run = new Arter($query);
		break;

	case 'art' :
		$run = new Art($query);
		break;

	case 'rige' :
		$run = new Rige($query);
		break;

	case 'hierarki' :
		$run = new Hierarki();
		break;

	case 'referencer' :
		$run = new Referencer($query);
		break;

	default : 
		break;
}

/* test

http://localhost/bif/api/?get=artsgrupper&query=%C3%A5r
http://localhost/bif/api/?get=artsgruppe-arter&query=fisk

*/
?>
