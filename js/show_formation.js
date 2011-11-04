$(document).ready(function() {	
	    
	    $('.delText').click(function(){
			$( "#dialog-confirm" ).dialog({
				resizable: false,
				height:140,
				modal: true,
				buttons: {
					"oui": function() {
						window.location = "delete_formation.php?id="+id;
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
