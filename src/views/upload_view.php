<!DOCTYPE html>
<html>
<head>
    <title>Gallery</title>
    <link rel="stylesheet" href="static/css/styles.css"/>
</head>
<body>
<header>
    <h1 class="title_bar">Image Upload</h1>
    <hr>
</header>

<form method="post" enctype="multipart/form-data" class="upload_form">
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
        <input type="text" name="author" required/>
    </label>
    <label>
        <span>Watermark text:</span><br/>
        <input type="text" name="watermarkText" required/>
    </label>
    <!--<input type="hidden" name="id" value="<?php /*= $image['_id'] */?>">-->
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
