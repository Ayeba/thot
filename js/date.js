$(document).ready(function() {
	$( "#datepicker" ).datepicker({ dateFormat: 'dd/mm/yy' });
	
	
    $('.delText').click(function(){
    	var id = $(this).attr('id');
		$( "#dialog-confirm" ).dialog({
			resizable: false,
			height:140,
			modal: true,
			buttons: {
				"oui": function() {
					window.location = "gestion_dates.php?"+id;
					$( this ).dialog( "close" );
				},
				"Annuler": function() {
					$( this ).dialog( "close" );
				}
			}
		});
		return false;
    });
	
	
});