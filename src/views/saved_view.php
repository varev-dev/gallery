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
            <h1 class="title_bar">Saved Images</h1>
            <?php if (isset($_COOKIE['alert'])): ?>
                <p><?=$_COOKIE['alert']?></p>
            <?php endif; ?>
            <div>
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
        <form method="post" action="/remove_images" class="gallery">
            <?php foreach ($images as $image): ?>
            <div class="image_element">
                <?php include "partial/image.php"?>
                <?php if($logged_in): ?>
                    <label class="img_check">
                        <span>Unsave: </span>
                        <input type="checkbox" name="unsave[]" value="<?=$image['_id']?>"/>
                    </label>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php if(isset($logged_in)): ?>
            <div class="new_row">
                <input type="submit" value="Remove Checked from Saved"/>
            </div>
        <?php endif; ?>
        </form>
    <?php else: ?>
        <p class="centered">0 saved images</p>
    <?php endif; ?>
    <div class="new_row">
        <a class="cancel" href="/">Return</a>
    </div>
    <?php include "includes/footer.inc.php" ?>
</body>
</html>