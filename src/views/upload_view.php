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

    <!--<input type="hidden" name="id" value="<?php /*= $image['_id'] */?>">-->
    <?php if(isset($validation)): ?>
        <div class="validation_error">
            <h4 class="validation_content"><?=$validation?></h4>
        </div>
    <?php endif ?>

    <div class="centered">
        <a href="/" class="cancel">Anuluj</a>
        <input type="submit" value="Zapisz"/>
    </div>
</form>

<?php include "includes/footer.inc.php" ?>
</body>
</html>