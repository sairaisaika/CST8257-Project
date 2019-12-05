<?php
function ValidatePrincipal($amount){
    if(!$amount||!is_numeric($amount)||$amount<=0)
        return False;
    else {
        return True;
    }
}
function ValidateRate($amount){
    if(!$amount||!is_numeric($amount)||$amount<0)
        return False;
    return True;
}
function ValidateYears($years){
    if(!$years||(!is_numeric($years))||$years<=0||$years>20)
        return False;
    return True;
}
function ValidateName($name){
    if(!$name)
        return False;
    return True;
}
function ValidatePostCode($code){
    $codeRegex = "/[a-z][0-9][a-z]\s*[0-9][a-z][0-9]/i";
    if(!$code||!preg_match($codeRegex, $code))//•	Postal code must be in the form of  XnX nXn, where X is an upper or lower case letter and n is a digit from 0 to 9, with or without space between the first 3 characters and the last 3 characters.
        return False;
    return True;
}
function ValidatePhone($phone){
    $phoneRegex = "/[2-9][0-9][0-9]-[2-9][0-9][0-9]-[0-9][0-9][0-9][0-9]/";
    if(!$phone||!preg_match($phoneRegex, $phone)) //•	Phone number must be in the form of nnn-nnn-nnnn where n is a digit, the first n in the first and the second 3-digit groups cannot be 0 or 1.
        return False;
    return True;
}
function ValidateEmail($email){
    $emailRegex = "/\w+@\w+\.\w+/i";
    if(!$email||!preg_match($emailRegex, $email)) // •	Email must be in the form of aaa@xxx.yyy where aaa and xxx is a character (including dot “.”) string of any length, yyy is a 2 to 4 characters string.
        return False;
    return True;
}
function ValidatePassword($password){
    if (strlen($password) < 6||!preg_match("#[0-9]+#", $password)||!preg_match("#[a-z]+#", $password)||!preg_match("#[A-Z]+#", $password)) {
        return False;
    }else
    return True;
}

function save_uploaded_file($destinationPath)
{
	if (!file_exists($destinationPath))
	{
		mkdir($destinationPath);
	}
	
	$tempFilePath = $_FILES['imgUpload']['tmp_name'];
	$filePath = $destinationPath."/".$_FILES['imgUpload']['name'];
	
	$pathInfo = pathinfo($filePath);
	$dir = $pathInfo['dirname'];
	$fileName = $pathInfo['filename'];
	$ext = $pathInfo['extension'];
	
	//make sure not to overwrite existing files 
	$i="";
	while (file_exists($filePath))
	{	
		$i++;
		$filePath = $dir."/".$fileName."_".$i.".".$ext;
	}
	move_uploaded_file($tempFilePath, $filePath);
	
	return $filePath;
}

function resamplePicture($filePath, $destinationPath, $maxWidth, $maxHeight)
{
	if (!file_exists($destinationPath))
	{
		mkdir($destinationPath);
	}

	$imageDetails = getimagesize($filePath);
	
	$originalResource = null;
	if ($imageDetails[2] == IMAGETYPE_JPEG) 
	{
		$originalResource = imagecreatefromjpeg($filePath);
	} 
	elseif ($imageDetails[2] == IMAGETYPE_PNG) 
	{
		$originalResource = imagecreatefrompng($filePath);
	} 
	elseif ($imageDetails[2] == IMAGETYPE_GIF) 
	{
		$originalResource = imagecreatefromgif($filePath);
	}
	$widthRatio = $imageDetails[0] / $maxWidth;
	$heightRatio = $imageDetails[1] / $maxHeight;
	$ratio = max($widthRatio, $heightRatio);
	
	$newWidth = $imageDetails[0] / $ratio;
	$newHeight = $imageDetails[1] / $ratio;
	
	$newImage = imagecreatetruecolor($newWidth, $newHeight);
	
	$success = imagecopyresampled($newImage, $originalResource, 0, 0, 0, 0, $newWidth, $newHeight, $imageDetails[0], $imageDetails[1]);
	
	if (!$success)
	{
		imagedestroy(newImage);
		imagedestroy(originalResource);
		return "";
	}
	$pathInfo = pathinfo($filePath);
	$newFilePath = $destinationPath."/".$pathInfo['filename'];
	if ($imageDetails[2] == IMAGETYPE_JPEG) 
	{
		$newFilePath .= ".jpg";
		$success = imagejpeg($newImage, $newFilePath, 100);
	} 
	elseif ($imageDetails[2] == IMAGETYPE_PNG) 
	{
		$newFilePath .= ".png";
		$success = imagepng($newImage, $newFilePath, 0);
	} 
	elseif ($imageDetails[2] == IMAGETYPE_GIF) 
	{
		$newFilePath .= ".gif";
		$success = imagegif($newImage, $newFilePath);
	}
	
	imagedestroy($newImage);
	imagedestroy($originalResource);
	
	if (!$success)
	{
		return "";
	}
	else
	{
		return newFilePath;
	}
}

function rotateImage($filePath, $degrees)
{
	$imageDetails = getimagesize($filePath);
	
	$originalResource = null;
	if ($imageDetails[2] == IMAGETYPE_JPEG) 
	{
		$originalResource = imagecreatefromjpeg($filePath);
	} 
	elseif ($imageDetails[2] == IMAGETYPE_PNG) 
	{
		$originalResource = imagecreatefrompng($filePath);
	} 
	elseif ($imageDetails[2] == IMAGETYPE_GIF) 
	{
		$originalResource = imagecreatefromgif($filePath);
	}
	
	$rotatedResource = imagerotate($originalResource, $degrees, 0);
	
	if ($imageDetails[2] == IMAGETYPE_JPEG) 
	{
		$success = imagejpeg($rotatedResource, $filePath, 100);
	} 
	elseif ($imageDetails[2] == IMAGETYPE_PNG) 
	{
		$success = imagepng($rotatedResource, $filePath, 0);
	} 
	elseif ($imageDetails[2] == IMAGETYPE_GIF) 
	{
		$success = imagegif($rotatedResource, $filePath);
	}
	
	imagedestroy($rotatedResource);
	imagedestroy($originalResource);
}
?>
