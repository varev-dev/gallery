<?php
function create_image_by_extension(&$image, string $pathToFile, string $fileType): bool {
    if ($fileType == 'image/jpeg') {
        $image = imagecreatefromjpeg($pathToFile);
        return true;
    }
    else if ($fileType == 'image/png') {
        $image = imagecreatefrompng($pathToFile);
        return true;
    }
    else
        return false;
}

function create_image_file($image, string $path, string $fileType): bool {
    if ($fileType == 'image/jpeg') {
        if (!imagejpeg($image, $path))
            return false;
    } else if ($fileType == 'image/png') {
        if (!imagepng($image, $path))
            return false;
    }

    return true;
}

function watermark_transformation(&$image, $path, $watermark): bool {
    $color = imagecolorallocatealpha($image, 255, 255, 255, 60);
    $font = IMAGE_BUILD_DIR . "/" . FONT_FAMILY;
    $size = getimagesize($path);
    $fontSize = ($size[0] + $size[1]) / (2 * strlen($watermark));

    if (!imagettftext($image, $fontSize, 0, 0, $size[1]/2, $color, $font, $watermark))
        return false;

    return true;
}

function scale_transformation(&$image): bool {
    $temp = imagescale($image, THUMBNAIL_WIDTH, THUMBNAIL_HEIGHT);

    if (!$temp)
        return false;

    $image = $temp;
    return true;
}