<?php
const IMAGE_DIR = "images";
const IMAGES_PER_PAGE = 12;

function get_last_page_number(): int {
    $images = get_images();
    return count($images) / IMAGES_PER_PAGE;
}

function get_images_from_page(int $page): array{
    $images = get_images();

    $offset = ($page - 1) * IMAGES_PER_PAGE;
    $images = array_slice($images, $offset, IMAGES_PER_PAGE);

    return $images;
}

function get_images(): array {
    $images = [];
    $files = scandir(IMAGE_DIR);

    if (!$files)
        return [];

    foreach ($files as $file) {
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        if ($extension != "jpg" && $extension != "png")
            continue;

        $images[] = IMAGE_DIR . "/" . $file;
    }

    return $images;
}