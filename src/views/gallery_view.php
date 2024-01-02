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
                <?php if (isset($_COOKIE['alert'])): ?>
                    <p><?=$_COOKIE['alert']?></p>
                <?php endif; ?>
                <div>
                    <a href="/upload"><button>Upload Image</button></a>
                    <?php if (isset($logged_in)): ?>
                        <a href="/logout"><button>Logout</button></a>
                    <?php else: ?>
                        <a href="/login"><button>Login</button></a>
                    <?php endif; ?>
                </div>
            </div>
            <hr>
        </header>
        <?php if(count($images)): ?>
            <<?=isset($logged_in) ? 'form method="post" action="/save_images"' : 'session'?> class="gallery">
                <?php foreach ($images as $image): ?>
                    <div class="image_element">
                        <?php include "partial/image.php"?>
                        <?php if($logged_in): ?>
                            <label class="img_check">
                                <span>Save: </span>
                                <input type="checkbox" name="save[]" value="<?=$image['_id']?>"/>
                            </label>
                        <?php endif; ?>
                    </div>
                <?php endforeach ?>
                <?php if(isset($logged_in)): ?>
                    <div class="new_row">
                        <input type="submit" value="Save Checked"/>
                        <a href="/saved" class="cancel">View saved</a>
                    </div>
                <?php endif; ?>
            </<?=isset($logged_in) ? 'form' : 'session'?>>
        <?php else: ?>
            <p class="centered">No images <?= !isset($_GET['page']) || $_GET['page'] == 1 ? " in gallery" : " on page " . $_GET['page']?></p>
        <?php endif ?>
        <div class="centered">
            <?php if ($page != 1): ?>
                <form name="previous_page" method="get">
                    <input type="number" name="page" value="<?=$page - 1?>" hidden="hidden"/>
                    <input type="submit" class="page_btn" value="Previous">
                </form>
            <?php endif ?>
            <?php if ($page < $maxPage): ?>
                <form name="next_page" method="get">
                    <input type="number" name="page" value="<?=$page + 1?>" hidden="hidden"/>
                    <input type="submit" class="page_btn" value="Next">
                </form>
            <?php endif ?>
        </div>
        <?php include "includes/footer.inc.php" ?>
    </body>
</html>