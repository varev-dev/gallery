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

        validate_image_extension_and_size($validation, $extension);

        if ($validation !== '') {
            $model['validation'] = $validation;
            return 'upload_view';
        }

        generate_unique_image_id($name);

        $target = IMAGE_DIR . "/" . $name . "." . $extension;

        if (!move_uploaded_file($_FILES['uploadImage']['tmp_name'], $target)) {
            $validation = "Upload not completed, unknown error occurred.";
            $model['validation'] = $validation;
            return 'upload_view';
        }

        validate_thumbnail_and_watermark($validation, $target);

        if ($validation !== '') {
            $model['validation'] = $validation;
            return 'upload_view';
        }

        return 'redirect:/';
    }

    return 'upload_view';
}