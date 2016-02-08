<div class="container container-allearter">
<div class="row">
<div class="span12">
<h2>Praktisk eksempel - Danske gæs</h2>
<p class="lead"><i>
Gæs er fugle i familien Andefugle der tilhører slægterne (Anser), (Chen), (Branta) eller (Cereopsis).
Gæs blev holdt som husdyr allerede for 5.000 år siden i Egypten. Visse tamgæs kan nå den betydelige vægt på 12–14 kg.
Hangæs kaldes gaser, og gåseunger hedder gæslinger.</i>
<small>[Kilde : <a href="http://da.wikipedia.org/wiki/G%C3%A5s" target=_blank>Wikipedia</a>]</small>
</p>

<style type="text/css">
img {
	border:1px solid silver;
	margin-left: 20px;
}
</style>

<div id="gaes-result" class="eksempel">
<img src="ajax-loader.gif" style="border:none;">
&nbsp;Henter artslistens informationer om danske gåsearter ...
</div>

<script type="text/javascript">
var danskegæs = ['Anser erythropus', 'Anser anser', 'Branta canadensis', 'Branta leucopsis', 
	'Branta bernicla', 'Branta ruficollis', 'Anser fabalis', 'Anser brachyrhynchus', 'Anser albifrons', 
	'Anser indicus', 'Anser caerulescens', 'Anser cygnoides'];

function visGås(artsInfo) {
	var html='<h3>'+artsInfo.Dansk_navn+'</h3>';
	html+='<table><tr>';
	html+='<td><table style="font-size:16px;">';
	html+='<tr><td><b>Videnskabeligt navn&nbsp;&nbsp;</b></td><td><em>'+artsInfo.Videnskabeligt_navn+'</em></td></tr>';
	html+='<tr><td><b>Række</b></td><td>'+artsInfo.Raekke+'</td></tr>';
	html+='<tr><td><b>Klasse</b></td><td>'+artsInfo.Klasse+'</td></tr>';
	html+='<tr><td><b>Orden</b></td><td>'+artsInfo.Orden+'</td></tr>';
	html+='<tr><td><b>Familie</b></td><td>'+artsInfo.Familie+'</td></tr>';
	html+='<tr><td><b>Slægt</b></td><td>'+artsInfo.Slaegt+'</td></tr>';
	html+='</table></td>'
	html+='<td style="width:300px;">';
	if (artsInfo.Billede!='') {
		html+='<img src="'+artsInfo.Billede+'" style="height:150px;">';
	}
	html+='</td></tr></table><hr>';
	$("#gaes-result").append(html);
}

$(document).ready(function() {
	var url='proxy.php?url=http://allearter-databasen.dk/api/?get=art&query=';
	var first = true;
	for (var i=0;i<danskegæs.length;i++) {
		var videnskabeligt_navn = danskegæs[i];
		$.ajax({
			url : url+videnskabeligt_navn,	
			dataType : 'json',
			async: false,
			success : function (json) {
				if (first) {
					$("#gaes-result").empty();
					first=false;
				}
				visGås(json.allearter[0]);
			}
		});
	}
});
</script>
<br><br>	
<h3>Sådan er eksemplet programmeret</h3>
<p>Vi har en prædefineret liste over de danske gåsearter, <code>danskegæs</code>. Denne liste køres gennem
en løkke, hvor artsinformationen for hver gåseart hentes fra API'et. Artsinformationen for de enkelte 
gåsearter behandles fortløbende af funktionen <code>visGås</code>, som genererer HTML ud fra 
<code>artsInfo</code> og tilsidst føjer HTML'en til elementet <code>gaes-result</code>.
</p>
<h4>Den komplette kildekode</h4>
<pre class="prettyprint">
&lt;div id="gaes-result" class="eksempel">&lt;/div>

&lt;script type="text/javascript">
var danskegæs = ['Anser erythropus', 'Anser anser', 'Branta canadensis', 'Branta leucopsis', 
	'Branta bernicla', 'Branta ruficollis', 'Anser fabalis', 'Anser brachyrhynchus', 'Anser albifrons', 
	'Anser indicus', 'Anser caerulescens', 'Anser cygnoides'];

function visGås(artsInfo) {
	var html='&lt;h3>'+artsInfo.Dansk_navn+'&lt;/h3>';
	html+='&lt;table>&lt;tr>';
	html+='&lt;td>&lt;table style="font-size:16px;">';
	html+='&lt;tr>&lt;td>&lt;b>Videnskabeligt navn&nbsp;&nbsp;&lt;/b>&lt;/td>&lt;td>'+artsInfo.Videnskabeligt_navn+'&lt;/td>&lt;/tr>';
	html+='&lt;tr>&lt;td>&lt;b>Række&lt;/b>&lt;/td>&lt;td>'+artsInfo.Raekke+'&lt;/td>&lt;/tr>';
	html+='&lt;tr>&lt;td>&lt;b>Klasse&lt;/b>&lt;/td>&lt;td>'+artsInfo.Klasse+'&lt;/td>&lt;/tr>';
	html+='&lt;tr>&lt;td>&lt;b>Orden&lt;/b>&lt;/td>&lt;td>'+artsInfo.Orden+'&lt;/td>&lt;/tr>';
	html+='&lt;tr>&lt;td>&lt;b>Familie&lt;/b>&lt;/td>&lt;td>'+artsInfo.Familie+'&lt;/td>&lt;/tr>';
	html+='&lt;tr>&lt;td>&lt;b>Slægt&lt;/b>&lt;/td>&lt;td>'+artsInfo.Slaegt+'&lt;/td>&lt;/tr>';
	html+='&lt;/table>&lt;/td>'
	html+='&lt;td style="width:300px;">';
	if (artsInfo.Billede!='') {
		html+='&lt;img src="'+artsInfo.Billede+'" style="height:150px;">';
	}
	html+='&lt;/td>&lt;/tr>&lt;/table>&lt;hr>';
	$("#gaes-result").append(html);
}

$(document).ready(function() {
	var url='proxy.php?url=http://allearter-databasen.dk/api/?get=art&query=';
	for (var i=0;i&lt;danskegæs.length;i++) {
		var videnskabeligt_navn = danskegæs[i];
		$.ajax({
			url : url+videnskabeligt_navn,	
			dataType : 'json',
			success : function (json) {
				visGås(json.allearter[0]);
			}
		});
	}
});
&lt;/script>
</pre>
<br><br><br><br><br><br><br><br><br><br><br><br>

