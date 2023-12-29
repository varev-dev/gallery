<?php

use MongoDB\BSON\ObjectID;
use MongoDB\Database;

const IMAGE_DIR = "images", IMAGE_BUILD_DIR = "images/build", IMAGES_PER_PAGE = 12;
const FONT_FAMILY = "RobotoMono.ttf";
const THUMBNAIL_WIDTH = 200, THUMBNAIL_HEIGHT = 125;
const WATERMARK_MODE = "wat", THUMBNAIL_MODE = "min";

require_once 'business_utils.php';

function get_db(): Database {
    $mongo = new MongoDB\Client(
        "mongodb://localhost:27017/wai",
        [
            'username' => 'wai_web',
            'password' => 'w@i_w3b',
        ]);

    return $mongo->wai;
}

function get_last_page_number(): int {
    return ceil(number_of_images_in_db() / IMAGES_PER_PAGE);
}

function get_images_from_page(int $page): array{
    $images = get_images_from_db($page);

    foreach ($images as $image) {
        $image["name"] = IMAGE_DIR . "/" . $image["name"];
    }

    return $images;
}

function get_images(): array {
    $images = [];
    $files = glob(IMAGE_DIR.'/image_*-wat.*');

    $data = [
        "name" => null,
        "extension" => null,
        "title" => null,
        "author" => null
    ];

    if (!$files)
        return [];

    foreach ($files as $file) {
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        if ($extension != "jpg" && $extension != "png")
            continue;

        $data["name"] = substr($file, 0, strrpos($file, "-wat"));
        $data["extension"] = $extension;

        if (!file_exists($data["name"].".".$extension) ||
            !file_exists($data["name"]."-min.".$extension))
            continue;

        $images[] = $data;
    }

    return $images;
}

function edit_image(string $path, string $mode, string $watermark = null): bool {
    $extension = pathinfo($path,PATHINFO_EXTENSION);

    if (!create_image_by_extension($image, $path, $extension))
        return false;

    if ($mode === WATERMARK_MODE) {
        if ($watermark == null || !watermark_transformation($image, $path, $watermark))
            return false;
    } else if ($mode === THUMBNAIL_MODE) {
        if (!scale_transformation($image))
            return false;
    } else return false;

    $finalPath = substr($path, 0, strrpos($path,".")) . "-" . $mode . ".". $extension;

    if (!create_image_file($image, $finalPath, $extension))
        return false;

    imagedestroy($image);
    return true;
}

function save_image($image): bool {
    $db = get_db();
    return $db->images->insertOne($image)->isAcknowledged();
}

function get_images_from_db(int $page): array {
    $db = get_db();

    $options = [
        'skip' => ($page-1) * IMAGES_PER_PAGE,
        'limit' => IMAGES_PER_PAGE
    ];

    return $db->images->find([], $options)->toArray();
}

function number_of_images_in_db(): int {
    $db = get_db();

    return $db->images->count();
}