<?php
// قراءة الملف - افترضنا أن المسار صح
$config = parse_ini_file('../app/config.ini');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="<?php echo $config['charset'] ?? 'UTF-8'; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $config['blogname'] . " | " . $config['slogan']; ?></title>
    <meta name="description" content="<?php echo $config['description']; ?>">
    <meta name="keywords" content="<?php echo $config['keywords']; ?>">
    <meta name="author" content="<?php echo $config['author']; ?>">

    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $config['base_url']; ?>">
    <meta property="og:title" content="<?php echo $config['blogname']; ?>">
    <meta property="og:description" content="<?php echo $config['description']; ?>">

    <?php $full_image_url = rtrim($config['base_url'], '/') . '/phpMyBlog_squar_logo.png'; ?>
    <meta property="og:image" content="<?php echo $full_image_url; ?>">
    <meta name="twitter:image" content="<?php echo $full_image_url; ?>">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $config['blogname']; ?>">
    <meta name="twitter:description" content="<?php echo $config['description']; ?>">

    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "WebSite",
          "name": "<?php echo $config['blogname']; ?>",
      "url": "<?php echo $config['base_url']; ?>",
      "author": {
        "@type": "Person",
        "name": "<?php echo $config['author']; ?>"
      },
      "description": "<?php echo $config['description']; ?>",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "<?php echo $config['base_url']; ?>/?search={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/style.css">
    <link rel="shortcut icon" href="/phpMyBlog_squar_logo.png" type="image/x-icon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
<div class="container">
    <header>
        <a href="/"><img src="/phpMyBlog_logo.png" alt="phpMyBlog Logo"></a>
        <form action="">
            <div class="form-group">
                <input type="text" name="search" id="search" required placeholder="Search" maxlength="255">
                <input type="submit" value="Search!">
            </div>
        </form>
    </header>
    <main>
        <aside>
            <p class="headTitle">
                Navigation
            </p>
            <div class="asidethings">
                <a href="/">Home</a>
                <a href="/content/">Show All Content</a>
            </div>
            <p class="headTitle">
                Categories
            </p>
            <div id="categories">
            </div>
        </aside>
            <article itemscope itemtype="https://schema.org/BlogPosting">
                <h1 id="titletitle">Category</h1>
                <div id="listcategories">
                </div>
            </article>
    </main>
    <footer>
        Made By <a href="https://github.com/mohamedsaleh077/phpmyblog" target="_blank">phpMyBlog Engine</a>, 2026
    </footer>

</div>

<script>
    $(document).ready(function () {
        $.get("/categories/listAll", function (result) {
            $.each(result, function (i, item) {
                $('#categories').append(`<a href='/categories/explore/${item.id}'>${item.name}</a>`);
            });
        })
        $.get("/content/getCategory/<?= $data[0] ?>", function (result) {
            $.each(result, function (i, item) {
                $('#titletitle').text(`${item.name}`);
                $(`#listcategories`).append(`<a href="/content/post/${item.slug}">${item.title}</a><br>`);
            });
        })
    })
</script>


</body>
</html>