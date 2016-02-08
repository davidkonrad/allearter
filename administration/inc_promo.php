<script type="text/javascript">
var Promo = {
	setCnt : function(html) {
		$("#promo-cnt").html(html);
		$("#image_url").bind('input propertychange', function(e) {
			$("#img-cnt").attr('src',$("#image_url").val());
		});
	},
	init : function() {
		var url='ajax_promo.php';
		$.ajax({
			url: url,
			dataType : 'text',
			cache: false,
			async: true,
			timeout : 5000,
			success: function(html) {
				Promo.setCnt(html);
			},
			error: function (xhr, ajaxOptions, thrownError){
	                    alert(xhr.status);
	                    alert(thrownError);
	                }  
		});
	},
	create : function() {
		var url='ajax_promo.php';
		url+='?action=create';
		url+='&target_id='+$("#target_id").val();
		url+='&target='+encodeURIComponent($("#target").val());
		url+='&image_url='+$("#image_url").val();
		var content=CKEDITOR.instances.content.getData()
		url+='&content='+encodeURIComponent(content);
		var ex=($("#exclusive").is(":checked")) ? '1' :'0';
		url+='&exclusive='+ex;
		//alert(url);
		$.ajax({
			url: url,
			dataType : 'text',
			cache: false,
			async: true,
			timeout : 5000,
			success: function(html) {
				Promo.setCnt(html);
			},
			error: function (xhr, ajaxOptions, thrownError){
	                    alert(xhr.status);
	                    alert(thrownError);
	                }  
		});
	},
	edit : function(id) {
		var url='ajax_promo.php';
		url+='?action=edit';
		url+='&id='+id;
		$.ajax({
			url: url,
			dataType : 'text',
			cache: false,
			async: true,
			timeout : 5000,
			success: function(html) {
				Promo.setCnt(html);
				$('html, body').animate({scrollTop:0}, 'slow');
			},
			error: function (xhr, ajaxOptions, thrownError){
	                    alert(xhr.status);
	                    alert(thrownError);
	                }  
		});
	},
	deletePromo : function(id) {
		var url='ajax_promo.php';
		url+='?action=delete';
		url+='&id='+id;
		if (confirm('Er du sikker på du vil slette denne promo?')) {
			$.ajax({
				url: url,
				dataType : 'text',
				cache: false,
				async: true,
				timeout : 5000,
				success: function(html) {
					Promo.setCnt(html);
				},
				error: function (xhr, ajaxOptions, thrownError){
		                    alert(xhr.status);
		                    alert(thrownError);
		                }  
			});
		}
	},
	save : function(id) {
		var url='ajax_promo.php';
		url+='?action=save';
		url+='&id='+id;
		url+='&target_id='+$("#target_id").val();
		url+='&target='+encodeURIComponent($("#target").val());
		url+='&image_url='+$("#image_url").val();
		var content=CKEDITOR.instances.content.getData()
		url+='&content='+encodeURIComponent(content);
		var ex=($("#exclusive").is(":checked")) ? '1' :'0';
		url+='&exclusive='+ex;
		var ac=($("#active").is(":checked")) ? '1' :'0';
		url+='&active='+ac;
		$.ajax({
			url: url,
			dataType : 'text',
			cache: false,
			async: true,
			timeout : 5000,
			success: function(html) {
				Promo.setCnt(html);
			},
			error: function (xhr, ajaxOptions, thrownError){
	                    alert(xhr.status);
	                    alert(thrownError);
	                }  
		});
	}
};

$(document).ready(function() {
	Promo.init();
});

</script>
<div id="promo-cnt"></div>
