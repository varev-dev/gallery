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
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/logout"><button>Logout</button></a>
                    <?php else: ?>
                        <a href="/login"><button>Login</button></a>
                    <?php endif; ?>
                </div>
            </div>
            <hr>
        </header>
        <?php if(count($images)): ?>
            <section class="gallery">
                <?php foreach ($images as $image): ?>
                    <div class="image_element">
                        <a href="<?=$image["name"]."-wat.".$image["extension"]?>">
                            <img class="gallery_image" src="<?=$image["name"]."-min.".$image["extension"]?>" alt="image"/>
                        </a>
                        <h4><?=$image['title']?></h4>
                        <p>Author: <span><?=$image['author']?></span></p>
                    </div>
                <?php endforeach ?>
            </section>
        <?php else: ?>
            <p>No images <?= !isset($_GET['page']) || $_GET['page'] == 1 ? " in gallery" : " on page " . $_GET['page']?></p>
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