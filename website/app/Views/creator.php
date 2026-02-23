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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .containr{
            padding: 25px;
        }
        .inputs{
            padding: 15px;
            display: flex;
        }
        .inputs *{
            margin: 5px;
            width: 40%;
            padding: 10px;
            font-size: large;
            flex-grow: 1;
        }
        .inputs button{
            width: 100px;
        }
        .form-editor{
            width: 100%;
            display: flex;
            height: 70dvh;
        }
        .form-editor *{
            border: 1px solid black;
            width: 50%;
            background: white;
            padding: 10px;
        }
        .form-editor div{
            flex-grow: 1;
            overflow-x: scroll;
        }
        .form-editor div *{
            border: none;
            width: 100%;
            background: none;
            padding: 3px;
            margin: 3px;
        }
        textarea{
            font-size: x-large;
            resize: horizontal;
            font-family: monospace;
            overflow-x: scroll;
        }
    </style>
</head>
</head>
<body bgcolor="#ccc">
<div class="containr">
    <h1>Blog Creator</h1>
    <h2>Login as <?= $_SESSION['username'] ?></h2>
    <nav>
        <a href="/Admin/logout">Logout</a> ::
        <a href="/Admin/categories">categories</a> ::
        <a href="/Admin/uploads">Uploads</a> ::
        <a href="/Admin/createBlog">Create Blog</a> ::
        <a href="/Admin/profile">Profile</a> ::
        <a href="/Admin/security">Security</a>
    </nav>
</div>

<form action="">
    <div class="inputs">
        <input type="text" name="title" id="title" maxlength="255" required placeholder="Post Title">
        <input type="text" name="slug" id="slug" maxlength="255" required placeholder="Slug">
        <button type="submit">Post!</button>
    </div>
    <div class="form-editor">
        <textarea name="post" id="post" cols="30" rows="10">
## Introduction
Today we are talking.
- Hi.</textarea>
        <div id="preview">
        </div>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/marked/lib/marked.umd.js"></script>
<script>
    let editor = document.getElementById("post");
    let preview = document.getElementById("preview");
    preview.innerHTML = marked.parse(editor.value);
    editor.addEventListener("keydown", function (event) {
        preview.innerHTML = marked.parse(editor.value);
    });
</script>

</body>
</html>