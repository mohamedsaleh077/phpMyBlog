<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>phpMyBlog</title>
</head>
<body bgcolor="#ccc">
    <h1>adminMyBlog</h1>
    <form action="/admin/auth/" method="post">
        <input type="hidden" name="csrf" value="<?= $_SESSION['CSRF'] ?>">
        <input type="text" name="username" id="username" maxlength="255" placeholder="Username">
        <input type="password" name="password" id="password" maxlength="255" placeholder="Password">
        <input type="text" name="2fa" id="2fa" maxlength="6" placeholder="2FA code if exists">
        <input type="submit" value="Login">
    </form>
    <br>
    <?php if (isset($_SESSION['errors'])) { ?>
        <h2>Errors</h2>
        <?php foreach ($_SESSION['errors'] as $key => $value ) { ?>
        <p><?= $key ?> : <?= $value ?></p>
    <?php }
        unset($_SESSION['errors']);
    }?>
</body>
</html>