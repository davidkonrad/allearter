<!DOCTYPE html>
<html lang="da">
<head>
<meta charset="utf-8">
<title>API - Allearter.dk</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="bootstrap/css/docs.css" rel="stylesheet">
<link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
<script src="js/jquery-1.9.1.min.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>
<link href="css/style.css" rel="stylesheet">
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar">

   <div class="navbar navbar-fixed-top">
      <div class="navbar-inner-top">
        <div class="container logo">
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="http://allearter-databasen.dk">&laquo;&nbsp;allearter-databasen.dk</a></li>
              <li><a href="http://allearter.dk">allearter.dk&nbsp;&raquo;</a></li>
            </ul>
          </div>
        </div>

<?
$page = (isset($_GET['page'])) ? $_GET['page'] : 'generelt';
echo '<input type="hidden" id="page" value="'.$page.'">';
?>

   <div class="navbar navbar-inverse">
      <div class="navbar-inner">
        <div class="container">
          <div class="nav-collapse collapse">
            <ul class="nav">
			  <li id="menu-generelt"><a href="?page=generelt"><b>Generelt</b></a></li>
			  <li id="menu-artsopslag"><a href="?page=artsopslag">Eksempel : <b>Artsopslag</b></a></li>
			  <li id="menu-danskegaes"><a href="?page=danske-gæs">Eksempel : <b>Danske gæs</b></a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?
switch ($page) {
	case 'generelt' :
		include('generelt.inc');
		break;
	case 'artsopslag' :
		include('artsopslag.inc');
		break;
	case 'danske-gæs' :
		include('gaes.inc');
		break;
	default :
		break;
}
?>

<script type="text/javascript">
$(document).ready(function() {
	var page=$("#page").val();
	if (page=='generelt') $("#menu-generelt").addClass('active');
	if (page=='artsopslag') $("#menu-artsopslag").addClass('active');
	if (page=='danske-gæs') $("#menu-danskegaes").addClass('active');
});
</script>
</body>
</html>
