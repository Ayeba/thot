$(document).ready(function() {	
	    
	    $('.delText').click(function(){
			$( "#dialog-delete" ).dialog({
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
	    
	    
	    $('.validate').click(function(){
			$( "#dialog-validate" ).dialog({
				resizable: false,
				height:140,
				modal: true,
				buttons: {
					"oui": function() {
						window.location = "show_formation.php?id="+id+"&pub=1";
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
