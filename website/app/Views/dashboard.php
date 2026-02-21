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
        <a href="/admin/blogs">Blogs</a> ::
        <a href="/admin/createBlog">Create Blog</a> ::
        <a href="/admin/profile">Profile</a> ::
        <a href="/admin/security">Security</a>
    </nav>
</body>
</html>