<?php
if(!isset($_SESSION['login']) && $_SESSION['login'] !== true){
    header('Location: /admin/login');
    die();
}
?>
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
<h2>Login as <?= $_SESSION['username'] ?></h2>
<nav>
    <a href="/admin/logout">Logout</a> ::
    <a href="/admin/categories">categories</a> ::
    <a href="/admin/uploads">Uploads</a> ::
    <a href="/admin/createBlog">Create Blog</a> ::
    <a href="/admin/profile">Profile</a> ::
    <a href="/admin/security">Security</a>
</nav>
<?php if($_SESSION["2fa_enable"] === false){ ?>
<p>To enable 2FA please scan the QRCode using your authenticator app:</p>
<img src="http://localhost/Tfa/QRCode" alt="2FA QR code">
<form action="/admin/tfa" method="post">
    <input type="text" maxlength="6" name="2fa">
    <input type="submit" value="Submit">
</form>
<br>
<?php } else {?>
    <p>2FA is enabled.</p>
<?php } ?>
<hr>
<p>Change Password</p>
<form action="/admin/changePassword" method="post">
    <input type="password" name="old_pwd" id="" maxlength="255" placeholder="Old password">
    <input type="password" name="new_pwd" id="" maxlength="255" placeholder="New password">
    <input type="submit" value="Update">
</form>

<?php if (isset($_SESSION['errors'])) { ?>
    <h2>Errors</h2>
    <?php foreach ($_SESSION['errors'] as $key => $value ) { ?>
        <p><?= $key ?> : <?= $value ?></p>
    <?php }
    unset($_SESSION['errors']);
}?>
</body>
</html>