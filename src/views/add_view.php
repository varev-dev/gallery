<!DOCTYPE html>
<html>
<head>
    <title>Dodawanie Obrazu</title>
    <link rel="stylesheet" href="static/css/styles.css"/>
</head>
<body>

<form method="post" enctype="multipart/form-data">
    <label>
        <span>Obraz:</span>
        <input type="image" name="image" alt="img" required/>
    </label>

    <!--<input type="hidden" name="id" value="<?php /*= $image['_id'] */?>">-->

    <div>
        <a href="products" class="cancel">Anuluj</a>
        <input type="submit" value="Zapisz"/>
    </div>
</form>

</body>
</html>
