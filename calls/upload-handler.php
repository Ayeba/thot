<?php

/** 
 * gestionnaire d'upload d'images, renvoie une réponse JSON avec des informations sur l'image
 * 
 * 
 * 
 * $_GET[]
 * 
 * string 'qqfile' : le nom du fichier
 * 
 * 
 * 
 * @package calls 
 * @author qqFileUpload + Romain BOURDON <romain@wadawedo.com> 
 */


//repertoire des images
$imgDir = '../media/formation_img/';


/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {    
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);
        
        if ($realSize != $this->getSize()){            
            return false;
        }
        
        $target = fopen($path, "w");        
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);
        
        return true;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];            
        } else {
            throw new Exception('Getting content length is not supported.');
        }      
    }   
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {  
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 1048571046;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 1048576){        
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;
        
        $this->checkServerSettings();       

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false; 
        }
    }
    
    private function checkServerSettings(){        
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));        
        
        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");    
        }        
    }
    
    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }
    
    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $userId, $replaceOldFile = TRUE){
        if (!is_writable($uploadDirectory)){
            return array('error' => "Server error. Upload directory isn't writable.");
        }
        
        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }
        
        $size = $this->file->getSize();
        
        if ($size == 0) {
            return array('error' => 'File is empty');
        }
        
        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large');
        }
        
        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];
        //$filename = md5(uniqid());
        $ext = $pathinfo['extension'];

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }
        
        if(!$replaceOldFile){
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(10, 99);
            }
        }
        
        //ajout d'un timestamp au nom de l'image
        $filename .= time();
        
        if ($this->file->save($uploadDirectory . $filename . '.' . $ext)){
            return array('success'=>true,'imgNewName'=>$filename . '.' . $ext,'imgFullPath'=>$userId.'/' . $filename . '.' . $ext);
        } else {
            return array('error'=> 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }
        
    }    
}


function resizeImage ($result,$maxWidth,$maxHeight,$imgDir,$append='')
{
	$result['imgFullPath'] = $imgDir . $result['imgFullPath'];
	
	$split = explode('.',$result['imgNewName']);
	$hRatio = $wRatio = 0;
	$i = 0;
	$ext = $name = '';
	//on r�cup�re le nom et l'extension
	while (isset($split[$i]))
	{
		$name .= $ext;
		$ext = $split[$i];
		$i++;
	}
	$ext = strtolower($ext);
	switch ($ext)
	{
	case 'jpeg':
	    $image_create_func = 'ImageCreateFromJPEG';
	    $image_save_func = 'ImageJPEG';
		$new_image_ext = 'jpg';
	    break;
	
	case 'jpg':
	    $image_create_func = 'ImageCreateFromJPEG';
	    $image_save_func = 'ImageJPEG';
		$new_image_ext = 'jpg';
	    break;
	    
	case 'png':
	    $image_create_func = 'ImageCreateFromPNG';
	    $image_save_func = 'ImagePNG';
		$new_image_ext = 'png';
	    break;
	
	
	case 'gif':
	    $image_create_func = 'ImageCreateFromGIF';
	    $image_save_func = 'ImageGIF';
		$new_image_ext = 'gif';
	    break;
	}

	$info = GetImageSize($result['imgFullPath']);
	if(empty($info))
	{
	  unlink($result['imgFullPath']);
	  exit("file is not an image");
	}
	
	$width = $info[0];
	$height = $info[1];
	
	if ($width > $maxWidth)
		$wRatio = $width / $maxWidth;
	if ($height > $maxHeight)
		$hRatio = $height / $maxHeight;
	
	if ($hRatio == 0 AND $wRatio == 0) {
				if ($append != '') {
			$split = explode('/',$result['imgFullPath']);
			$i = 0;
			$name = $path = $slash = '';
			while (isset($split[$i]))
			{
				if ($name != '')
					$path .= $name.'/';
				$name = $split[$i];
				$i++;
			}
			$saveName = $path.$append.$name;
			copy($result['imgFullPath'],$saveName);
		}
		return;
			
	}

	
	$ratio = max($wRatio,$hRatio);
	$newWidth = floor($width / $ratio);
	$newHeight = floor($height / $ratio);
	
	
	

		
	$image = ImageCreateTrueColor($newWidth, $newHeight);
	$newImage = $image_create_func($result['imgFullPath']);
	ImageCopyResampled($image, $newImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
	if ($append != '') {
		$split = explode('/',$result['imgFullPath']);
		$i = 0;
		$name = $path = $slash = '';
	//on r�cup�re le nom et l'extension
	while (isset($split[$i]))
	{
		if ($name != '')
			$path .= $name.'/';
		$name = $split[$i];
		$i++;
	}
	$saveName = $path.$append.$name;
	}
	else {
		$saveName = $result['imgFullPath'];
	}
		
	$process = $image_save_func($image, $saveName);
	
}



// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array('jpg','jpeg','gif','png');
// max file size in bytes
$sizeLimit = 2 * 1024 * 1024;

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);

if (!is_dir($imgDir.$user->id))
{
	mkdir($imgDir.$user->id);
}

$result = $uploader->handleUpload($imgDir.$user->id.'/',$user->id);
if ($result['success'] == true){
	resizeImage ($result,540,450,$imgDir);
	resizeImage ($result,300,300,$imgDir,'small');
	
}
// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
