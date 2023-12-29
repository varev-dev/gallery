<?php
require_once 'controllers_utils.php';
require_once 'business.php';

define("IMAGE_MAX_SIZE", pow(2, 20));
define("MB_SIZE", pow(2, 20));

function gallery(&$model) {
    $page = $_GET['page'] ?? 1;

    if ($page < 1)
        return 'redirect:/';

    $model["page"] = $page;
    $model["maxPage"] = get_last_page_number();
    $model["images"] = get_images_from_page($page);

    return 'gallery_view';
}

function upload_image(&$model) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $validation = '';

        if (empty($_FILES['uploadImage'])) {
            $validation = "Image have not been sent.<br>";
            $model['validation'] = $validation;
            return 'upload_view';
        }
        $extension = pathinfo($_FILES['uploadImage']['name'], PATHINFO_EXTENSION);

        if ($extension != "jpg" && $extension != "png")
            $validation .= "Image has to have JPG or PNG extension.<br>";
        if ($_FILES['uploadImage']['size'] > IMAGE_MAX_SIZE)
            $validation .= "Max file size " . IMAGE_MAX_SIZE * 1.0 / MB_SIZE . "MB<br>";

        if ($validation !== '') {
            $model['validation'] = $validation;
            return 'upload_view';
        }

        do {
            $name = uniqid('image_');
        } while (file_exists(IMAGE_DIR . "/" . $name));

        $target = IMAGE_DIR . "/" . $name . "." . $extension;

        if (!move_uploaded_file($_FILES['uploadImage']['tmp_name'], $target)) {
            $validation = "Upload not completed, unknown error occurred.";
            $model['validation'] = $validation;
            return 'upload_view';
        }

        if (!edit_image($target, WATERMARK_MODE, $_POST['watermarkText']))
            $validation .= "Unknown error occurred, while adding watermark.<br>";
        if (!edit_image($target, THUMBNAIL_MODE))
            $validation .= "Unknown error occurred, while creating thumbnail.<br>";

        if ($validation !== '') {
            $model['validation'] = $validation;
            return 'upload_view';
        }

        return 'redirect:/';
    }

    return 'upload_view';
}