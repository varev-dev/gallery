<?php
require_once 'controllers_utils.php';
require_once 'business.php';

define("IMAGE_MAX_SIZE", pow(2, 20));
define("MB_SIZE", pow(2, 20));
const MIN_LOGIN_LENGTH = 3;
const MIN_PASSWORD_LENGTH = 8;

function gallery(&$model): string {
    $page = $_GET['page'] ?? 1;
    $page = min(get_last_page_number(), $page);
    $page = max($page, 1);

    if (isset($_COOKIE['alert']))
        $model['alert'] = $_COOKIE['alert'];

    $model['saved'] = get_saved_images();
    $model['logged_in'] = isset($_SESSION['user_id']);
    $model['page'] = $page;
    $model['max_page'] = get_last_page_number();
    $model['images'] = get_images_from_page($page);

    return 'gallery_view';
}

function upload_image(&$model): string {
    $model['logged_in'] = isset($_SESSION['user_id']);
    if (isset($_SESSION['user_id'])) {
        $user = get_user_by_id($_SESSION['user_id']);
        if ($user) {
            $model['user_id'] = $user['_id'];
            $model['login'] = $user['login'];
        }
    }

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

        validate_image_extension_and_size($validation, $_FILES['uploadImage']['type']);
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

        validate_thumbnail_and_watermark($validation, $_FILES['uploadImage']['type'], $target);
        if ($validation !== '') {
            $model['validation'] = $validation;
            return 'upload_view';
        }

        $visibility = $_POST['visibility'] ?? "public";
        if (!in_array($visibility, ["public", "private"]))
            $visibility = "public";

        $image = [
            "name" => $name,
            "extension" => $extension,
            "title" => $_POST['title'],
            "author" => $_POST['author'],
            "author_id" => $_SESSION['user_id'] ?? "",
            "visibility" => $visibility
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
        return 'redirect:/';

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

    if (get_user_by_login($user['login']) != null) {
        $model['validation'] = "Login already in use, choose different one.";
        return 'register_view';
    }

    $validation = validate_register_form($user, $_POST['pwd_rp']);
    if ($validation !== "") {
        $model['validation'] = $validation;
        return 'register_view';
    }

    escape_register_data($user);
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
        return 'redirect:/';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        return "login_view";

    $login = [
        'login' => $_POST['login'],
        'pwd' => $_POST['pwd']
    ];

    sanitize_login_form($login);
    escape_login_data($login);
    $user = get_user_by_login($_POST['login']);

    if ($user == null) {
        $model['validation'] = "Wrong login.";
        return 'login_view';
    }

    if(!password_verify($login['pwd'], $user['pwd'])) {
        $model['validation'] = "Wrong password.";
        return 'login_view';
    }

    $_SESSION['user_id'] = $user['_id'];
    session_regenerate_id(true);
    setcookie("alert", "Successfully logged in.", time() + 1);

    return 'redirect:/';
}

function logout(): string {
    if (isset($_SESSION['user_id'])) {
        foreach ($_COOKIE as $key => $value)
            setcookie($key, null, 1);
        session_unset();
        session_destroy();
        session_start();
        setcookie("alert", "Successfully logged out.", time() + 1);
    }

    return 'redirect:/';
}

function save_images(): string {
    if ($_SERVER['REQUEST_METHOD'] != "POST")
        return 'redirect:/';

    if (isset($_POST['save'])) {
        add_saved_images($_POST['save']);
        $count = count($_POST['save']);
    } else {
        $count = 0;
    }

    setcookie("alert", "Saved $count images.", time() + 1);
    return 'redirect:/saved';
}

function remove_images(): string {
    if ($_SERVER['REQUEST_METHOD'] != "POST")
        return 'redirect:/';

    if (isset($_POST['unsave'])) {
        remove_saved_images($_POST['unsave']);
        $count = count($_POST['unsave']);
    } else {
        $count = 0;
    }

    setcookie("alert", "Removed $count images.", time() + 1);
    return 'redirect:/saved';
}

function saved(&$model): string {
    if (isset($_COOKIE['alert']))
        $model['alert'] = $_COOKIE['alert'];

    $images = get_saved_images();

    $max_pages = ceil(count($images)/IMAGES_PER_PAGE);
    $page = $_GET['page'] ?? 1;
    $page = min($max_pages, $page);
    $page = max($page, 1);

    $images = array_slice($images, ($page - 1) * IMAGES_PER_PAGE, IMAGES_PER_PAGE);

    $model['page'] = $page;
    $model['max_page'] = $max_pages;
    $model['images'] = $images;
    $model['logged_in'] = isset($_SESSION['user_id']);

    return 'saved_view';
}