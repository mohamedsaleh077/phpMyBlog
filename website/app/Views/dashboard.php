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
<p>Welcome to your phpMyBlog admin panel!, What would you share with the world?</p>
</body>
</html>