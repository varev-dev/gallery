<!DOCTYPE html>
<html>
<head>
    <title>Gallery</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="static/css/styles.css"/>
</head>
<body>
<header>
    <h1 class="title_bar">Register</h1>
    <hr>
</header>

<form method="post" enctype="multipart/form-data" class="form">
    <label>
        <span>Login: (only letters and digits allowed, min. <?=$model['login_length']?> characters)</span><br/>
        <input type="text" name="login" required/>
    </label>
    <label>
        <span>Email Address:</span><br/>
        <input type="email" name="email" required/>
    </label>
    <label>
        <span>Password: (min. <?=$model['pwd_length']?> characters)</span><br/>
        <input type="password" name="pwd" required/>
    </label>
    <label>
        <span>Repeat password:</span><br/>
        <input type="password" name="pwd_rp" required/>
    </label>

    <?php if(isset($validation)): ?>
        <div class="validation_error">
            <h4 class="validation_content"><?=$validation?></h4>
        </div>
    <?php endif ?>

    <div class="centered">
        <a href="/" class="cancel">Return</a>
        <a href="/login" class="cancel">Login</a>
        <input type="submit" value="Register"/>
    </div>
</form>

<?php include "includes/footer.inc.php" ?>
</body>
</html>
