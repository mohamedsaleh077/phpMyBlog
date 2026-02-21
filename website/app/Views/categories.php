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
<p>Your Blog Categories</p>
<form action="/Categories/add" method="post">
    <input type="hidden" name="csrf" value="<?= $_SESSION['CSRF'] ?>">
    <input type="text" name="name" placeholder="Name" maxlength="255"><br>
    <textarea name="info" id="" cols="27" rows="10" maxlength="1000" placeholder="Category info"></textarea><br>
    <input type="submit" value="Submit">
</form>
<?php if (isset($_SESSION['errors'])) { ?>
    <h2>Errors</h2>
    <?php foreach ($_SESSION['errors'] as $key => $value ) { ?>
        <p><?= $key ?> : <?= $value ?></p>
    <?php }
    unset($_SESSION['errors']);
}?>
<hr>
<?php
    use Controllers\Categories;
    $categories = new Categories();
    $list = $categories->list();
//    print_r($list);
    foreach ($list[0] as $value) {
        echo "<p>" . $value['id'] . " : " . $value['name'] . " | ";
        echo $value['info'];
        echo " : <a href='/Categories/del/" . $value['id'] . "'>del</a> ";
        echo "</p>";
    }
?>

</body>
</html>