<?php
require_once 'controllers_utils.php';
require_once 'business.php';

define("IMAGE_MAX_SIZE", pow(2, 20));
define("MB_SIZE", pow(2, 20));
const MIN_LOGIN_LENGTH = 3;
const MIN_PASSWORD_LENGTH = 8;

function gallery(&$model): string {
    $page = $_GET['page'] ?? 1;

    if ($page < 1)
        return 'redirect:/';

    $model["page"] = $page;
    $model["maxPage"] = get_last_page_number();
    $model["images"] = get_images_from_page($page);

    return 'gallery_view';
}

function upload_image(&$model): string {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $validation = '';

        if (!isset($_POST['title']) || !isset($_POST['author']) || !isset($_POST['watermarkText'])) {
            $model['validation'] = "Image title or/and author or/and watermark not set.";
            return 'upload_view';
        }

        sanitize_form($_POST);
        validate_upload_form($_POST, $validation);

        if (empty($_FILES['uploadImage']))
            $validation .= "Image have not been sent.";

        if ($validation != '') {
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
            $model['validation'] = "Upload not completed, unknown error occurred.";
            return 'upload_view';
        }

        validate_thumbnail_and_watermark($validation, $target);
        if ($validation !== '') {
            $model['validation'] = $validation;
            return 'upload_view';
        }

        $image = [
            "name" => $name,
            "extension" => $extension,
            "title" => $_POST['title'],
            "author" => $_POST['author']
        ];
        if(!save_image($image)) {
            $model['validation'] = "Saving image in database went wrong.";
            return 'upload_view';
        }

        return 'redirect:/';
    }
    return 'upload_view';
}

function register(&$model): string {
    if (isset($_SESSION['user_id']))
        return 'gallery_view';

    $model['login_length'] = MIN_LOGIN_LENGTH;
    $model['pwd_length'] = MIN_PASSWORD_LENGTH;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        return 'register_view';

    $validation = check_is_register_form_set($_POST);
    if ($validation !== "") {
        $model['validation'] = $validation;
        return 'register_view';
    }

    $user = [
        'login' => $_POST['login'],
        'email' => $_POST['email'],
        'pwd' => $_POST['pwd']
    ];
    sanitize_register_data($user);

    $validation = validate_register_form($user, $_POST['pwd_rp']);
    if ($validation !== "") {
        $model['validation'] = $validation;
        return 'register_view';
    }

    escape_user_data($user);
    $user['pwd'] = password_hash($user['pwd'], PASSWORD_BCRYPT);
    if (!save_user($user)) {
        $model['validation'] = "Error appeared while saving user, try again.";
        return 'register_view';
    }

    $model['validation'] = "Successfully registered.";
    return 'redirect:/login';
}

function login(&$model): string {
    if (isset($_SESSION['user_id']))
        return 'gallery_view';

    session_regenerate_id();
    return 'login_view';
}