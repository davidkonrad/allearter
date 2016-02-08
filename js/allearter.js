
var resizing=false;
var resultTable=null;
var openDetails = [];
var valueStore = [];
var resetFlag = false;

function doResize() {
	return;

	try {
		var lw=$("#bif-search-left").offset();
		lw=Math.round(lw.left)+295;
	} catch(e) {
		lw=0;
	}
	var w=$(window).innerWidth();
	w=(w*0.9);w=Math.round(w-315);
	ll=lw;
	$("#bif-search-right").width(w+'px');
	$("#bif-search-right").css('left',ll+'px');
	//$("#bif-header-cnt").css('margin-left',ll+'px');
}
$(window).resize(function(e) {
	if (resizing!==false) {
		clearTimeout(resizing);
	} 
	resizing=setTimeout(doResize,200);
});

function adjustHeights() {
	var mx=1550;
	var ri=$("#bif-search-right").innerHeight();
	var th=$("#search-result_info").offset();
	try { th=th.top;} catch(e) { th=mx; }
	var hh=(ri>mx) ? ri : mx;
	hh=(th>hh) ? th : hh;
	$("#bif-search-right").height(hh);
	$("#bif-search").height(hh);
	$("#bif-search-left").height(hh);
}

function enableButton(button, enable) {
	if (enable===true) {
		$(button).removeAttr("disabled");
		//fix jquery UI bug
		$(button).removeClass('ui-state-disabled');
	} else {
		$(button).attr("disabled", "disabled");
		$(button).addClass('ui-state-disabled');
	}
}

function paramExists(param) {
	var url = window.location.href;
	if(url.indexOf('?' + param) != -1) return true;
	if(url.indexOf('?' + param + '=') != -1) return true;
	if(url.indexOf('&' + param + '=') != -1) return true;
	return false
}

/* now performed serverside
function linkify(html) {
	var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
	html=html.replace(exp,"<a target=_blank href='$1'>$1</a>"); 
	return html;
}
*/

function initResult() {
	var isTaxon = paramExists('taksonomi') || paramExists('taxon');
	var taksonomi = (isTaxon && ($('#search-result tr').length)==2);

	resultTable=$("#search-result").dataTable({
		bPaginate: (taksonomi) ? false : true,
		sPaginationType: "full_numbers",
		aLengthMenu: [25,50,100,500],
		bAutoWidth: false,
		bStateSave: true,
		bScrollAutoCss: false,
		bSortClasses: false,
		aaSorting: [], 
		iDisplayLength: 50,
		bProcessing: false,
		bInfo: (taksonomi) ? false : true,
		oColVis: {
			"buttonText": "&#9660; Vis kolonner"
		},
		//sDom: 'C<"clear">Rlfrtip', ok
		//sDom: 'C<"clear">Rlfrtip',
		//sDom: 'T<"clear"><"H"lfr>t<"F"ip>',

		sDom: 'TC<"clear">R<"H"lpfr>t<"F"i>',
		//"sDom": 'T<"clear">RClfrtip',

		bJQueryUI: true,
		oLanguage: { "sUrl": "DataTables-1.9.1/danish.txt" },
		"aoColumns": [ 
		      {"bVisible": true},  /* Videnskabeligt navn */
		      {"bVisible": true},  /* autor */
		      {"bVisible": true},  /* dansk navn */
		      {"bVisible": true},  /* Familie */
		      {"bVisible": true},  /* Orden */
		      {"bVisible": true},  /* Artsgruppe dk */

		      {"bVisible": false}, /* Familie (dk)  */
		      {"bVisible": false}, /* Orden (dk) */
		      {"bVisible": false}, /* Artsgruppe) */
		      {"bVisible": false}, /* Synonymer */
		      {"bVisible": false}, /* Synonymer_dk */
		      {"bVisible": false}, /* Referencenavn */
		      {"bVisible": false}, /* Reference_aar */
		      {"bVisible": false}, /* Referencetekst */
		      {"bVisible": false}, /* Den_danske_roedliste */
		      {"bVisible": false}, /* Fredede_arter */
		      {"bVisible": false}  /* Dansk */
	      	],
		fnDrawCallback: function (o) {
			var nColVis = $('div.ColVis', o.nTableWrapper)[0];
			$(nColVis).find("button").removeClass('ColVis_Button TableTools_Button ui-button ui-state-default ColVis_MasterButton');
			$(nColVis).find("button").addClass("ui-button ui-state-default");
			$(nColVis).find("button").css('width','110px');
			nColVis.style.width = "112px";
			nColVis.style.top = "-3px";
		},
		fnInitComplete: function(oSettings, json) {
			adjustHeights();
		},
		oTableTools: {
			sSwfPath: "DataTables-1.9.1/extras/TableTools/media/swf/copy_csv_xls_pdf.swf",
			aButtons: [
				{ "sExtends": "print",
				  'mColumns':'visible',
				  "sInfo" : 'Click "print" eller ctrl-P for at printe, tryk escape / ESC for at gå tilbage til søgeside',
				  "sToolTip" : 'Udskriv søgeresultater'
				},
				{ "sExtends": "csv",
				  'mColumns':'visible',
				  'sFileName': 'allearter.csv',
				  'sFieldSeperator': ";",
				  'sToolTip': 'Gem søgeresultater som CSV fil'
				},
				{ "sExtends": "xls",
				  'mColumns':'visible',
				  'sFileName': 'allearter.xls',
				  'sToolTip': 'Gem søgeresultater som fil der kan læses af Excel'
				},
				{	
				  "sExtends": "pdf",
				  'mColumns':'visible',
				  'sToolTip': 'Gem søgeresultater som PDF fil',
				  "sPdfOrientation": "landscape",
				  "fnClick": function( nButton, oConfig, flash ) {
						flash.setFileName('allearter.pdf');
						this.fnSetText( flash,
							"title:"+ this.fnGetTitle(oConfig) +"\n"+
							"message:"+ oConfig.sPdfMessage +"\n"+
							"colWidth:"+ this.fnCalcColRatios(oConfig) +"\n"+
							"orientation:"+ oConfig.sPdfOrientation +"\n"+
							"size:"+ oConfig.sPdfSize +"\n"+
							"--/TableToolsOpts--\n" +
							this.fnGetTableData(oConfig)
						);
					    }  
				},
				{
				 "sExtends" : "text",
				 "sButtonText": "CSV - samtlige felter",
				 'sToolTip': 'Gem søgeresultatet og samtlige bagvedliggende felter fra databasen som CSV',
				 "fnClick" : function( nButton, oConfig, flash ) {
						downloadDlg();
					     }
				}				
			]
		}
        });
	$('#search-result td.details').css('background-color','#ffffff');
	openDetails = [];

	if (!taksonomi) {
	$('#search-result td.details').on('click', function (e) {
		//console.log(e);
		e.stopPropagation();
		$this = $(this);
		var tr=this.parentNode;
		var id=$this.attr('artid');
		var i=$.inArray(tr, openDetails);
		if (i===-1) {
			$this.css('border','1px solid #ebebeb');
			$this.css('background-color','#ebebeb');
			var url="ajax/details.php?id="+id+'&lang='+$('input[name=sprog]:checked').val();
			$.get(url, function (response) {
				var html=response;
				//html=linkify(html);
				details = resultTable.fnOpen(tr, html, 'details');
				var taxon=$("#eol-link-"+id).attr("taxon");
				var href='http://eol.org/search/?q='+taxon+'&search=Go';
				$("#eol-link-"+id).attr('href',href);
				openDetails.push(tr);
				getEOLImage(taxon, id);
				adjustHeights();
				//update .details-cnt height according to .details-item
				setTimeout(function() {
					var $details = $(details).find('.details-cnt'),
						detailsItem = $details.find('.details-item')[0];
					$(detailsItem).height(detailsItem.scrollHeight);
					$details.height(detailsItem.scrollHeight);
				}, 500);
			});
		} else {
			//is it the actual taxon link we have clicked on?
			if ($this.attr('artid')>0) {
				$this.css('border','none');
				$this.css('background-color','#ffffff');
				resultTable.fnClose(tr);
				openDetails.splice(i,1);
				adjustHeights();
			}
		}
	});
	} else {
		setTimeout(removeToolbars, 200);
		//var html=$(".details-cnt").html();
		//html=linkify(html);
		//$(".details-cnt").html(html);
		var id=$('.details').attr('artid');
		var taxon=$("#eol-link-"+id).attr("taxon");
		var href='http://eol.org/search/?q='+taxon+'&search=Go';
		$("#eol-link-"+id).attr('href',href);
		getEOLImage(taxon, id);
	}

	$("#search-result_length select").on('change', function() {
		setTimeout(adjustHeights, 100);
	});
}

function removeToolbars() {
	$(".fg-toolbar").hide();
}

function getSupplierText(url, link) {
	if (url.indexOf('miridae.dk')>-1) {
		if (!link) {
			return '&copy; miridae.dk - Danmarks Blomstertæger';
		} else {
			var text="&copy;&nbsp;";
			text+='<a href="http://www.miridae.dk/" target=_blank title="Danmarks Blomstertæger" style="color:gray;text-decoration:none;"><b style="color:teal;">miridae.dk</b> - Danmarks Blomstertæger</a><br>';
			return text;
		}
	} else {
		if (!link) {
			return '&copy eol.org';
		} else {
			return '<sup>&copy eol.org</sup><br>';
		}
	}
}
	
function setTaxonImage(url, id) {
	var img='<img id="eol-found-image-'+id+'" src="'+url+'" title="'+getSupplierText(url, false)+'" class="details-image">';
	img=getSupplierText(url, true)+img;
/*
	$("#eol-image-"+id).css('height','120px');
	$("#eol-image-"+id).css('min-height','120px');
	$("#eol-image-"+id).css('overflow','visible');
	$("#eol-image-"+id).css('display','block');
	$("#eol-image-"+id).css('position','relative');
*/
	$("#eol-image-"+id).html(img);

	$("#eol-found-image-"+id).on("mouseover",function() {
		//$("#eol-found-image-"+id).css('width','480px');
		$("#eol-found-image-"+id).css('width','450px');
		$("#eol-found-image-"+id).css('height','auto');
	});
	$("#eol-found-image-"+id).on("mouseout",function() {
		$("#eol-found-image-"+id).css('width','auto');
		$("#eol-found-image-"+id).css('height','100px');
	});
}

function getEOLImage(taxon, id) {
	var url='ajax/taxonimage.php?action=get&taxon='+taxon;
	$.ajax({
		url: url,
		cache: true,
		success: function(response) {
			//console.log('X'+response+'X');
			switch (response) {
				case 'EXCLUDED' :
				case 'FAIL' :
					$("#eol-image-"+id).remove();
					break;
				default :
					setTaxonImage(response, id);
					break;
			}
		}
	});
}

function recordCount(count, open) {
	$('#recordcount').countTo({
		from: 1,
		to: count,
		speed: 500,
		refreshInterval: 50,
		onComplete: function(value) {
			if (open) {
				$('#search-result td.details').trigger('click');
			}
		}
	});
}

function insertExtraKlassifikation(field, value) {
	field=field.replace('klas_','');
	valueStore['extra_field']=field;
	valueStore['extra_value']=value;
	field=field.charAt(0).toUpperCase()+field.slice(1);
	$("#extra-klassifikation").html('');
	var fieldHTML=field.replace('ae','&aelig;');
	var html='<strong>+</strong>&nbsp;'+fieldHTML+' : '+value+'<br>';
	$("#extra-klassifikation").append(html);
	$("#extra-klassifikation").css('background-color','#ebebeb');
	enableSearch();
}

var extra_klas = ['klas_underorden','klas_overorden','klas_underklasse','klas_overklasse','klas_underraekke',
'klas_infraklasse','klas_infraorden','klas_underfamilie','klas_overfamilie','klas_tribus'];

function valueStoreCallback(field, value) {
	if (value===valueStore[field]) return false;

	if (value==='') {
		delete valueStore[field]; //
		resetKlasInput(field);
		resetDownwards(field);
	} else {
		valueStore[field]=value; //
		$('#'+field).next().find('input').removeClass('placeholder');
		autoLookup(field, value);
	}
	if ($.inArray(field, extra_klas)>-1) {
		insertExtraKlassifikation(field, value);
		return false;
	}
	/*
	if (value!='') {
		$('#'+field).next().find('input').removeClass('placeholder');
		autoLookup(field, value);
	} else {
		resetKlasInput(field);
		resetDownwards(field);
	}
	*/
	enableSearch();

	if (field=='artsgruppe' || field=='artsgruppedk') {
		var f=(field=='artsgruppe') ? 'artsgruppedk' : 'artsgruppe';
		resetKlasInput(f);
		var p=(field=='artsgruppe') ? 'a' : 'ad';
		$.ajax({
			url: 'ajax/systematik.php?'+p+'='+value,
			cache: true,
			async: true,
			timeout : 3000,
			success: function(response) {
				if (response=='yes') {
					$("#systematik_sorter").show();
				} else {
					$("#systematik_sorter").hide();
				}
			}
		});
	}
}

function autoLookup(field, value) {
	var target='';
	var prior='';
	switch (field) {
		case 'klas_slaegt' : prior='Slaegt'; target='Familie'; break;
		case 'klas_familie' : prior='Familie'; target='Orden'; break;
		case 'klas_orden' : prior='Orden'; target='Klasse'; break;
		case 'klas_klasse' : prior='Klasse'; target='Raekke'; break;
		case 'klas_raekke' : prior='Raekke'; target='Rige'; break;
		default : return false; break;
	}
	var url='ajax/lookup.php';
	url+='?lang='+$('input[name=sprog]:checked').val();
	url+='&target='+target;
	url+='&prior='+prior;
	url+='&value='+encodeURIComponent(value);
	url+='&extra='+valueStore['extra_field'];
	url+='&extra_value='+encodeURIComponent(valueStore['extra_value']);
	$.ajax({
		url: url,
		dataType : 'text',
		cache: true,
		async: true,
		timeout : 10000,
		success: function(html) {
			var new_field='klas_'+target.toLowerCase();
			$("#"+new_field).next().find('input').val(html);
			valueStoreCallback(new_field, html);
		},
		error: function(xhr, ajaxOptions, thrownError){
                    //alert(xhr.status);
                    //alert(thrownError);
                }   

	});
}

function resetDownwards(field) {
	var target='';
	switch (field) {
		case 'klas_familie' : target='klas_slaegt'; break;
		case 'klas_orden' : target='klas_familie'; break;
		case 'klas_klasse' : target='klas_orden'; break;
		case 'klas_raekke' : target='klas_klasse'; break;
		case 'klas_rige' : target='klas_raekke'; break;
		default : return false; break;
	}
	resetKlasInput(target);
	valueStoreCallback(target,'');
}		

function resetKlassifikation() {
	valueStore = [];
	resetKlasInput('klas_rige');
	resetKlasInput('klas_orden');
	resetKlasInput('klas_raekke');
	resetKlasInput('klas_familie');
	resetKlasInput('klas_klasse');
	resetKlasInput('klas_slaegt');
	$("#extra-klassifikation").html('');
	$("#extra-klassifikation").css('background-color','#ffffff');
}

function resetKlasInput(klas) {
	$("#"+klas).next().find('input').val('');
	$("#"+klas).next().find('input').addClass('placeholder');
}

function arterClick(item) {
	//force set if counterpart is not set
	switch ($(item).attr('id')) {
		case 'arter_danske' : 
			if (!$("#arter_ikke_danske").is(':checked')) $(item).attr('checked', 'checked');
			break;
		case 'arter_ikke_danske' : 
			if (!$("#arter_danske").is(':checked')) $(item).attr('checked', 'checked');
			break;
		default :
			return true;
			break;
	}
}

function forvlChecked() {
	return $('#forv_roedliste').is(':checked') ||
	$('#forv_fredede').is(':checked') ||
	$('#forv_efhabitat').is(':checked') ||
	$('#forv_effugle').is(':checked') ||
	$('#forv_bern').is(':checked') ||
	$('#forv_bonn').is(':checked') ||
	$('#forv_cites').is(':checked') ||
	$('#forv_oevrige').is(':checked');
}

function taxonChecked() {
	return  $('#taxon_art').is(':checked') ||
	$('#taxon_underart').is(':checked') ||
	$('#taxon_varietet').is(':checked') ||
	$('#taxon_form').is(':checked') ||
	$('#taxon_hybrid').is(':checked');
}

function taxonClick() {
	var disable=taxonChecked();
	if (disable) {
		$('#taxon_alle').removeAttr('checked');
		$('#taxon_alle').attr('disabled','disabled');
		$('#taxon_alle').next().addClass('disable');
	} else {
		$('#taxon_alle').attr('checked', 'checked');
		$('#taxon_alle').attr('disabled','disabled');
		$('#taxon_alle').next().removeClass('disable');
	}
	enableSearch();
}

function reset() {
	$("#fritext").val('');
	$("#fritext").addClass('placeholder');
	$("#fri_alle_navne").attr("checked","checked");
	$(".taxon-kategori").removeAttr('checked');
	$("#taxon_alle").attr('checked','checked');
	$(".forvl-cri").removeAttr('checked');
	$("#forvl-mode-or").attr('checked','checked');
	$("#fri_samtlige").attr('checked', 'checked');
	$("#multi-opslag").val('');
	resetKlasInput("artsgruppe");
	resetKlasInput("artsgruppedk");
	$("#nye_arter").removeAttr('checked');
	$("#arter_ikke_danske").removeAttr('checked');
	$("#arter_danske").attr('checked','checked');
	resetKlassifikation();
	$("#systematik_sorter").hide();
	enableSearch();
}

Object.size = function(obj) {
	var size = 0, key;
	for (key in obj) {
		if (obj.hasOwnProperty(key)) size++;
	}
	return size;
};

function hasCriterias() {
	if ($("#fritext").val()!='') return true;
	if (taxonChecked()) return true;
	if (forvlChecked()) return true;
	if (Object.size(valueStore)>0) return true;
	if ($('#nye_arter').is(':checked')) return true;
	return false;
}	

function enableSearch() {
	if (hasCriterias()) {
		$("#search-btn").removeAttr('disabled');
	} else {
		$("#search-btn").attr('disabled','disabled');
	}
}

$(document).ready(function() {
	$("#artsgruppe").combobox({ source: "ajax/artsgruppe.php" });
	$("#artsgruppe").next('span').find('input').watermark('[ Videnskabelig ]');

	$("#artsgruppedk").combobox({ source: "ajax/artsgruppe.php" });
	$("#artsgruppedk").next('span').find('input').watermark('[ Dansk ]');

	$("#multi-opslag").watermark('Klassifikationsnavn');

	$("#klas_rige").combobox({ source: "ajax/klassifikation.php" });
	$("#klas_rige").next('span').find('input').watermark('[ Rige ]');

	$("#klas_raekke").combobox({ source: "ajax/klassifikation.php" });
	$("#klas_raekke").next('span').find('input').watermark('[ Række ]');

	$("#klas_klasse").combobox({ source: "ajax/klassifikation.php" });
	$("#klas_klasse").next('span').find('input').watermark('[ Klasse ]');

	$("#klas_familie").combobox({ source: "ajax/klassifikation.php" });
	$("#klas_familie").next('span').find('input').watermark('[ Familie ]');

	$("#klas_orden").combobox({ source: "ajax/klassifikation.php" });
	$("#klas_orden").next('span').find('input').watermark('[ Orden ]');

	$("#klas_slaegt").combobox({ source: "ajax/klassifikation.php" });
	$("#klas_slaegt").next('span').find('input').watermark('[ Slægt ]');

	$("#fritext").watermark('skriv ...');

	/*
	$('.collapsible').collapsible({
		//defaultOpen: "artsgruppeh3,klassifikationh3,fritexth3,forvaltningh3,taxonkategorih3,artsudbredelseh3,extrah3"
		defaultOpen: "fritexth3"
	});
	*/

	//all records, or search by permalink
	if (paramExists('perma')) {
		recordCount($('#search-result tr').length-1, false);
	} else {
		//recordCount(<? echo $html->getNumberOfSpecies();?>, true);
		//now we load the details serverside
		recordCount(arterCount, false);
	}

	initResult();

	/*
	$("#statistik").button();
	$("#artsgruppe-statistik").button();
	$("#referencer").button();
	*/

	doResize();
	reset();
	initializeHints();

	$("#fritext").bind('keypress', function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if (code == 13) { 
			if ($("#fritext").val()=='') return false;
			Search.searchSubmit();
		}
	});
	$("#fritext").bind('keyup', function(e) {
		if ($("#fritext").val()=='') { 
			$("#fritext").addClass('placeholder');
		} else {
			$("#fritext").removeClass('placeholder');
		}
		enableSearch();
	});
	$("body").bind('keypress', function(e) {
		var attr = $("#search-btn").attr('disabled');
		var code = (e.keyCode ? e.keyCode : e.which);
		if (code == 13) {
			if (typeof attr == 'undefined' || attr == false) {
				Search.searchSubmit();
			}
		}
	});
	$("#hierarchy").click(function(e) {
		e.preventDefault();
		e.stopPropagation();
		//$('html, body').animate({scrollTop:0}, 'fast');
		$('#hierarchy-modal').dialog({
 			dialogClass: 'klassifikation-hierarchy',
			title: 'Klassifikations-hierarki',
			height: 300,
			width: 400,
			position: { my: "left top", at: "left top" }
		});
		$('#hierarchy-modal').load('inc/hierarchy.html');
	});

	$("#promo-image").on("click",function() {
		if ($("#promo-image").css('width')=='340px') {
			$("#promo-image").parent().css('overflow','visible');
			$("#promo-image").css('width','770px');
			$("#promo-image").css('height','auto');
			$("#promo-image").css('float','right');
			$("#promo-image").css('z-index','200');
			$("#promo-image").css('display','block');
			$("#promo-image").css('position','relative');
		} else {
			$("#promo-image").css('width','340px');
			$("#promo-image").css('height','auto');
			$("#promo-image").parent().css('overflow','hidden');
		}
	});

	$('#fritext, #multi-opslag').addClass('ui-widget-content ui-corner-all ui-corner-top ui-corner-left ui-corner-tl ui-corner-all ui-corner-bottom ui-corner-left ui-corner-bl');
	$("#fritext").focus();
});

var Search = {
	script: 'ajax/search.php',
	logSearch: function(url) {
		url='ajax/ajax_log.php'+url;
		$.ajax({
			url: url,
			cache: false,
			async: true,
			timeout : 5000,
			success: function(log_id) {
				History.add(log_id);
				Search.setPermaLink(log_id);
			}
		});
	},
	setPermaLink : function(log_id) {
		var url='ajax/permalink.php?log_id='+log_id;
		$.ajax({
			url: url,
			cache: false,
			async: true,
			timeout : 5000,
			success: function(html) {
				$("#perma-link-cnt").show();
				$("#perma-link").val(html);
			}
		});
	},		
	enableCSVBtn : function() {
		$("#ToolTables_search-result_4").css('display','inline');
		$("#ToolTables_search-result_4").css('width','170px');
		$("#ToolTables_search-result_4").css('padding-left','10px');
		$("#ToolTables_search-result_4").css('padding-right','10px');
	},		
	getVal : function(field) {
		var val=valueStore[field];
		if (val===undefined) val='';
		return val;
	},
	getTaxon : function() {
		var taxon='';
		if ($('#taxon_art').is(':checked')) taxon+='Art ';
		if ($('#taxon_underart').is(':checked')) taxon+='Underart ';
		if ($('#taxon_varietet').is(':checked')) taxon+='Varietet ';
		if ($('#taxon_form').is(':checked')) taxon+='Form ';
		if ($('#taxon_hybrid').is(':checked')) taxon+='Hybrid';
		return taxon;
	},
	getText : function() {
		var text='';
		if ($("#fritext").val()!='') {
			text='&text='+encodeURIComponent($("#fritext").val());
			text+='&textmode='+$('input[name=fritext-cri]:checked').val();
		}
		return text;
	},
	getSystematik : function() {
		return 	($('#systematik').is(':checked') &&
			  ((this.getVal('artsgruppe')!='') || 
			   (this.getVal('artsgruppedk')!=''))) ? '&systematik=yes' : '';
	},
	getNyeArter : function() {
		var nyearter='';
		if ($('#nye_arter').is(':checked')) nyearter='&nyearter=yes';
		return nyearter;
	},
	getForvl : function() {
		var forvl='';
		if ($('#forv_roedliste').is(':checked')) forvl+='Den_danske_roedliste ';
		if ($('#forv_fredede').is(':checked')) forvl+='Fredede_arter ';
		if ($('#forv_efhabitat').is(':checked')) forvl+='Habitatdirektivet ';
		if ($('#forv_effugle').is(':checked')) forvl+='Fuglebeskyttelsesdirektivet ';
		if ($('#forv_bern').is(':checked')) forvl+='Bern_konventionen ';
		if ($('#forv_bonn').is(':checked')) forvl+='Bonn_konventionen ';
		if ($('#forv_cites').is(':checked')) forvl+='CITES ';
		if ($('#forv_oevrige').is(':checked')) forvl+='Oevrige';
		var mode = ($("#forvl-mode-or").is(':checked')) ? '&mode=or' : '&mode=and';
		forvl+=mode;
		return forvl;
	},
	getArter : function() {
		//1=hjemmehørende, 2=ikke danske, 3=begge
		if ($('#arter_danske').is(':checked') && $('#arter_ikke_danske').is(':checked')) return 3;
		if ($('#arter_ikke_danske').is(':checked')) return 2;
		if ($('#arter_danske').is(':checked')) return 1;
	},
	getExtra : function() {
		var extra='';		
		if (Search.getVal('extra_field')!='') {
			extra+='&extra='+valueStore['extra_field'];
			extra+='&extra_value='+encodeURIComponent(valueStore['extra_value']);
		}
		return extra;
	},
	setAjax: function() {
		$("#search-cnt").empty();
		var html='<img src="images/ajax-loader2.gif" class="load">';
		$("#search-cnt").append(html);
	},
	getParams : function() {
		var url='';
		url+='?lang='+$('input[name=sprog]:checked').val();
		url+='&videnskabeligt_navn='+encodeURIComponent(this.getVal('videnskabeligt_navn'));
		url+='&artsgruppe='+encodeURIComponent(this.getVal('artsgruppe'));
		url+='&artsgruppedk='+encodeURIComponent(this.getVal('artsgruppedk'));
		url+='&rige='+encodeURIComponent(this.getVal('klas_rige'));
		url+='&familie='+encodeURIComponent(this.getVal('klas_familie'));
		url+='&orden='+encodeURIComponent(this.getVal('klas_orden'));
		url+='&raekke='+encodeURIComponent(this.getVal('klas_raekke'));
		url+='&klasse='+encodeURIComponent(this.getVal('klas_klasse'));
		url+='&slaegt='+encodeURIComponent(this.getVal('klas_slaegt'));

		var taxon=this.getTaxon();
		if (taxon!='') url+='&taxon_kategori='+taxon;

		var text=this.getText();
		if (text!='') url+=text;

		var forvl=this.getForvl();
		if (forvl!='') url+='&forvl='+forvl;

		var arter=this.getArter();
		url+='&arter='+arter;

		var extra=this.getExtra();
		if (extra!='') url+=extra;

		var nyearter=this.getNyeArter();
		if (nyearter!='') url+=nyearter;

		url+=this.getSystematik();

		return url;
	},
	searchSuccess : function(html) {
		$("#search-cnt").html(html);
		initResult();
		recordCount($('#search-result tr').length-1, false);
		setTimeout(adjustHeights, 500);
		setTimeout(Search.enableCSVBtn, 500);
	 	$('html, body').animate({
	        scrollTop: $("#search-cnt").offset().top
	    }, 1);
	},
	searchSubmit : function(isSimple) {
		if (isSimple) {
			var params='?lang='+$('input[name=sprog]:checked').val();
			params+='&text='+encodeURIComponent($("#search-input-simple").val());
			params+='&textmode='+$('input[name=fritext-cri]:checked').val();
		} else {
			var params=this.getParams();
		}
		this.logSearch(params);
		var url=this.script+params;
		this.setAjax();
		$.ajax({
			url: url,
			cache: false,
			async: true,
			timeout : 5000,
			success: function(html) {
				Search.searchSuccess(html);
			}
		});
	},
	permaSearch : function(guid) {
		var url=this.script+'?perma='+guid;
		this.setAjax();
		$.ajax({
			url: url,
			cache: false,
			async: true,
			timeout : 5000,
			success: function(html) {
				Search.searchSuccess(html);
			}
		});
	},
	hierarchySearch : function(scope, searchfor) {
		resetKlassifikation();
		valueStoreCallback(scope, searchfor);
		Search.searchSubmit();
	},
	downloadAll : function(separator, column, filename) {
		var params=this.getParams();
		params+='&all=yes';
		params+='&separator='+encodeURIComponent(separator);
		params+='&column='+encodeURIComponent(column);
		params+='&filename='+encodeURIComponent(filename);
		var url=this.script+params;
		window.location=url;
	}
};

$(document).ready(function() {
$("#multi-opslag").autocomplete({
	minLength: 1,
	select: function(event, ui) {
		resetKlassifikation();
		var sel=ui.item.label;
		var s=sel.split(':');
		var id=s[0].toLowerCase();
		if (id=='række') id='raekke';
		if (id=='slægt') id='slaegt';
		var id='klas_'+id;
		var value=s[1].replace(/^\s+/,"");
		$("#"+id).next().find('input').val(value);
		valueStoreCallback(id, value)
	},
	source: function(request, response) {
		$("body, html, #multi-opslag").css("cursor", "wait");
		$.ajax({
			cache: true,
			async: true,
			url: "ajax/multiopslag.php",
			dataType: "json",
			data: {
				lang: $('input[name=sprog]:checked').val(),
				opslag: request.term
			},
			error :function(jqXHR, textStatus, errorThrown) {
			},
			complete :function (jqXHR, textStatus) {
			},
			success: function( data ) {
				response($.map(data, function(item) {
					return {
						label: item.label,
						value: item.taxon
					}
				}));
				$("body, html, #multi-opslag").css("cursor", "auto");
			}
		});
	}
});
$("#multi-opslag").click(function() {
	$(this).val('');
	return false;
});
});
$(document).ready(function() {
	setTimeout(adjustHeights, 1000);
});

function popupImage(img, provider, slogan, link, photo) {
	var top = window.pageYOffset || document.documentElement.scrollTop;
	top = top + 30;
	var left = ($(window).width()-900)/2;
	var html='<div id="popup-image-header">';
	html+='<a href="'+link+'" target=_blank title="Besøg hjemmeside (åbner i nyt vindue / tab)">'+provider+'</a>';
	html+='&nbsp;'+photo;
	html+='<span style="float:right;">'+slogan+'&nbsp;&nbsp;</span>';
	html+='</div>';	
	html+='<img src="'+img+'" title="Klik for at lukke billedet">';
	$("#popup-image").css('top', top+'px');
	$("#popup-image").css('left', left+'px');
	$("#popup-image").html(html);
	$("#popup-image").show();
}

function toggleSearch(simple) {
	if (simple) {
		$("#search-box-advanced").hide();
		$("#search-box-simple").show();
		$("#search-input-simple").val('');
		$("#search-input-simple").focus();
		$("#search-btn-simple").attr('disabled', 'disabled');
	} else {
		$("#search-box-simple").hide();
		$("#search-box-advanced").show();
		$("#fritext").focus();
	}
}

$(document).ready(function() {
	$("#popup-image").click(function() {
		$("#popup-image").hide();
	});

	$("#show-search-advanced").click(function() {
		toggleSearch(false);
	});
	$("#show-search-simple").click(function() {
		toggleSearch(true);
	});

	$("#search-input-simple").bind('keyup', function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		var val = $(this).val();
		if (code == 13) { 
			if (val=='') return false;
			Search.searchSubmit(true);
		} else {
			if (val!='') {
				$("#search-btn-simple").removeAttr('disabled');
			} else {
				$("#search-btn-simple").attr('disabled', 'disabled');
			}
		}
	});

	$("#search-btn-simple").click(function() {
		Search.searchSubmit(true);
	});

	if (paramExists('sitemap') ||
		paramExists('showtips') ||
		paramExists('statistik') ||
		paramExists('leksikon') ||
		paramExists('artsgruppe-statistik') ||
		paramExists('referencer')) {
			$("#search-box-simple").hide();
			$("#search-box-advanced").hide();
	} else {
		//hide advanced search on load
		toggleSearch(true);	
	}


});

