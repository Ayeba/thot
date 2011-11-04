$(document).ready(function() {
	var thumb = $('img#thumb');
	var eventImgOld = '';
	
	 var uploader = new qq.FileUploader({
		    element: document.getElementById('file-uploader'),
		    action: 'calls/upload-handler.php',
		    allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],        
		    sizeLimit: 1048576,
		    debug: true,
		    onSubmit: function(id, fileName) {
			 	eventImgOld = $(document).find("input[name='image']").val();
			 	thumb.attr('src', '/img/loadingbig.gif');
			},
			onComplete: function(id, filename, responseJSON) {
				if (responseJSON['success'] == true){
					split = responseJSON['imgFullPath'].split('/');
					i = 0;
					imgName = imgPath = slash = '';
				//on récupère le nom et l'extension
				for (val in split)
				{
					if (imgName != '')
						imgPath += imgName+'/';
					imgName = split[val];
					i++;
				}
				saveName = imgPath+'small'+imgName;
					thumb.attr('src', 'media/formation_img/'+saveName);
					var imgField = $(document).find("input[name='image']").val(responseJSON['imgNewName']);
					$(document).find("div[id='deletePicture']").show(0, function() {});
					$('#errorMsg').remove();
				}
				else{
					thumb.attr('src', eventImgOld);
					$('#errorMsg').remove();
					$('div.ui-dialog-buttonpane').prepend('<div id="errorMsg" style="position: relative; float: left; color: red; left: 15px; top: 15px;">'+responseJSON['error']+'</div>');		
				}
			},
			onCancel: function(id, fileName){
				thumb.attr('src', eventImgOld);
				}

		 });
	     	

	    
	    //suppression de la photo  
	    $("a#deletePicture").click(function(){
	   	 if (eventImgOld != '') {
	   		 $('img#thumb').attr('src', 'media/formation_img/'+eventImgOld);
	   		$(document).find("input[name='image']").val(eventImgOld);
	   		eventImgOld = '';
	   	 }
	   	 else {
	   		$('img#thumb').attr('src', 'media/formation_img/default.png'); 
	   		$(document).find("input[name='image']").val('');
	   		$(document).find("div[id='deletePicture']").hide(0, function() {});
	   	 }
	   	
	   	 return false;
	    });
	
	    
});
