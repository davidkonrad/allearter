function initializeHints() {
$(".info").each(function() {
	var id=$(this).attr('id');
	switch (id) {
		case 'fritexth3' : 
			var text='Med fritekstsøgningen kan der søges:<ul>';
			text+='<li>i alle felter, der rummer navne (rige, orden, familie, art, underart, synonymer etc.)</li>';
			text+='<li>på arter, underarter, varianter, former og hybrider (incl. synonymer)</li>';
			text+='<li>udelukkende på referencer</li>';
			text+='<li>i samtlige felter (navne, autorer, referencer, klassifikationskategorier, notefelter etc.)</li>';
			var title='Fritekstsøgning';
			break;
		case 'artsgruppeh3' : 
			var text='Artsgrupperne benyttet på allearter.dk er subjektive inddelinger, der ikke nødvendigvis afspejler naturlige, systematisk afgrænsede, enheder. De er i højere grad valgt ud fra praktiske hensyn og efter, hvor vidt arter er behandlet samlet i litteraturen.';
			text+='<br><br>Læs mere <a href="http://allearter.dk/artsgrupper/">her</a>';
			var title='Artsgrupper';
			break;
		case 'klassifikationh3' : 
			var text='I søgefeltet kan der, udover i de overordnede kategorier som i boksene nedenfor, søges i samtlige mellemliggende niveauer, såsom underorden og overfamilie.';
			text+='<br><br>Ved søgninger på dansk, angives det videnskabelige navn i tilfælde, hvor databasen ikke rummer et dansk navn.';
			text+='<br><br>Læs mere <a href="http://allearter.dk/om-allearter/systematik/">her</a>';
			var title='Klassifikation';
			break;
		case 'forvaltningh3' :
			var text='<b>Øvrige forvaltningskategorier :</b><br>';
			text+='AEWA (trækfugle) <br> ';
			text+='ASCOBANS (småhvaler) <br> ';
			text+='CWSS (sæler i Vadehavet)<br>';
			text+='EUROBATS (flagermus)<br><br>';
			text+='Læs mere om forvaltningskategorier <a href="http://allearter.dk/om-allearter/om-databasen/">her</a>';
			var title='Forvaltningskategorier';
			break;
		case 'taxonkategorih3':
			var text='Underarter, varieteter m.v. er indtil videre kun medtaget for planter, svampe og nogle få andre grupper.';
			var title='Taxonkategorier';
			break;
		case 'artsudbredelseh3' : 
			var text='Accepterede arter, underarter m.v., er dem, der bør optræde på en dansk liste i følge ekspertvurderinger og/eller traditioner for de enkelte artsgrupper.';
			text+='Hvor der ikke findes tilstrækkelig ekspertise eller viden, følges de overordnede kriterier for allearter.dk.';
			text+='<br><br>Læs mere om disse og se eksempler på accepterede/ikke accepterede arter <a href="http://allearter.dk/om-allearter/hvor-mange-arter-i-dk/">her</a>.';
			var title='Status';
			break;
		case 'extrah3' : 
var text='<b>Filtrér efter nye arter</b><br>';
text+='Filtrér efter arter, der er registreret som nye for Danmark de seneste år. ';
text+='Som udgangspunkt er 2009 valgt (lanceringen af allearter.dk). ';
text+='Evt. uddybende information findes i feltet "Nye arter" i detaljevisningen for de enkelte arter. ';
text+='Læs mere <a href="http://allearter.dk/nye_arter.htm">her</a>.';
text+='<br><br><b>Din søgehistorik</b><br>';
text+='Her kan du se dine tidligere søgninger og kalde dem frem igen ved et enkelt klik.';
text+='Dato samt søgekriterier angives, nogle dog i forkortet form.';
text+='<br><br><b>Perma-Link</b><br>';
text+='En søgning genererer et link, der vises i et felt under "Din søgehistorik". Dette link indeholder samtlige søgekriterier for den aktuelle søgning, og kan kopieres og gemmes til senere brug eller deles med andre.';
var title="Ekstra funktioner";
			break;

		case 'statistikh3' :
var text='<b>Statistik & nøgletal</b><br>';
text+='Se en oversigt over antallet af arter fordelt på artsgrupper, se de artsrigeste klasser, familier, slægter m.v., fordelingen af rødlistede arter på artsgrupper og meget mere.';
var title="Statistik";
			break;

		default :
			var text='Beskrivelse skal defineres';
			var title='Beskrivelse mangler';
			break;
	}
	var position = (id == 'forvaltningh3' || id == 'statistikh3')
		? {	my: 'top right', at: 'bottom left' }
		: {	my: 'top left', at: 'bottom right' }		

	$(this).qtip({
		position: position,
		content : {
			text : text
		},
		title: {
			text: title, 
			button: false
		},
		show: {
			event: 'mouseover',
			solo: true 
		},
		hide: {
			fixed: true,
			delay: 100
		},
		style: {
			//classes : 'ui-tooltip qtip ui-helper-reset ui-tooltip-default ui-tooltip-shadow ui-tooltip-plain ui-tooltip-pos-rc',
			width: 300
		}
	});
});
}
