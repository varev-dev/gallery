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

function sanitize_form(&$data) {
    $data['title'] = filter_var(trim($data['title']), FILTER_SANITIZE_STRING);
    $data['author'] = filter_var(trim($data['author']), FILTER_SANITIZE_STRING);
    $data['watermarkText'] = filter_var(trim($data['watermarkText']), FILTER_SANITIZE_STRING);
}
function validate_upload_form($data, &$validation) {
    if(empty($data['title']))
        $validation .= "Incorrect title input.<br>";
    if (empty($data['author']))
        $validation .= "Incorrect author input.<br>";
    if (empty($data['watermarkText']))
        $validation .= "Incorrect watermark input.<br>";
}

function check_if_no_special_chars(string $text): bool {
    $pattern = "/^[a-zA-Z0-9@#$%&*()!?:._\-+=\"' ]{3,127}$/";

    if (!preg_match($pattern, $text))
        return false;

    return true;
}

function check_is_register_form_set(array $data): string {
    $output = "";

    if (!isset($data['login']))
        $output .= 'Login was not set.<br>';
    if (!isset($data['email']))
        $output .= 'email was not set.<br>';
    if (!isset($data['pwd']))
        $output .= 'Password was not set.<br>';
    if (!isset($data['pwd_rp']))
        $output .= 'Repeat password was not set.<br>';

    return $output;
}

function sanitize_register_data(array &$data) {
    $data['login'] = filter_var($data['login'], FILTER_SANITIZE_STRING);
    $data['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
    $data['pwd'] = filter_var($data['pwd'], FILTER_SANITIZE_STRING);
}

function validate_register_form(array $data, string $pwd_rp): string {
    $output = "";

    if (!match_to_pattern($data['login'], '[\w]', MIN_LOGIN_LENGTH))
        $output .= "Login is not valid.<br>";
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
        $output .= "Given email is not valid.<br>";
    if (!match_to_pattern($data['pwd'], '[\s\S]', MIN_PASSWORD_LENGTH))
        $output .= "Password is not valid.<br>";
    if ($data['pwd'] != $pwd_rp)
        $output .= "Password and repeated password are not the same.<br>";

    return $output;
}

function match_to_pattern(string $text, string $pattern, int $min_length, int $max_length = 128) {
    $pattern = "/^$pattern{{$min_length},$max_length}$/";
    return preg_match($pattern, $text);
}

function escape_user_data(array &$user) {
    $user['login'] = htmlentities($user['login'], ENT_QUOTES, 'UTF-8');
    $user['email'] = htmlentities($user['email'], ENT_QUOTES, 'UTF-8');
    $user['pwd'] = htmlentities($user['pwd'], ENT_QUOTES, 'UTF-8');
}