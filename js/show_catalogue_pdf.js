$(document).ready(function() {
	
	$(".pdfLink").click(function() {
		var id = $(this).attr('id');
		$('#pdfFrame').attr('src', id);
		return false;
	});
});