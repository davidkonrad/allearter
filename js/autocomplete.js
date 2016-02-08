(function( $ ) {
	$.widget("ui.combobox", {
		_create: function() {
			var input,
			self = this,
			select = this.element.hide(),
			selected = select.children( ":selected" ),
			value = selected.val() ? selected.text() : "",
			wrapper = this.wrapper = $( "<span>" )
				.addClass( "ui-combobox" )
				.insertAfter( select );
			input = $( "<input>" )
				.appendTo( wrapper )
				.val( value )
				.addClass( "ui-combobox-input placeholder" )
				.autocomplete({
					delay: 0,
					minLength: 1,
					source: function( request, response ) {
						$.ajax({
						url: self.options.source,
						dataType: "json",
						data: {
							rige: valueStore['klas_rige'],
							raekke: valueStore['klas_raekke'],
							orden: valueStore['klas_orden'],
							klasse: valueStore['klas_klasse'],
							familie: valueStore['klas_familie'],
							slaegt: valueStore['klas_slaegt'],
							extra: valueStore['extra_field'],
							extra_value: valueStore['extra_value'],
							lang: $('input[name=sprog]:checked').val(),
							target : select.attr('id'),
							name_startsWith: request.term
						},
						error :function(jqXHR, textStatus, errorThrown) {
							alert(jqXHR.responseText+' '+textStatus+' '+errorThrown);
						},
						complete :function (jqXHR, textStatus) {
							//alert('complete: '+textStatus);
						},
						success: function( data ) {
							response( $.map( data, function( item ) {
								return {
									value: item.taxon,
									label: item.taxon
								}
							}));
						}
					});
				},
				select: function( event, ui ) {
					valueStoreCallback($(select).attr('id'), ui.item.label);
					ui.item.selected = true;
					self.store=ui.item.label;
					self._trigger("selected", event, {
						item: ui.item.label //option
					});
				},
				change: function(event, ui) {
					if (!ui.item) {
						var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $(this).val() ) + "$", "i" ),
						valid = false;
						select.children("option").each(function() {
							if ( $( this ).text().match( matcher ) ) {
								this.selected = valid = true;
								return false;
							}
						});
						if (!valid) {
							// remove invalid value, as it didn't match anything
							$( this ).val( "" );
							select.val( "" );
							input.data( "autocomplete" ).term = "";
							return false;
						} 
					}
				}
			})
			.addClass( "ui-widget ui-widget-content ui-corner-left" );
		input.click(function() {
			valueStoreCallback($(select).attr('id'), '');
			input.val('');
		});
		input.data( "autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li></li>" )
				.data( "item.autocomplete", item )
				.append( "<a>" + item.label + "</a>" )
				.appendTo( ul );
		};
		$( "<a>" )
			.attr( "tabIndex", -1 )
			.attr( "title", "Vis alle muligheder" )
			.appendTo( wrapper )
			.button({
				icons: {
					primary: "ui-icon-triangle-1-s"
				},
				text: false
			})
			.removeClass( "ui-corner-all" )
			.addClass( "ui-corner-right ui-combobox-toggle" )
				.click(function() {
					// close if already visible
					if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
						input.autocomplete( "close" );
						return;
					}
					$( this ).blur();
					// pass empty string as value to search for, displaying all results
					// " " because of minlength=1
					input.autocomplete("search"," ");
					input.focus();
				});
		},
		destroy: function() {
			this.wrapper.remove();
			this.element.show();
			$.Widget.prototype.destroy.call( this );
		}
	});
})( jQuery );
