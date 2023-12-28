<!DOCTYPE html>
<html>
    <head>
        <title>Gallery</title>
        <link rel="stylesheet" href="static/css/styles.css"/>
    </head>
    <body>
        <header>
            <h1 class="title_bar">Gallery</h1>
            <hr>
        </header>
        <?php if(count($images)): ?>
            <section class="gallery">
                <?php foreach ($images as $image): ?>
                    <div>
                        <a href="<?=$image?>">
                            <img class="gallery_image" src="<?=$image?>" alt="image"/>
                        </a>
                    </div>
                <?php endforeach ?>
            </section>
        <?php else: ?>
            <p>No images<?= isset($_GET['page']) ? " on page " . $_GET['page'] : 'in gallery'?></p>
        <?php endif ?>
        <div>
            <?php if ($page != 1): ?>
                <form name="previous_page" method="get">
                    <input type="number" name="page" value="<?=$page - 1?>" hidden="hidden"/>
                    <input type="submit" value="Previous">
                </form>
            <?php endif ?>
            <?php if ($page < $maxPage): ?>
                <form name="next_page" method="get">
                    <input type="number" name="page" value="<?=$page + 1?>" hidden="hidden"/>
                    <input type="submit" value="Next">
                </form>
            <?php endif ?>
        </div>
        <?php include "includes/footer.inc.php" ?>
    </body>
</html>
