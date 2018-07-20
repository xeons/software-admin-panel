$(document).ready(function() {
	$('#dialog').dialog({
		autoOpen: false,
		width: 600,
		buttons: {
			"Ok": function() { 
				$.get($(this).dialog('option', 'href'), function(data) {
					if(data.indexOf('OK') != -1) {
						alert('Action completed successfully.');
						location.reload(true);
					} else {
						alert('Error completing action.');
					}
				});
				$(this).dialog("close"); 
			}, 
			"Cancel": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
	$('.action-link').click(function(event){
		event.preventDefault();
		var href = $(event.target).attr('href');
		$('#dialog').dialog('option', 'href', href);
		$('#dialog').dialog('open');
		return false;
	});
	$('.remove-access-button').click(function(event){
		event.preventDefault();
		var form = $(event.target).parents('form:first');
		var href = $("#pid option:selected", form).val();
		$('#dialog').dialog('option', 'href', href);
		$('#dialog').dialog('open');
		return false;
	});

});