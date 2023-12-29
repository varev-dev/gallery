<?php
const IMAGE_DIR = "images", IMAGE_BUILD_DIR = "images/build", IMAGES_PER_PAGE = 12;
const FONT_SIZE = 35, FONT_FAMILY = "Roboto-Regular.ttf";
const THUMBNAIL_WIDTH = 200, THUMBNAIL_HEIGHT = 125;
const WATERMARK_MODE = "wat", THUMBNAIL_MODE = "min";

require_once 'business_utils.php';

function get_last_page_number(): int {
    $images = get_images();
    return ceil(count($images) / IMAGES_PER_PAGE);
}

function get_images_from_page(int $page): array{
    $images = get_images();

    $offset = ($page - 1) * IMAGES_PER_PAGE;
    $images = array_slice($images, $offset, IMAGES_PER_PAGE);

    return $images;
}

function get_images(): array {
    $images = [];
    $files = glob(IMAGE_DIR.'/image_*-wat.*');

    $data = [
        "name" => null,
        "extension" => null
    ];

    if (!$files)
        return [];

    foreach ($files as $file) {
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        if ($extension != "jpg" && $extension != "png")
            continue;

        $data["name"] = substr($file, 0, strrpos($file, "-wat"));
        $data["extension"] = $extension;

        if (!file_exists($data["name"].".".$data["extension"]) ||
            !file_exists($data["name"]."-min.".$data["extension"]))
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