function downloadDlg() {
	$('<div/>').qtip({
		id: 'download', // Since we're only creating one modal, give it an ID so we can style it
		content: {
			text: $('#download-form'),
			title: {
				text: 'Download alle database-felter som CSV',
				button: false
			}
		},
		position: {
			my: 'center', // ...at the center of the viewport
			at: 'center',
			target: $(window)
		},
		show: {
			event: 'click', // Show it on click...
			ready: true, // Show it immediately on page load.. force them to login!
			modal: {
				on: true,
 
				// Don't let users exit the modal in any way
				blur: false, escape: false
			}
		},
		hide: false,
		style: {
			classes: 'ui-tooltip-light ui-tooltip-rounded',
			tip: false
		},
		events: {
			// Hide the tooltip when any buttons in the dialogue are clicked
			render: function(event, api) {
				$('#cancel-download', api.elements.content).click(function() {
					api.hide();
				});
			}
		}
	});
	$('#begin-download').click(function() {
		Search.downloadAll(
			$("#separator option:selected").val(),
			$("#column option:selected").val(),
			$("#filename").val()
		);
		$("#cancel-download").click();
	});
};
