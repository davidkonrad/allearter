<?
include('common/dynamicHTML.php');
include('common/meta.php');
$html=new HTML();
$meta=new Meta();
?>
<!doctype html>
<html lang="da">
<head> 
<meta http-equiv="x-ua-compatible" content="IE=Edge"/>
<title><? echo $meta->getTitle();?></title>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta name="description" content="<? echo $meta->getMetaDesc();?>" />
<!--
<link rel="shortcut icon" type="image/x-icon" href="http://allearter.dk/grafik/images/favicons/favicon_fa.ico" /> 
-->
<link rel="shortcut icon" type="image/x-icon" href="images/favicon_fa.ico" /> 
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>

<link rel="stylesheet" type="text/css" href="dataTables/jQueryUI-1.11.4/jquery-ui.css"/>
<link rel="stylesheet" type="text/css" href="dataTables/jQueryUI-smooth/jquery-ui-1.9.2.custom.css"/>
<link rel="stylesheet" type="text/css" href="dataTables/Buttons-1.3.1/css/buttons.jqueryui.css"/>
<link rel="stylesheet" type="text/css" href="dataTables/ColReorder-1.3.3/css/colReorder.jqueryui.css"/>
<link rel="stylesheet" type="text/css" href="dataTables/DataTables-1.10.15/css/jquery.dataTables_themeroller.css" media="screen" />
<script type="text/javascript" src="dataTables/JSZip-3.1.3/jszip.js"></script>
<script type="text/javascript" src="dataTables/pdfmake-0.1.27/build/pdfmake.js"></script>
<script type="text/javascript" src="dataTables/pdfmake-0.1.27/build/vfs_fonts.js"></script>
<script type="text/javascript" src="dataTables/DataTables-1.10.15/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="dataTables/DataTables-1.10.15/js/dataTables.jqueryui.js"></script>
<script type="text/javascript" src="dataTables/Buttons-1.3.1/js/dataTables.buttons.js"></script>
<script type="text/javascript" src="dataTables/Buttons-1.3.1/js/buttons.jqueryui.js"></script>
<script type="text/javascript" src="dataTables/Buttons-1.3.1/js/buttons.colVis.js"></script>
<script type="text/javascript" src="dataTables/Buttons-1.3.1/js/buttons.html5.js"></script>
<script type="text/javascript" src="dataTables/Buttons-1.3.1/js/buttons.print.js"></script>
<script type="text/javascript" src="dataTables/ColReorder-1.3.3/js/dataTables.colReorder.js"></script>

<link rel="stylesheet" href="js/jquery.qtip.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/menu.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/ui-styling.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/styling.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/details.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/downloadPopup.css" type="text/css" media="screen" />

<script type="text/javascript" src="js/autocomplete.js"></script>
<script type="text/javascript" src="js/counter.js"></script>
<script type="text/javascript" src="js/jquery.watermark.js"></script>
<script type="text/javascript" src="js/jquery.qtip.js"></script>
<script type="text/javascript" src="js/downloadPopup.js"></script>
<script type="text/javascript" src="js/cookie.js"></script>
<script type="text/javascript" src="js/history.js"></script>
<script type="text/javascript" src="js/hints.js"></script>

<? 
if (isset($_GET['referencer']) || isset($_GET['artsgruppe-statistik']) || isset($_GET['statistik'])) {
echo '<link rel="stylesheet" href="css/statistik.css" type="text/css" media="screen" />'."\n";
}
?>
<script type="text/javascript">
var arterCount=<? echo $html->getNumberOfSpecies();?>;
</script>
<script type="text/javascript" src="js/allearter.js?ver=3"></script>
<link rel="stylesheet" href="css/allearter.css" type="text/css" media="screen" />

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
<div class="buttonpanel">
	<br>
	<button type="button" id="search-btn" class="search" onclick="Search.searchSubmit();" title="Søg">
		<img src="images/search.png" alt="Søg" style="margin-top:3px;"/>&nbsp;S&oslash;g&nbsp;
	</button>
	<button type="button" id="search-reset" class="search" onclick="reset();" title="Nulstil søgekriterier">
		&nbsp;<img src="images/reset.png" alt="Nulstil" style="margin-top:3px;"/>&nbsp;
	</button>
	<br>
</div>
<?
}
?>

<div id="wrapper">

	<div style="position:relative;left:20px;width:40px;top:6px;float:left;clear:none;height:120px;">
		<a href="http://allearter.dk/" title="Projekt Allearter">
			<img src="images/faelles.gif" alt="Forside">		
		</a>
	</div>
	<div style="position:relative;float:left;left:60px;clear:none;top:0px;">
		<a href="http://allearter.dk/" title="Projekt Allearter">
			<img src="images/navnetraek.gif" alt="Projekt allearter">
		</a>
	</div>

	<div id="redbar"></div>

	<div id="main-content" style="padding-left:20px;">
		<div id="search-box-simple">
			<span style="float:left">
				<a href="https://allearter.dk/" style="text-decoration:none;">&#171;&nbsp;Projekt Allearter Startside</a>
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
					<a href="https://allearter.dk/" style="text-decoration:none;">Projekt Allearter Startside</a>
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

	</div> 

<? $html->divider(50);?>

	<div id="footer">
		<div id="footer-col-left">
			<address>
				<a href="https://www.danbif.dk/">DanBIF - Danish Biodiversity Information Facility c/o Statens Naturhistoriske Museum</a><br>
				<a href="https://www.ku.dk/">Københavns Universitet</a>
				<br>
				Universitetsparken 15, 2100 København Ø
				<br>
			</address>
		</div>
		<!-- End footer-col-left -->
		<br style="display: none;">
		<div id="footer-col-right">
			<code>ver. maj 2017.</code>
			Kontakt:
			<address>
				Projektansvarlig: Lars Skipper<br>
				<a href="mailto:lars.skipper@get2net.dk">lars.skipper<!-- @@@ -->@<!-- @@@ -->get2net<!-- nospam -->.<!-- nomorespam -->dk</a><br>
				Web: David Konrad<br>
				<a href="mailto:davidkonrad@gmail.com">davidkonrad<!-- @@@ -->@<!-- @@@ -->gmail<!-- nospam -->.<!-- nomorespam -->com</a><br>
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
		</select>
		<br>
		<label for="column">Sorter efter</label>
		<select id="column" name="column">
			<? echo $html->getOptionsColumnNames();?>
		</select>
		<br>
 		<label for="filename">Filnavn</label>
		<input id="filename" name="filename" value="allearter.csv" type="text" />
 		<span style="float:right;clear:both;border-top:1px solid #ebebeb;width:100%;margin-top:10px;margin-bottom:10px;text-align:right;padding-top:6px;">
			<input type="button" value="Download" id="begin-download"/>
			<input type="button" value="Fortryd" id="cancel-download"/>
		</span>
	</form>
</div>

<div id="popup-image" style="display:none;"></div>

<div id="hierarchy-modal"></div>

</body>
</html>
