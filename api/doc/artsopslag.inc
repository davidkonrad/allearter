<div class="container container-allearter">
<div class="row">
<div class="span12">
<h2>Praktisk eksempel - opslag i artslisten</h2>
<p class="lead">
Dette eksempel udnytter API'ets opslag på <em>arter</em> og <em>art</em>. Det lader brugere taste 
tekst ind i et tekstfelt, hvorefter et opslag på <em>arter</em> giver brugeren en 
liste af valgmuligheder. Vælges en art, slåes arten op og informationen for denne art vises under tekstfeltet.
</p>
<div class="eksempel">
<input type="text" id="artsopslag" placeholder="Artsopslag" data-provide="typeahead">
<div id="arts-information"></div>
</div>

<script type="text/javascript">
function hentArtsInformation(art) {
	var url='proxy.php?url=http://allearter-databasen.dk/api/?get=art&query='+encodeURIComponent(art);
	var synligeFelter = ['Videnskabeligt_navn', 'Dansk_navn', 'Artsgruppe_dk', 
		'Rige_dk', 'Raekke_dk', 'Klasse_dk', 'Orden_dk', 'Familie_dk', 'Slaegt_dk']
	$.ajax({
		url : url,
		dataType : 'json',
		success : function(json) {
			$("#arts-information").empty();
			var artsInformation = json.allearter[0];
			$("#arts-information").append('<table>');
			for (key in artsInformation) {
				if (synligeFelter.indexOf(key)>-1) {
					var navn=key.replace('_dk','');
					navn=navn.replace('ae','&aelig;');
					navn=navn.replace('_',' ');
					var info = '<tr><td><b>'+navn+'</b></td><td>&nbsp;&nbsp;'+artsInformation[key]+'</td></tr>';
					$("#arts-information").append(info);
				}
			}
			$("#arts-information").append('</table>');
			if (artsInformation.Billede!='') {
				var img='<img src="'+artsInformation.Billede+'" style="height: 200px;"><br>';
				$("#arts-information").append(img);
			}
		}
	});
}
$("#artsopslag").typeahead({
	minLength : 2,
	items : 10,
	source: function (query, process) {
		var url='proxy.php?url=http://allearter-databasen.dk/api/?get=arter&query='+query;//encodeURIComponent(query);
		return $.get(url, {}, function (data) {
			data=JSON.parse(data);
			var liste=[];
			for (var i=0;i<data.allearter.length;i++) {
				liste.push(data.allearter[i].Videnskabeligt_navn);
			}
			return process(liste);
		});
	},
	updater: function(item) {
		hentArtsInformation(item);
		return item;
	}
});
$(document).ready(function() {
	$("#artsopslag").focus();
});
</script>
<br>
<h3>Sådan er eksemplet programmeret</h3>
<p>Denne side er produceret ved hjælp af front-end frameworket <a href="http://twitter.github.com/bootstrap/index.html" target=_blank>Twitter Bootstrap</a>.
Bootstrap indeholder et autocomplete komponent ved navn <code>typeahead</code>, som benyttes i eksemplet -
men man kan benytte alle slags autocomplete-plugins, eller lave et selv. Her handler det dog om på enkel 
vis at demonstrere anvendelsen af allearter's API. <br><br>
<h4>Sidens HTML</h4>
<pre class="prettyprint">
&lt;input type="text" id="artsopslag" placeholder="Artsopslag" data-provide="typeahead">
&lt;div id="arts-information">&lt;/div>
</pre>

<h4>Typeahead-implementation</h4>
<pre class="prettyprint">
$("#artsopslag").typeahead({
	minLength : 2,
	items : 10,
	source: function (query, process) {
		var url='proxy.php?url=http://allearter-databasen.dk/api/?get=arter&query='+query;
		return $.get(url, {}, function (data) {
			data=JSON.parse(data);
			var liste=[];
			for (var i=0;i&lt;data.allearter.length;i++) {
				liste.push(data.allearter[i].Videnskabeligt_navn);
			}
			return process(liste);
		});
	},
	updater: function(item) {
		hentArtsInformation(item);
		return item;
	}
});
</pre>
For en udtømmende forklaring vedr. <code>typeahead</code> henvises til Bootstraps dokumentation. Ellers er alt "normalt", kaldet til
API'et helt som vanligt. Bemærk at JSON-responsen cykles igennem, og der opbugges et nyt array, <em>liste</em>, som repræsenterer opslaget.
Dette gøres fordi responsen indeholder både latinske og danske navne, men vi her kun er interesserede i de latinske navne.
Bemærk endvidere <code>updater</code>-funktionen. Det er her brugeren vælger en bestemt art
fra listen, hvorved funktionen <code>hentArtsInformation</code> aktiveres. <br><br>En ikke uvæsentlig detalje er, at der først
slåes op i API'et når brugeren har tastet mindst to karakterer i tekstboksen (<code>minLength</code>. 
Det er for at undgå unødige og ofte store opslag, hvor der f.eks ville blive returneret omtrent 3.500 arter, 
blot brugeren tastede "a". 
<br><br>
<h4>funktionen hentArtsInformation</h4>
<pre class="prettyprint">
function hentArtsInformation(art) {
	var url='proxy.php?url=http://allearter-databasen.dk/api/?get=art&query='+encodeURIComponent(art);
	var synligeFelter = ['Videnskabeligt_navn', 'Dansk_navn', 'Artsgruppe_dk', 
		'Rige_dk', 'Raekke_dk', 'Klasse_dk', 'Orden_dk', 'Familie_dk', 'Slaegt_dk']
	$.ajax({
		url : url,
		dataType : 'json',
		success : function(json) {
			$("#arts-information").empty();
			var artsInformation = json.allearter[0];
			$("#arts-information").append('&lt;table>');
			for (key in artsInformation) {
				if (synligeFelter.indexOf(key)>-1) {
					var navn=key.replace('_dk','');
					navn=navn.replace('ae','&aelig;');
					navn=navn.replace('_',' ');
					var info = '&lt;tr>&lt;td>&lt;b>'+navn+'&lt;/b>&lt;/td>&lt;td>&nbsp;&nbsp;'+artsInformation[key]+'&lt;/td>&lt;/tr>';
					$("#arts-information").append(info);
				}
			}
			$("#arts-information").append('&lt;/table>');
			if (artsInformation.Billede!='') {
				var img='&lt;img src="'+artsInformation.Billede+'" style="height: 200px;">&lt;br>';
				$("#arts-information").append(img);
			}
		}
	});
}
</pre>
Funktionen opererer med en liste af de felter fra API'et som vi ønsker at vise, <code>synligeFelter</code>. 
Artsinformationen slåes op, og herefter cykles alle felterne igennem - for hver af de <code>synligeFelter</code>
transformeres feltnavnet om til noget mere menneskeligt læsbart, og navn og information sættes derefter ind i 
en tabel. Til slut tjekkes efter om der er et billede tilknyttet, hvis der er vises billedet. 
</p>
<br><br><br><br><br><br><br><br><br><br><br><br>
</div>
</div>
