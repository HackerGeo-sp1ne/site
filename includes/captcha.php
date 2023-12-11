<?php 
	session_start(); 
	$text = rand(100000,999999); 
	$_SESSION["verify_code"] = $text; 
	$height = 25; 
	$width = 65;   
	$image_p = imagecreate($width, $height); 
	$black = imagecolorallocate($image_p, 0, 0, 0); 
	$white = imagecolorallocate($image_p, 200, 255, 255); 
	$font_size = 20; 
	imagestring($image_p, $font_size, 5, 5, $text, $white); 
	imagejpeg($image_p, null, 5);
?>