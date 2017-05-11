<?
include('common/dynamicHTML.php');
include('common/meta.php');
$html=new HTML();
$meta=new Meta();
?>
<!doctype html>
<html>
<head> 
<meta http-equiv="x-ua-compatible" content="IE=Edge"/>
<title><? echo $meta->getTitle();?></title>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta name="description" content="<? echo $meta->getMetaDesc();?>" />
<link rel="shortcut icon" type="image/x-icon" href="http://allearter.dk/grafik/images/favicons/favicon_fa.ico" /> 
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
<script type="text/javascript" language="javascript" src="DataTables-1.9.1/media/js/jquery.dataTables.js"></script> 
<link rel="stylesheet" href="DataTables-1.9.1/media/css/jquery.dataTables.css" type="text/css" media="screen" />
<link rel="stylesheet" href="DataTables-1.9.1/media/css/jquery.dataTables_themeroller.css" type="text/css" media="screen" />
<script type="text/javascript" src="DataTables-1.9.1/extras/TableTools/media/js/TableTools.min.js"></script> 
<script type="text/javascript" src="DataTables-1.9.1/extras/ColReorder/media/js/ColReorder.js"></script>
<script type="text/javascript" src="DataTables-1.9.1/extras/ColVis/media/js/ColVis.js"></script>  
<link rel="stylesheet" href="DataTables-1.9.1/extras/ColReorder/media/css/ColReorder.css" type="text/css" media="screen" />
<link rel="stylesheet" href="DataTables-1.9.1/extras/ColVis/media/css/ColVis.css" type="text/css" media="screen" />
<link rel="stylesheet" href="DataTables-1.9.1/extras/ColVis/media/css/ColVisAlt.css" type="text/css" media="screen" />
<script type="text/javascript" src="DataTables-1.9.1/extras/ColReorder/media/js/ColReorderWithResize.js"></script> 
<script type="text/javascript" src="js/autocomplete.js"></script>
<script type="text/javascript" src="js/counter.js"></script>
<script type="text/javascript" src="js/jquery.watermark.js"></script>
<script type="text/javascript" src="js/jquery.qtip.js"></script>
<script type="text/javascript" src="js/downloadPopup.js"></script>
<script type="text/javascript" src="js/cookie.js"></script>
<script type="text/javascript" src="js/history.js"></script>
<script type="text/javascript" src="js/hints.js"></script>
<link rel="stylesheet" href="js/jquery.qtip.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/menu.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/ui-styling.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/styling.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/details.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/downloadPopup.css" type="text/css" media="screen" />
<? 
if (isset($_GET['referencer']) || isset($_GET['artsgruppe-statistik']) || isset($_GET['statistik'])) {
echo '<link rel="stylesheet" href="css/statistik.css" type="text/css" media="screen" />'."\n";
}
?>
<script type="text/javascript">
var arterCount=<? echo $html->getNumberOfSpecies();?>;
</script>
<script type="text/javascript" src="js/allearter.js?ver=2"></script>
<style type="text/css">
/* download all as CSV */
#ToolTables_search-result_4 {
	width: 160px;
	display: none;
}
button.history-down {
	margin: 0px;
	background-color: #ebebeb;
	border: 1px outset white;
}
button.history {
	height: 21px;
	margin: 0px;
}
button::-moz-focus-inner {
  border: 0;
}
#history-combo {
	position :absolute;
	background-color: white;
	border: 1px solid #dadada;
	z-index: 88;
	width: 600px;
	height: 100px;
	overflow-y: scroll;
	overflow-x: hidden;
	box-shadow: 5px 5px 5px #888888;
}
.history-combo-row {
	cursor:pointer;
	white-space: nowrap;
	overflow: hidden;
	word-wrap: none;
}
.history-combo-tag {
	clear:none;
	float:left;
	white-space:nowrap;
	width:75px;
	border-right: 1px solid #ebebeb;
}
.history-combo-values {
	overflow:hidden;
	text-overflow:ellipsis;
	float:left;
	clear:none;
	white-space:nowrap;
	background-color:white;
}
.history-combo-values span {
	float:none;
	clear:none;
}
.eol-image {
	height:120px;
	min-height: 120px;
	overflow: visible;
	display: block;
	position: relative;
}

/***************************
  -- new obvious layout --
****************************/
#wrapper {
	width: 984px;
	max-width: 984px;
	margin-left: auto;
	margin-right: auto;
	xxmargin-top: 2em;
	border-right: 1px solid #CCC;
	border-bottom: 1px solid #CCC;
	border-left: 1px solid #CCC;
	border-top: none;
	overflow: auto;
	background: #fff url(http://danbif.dk/allearter/grafik/topkollage.jpg/) no-repeat right top;
}
#snm-top {
	position: absolute;
	left: 470px;
	top: 0px;
	width: 514px;
	height: 86px;
	background-image: url(http://danbif.dk/allearter/grafik/topkollage.jpg/);
}
#redbar {
	position: relative;
	top: 86px;
	width: 982px;
	height: 1px;
	background-color: #901A1E;
}
#search-input-simple {
	-moz-border-radius:10px; /* Firefox */
	-webkit-border-radius: 10px; /* Safari, Chrome */
	-khtml-border-radius: 10px; /* KHTML */
	border-radius: 10px; /* CSS3 */
	behavior:url("border-radius.htc");
	width:460px;
	font-size: 18px;
	padding: 8px;
	margin-bottom: 3px;
	outline: 0;
}
body, th, td, legend, fieldset, p {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #4A4949;
}
table#search-result td.details {
	color: #165AAD;
}
#search-box-advanced {
	float: left;
	clear: both;
	width: 100%;
}
.search-box-advanced-column {
	width: 33%;
	height: auto;
	float: left;
	clear: none;
}
#search-box-advanced fieldset {
	border:1px solid #dadada;
	margin-top: 20px;
	width: 280px;
	max-width: 290px;
}
#search-box-advanced fieldset legend {
	font-size: 14px;
	padding-left: 5px;
	padding-right: 5px;
}
#search-box-simple {
	text-align: center;
	float: left;
	clear: both;
	width: 100%;
}
table {
	border-color: white;
}
#search-cnt {
	float: left;
	clear: both;
}
/* footer */
div#footer, .copyright {
	font-size: 10px;
	line-height: 1.45em;
}
#footer {
	clear: both;
	margin: 0;
	padding: 1.21951219512195% 0 0 22.05284552845528%;
	width: 77.94715447154472%;
}
#footer {
	color: #777;
	background-color: #F3F3F3;
}
div#footer-col-right {
	padding-bottom: 6px;
	padding-right: 272px;
	clear: right;
}
div#footer-col-right {
	text-align: right;
}
div#footer-col-left {
	float: left;
	width: 270px;
	padding-bottom: 6px;
}
a img {
	outline : none;
	border: 0;
}
/* info */
fieldset .info {
	width: 16px;
	min-width: 16px;
	height: 10px;
	min-height: 10px;
	position: relative;
	top: -9px;
	left: 275px;
	background-image : url(images/info.png);
	background-size: 10px 10px;
	background-repeat: no-repeat;
	cursor: pointer;
}
.qtip {
	font-size: 110%;
	line-height: 120%;
}
</style>
<script type="text/javascript">
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-34858110-1']);
_gaq.push(['_trackPageview']);
(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
</script>
</head>
<body>

<? 
function buttonPanel() {
?>
<div class="buttonpanel"><br/>
<button type="button" id="search-btn" class="search" onclick="Search.searchSubmit();" title="Søg"><img src="images/search.png" alt="Søg" style="margin-top:3px;"/>&nbsp;S&oslash;g&nbsp;</button>
<button type="button" id="search-reset" class="search" onclick="reset();" title="Nulstil søgekriterier">&nbsp;<img src="images/reset.png" alt="Nulstil" style="margin-top:3px;"/>&nbsp;</button>
<br/>
</div>
<?
}
?>

<div id="wrapper">

	<div style="position:relative;left:20px;width:40px;top:6px;float:left;clear:none;height:120px;">
		<a href="http://allearter.dk/" title="Projekt Allearter">
			<img src="http://cms.ku.dk/grafik/topgrafik/faelles.gif" alt="Forside">		
		</a>
	</div>
	<div style="position:relative;float:left;left:60px;clear:none;top:0px;">
		<a href="http://allearter.dk/" title="Projekt Allearter">
			<img src="http://allearter.dk/grafik/navnetraek.gif">
		</a>
	</div>

	<div id="redbar"></div>

	<div id="main-content" style="padding-left:20px;">
		<div id="search-box-simple">
			<span style="float:left">
				<a href="http://allearter.dk/" style="text-decoration:none;">&#171;&nbsp;Projekt Allearter Startside</a>
			</span>
			<div style="margin-top:40px;text-align:center;">
				Søg i Allearter databasen :<br>
				<input type="text" id="search-input-simple" placeholder="Skriv ..." style="margin-bottom:0px;"><br>
				<span style="font-size:80%;clear:both;display:inline-block;padding:0px;margin:0px;line-height:90%;margin-bottom:10px;">
					Overhold venligst brugslicenser angivet for artslisterne  <a href="https://creativecommons.org/licenses/by/4.0/">CC BY 4.0</a> / <a href="https://creativecommons.org/licenses/by-nc/4.0/">CC BY-NC 4.0</a> – Læs mere <b><a href="http://allearter.dk/hoejrebokse/nyt-og-aktuelt/retningslinjer/" title="Retningslinjer for brug af data">her</a></b>.
				</span><br>
				<button id="search-btn-simple">Søg</button>
				<button id="show-search-advanced">Avanceret søgning</button>
				<button id="hierarchy" title="Klik for at vise klassifikatiovns-hierarki">
					Klassifikations-hierarki
				</button>
			</div>
		</div>

		<div id="search-box-advanced" style="display: none;">
			<div>
				<span style="float:left;">
					<a href="#" id="show-search-simple" style="text-decoration:none;">&#171;&nbsp;Simpel søgning</a>
					&nbsp;>&nbsp;
					<a href="http://allearter.dk/" style="text-decoration:none;">Projekt Allearter Startside</a>
				</span>
				<span style="padding-left:104px;">
					<button id="search-btn" style="width:80px;" onclick="Search.searchSubmit();" title="Søg">&nbsp;S&oslash;g&nbsp;</button>
					<button id="search-reset"  style="width:80px;" onclick="reset();" title="Nulstil søgekriterier">Nulstil</button>
					<button id="history-btn" class="history">Min søgehistorik&nbsp;&#9660;</button>
				</span>
			</div>
			<br>
			<div class="search-box-advanced-column">
				<fieldset>
					<legend>Fritekstsøgning</legend>
					<div class="info" id="fritexth3"></div>
					<div class="container"> 
						<input type="text" id="fritext" style="width:235px;"/><br>
						<input type="radio" name="fritext-cri" id="fri_alle_navne" value="alle" checked="checked"/><label class="right" for="fri_alle_navne">Alle navne (standard)</label><br/>
						<input type="radio" name="fritext-cri" id="fri_arter" value="arter" /><label class="right" for="fri_arter">Kun arter, underarter m.v</label><br/>
						<input type="radio" name="fritext-cri" id="fri_referencer" value="referencer" /><label class="right" for="fri_referencer">Kun referencer</label><br/>
						<input type="radio" name="fritext-cri" id="fri_samtlige_felter" value="allefelter"/><label class="right" for="fri_samtlige_felter">Samtlige felter</label><br/>
					</div>
				</fieldset>

				<fieldset>
					<legend>Artsgruppe</legend>
					<div class="info" id="artsgruppeh3"></div>
					<div class="container">
						<select id="artsgruppe" name="artsgruppe"><option></option></select><br>
						<? $html->divider(5);?>
						<select id="artsgruppedk" name="artsgruppedk"><option></option></select>
						<!--
						<div id="systematik_sorter" style="display:none;text-align:right;padding-right:10px;">
							<label for="systematik" class="right" style="float:none;">Sorter systematisk</label>
							<input type="checkbox" id="systematik" name="systematik" style="padding-top:3px;"/>
						</div>
						-->
					</div>
				</fieldset>

				<fieldset>
					<legend>Taxonkategori</legend>
					<div class="info" id="taxonkategorih3"></div>
					<div class="container"> 
						<input type="checkbox" id="taxon_alle" class="taxon-kategori" checked="checked"/><label class="right" for="taxon_alle">Alle (standard)</label><br/>
						<input type="checkbox" id="taxon_art" class="taxon-kategori" onchange="taxonClick(this);" /><label class="right" for="taxon_art">Art</label><br/>
						<input type="checkbox" id="taxon_underart" class="taxon-kategori" onchange="taxonClick(this);"/><label class="right" for="taxon_underart">Underart</label><br/>
						<input type="checkbox" id="taxon_varietet" class="taxon-kategori" onchange="taxonClick(this);"/><label class="right" for="taxon_varietet">Varietet</label><br/>
						<input type="checkbox" id="taxon_form" class="taxon-kategori" onclick="taxonClick(this);"/><label class="right" for="taxon_form">Form</label><br/>
						<input type="checkbox" id="taxon_hybrid" class="taxon-kategori" onclick="taxonClick(this);"/><label class="right" for="taxon_hybrid">Hybrid</label><br/>
					</div>
				</fieldset>
			</div>

			<div class="search-box-advanced-column">
				<fieldset>
					<legend>Klassifikation</legend>
					<div class="info" id="klassifikationh3"></div>
					<div class="container">
						<input type="radio" name="sprog" id="sprogint" value="int" onchange="reset();" checked="checked"/><label for="sprogint" class="right">Videnskabelige navne</label><br/>
						<input type="radio" name="sprog" id="sprogda" value="da" onchange="reset();"/><label for="sprogda" class="right">Danske navne</label><br/>
						<? $html->divider(5);?>
						<small>Søg i alle klassifikations-kategorier</small><br>
						<input type="text" style="width:230px;" id="multi-opslag" name="multi-opslag"/> 
						<br>
						<? $html->divider(5);?>
						<select id="klas_rige" name="klas_rige"><option></option></select><br>
						<? $html->divider(5);?>
						<select id="klas_raekke" name="klas_raekke"><option></option></select><br>
						<? $html->divider(5);?>
						<select id="klas_klasse" name="klas_klasse"><option></option></select><br>
						<? $html->divider(5);?>
						<select id="klas_orden" name="klas_orden"><option></option></select><br>
						<? $html->divider(5);?>
						<select id="klas_familie" name="klas_familie"><option></option></select><br>
						<? $html->divider(5);?>
						<select id="klas_slaegt" name="klas_slaegt" style="margin-bottom:5px;"><option></option></select><br>
						<div id="extra-klassifikation"></div><br><br>
					</div>	
				</fieldset>

				<fieldset>
					<legend>Status på allearter.dk</legend>
					<div class="info" id="artsudbredelseh3"></div>
					<div class="container"> 
						<input type="checkbox" id="arter_danske" class="arter-cri" checked="checked" onchange="arterClick(this);"/><label class="right" for="arter_danske">Accepteret (Standard)</label><br/>
						<input type="checkbox" id="arter_ikke_danske" class="arter-cri" onchange="arterClick(this);"/><label class="right" for="arter_ikke_danske">Ikke accepteret</label><br/>
					</div>
				</fieldset>
			</div>

			<div class="search-box-advanced-column">
				<fieldset>
					<legend>Forvaltningskategorier</legend>
					<div class="info" id="forvaltningh3"></div>
					<div class="container"> 
						<fieldset style="color:#999;border:1px solid #ebebeb;width:240px;margin-top:1px;">
							<legend style="font-size:12px;">Søgemodus</legend>
							<input type="radio" name="forvl-mode" id="forvl-mode-or" checked="checked"/><label class="right" style="font-size:11px;" for="forvl-mode-or">Optræder på mindst én markeret</label><br/>
							<input type="radio" name="forvl-mode" id="forvl-mode-and"/><label class="right" style="font-size:11px;" for="forvl-mode-and">Optræder på samtlige markerede</label>
						</fieldset>
						<? $html->divider(15);?>
						<input type="checkbox" id="forv_roedliste" class="forvl-cri" onchange="enableSearch();"/><label class="right" for="forv_roedliste">Den danske rødliste</label><br/>
						<input type="checkbox" id="forv_fredede" class="forvl-cri" onchange="enableSearch();"/><label class="right" for="forv_fredede">Fredede arter</label><br/>
						<input type="checkbox" id="forv_efhabitat" class="forvl-cri" onchange="enableSearch();"/><label class="right" for="forv_efhabitat">EF-habitatdirektivet</label><br/>
						<input type="checkbox" id="forv_effugle" class="forvl-cri" onchange="enableSearch();"/><label class="right" for="forv_effugle">EF-fuglebeskyttelsesdirektivet</label><br/>
						<input type="checkbox" id="forv_bern" class="forvl-cri" onchange="enableSearch();"/><label class="right" for="forv_bern">Bern-konventionen</label><br/>
						<input type="checkbox" id="forv_bonn" class="forvl-cri" onchange="enableSearch();"/><label class="right" for="forv_bonn">Bonn-konventionen</label><br/>
						<input type="checkbox" id="forv_cites" class="forvl-cri" onchange="enableSearch();"/><label class="right" for="forv_cites">CITES</label><br/>
						<input type="checkbox" id="forv_oevrige" class="forvl-cri" onchange="enableSearch();"/><label class="right" for="forv_oevrige">Øvrige forvaltningskategorier</label><br/>
					</div>
				</fieldset>

				<fieldset>
					<legend>Statistik og Leksikon</legend>
					<div class="info" id="statistikh3"></div>
					<div class="container"> 
						<a href="?artsgruppe-statistik" id="artsgruppe-statistik">&gt;&nbsp;Artsgrupper</a><br>
						<a href="?statistik" id="statistik">&gt;&nbsp;Klassifikation m.v.</a><br>
						<a href="?referencer" id="referencer">&gt;&nbsp;Referencer</a><br>
						<a href="?leksikon&arter=da&index=a" title="Alle arter i Danmark fra A til Å">&gt;&nbsp;Alle arter A til Å artsregister</a>
					</div>
				</fieldset>

				<!--
				<fieldset>
					<legend>Ekstra funktioner</legend>
					<div class="container"> 
						<input type="checkbox" id="nye_arter" class="nye-arter-cri" onchange="enableSearch();"/><label class="right" for="nye_arter">Nye arter</label>
						<br/><br/>
						<div id="perma-link-cnt" style="display:none;">
						<small>Perma-link til aktuel søgning</small><br/>
							<textarea id="perma-link" cols="50" rows="3" style="background-color:#ebebeb;width:255px;height:50px;" onclick="this.select();"></textarea>
							<br/><br/>
						</div>
					</div>
				</fieldset>
				-->

			</div>

		</div>
	</div>

	<div id="search-cnt">
<? 
if (isset($_GET['sitemap']) || isset($_GET['leksikon'])) {
	include('sitemap.php');
} elseif (isset($_GET['showtips'])) {
	include('showtips.php');
} elseif (isset($_GET['statistik'])) {
	include('statistik.php');
} elseif (isset($_GET['artsgruppe-statistik'])) {
	include('artsgruppe-statistik.php');
} elseif (isset($_GET['referencer'])) {
	include('referencer.php');
} else {
	$html->divider(45);
	include('ajax/search.php');

?>
<script type="text/javascript">
$(document).ready(function() {
	recordCount($('#search-result tr').length-1, false);
});
</script>
<?
}
?>

</div></div>

<? $html->divider(75);?>

<div id="footer">
	<div id="footer-col-left">
		<address>
			<a href="http://www.danbif.dk/">DanBIF - Danish Biodiversity Information Facility c/o Statens Naturhistoriske Museum</a><br>
			<a href="http://www.ku.dk/">Københavns Universitet</a>
			<br>
			Universitetsparken 15, 2100 København Ø
			<br>
		</address>
	</div>
	<!-- End footer-col-left -->
	<br style="display: none;">
	<div id="footer-col-right">
		Kontakt:
	<address>
		Projektansvarlig: Lars Skipper<br>
		<a href="mailto:lars.skipper@get2net.dk">lars.skipper<!-- @@@ -->@<!-- @@@ -->get2net<!-- nospam -->.<!-- nomorespam -->dk</a><br>
		Web: David Konrad<br>
		<a href="mailto:david.konrad@snm.ku.dk">david.konrad<!-- @@@ -->@<!-- @@@ -->snm.ku<!-- nospam -->.<!-- nomorespam -->dk</a><br>

	</address>
    </div>

    <!-- End footer-col-right -->
	<br style="clear: both">
</div>

</div>

<div id="download-modal">
 	<form action="" id="download-form" style="display: none;">
		<label for="separator">Separator</label>
		<select id="separator" name="separator">
		<option value=";" selected="selected">; (semikolon)</option>
		<option value=",">, (komma)</option>
		</select><br/>
		<label for="column">Sorter efter</label>
		<select id="column" name="column">
		<? echo $html->getOptionsColumnNames();?>
		</select><br/>
 		<label for="filename">Filnavn</label>
		<input id="filename" name="filename" value="allearter.csv" type="text" />
 		<span style="float:right;clear:both;border-top:1px solid #ebebeb;width:100%;margin-top:10px;margin-bottom:10px;text-align:right;padding-top:6px;">
		<input type="button" value="Download" id="begin-download"/>
		<input type="button" value="Fortryd" id="cancel-download"/>
		</span>
	</div>
</div>

<div id="popup-image" style="display:none;">

<div id="hierarchy-modal"></div>

</div>
<? $html->divider(25);?>
</body>
</html>
