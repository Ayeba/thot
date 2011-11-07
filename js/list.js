$(document).ready(function() {
	
	$("select[name=status]").change(function() {
		 status = $("select[name=status] option:selected").val(); 
		 window.location = "list.php?status="+status;
	});
});