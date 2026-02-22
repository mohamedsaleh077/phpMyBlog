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
    <a href="/Admin/logout">Logout</a> ::
    <a href="/Admin/categories">categories</a> ::
    <a href="/Admin/uploads">Uploads</a> ::
    <a href="/Admin/createBlog">Create Blog</a> ::
    <a href="/Admin/profile">Profile</a> ::
    <a href="/Admin/security">Security</a>
</nav>
<p>UPLOADER!</p>
<form action="/admin/uploadFile" method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= $_SESSION['CSRF'] ?>">
    <input type="file" name="media" accept="image/jpeg image/png image/gif image/webp video/mp4" required>
    <input type="submit" value="Upload">
</form>
<br>
<?php if (isset($_SESSION['errors'])) { ?>
    <h2>Errors</h2>
    <?php foreach ($_SESSION['errors'] as $key => $value ) { ?>
        <p><?= $key ?> : <?= $value ?></p>
    <?php }
    unset($_SESSION['errors']);
}?>
<div style="column-count: 4;">
    <?php
        $arr = scandir($_SERVER['DOCUMENT_ROOT'] . "/app/uploads", SCANDIR_SORT_DESCENDING);
//        print_r($arr);
        foreach($arr as $file){
            if($file != "." && $file != ".."){
                ?>
                <img style="width: 25dvw" src="/media/out/<?= $file ?>" alt="ff">
            <?php
            }
        }
?>
</div>
</body>
</html>