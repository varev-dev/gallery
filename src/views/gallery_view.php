<!DOCTYPE html>
<html>
    <head>
        <title>Gallery</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="static/css/styles.css"/>
    </head>
    <body>
        <header>
            <div>
                <h1 class="title_bar">Gallery</h1>
                <?php if (isset($alert)): ?>
                    <p><?=$alert?></p>
                <?php endif; ?>
                <div>
                    <a href="/upload"><button>Upload Image</button></a>
                    <?php if ($logged_in): ?>
                        <a href="/logout"><button>Logout</button></a>
                    <?php else: ?>
                        <a href="/login"><button>Login</button></a>
                    <?php endif; ?>
                </div>
            </div>
            <hr>
        </header>
        <?php if(count($images)): ?>
            <form method="post" action="/save_images" class="gallery">
                <?php foreach ($images as $image): ?>
                    <div class="image_element">
                        <?php include "partial/image.php"?>
                        <label class="img_check">
                            <span>Save: </span>
                            <input type="checkbox" name="save[]" value="<?=$image['_id']?>"
                                <?php if (in_array($image, $saved)): ?>
                                    checked
                                <?php endif; ?>
                            />
                        </label>
                    </div>
                <?php endforeach ?>
                <div class="new_row">
                    <input type="submit" value="Save Checked"/>
                    <a href="/saved" class="cancel">View saved</a>
                </div>
            </form>
            <?php include "partial/manage_page.php"; ?>
        <?php else: ?>
            <p class="centered">No images <?= !isset($_GET['page']) || $_GET['page'] == 1 ? " in gallery" : " on page " . $_GET['page']?></p>
        <?php endif ?>
        <?php include "includes/footer.inc.php"; ?>
    </body>
</html>