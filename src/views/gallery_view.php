<!DOCTYPE html>
<html>
    <head>
        <title>Gallery</title>
        <link rel="stylesheet" href="static/css/styles.css"/>
    </head>
    <body>
        <header>
            <div>
                <h1 class="title_bar">Gallery</h1>
                <a href="/upload"><button>Upload Image</button></a>
            </div>
            <hr>
        </header>
        <?php if(count($images)): ?>
            <section class="gallery">
                <?php foreach ($images as $image): ?>
                    <div>
                        <a href="<?=$image["name"]."-wat.".$image["extension"]?>">
                            <img class="gallery_image" src="<?=$image["name"]."-min.".$image["extension"]?>" alt="image"/>
                        </a>
                    </div>
                <?php endforeach ?>
            </section>
        <?php else: ?>
            <p>No images<?= isset($_GET['page']) ? " on page " . $_GET['page'] : ' in gallery'?></p>
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
