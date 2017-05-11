<script type="text/javascript">
var Pictures = {
	init : function() {
		var url='ajax_picture.php';
		$.ajax({
			url: url,
			cache: false,
			async: true,
			timeout : 5000,
			success: function(html) {
				$("#picture-cnt").html(html);
			},
			error: function (xhr, ajaxOptions, thrownError){
				alert(xhr.status);
				alert(thrownError);
	                }  
		});
	},
	getAcceptedParam : function() {
		var accepted=($('input[name=accepted]:checked').attr('id'))=='is_accepted' ? 'yes' : 'no';
		return '&accepted='+accepted;
	},
	getLetterParam : function() {
		return '&letter='+$("#letter").val();
	},
	changeAccepted : function() {
		var url='ajax_picture.php?action=none'+this.getExtra();
		this.performUpdate(url);
	},
	getExtra : function() {
		return this.getLetterParam()+this.getAcceptedParam();
	},
	changeLetter : function(letter) {
		var url='ajax_picture.php?letter='+letter+this.getAcceptedParam();
		$("body, html").css("cursor", "wait");
		$.ajax({
			url: url,
			cache: false,
			async: true,
			timeout : 10000,
			success: function(html) {
				$("#picture-cnt").html(html);
				$("body, html").css("cursor", "auto");
			}
		});
	},
	performUpdate : function(url) {
		$("body, html").css("cursor", "wait");
		$.ajax({
			url: url,
			cache: false,
			async: true,
			timeout : 10000,
			success: function(html) {
				$("#picture-cnt").html(html);
				$("body, html").css("cursor", "auto");
			},
			error: function (xhr, ajaxOptions, thrownError){
				alert(xhr.status);
				alert(thrownError);
				$("body, html").css("cursor", "auto");
	                }  
		});
	},
	updatePicture : function(taxon) {
		var url=$('#edit-'+taxon).val();
		var realtaxon=$('#edit-'+taxon).attr('real-taxon'); 
		var param='?action=update&taxon='+realtaxon+'&url='+url+this.getExtra();
		var url='ajax_picture.php'+param;
		this.performUpdate(url);
	},
	setAccepted : function(taxon, accept) {
		var realtaxon=$('#edit-'+taxon).attr('real-taxon'); 
		var param='?action=accept&new_accept='+accept+'&taxon='+realtaxon+this.getExtra();
		var url='ajax_picture.php'+param;
		//alert(url);
		this.performUpdate(url);
	},
	saveAndAccept : function(taxon) {
		var url=$('#edit-'+taxon).val();
		var realtaxon=$('#edit-'+taxon).attr('real-taxon'); 
		var param='?action=saveaccept&new_accept=1&url='+url+'&taxon='+realtaxon+this.getExtra();
		var url='ajax_picture.php'+param;
		this.performUpdate(url);
	},
	setFAIL : function(taxon) {
		var url='FAIL';
		var realtaxon=$('#edit-'+taxon).attr('real-taxon'); 
		var param='?action=update&taxon='+realtaxon+'&url='+url+this.getExtra();
		var url='ajax_picture.php'+param;
		this.performUpdate(url);
	},
	setEXCLUDED : function(taxon) {
		if (!confirm('Er du sikker?')) return false;
		var url='EXCLUDED';
		var realtaxon=$('#edit-'+taxon).attr('real-taxon'); 
		var param='?action=update&taxon='+realtaxon+'&url='+url+this.getExtra();
		var url='ajax_picture.php'+param;
		this.performUpdate(url);
	},
	removeFromList : function(taxon) {
		var realtaxon=$('#edit-'+taxon).attr('real-taxon'); 
		var param='?action=remove&taxon='+realtaxon+'&letter='+this.getExtra();
		var url='ajax_picture.php'+param;
		this.performUpdate(url);
	}		
};

$(document).ready(function() {
	Pictures.init();
});
</script>
<style type="text/css">
span.abc {
	font-size : 1.5em;
	color: teal;
	text-transform :uppercase;
	margin-right : 5px;
	text-decoration: none;
	cursor: pointer;
}
span.abc-selected {
	cursor: pointer;
	color: black;
	font-weight: bold;
}
span.smaller {
	font-size: 1.1em;
}
td.sep {
	border-bottom:2px solid silver;
}
#accepted label {
	font-size: 12px;
}
</style>
<div id="picture-cnt"></div>

