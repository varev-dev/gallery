<?php
require_once 'business.php';

function gallery(&$model) {
    $page = $_GET['page'] ?? 1;

    if ($page < 1)
        return 'redirect:/';

    $model["page"] = $page;
    $model["maxPage"] = get_last_page_number();
    $model["images"] = get_images_from_page($page);

    return 'gallery_view';
}
