<div class="container container-allearter">
<div class="row">
	<div class="span3 bs-docs-sidebar">
		<ul class="nav nav-list bs-docs-sidenav affix" id="sidebar">
		<li class=""><a href="#introduktion"><i class="icon-chevron-right"></i>Introduktion</a></li>
		<li class=""><a href="#uri"><i class="icon-chevron-right"></i>Opbygning af URI</a></li>
		<li class=""><a href="#programmeringssprog"><i class="icon-chevron-right"></i>Hvilke programmeringssprog?</a></li>
		<li class=""><a href="#serverside"><i class="icon-chevron-right"></i>PHP serverside eksempel</a></li>
		<li class=""><a href="#crossdomain"><i class="icon-chevron-right"></i>Crossdomain og proxies</a></li>
		<li class=""><a href="#ajax1"><i class="icon-chevron-right"></i>Simpelt AJAX eksempel (jquery)</a></li>
		<li class=""><a href="#pure-javascript"><i class="icon-chevron-right"></i>"Pure javascript"-eksempel</a></li>
		<li class=""><a href="#kontakt"><i class="icon-chevron-right"></i>Kontakt</a></li>
		</ul>
	</div>
	<div class="span9">
<section id="introduktion">
<h2>Introduktion til allearter.dk's API</h2>
<p class="lead">
Allearter.dk stiller et API til rådighed, som gør det nemt og hurtigt 
for hjemmesideejere og systemudviklere at trække på allearter.dk's artsliste. 
</p>
<h4>Opslag</h4>
<p>Pt. kan man foretage følgende opslag på <b>artslisten</b> :
<dl>
	<dt>Artsgrupper</dt>
	<dd>Opslag på allearters opdeling af <em>artsgrupper</em>. F.eks alle artsgrupper der indeholder ordet <b>flue</b>.</dd>
	<br>
	<dt>Artsgruppers arter</dt>
	<dd>Opslag på <em>arter</em> inden for en given <em>artsgruppe</em>. F.eks alle arter inden for artsgruppen <b>fisk</b>.</dd>
	<br>
	<dt>Arter</dt>
	<dd>Opslag på samtlige <em>arter</em>. F.eks alle arter hvis latinske navn begynder med <b>salmo</b>.</dd>
	<br>
	<dt>Art</dt>
	<dd>Opslag på en bestemt <em>art</em>. F.eks alle informationer for arten <b>canis lupus</b>.</dd>
	<br>
	<dt>Rige</dt>
	<dd>Opslag på hvilket <em>rige</em> en <em>art</em> tilhører, f.eks <b>canis lupus</b>.</dd>

</dl>
For alle opslag - undtagen <em>art</em> - kan der søges på både latinske og danske navne. For <em>art</em> er det kun videnskabelige navne 
der er lovlige. Dette er for at undgå navne-forvirring, f.eks ift. <em>hejre</em> eller <em>trompetsvamp</em>. 
Der returneres ikke en fejl, blot et tomt datasæt. 
<br><br></p>
<h4>Respons</h4>
<p>
Er der tale om et lovligt opslag vil API'et returnere en tekstblok kodet i JSON. <br>
Strukturen er (skematisk) :
</p>
<pre class="prettyprint">
{ "allearter" : [
	{ "<em>resultat</em>" },
	{ "<em>resultat</em>" },
	{ "<em>resultat</em>" },
	{ "<em>resultat</em>" } 
] }
</pre>
<em>Arter</em> returnerer indtil videre kun de latinske navne. For de Hvis opslaget ikke er lovligt, returneres en JSON på formen :<br><br>
<pre class="prettyprint">
{ "error" : "&lt;<em>fejlbesked</em>&gt;" }
</pre>
<h4>Respons ift. Art</h4>
Opslag på en Art afviger en smule fra ovenstående. Der returneres en JSON som ovenover, men frem for en
liste af resultater leveres en liste af informationer for den pågældende art. 
Pt. leveres følgende informations-felter for en Art :<br>
<dd class="code">
<ul class="inline" style="margin-bottom:0px;">
<li>Artsgruppe</li><li>Artsgruppe_dk</li><li>Videnskabeligt_navn</li><li>Autor</li><li>Dansk_navn</li><li>Rige</li><li>Rige_dk</li><li>Raekke</li><li>Raekke_dk</li><li>Underraekke</li><li>Underraekke_dk</li><li>Overklasse</li><li>Overklasse_dk</li><li>Klasse</li><li>Klasse_dk</li><li>Underklasse</li><li>Underklasse_dk</li><li>Infraklasse</li><li>Infraklasse_dk</li><li>Overorden</li><li>Overorden_dk</li><li>Orden</li><li>Orden_dk</li><li>Underorden</li><li>Underorden_dk</li><li>Infraorden</li><li>Infraorden_dk</li><li>Overfamilie</li><li>Overfamilie_dk</li><li>Familie</li><li>Familie_dk</li><li>Underfamilie</li><li>Underfamilie_dk</li><li>Tribus</li><li>Tribus_dk</li><li>Slaegt</li><li>Slaegt_dk</li><li>Referencenavn</li><li>Reference_aar</li><li>Referencetekst</li><li>Billede</li>
</ul>
</dd>
<br>
JSON'en bliver således :
<pre class="prettyprint">
{ "allearter" : [
	{ "<em>Artsgruppe</em>" : "<em>&lt;artsgruppen arten tilhører></em>",
	  "<em>Artsgruppe_dk</em>" : "<em>&lt;artsgruppens danske navn></em>", 
	...
	  "<em>Raekke</em>" : "<em>&lt;rækken som arten tilhører></em>",
	  "<em>Raekke_dk</em>" : "<em>&lt;dansk betegnelse for den række, som arten tilhører></em>",
	...
] }
</pre>

<div class="alert alert-info">
Hvis der ønskes alternative respons-typer, såsom HTML eller XML, så følg kontaktoplysningerne i bunden af siden. Måske kan vi hjælpe.
</div>
</p>
</p>
</section>
<section id="uri">
<h3>Det basale - opbygning af URI</h3>
<p>Alle kald til allearter.dk's API går på formen</p>
<dd class="code">
http://allearter-databasen.dk/api/?get=&lt;<em>indholdstype</em>&gt;&query=&lt;<em>søgetekst</em>&gt;
</dd>
<br><p>Lovlige indholdstyper :
<dd class="code">
<ul class="inline" style="margin-bottom:0px;">
<li>artsgrupper</li>
<li>artsgruppe-arter</li>
<li>arter</li>
<li>art</li>
<li>rige</li>
</ul>
</dd>
<br>Dermed, ønsker man f.eks at hente alle <em>artsgrupper</em> der indeholder <b>flue</b> bliver URI'en :
<dd class="code">
http://allearter-databasen.dk/api/?get=artsgrupper&query=flue
</dd>
<br>Ønsker man at hente alle <em>arter</em> indenfor <em>artsgruppen</em> <b>edderkopper</b>, bliver URI'en :
<dd class="code">
http://allearter-databasen.dk/api/?get=artsgruppe-arter&query=edderkopper
</dd>
<br>Ønsker man at hente alle <em>arter</em> der indeholder <b>salmo</b>, bliver URI'en :
<dd class="code">
http://allearter-databasen.dk/api/?get=arter&query=salmo
</dd>
<br>Ønsker man at hente artslistens information om en bestemt art, f.eks <b>canis lupus</b> (ulv), er URI'en :
<dd class="code">
http://allearter-databasen.dk/api/?get=art&query=canis lupus
</dd>
<br>Eller, ønsker man at finde ud af hvilket <em>rige</em> en <em>art</em> tilhører, f.eks <b>canis lupus</b> (ulv), er URI'en :
<dd class="code">
http://allearter-databasen.dk/api/?get=rige&query=canis lupus
</dd>
<p>
<br><b>Bemærk</b> : query bør være URL-encoded. <br>Dette er normalt ikke et problem, men opstår der
fejl bør man sikre sig at f.eks <b>salmo salar</b> i kaldet er kodet som <b>salmo%20salar</b>. 
Med javascript kan dette gøres via f.eks. <code>encodeURIComponent</code>.
<br>
Når der søges på <em>art</em>, <em>arter</em> og <em>artsgrupper</em> søges der automatisk på både de latinske navne og danske navne. 
<br><br>
</p>
<b>Bemærk</b> : Alle opslag kræver at <em>query</em>-parametret er defineret og indeholder noget tekst.<br>
En tom søgning på <em>art</em>, <em>arter</em> og <em>artsgruppe-arter</em> vil resultere i en fejl. <br>
Eneste undtagelse er <em>artsgrupper</em>, hvor en uspecificeret søgning vil returnere samtlige artsgrupper.
</p>

<br><div class="alert alert-success">
Prøv at paste ovenstående URI'er ind i en browser, og se hvad der sker (browsere encoder normalt URL'er automatisk, så 
<code style="background:transparent;border:0px;padding:0px;color:#000;">http://allearter-databasen.dk/api/?get=art&query=canis lupus</code> kan kopieres direkte ind i adressefeltet)
</div>
</section>

<section id="programmeringssprog">
<h3>Programmeringssprog</h3>
<p>Ethvert sprog eller framework der kan hente data fra en ekstern ressource på nettet, og som kan parse JSON, kan benyttes op imod allearter.dk's API.
Dette stiller allearter.dk's API til rådighed for praktisk talt samtlige webbaserede sprog og teknologier.</p>
<p>Eksemplerne vil her være fokuseret på <b>PHP</b> og <b>JavaScript</b>, idet PHP stadig er det 
mest populære script-sprog på serversiden, og javascript i højere og højere grad er kernen 
i moderne såkaldt "rige" web 2.0 applikationer.</p>
<p></p>
<div class="alert alert-info">
Hvis eksemplerne er mangelfulde, eller du mangler et eksempel i et bestemt sprog, så følg kontaktoplysningerne i bunden af siden. Måske kan vi hjælpe.
</div>
</section>

<section id="serverside">
<h3>PHP serverside eksempel</h3>
<p>
Den mest enkle udnyttelse af API'et må være at benytte det serverside. PHP-scriptet nedenfor henter alle fisk
fra artslisten og præsenterer dem i en HTML-tabel<br>
<code>serverside.php</code> : 
</p>
<pre class="prettyprint lang-html">
&lt;?
$JSON=file_get_contents('http://allearter-databasen.dk/api/?get=artsgruppe-arter&query=fisk');
$data=json_decode($JSON, true);
$HTML='&lt;h2>Danske fiskearter&lt;/h2>';
$HTML.='&lt;table>';
$HTML.='&lt;thead>&lt;tr>&lt;th>Latinsk navn&lt;/th>&lt;th>Dansk navn&lt;/th>&lt;/tr>&lt;/thead>';
foreach ($data['allearter'] as $fiskeart) {
    $HTML.='&lt;tr>&lt;td>'.$fiskeart['Videnskabeligt_navn'].'&lt;/td>&lt;td>'.
    utf8_decode($fiskeart['Dansk_navn']).'&lt;/td>&lt;/tr>';
}
echo $HTML;
?&gt;
</pre>
<p>
<small>(Bemærk anvendelsen af <code>utf8_decode</code>. Dette forebygger at æøå ender som mærkelige tegn i browseren)</small>
<br><br>
<button class="btn btn-primary" id="serverside-btn">Kør eksemplet</button>
<p>
Der bliver "snydt" lidt her, eftersom PHP-scriptet - hvis det var inkluderet på siden - allerede ville vise tabellen. 
Scriptet <em>bliver</em> kørt, blot via AJAX. 
</p>
<div id="serverside-result" class="eksempel"></div>
</section>

<section id="crossdomain">
<h3>Crossdomain-problematikken og proxies</h3>
<p>Forinden demonstration af et par javascript-eksempler er det værd dvæle lidt ved browsernes 
<a href="http://en.wikipedia.org/wiki/Same_origin_policy" target=_blank>same origin policy</a>.
Kort fortalt handler det om, at browserne af sikkerhedsgrunde ikke tillader klient-scripts at 
køre eller aktivere kode der ligger på en anden host (primitivt sagt domæne) end det samme som klienten refererer til.
Dette betyder at man med et scriptsprog på serversiden nemt kan hente indholdet af en hjemmeside, webservice eller
som her foretage et kald til et API - hvorimod man på klient-siden bliver bremset i forsøget.
<br><br>
Den problematik er der naturligvis mange løsninger på. Der findes et hav af javascript-udvidelser der muliggør et crossdomain-kald. 
Man kan også, hvis man f.eks bruger jQuery, benytte sig af den 
standardiserede <a href="http://en.wikipedia.org/wiki/JSONP" target=_blank>JSONP</a>-protokol.
<br><br>
Det allernemmeste, og som benyttes i eksemplerne herunder, er dog at tilgå "fremmede" domæner/ressourcer
via en bro - en såkaldt proxy. 
En proxy er et lille script der ligger på samme domæne og på samme niveau som hjemmesiden, og som gør det virkelige arbejde 
med at kalde en ekstern ressource, og derefter leverer det til klienten. På den måde "narres" f.eks 
en browser til at tro, at man kalder en lokal ressource.
<br><br>
Dette er ikke raketvidenskab. Den proxy der benyttes her på siden er slet og ret, proxy.php :
<pre class="prettyprint">
&lt;?
$kommando=$_SERVER['QUERY_STRING'];
$fra=strpos($kommando, 'url=')+4;
$url=substr($kommando, $fra);
echo utf8_decode(file_get_contents($url));
?>
</pre>
Så i stedet for at javascript-funktionerne kalder API'et direkte, f.eks 
<code>http://allearter-databasen.dk/api/?get=artsgruppe-arter&query=edderkopper</code>
<br><br>kaldes API'et via proxyen :<br>
<code>
proxy.php?url=http://allearter-databasen.dk/api/?get=artsgruppe-arter&query=edderkopper
</code>
<br><br>Resultatet er det samme, og der er ikke noget nævneværdigt overhead forbundet med det.
</p>
</section>

<section id="ajax1">
<h3>Simpelt AJAX eksempel (javascript og jQuery)</h3>
<p>I mange sammenhænge vil man få brug for at benytte API'et dynamisk. Det kan være at udfylde 
HTML-elementer såsom select-bokse med data, samt ikke mindst reagere på input eller aktioner fra brugeren.</p>
<p>Nedenstående script gør brug af jQuery's forenkling af AJAX kald, 
men man kan benytte dét javascript-bibliotek man vil, så længe det understøtter AJAX, eller 
bruge API'et via "ren" javascript, som et eksempel lidt længere nede demonstrerer. </p>
<p>Eksemplet gør følgende : Vi har to knapper identificeret via klassen <code>.ajax-eksempel</code> og en <code>&lt;select></code>-boks. Når der klikkes på knapperne
aktiveres et script, der fylder <code>&lt;select></code>-boksen op med samtlige artsgrupper fra artslisten,
hhv. de latinske betegnelser og de danske betegnelser.<br><br>
<button class="btn btn-primary ajax-eksempel" id="aelat">Hent artsgruppernes latinske betegnelser</button>
<button class="btn btn-primary ajax-eksempel" id="aeda">Hent artsgruppernes danske betegnelser</button>
<br><br>
<div class="eksempel">
<b>Artsgrupper</b> :<br>
<select id="ajax-eksempel-select" size="8"></select>
</div><br>
<pre class="prettyprint">
&lt;script type="text/javascript">
$(".ajax-eksempel").click(function() {
  var id=$(this).attr('id');
  var url='proxy.php?url=http://allearter-databasen.dk/api/?get=artsgrupper';
  $.ajax({
    url: url,
    dataType : 'json',
    success : function(json) {
      $("#ajax-eksempel-select").empty();
      for (var i=0;i<&#106;son.allearter.length;i++) {
        var artsgruppe = (id=='aelat') ? 
          json.allearter[i].Artsgruppe : 
          json.allearter[i].Artsgruppe_dk;
        var option='&lt;option>'+artsgruppe+'&lt;/option>';
        $("#ajax-eksempel-select").append(option);
      }
    }
  });
});
&lt;/script>
</pre>
</section>

<section id="pure-javascript">
<h3>"Pure javascript"-eksempel</h3>
<p>Der kan være mange grunde til at man vil undgå javascript-biblioteker som jQuery, prototype mv. 
Det kan handle om vaner, om systemkrav, om så lille load som muligt - eller slet og ret af "politiske" årsager.
<br><br>
Det følgende eksempel demonstrerer kald til API'et i ren javascript uden falbelader, og gør akkurat det samme som 
AJAX-eksemplet ovenover.
</p>
<button class="btn btn-primary" onclick="pureJavascriptEksempel(&quot;lat&quot;);">Hent artsgruppernes latinske betegnelser</button>
<button class="btn btn-primary" onclick="pureJavascriptEksempel(&quot;da&quot;);">Hent artsgruppernes danske betegnelser</button>
<br><br>
<div class="eksempel">
<b>Artsgrupper</b> :<br>
<select id="pure-javascript-select" size="8"></select>
</div><br>
<pre class="prettyprint">
&lt;script type="text/javascript">
function pureJavascriptEksempel(id) {
  var xmlhttp;
  if (window.XMLHttpRequest) {
    xmlhttp=new XMLHttpRequest();
  } else {
    //for IE6 og lavere, burde ikke være nødvendigt
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      var json=JSON.parse(xmlhttp.responseText);
      var select=document.getElementById("pure-javascript-select");
      select.options.length=0;
      for (var i=0;i<&#106;son.allearter.length;i++) {
        var artsgruppe = (id=='lat') ? json.allearter[i].Artsgruppe : json.allearter[i].Artsgruppe_dk;
        var option=document.createElement("option");
        option.text=artsgruppe;
        select.add(option);
      }
    }
  }
  var url='proxy.php?url=http://allearter-databasen.dk/api/?get=artsgrupper';
  xmlhttp.open("GET", url, true);
  xmlhttp.send();
}
&lt;/script>

</pre>

<section id="kontakt">
<h3>Kontakt</h3>
<p>
Vil du vide mere om allearter.dk's API, har tekniske spørgsmål o.lign, så skriv til <a href="mailto:David.Konrad@snm.ku.dk">David.Konrad at snm.ku.dk</a>.
Har du spørgsmål generelt om allearter.dk, send en mail til bagmand og primus motor <a href="mailto:lars.skipper@get2net.dk">lars.skipper at get2net.dk</a>.
</p>
</section>
<br><br><br><br><br><br><br>
	</div>
</div>
</div>

<script type="text/javascript">
var offset = 450;
$('.nav li a').click(function(event) {
	var e=$($(this).attr('href'))[0];
    $($(this).attr('href'))[0].scrollIntoView();
	window.scrollBy(0, -100);
	event.preventDefault();
});
$("#serverside-btn").click(function() {
	var url='serverside.php';
	$.ajax({
		url : url,
		success : function(html) {
			$("#serverside-result").html(html);
		}
	});
});
$(".ajax-eksempel").click(function() {
	var id=$(this).attr('id');
	var url='proxy.php?url=http://allearter-databasen.dk/api/?get=artsgrupper';
	$.ajax({
		url: url,
		dataType : 'json',
		success : function(json) {
			$("#ajax-eksempel-select").empty();
			for (var i=0;i<json.allearter.length;i++) {
				var artsgruppe = (id=='aelat') ? 
					json.allearter[i].Artsgruppe : 
					json.allearter[i].Artsgruppe_dk;
				var option='<option>'+artsgruppe+'</option>';
				$("#ajax-eksempel-select").append(option);
			}
		}
	});
});
</script>

<script type="text/javascript">
function pureJavascriptEksempel(id) {
  var xmlhttp;
  if (window.XMLHttpRequest) {
    xmlhttp=new XMLHttpRequest();
  } else {
    //for IE6 og lavere, unødvendig gimmick
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
	  var json=JSON.parse(xmlhttp.responseText);
      var select=document.getElementById("pure-javascript-select");
      select.options.length=0;
      for (var i=0;i<json.allearter.length;i++) {
        var artsgruppe = (id=='lat') ? json.allearter[i].Artsgruppe : json.allearter[i].Artsgruppe_dk;
        var option=document.createElement("option");
        option.text=artsgruppe;
        select.add(option);
      }
    }
  }
  var url='proxy.php?url=http://allearter-databasen.dk/api/?get=artsgrupper';
  xmlhttp.open("GET", url, true);
  xmlhttp.send();
}
</script>

