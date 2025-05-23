<?php
session_start();

$captcha_text = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"), 0, 6);
$_SESSION['captcha'] = $captcha_text;

$width = 150;
$height = 50;
$image = imagecreate($width, $height);

$background_color = imagecolorallocate($image, 255, 255, 255); // Putih
$text_color = imagecolorallocate($image, 0, 0, 0); // Hitam

$font_size = 5;
imagestring($image, $font_size, 10, 15, $captcha_text, $text_color);

header("Content-Type: image/png");
imagepng($image);
imagedestroy($image);
?>
