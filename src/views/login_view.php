<!DOCTYPE html>
<html>
<head>
    <title>Gallery</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="static/css/styles.css"/>
</head>
<body>
<header>
    <h1 class="title_bar">Login</h1>
    <hr>
</header>
<form method="post" enctype="multipart/form-data" class="form">
    <label>
        <span>Login: </span><br/>
        <input type="text" name="login" required/>
    </label>
    <label>
        <span>Password: </span><br/>
        <input type="password" name="pwd" required/>
    </label>

    <?php if(isset($validation)): ?>
        <div class="validation_error">
            <h4 class="validation_content"><?=$validation?></h4>
        </div>
    <?php endif ?>

    <div class="centered">
        <a href="/" class="cancel">Return</a>
        <a href="/register" class="cancel">Register</a>
        <input type="submit" value="Login"/>
    </div>
</form>

<?php include "includes/footer.inc.php" ?>
</body>
</html>
