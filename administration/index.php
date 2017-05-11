<?
//include('ajax_promo.php');

session_start();

if (!$_SESSION['logged_in']) {
	header('location:login.php');
}

if (isset($_GET['menu'])) {
	$_SESSION['menu']=$_GET['menu'];
} else {
	if (!isset($_SESSION['menu'])) {
		$_SESSION['menu']='db';
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<link rel="shortcut icon" type="image/x-icon" href="http://root.danbif.dk/allearter/favicon.ico" /> 
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js"></script>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<style type="text/css">
body, h1, h2, h3, h4, h5 {
	font-family : helvetica;
	margin:0px;
	padding: 0px;
}
p {
	margin:0px;
	padding:10px;
	margin-left: 15px;
	clear: both;
	width: 600px;
	font-family: helvetica;
	background-color:snow;
	border:1px solid #dadada;
	margin-bottom: 25px;
	font-size: 12px;
}
fieldset {
	margin:0px;
	clear: both;
	width: 600px;
	font-family: helvetica;
	background-color:snow;
	border:1px solid #dadada;
	margin-bottom: 5px;
}
input[type=text] {
	border:1px solid silver;
	font-size: 14px;
	padding: 2px;
}
legend {
	font-size: 13px;
}
label {
	cursor:pointer;
}
code {
	font-size: 16px;
}
input[type=button], input[type=submit] {
	font-family: helvetica;
	font-size: 17px;
}
.logout {
	width:100%;
	height: 16px;
	margin:0px;
	font-size: 12px;
	line-height: 13px;
	padding: 0px;
	background-color: #dadada;
	border-bottom:1px solid silver;
	text-align: right;
}
a.menu {
	float: left;
	height:100%;
	width:100px;
	background-color: silver;
	border-left: 1px solid gray;
	text-align: center;
	text-decoration: none;
	color: black;
	font-weight: bold;
	font-family: helvetica;
	font-size: 14px;
	line-height: 16px;
}
a.selected {
	background-color: white;
	border-bottom: 1px solid white;
}
.console {
	background-color: black;
	color : #00FF00;
	overflow: scroll;
	width: 450px;
	height: 300px;
	padding: 10px;
	font-size: 12px;
	border: 2px solid gray;
	font-family : terminal, courier;
	font-weight: bold;
}
td {
	font-size: 13px;
	padding-right: 10px;
}
th {
	border-bottom: 2px solid #ebebeb;
	text-align: left;
	font-size: 14px;
}
</style>
</head>
<body>
<div class="logout">
<a class="menu<? if ($_SESSION['menu']=='db') echo ' selected';?>" href="index.php?menu=db" style="margin-left:20px;">Database</a>
<a class="menu<? if ($_SESSION['menu']=='story') echo ' selected';?>" href="index.php?menu=story">Promos</a>
<a class="menu<? if ($_SESSION['menu']=='teaser') echo ' selected';?>" href="index.php?menu=teaser">Teasers</a>
<a class="menu<? if ($_SESSION['menu']=='picture') echo ' selected';?>" href="index.php?menu=picture">Billeder</a>
<a href="login.php" style="text-decoration:none;color:blue;padding-right:10px;">Log ud</a>
</div>

<div style="margin:20px;">
<?
switch ($_SESSION['menu']) {
	case 'story' : include('inc_promo.php'); break;
	case 'teaser' : include('inc_teaser.php'); break;
	case 'picture' : include('inc_picture.php'); break;
	default : include('inc_db.php'); break;
}
?>
</div>

</body>
</html>

