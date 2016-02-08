<script type="text/javascript">
var Teaser = {
	init : function() {
		var url='ajax_teaser.php';
		$.ajax({
			url: url,
			cache: false,
			async: true,
			timeout : 5000,
			success: function(html) {
				$("#teaser-cnt").html(html);
			},
			error: function (xhr, ajaxOptions, thrownError){
	                    alert(xhr.status);
	                    alert(thrownError);
	                }  
		});
	},
	update : function(id) {
		var text=encodeURIComponent($("#teaser_text"+id).val());
		var url='ajax_teaser.php?action=update&text='+text+'&id='+id;
		$.ajax({
			url: url,
			cache: false,
			async: true,
			timeout : 5000,
			success: function(html) {
				$("#teaser-cnt").html(html);
			},
			error: function (xhr, ajaxOptions, thrownError){
	                    alert(xhr.status);
	                    alert(thrownError);
	                }  
		});
	},
	insert : function() {
		var text=encodeURIComponent($("#teaser_text").val());
		var url='ajax_teaser.php?action=insert&text='+text;
		$.ajax({
			url: url,
			cache: false,
			async: true,
			timeout : 5000,
			success: function(html) {
				$("#teaser-cnt").html(html);
			},
			error: function (xhr, ajaxOptions, thrownError){
	                    alert(xhr.status);
	                    alert(thrownError);
	                }  
		});
	},
	//delete is a reserved word
	Delete : function (id) {
		var url='ajax_teaser.php?action=delete&id='+id;
		$.ajax({
			url: url,
			cache: false,
			async: true,
			timeout : 5000,
			success: function(html) {
				$("#teaser-cnt").html(html);
			},
			error: function (xhr, ajaxOptions, thrownError){
	                    alert(xhr.status);
	                    alert(thrownError);
	                }  
		});

	}
};

$(document).ready(function() {
	Teaser.init();
});
</script>
<style type="text/css">
input.teaser-button {
	width: 50px;
	font-size: 11px;
}
</style>
<div id="teaser-cnt"></div>

