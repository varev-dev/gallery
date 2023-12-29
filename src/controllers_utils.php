<?php

function validate_image_extension_and_size(string &$validation, string $extension) {
    if ($extension != "jpg" && $extension != "png")
        $validation .= "Image has to have JPG or PNG extension.<br>";
    if ($_FILES['uploadImage']['size'] > IMAGE_MAX_SIZE)
        $validation .= "Max file size " . IMAGE_MAX_SIZE * 1.0 / MB_SIZE . "MB<br>";
}

function validate_thumbnail_and_watermark(&$validation, string $target) {
    if (!edit_image($target, WATERMARK_MODE, $_POST['watermarkText']))
        $validation .= "Unknown error occurred, while adding watermark.<br>";
    if (!edit_image($target, THUMBNAIL_MODE))
        $validation .= "Unknown error occurred, while creating thumbnail.<br>";
}

function generate_unique_image_id(&$name) {
    do {
        $name = uniqid('image_');
    } while (file_exists(IMAGE_DIR . "/" . $name));
}