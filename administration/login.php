<?
session_start();
if (isset($_POST['kodeord'])) {
	if ($_POST['kodeord']=='hest') {
		$_SESSION['logged_in']='yes';
		header('location:index.php');
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<style type="text/css">
.login {
	position: absolute;
	top: 30%;
	left: 40%;
	font-family : helvetica;
}
input[type=password] {
	border: 1px solid silver;
	font-size: 16px;
	padding: 4px;
}
</style>
</head>
<body onLoad="document.forms.bif.kodeord.focus()">

<div class="login">
<form name="bif" method="post" action="login.php">
<center><img src="../images/Logo.gif" width=50"/><img src="../images/danbiflogo.gif" width=50"/>
<br/><br/>
Skriv det hemmelige kodeord<br/>
<input type="password" name="kodeord"/><br>
<input type="submit" value="Login"/>
</venter>
</form>
</div>

</body>
</html>
	
