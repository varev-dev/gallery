<!DOCTYPE html>
<html>
<head>
    <title>Gallery</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="static/css/styles.css"/>
</head>
<body>
<header>
    <h1 class="title_bar">Image Upload</h1>
    <hr>
</header>

<form method="post" enctype="multipart/form-data" class="form">
    <label>
        <span>Select image:</span><br/>
        <input type="file" name="uploadImage" required/>
    </label>
    <label>
        <span>Title: </span><br/>
        <input type="text" name="title" required/>
    </label>
    <label>
        <span>Author: </span><br/>
        <input type="text" name="author" value="<?php if ($logged_in) echo $login;?>" required/>
    </label>
    <label>
        <span>Watermark text:</span><br/>
        <input type="text" name="watermarkText" required/>
    </label>
    <?php if ($logged_in): ?>
        <div>
            <label>
                <input type="radio" name="visibility" value="private" checked required> Private
            </label>
            <label>
                <input type="radio" name="visibility" value="public" required> Public
            </label>
        </div>
    <?php else: ?>
        <input type="radio" name="visibility" value="public" checked hidden>
    <?php endif; ?>
    <?php if(isset($validation)): ?>
        <div class="validation_error">
            <h4 class="validation_content"><?=$validation?></h4>
        </div>
    <?php endif ?>

    <div class="centered">
        <a href="/" class="cancel">Return</a>
        <input type="submit" value="Upload"/>
    </div>
</form>

<?php include "includes/footer.inc.php" ?>
</body>
</html>
