<?php
function processImage($file) {
    if (!in_array($file['type'], ALLOWED_TYPES) || $file['size'] > MAX_IMAGE_SIZE) {
        return false;
    }

    $image = null;
    switch($file['type']) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($file['tmp_name']);
            break;
        case 'image/png':
            $image = imagecreatefrompng($file['tmp_name']);
            break;
        case 'image/webp':
            $image = imagecreatefromwebp($file['tmp_name']);
            break;
        default:
            return false;
    }

    // Resize logic
    $width = imagesx($image);
    $height = imagesy($image);
    
    if($width > MAX_DIMENSION || $height > MAX_DIMENSION) {
        $ratio = $width / $height;
        if($ratio > 1) {
            $new_width = MAX_DIMENSION;
            $new_height = MAX_DIMENSION / $ratio;
        } else {
            $new_height = MAX_DIMENSION;
            $new_width = MAX_DIMENSION * $ratio;
        }
        
        $resized = imagescale($image, $new_width, $new_height);
        imagedestroy($image);
        $image = $resized;
    }

    $path = 'uploads/' . uniqid() . '.webp';
    imagewebp($image, $path, COMPRESSION_QUALITY);
    imagedestroy($image);
    
    return $path;
}