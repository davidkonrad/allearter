/* allearter.dk search history */

var COOKIE = 'allearter_search_history';

var History = {
	history : [],
	load : function() {
		var value = unescape($.cookie(COOKIE));
		//null is translated to 'null'
		if (value!='null') {
			this.history = value.split(',');
		}
	},
	write : function() {
		var value=this.history.toString();
		$.cookie(COOKIE, value, { expires: 365 });
	},
	add : function(log_id) {
		this.history.push(log_id);
		this.write();
	}
};

//init
History.load();

//history-lookup select box
$(document).ready(function() {
$("#history-btn").on('click',function() {
	var open=$(this).attr('open');
	if (open===undefined) {
		$(this).addClass('history-down');
		$(this).attr('open','open');
		$(this).html('Min s&oslash;gehistorik&nbsp;&#9650;');
		$('body').append('<div id="history-combo"></div>');
		var offset=$("#history-btn").offset();
		$("#history-combo").css('left',offset.left+'px');
		$("#history-combo").css('top',offset.top+21+'px');
		$.ajax({
			cache: true,
			async: true,
			url: "ajax/history.php",
			dataType: "html",
			data: {	historik: History.history.toString() },
			error :function(jqXHR, textStatus, errorThrown) {
				alert(jqXHR.responseText+' '+textStatus+' '+errorThrown);
			},
			complete :function(jqXHR, textStatus) {
			},
			success: function(html) {
				$("#history-combo").html(html);
				$(".history-combo-row").on("mouseover", function() {
					$(this).find('div').css('background-color', "#ebebeb");
				});
				$(".history-combo-row").on("mouseout", function() {
					$(this).find('div').css('background-color', "#fff");
				});
				$(".history-combo-row").on("click", function() {
					var guid=$(this).attr('guid');
					Search.permaSearch(guid);
					$('html, body').animate({scrollTop:0}, 'slow');
					$("#perma-link-cnt").show();
					$("#perma-link").val('http://allearter-databasen.dk?perma='+guid);

					$("#history-btn").html('Min s&oslash;gehistorik&nbsp;&#9660;');
					$("#history-btn").removeClass('history-down');
					$("#history-btn").removeAttr('open');
					$("#history-combo").remove();

					return false;
				});

			}
		});
	} else {
		$(this).html('Min s&oslash;gehistorik&nbsp;&#9660;');
		$(this).removeClass('history-down');
		$(this).removeAttr('open');
		$("#history-combo").remove();
	}
});
});
