<?php
// creates and saves the image with a water mark and with a 
// thumbnail in images/thumbnails/image.type
// return false if upload images
function manipulateSentImage($image, $watermark)
{
    define('UPLOAD_DIR', '/var/www/dev/src/web/images');
    $imageName = basename($image['name']);
    $target = UPLOAD_DIR . '/originals/' . $imageName;
    if (!move_uploaded_file($image['tmp_name'], $target)) {
        return false;
    }

    $newImg = null;
    if ($image['type'] === 'image/jpg' || $image['type'] === 'image/jpeg') {
        $newImg = imagecreatefromjpeg($target);
    } elseif ($image['type'] === 'image/png') {
        $newImg = imagecreatefrompng($target);
    }

    createWatermark($image, $newImg, $watermark);
    createThumbnail($image, $newImg);

    return true;
}

// directory -> a directory inside image directory
function saveImage($image, $newImg, $directory)
{
    if ($image['type'] === 'image/jpg' || $image['type'] === 'image/jpeg') {
        imagejpeg($newImg, UPLOAD_DIR . '/' . $directory . '/' . basename($image['name']));
    } elseif ($image['type'] === 'image/png') {
        imagepng($newImg, UPLOAD_DIR . '/' . $directory . '/' . basename($image['name']));
    }
}

// adds the watermark and saves the image with a watermark
function createWatermark($image, $newImg, $watermark)
{
    $color = imagecolorallocate($newImg, 255, 255, 255);
    $fontSize = 5;
    $posX = ceil(imagesx($newImg) / 2) - strlen($watermark) * $fontSize;
    $posY = ceil(imagesy($newImg) / 2);
    imagestring($newImg, $fontSize, $posX, $posY, $watermark, $color);

    saveImage($image, $newImg, 'watermarked');
}

// resizes image nad saves resized images in thumbnails
function createThumbnail($image, $newImg)
{
    define('THUMBNAIL_WIDTH', 200);
    define('THUMBNAIL_HEIGHT', 125);
    $newImg = imagescale($newImg, THUMBNAIL_WIDTH, THUMBNAIL_HEIGHT);

    saveImage($image, $newImg, 'thumbnails');
}
