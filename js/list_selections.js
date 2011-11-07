$(document).ready(function() {	
	    
	 $('.delText').click(function(){
	    	var id = $(this).attr('id');
			$( "#dialog-confirm" ).dialog({
				resizable: false,
				height:140,
				modal: true,
				buttons: {
					"oui": function() {
						window.location = "list_selections.php?del=1&id="+id;
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