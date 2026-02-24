<?php
// 1. قراءة الإعدادات
$config = parse_ini_file('../app/config.ini');

// 2. فك تشفير الداتا (بناءً على الـ Array اللي بعتها)
$post = $data[0][0] ?? null;

// 3. دالة الحماية
if (!function_exists('e')) {
    function e($str) {
        return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
    }
}

// 4. منطق السيو (SEO Logic)
// لو إحنا في صفحة مقال
if ($post) {
    $pageTitle = e($post['title']);
    $pageDesc  = e($post['meta_description']);
    $pageKeys  = e($post['keywords']);
    $pageUrl   = rtrim($config['base_url'], '/') . '/content/post/' . e($post['slug']);
    // تحديد مسار الصورة (لو مقال ياخد الـ thumbnail بتاعته)
    $thumbName = !empty($post['thumbnail']) ? $post['thumbnail'] : 'phpMyBlog_squar_logo.png';
    $pageImage = rtrim($config['base_url'], '/') . '/media/out/' . $thumbName;
} else {
    // لو إحنا في الصفحة الرئيسية (Default)
    $pageTitle = e($config['blogname']) . " | " . e($config['slogan']);
    $pageDesc  = e($config['description']);
    $pageKeys  = e($config['keywords']);
    $pageUrl   = rtrim($config['base_url'], '/');
    $pageImage = rtrim($config['base_url'], '/') . '/phpMyBlog_squar_logo.png';
}

use Michelf\MarkdownExtra;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $pageTitle ?></title>
    <meta name="description" content="<?= $pageDesc ?>">
    <meta name="keywords" content="<?= $pageKeys ?>">
    <meta name="author" content="<?= e($config['author']) ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?= $post ? 'article' : 'website' ?>">
    <meta property="og:url" content="<?= $pageUrl ?>">
    <meta property="og:title" content="<?= $pageTitle ?>">
    <meta property="og:description" content="<?= $pageDesc ?>">
    <meta property="og:image" content="<?= $pageImage ?>">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?= $pageUrl ?>">
    <meta name="twitter:title" content="<?= $pageTitle ?>">
    <meta name="twitter:description" content="<?= $pageDesc ?>">
    <meta name="twitter:image" content="<?= $pageImage ?>">

    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "<?= $post ? 'BlogPosting' : 'WebSite' ?>",
      "name": "<?= e($config['blogname']) ?>",
      "headline": "<?= $pageTitle ?>",
      "url": "<?= $pageUrl ?>",
      "description": "<?= $pageDesc ?>",
      "image": "<?= $pageImage ?>",
      "author": {
        "@type": "Person",
        "name": "<?= e($config['author']) ?>"
      }
        <?php if(!$post): ?>,
      "potentialAction": {
        "@type": "SearchAction",
        "target": "<?= rtrim($config['base_url'], '/') ?>/?search={search_term_string}",
        "query-input": "required name=search_term_string"
      }
      <?php endif; ?>
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
        <form action="/" method="GET">
            <div class="form-group">
                <input type="text" name="search" id="search" required placeholder="Search" maxlength="255">
                <input type="submit" value="Search!">
            </div>
        </form>
    </header>

    <main>
        <aside>
            <p class="headTitle">Navigation</p>
            <div class="asidethings">
                <a href="/">Home</a>
                <a href="/content/">Show All Content</a>
            </div>
            <p class="headTitle">Categories</p>
            <div id="categories">
                <!-- AJAX Loads Here -->
            </div>
        </aside>

        <article itemscope itemtype="https://schema.org/BlogPosting">
            <?php if ($post): ?>
                <!-- عرض المقال الديناميكي -->
                <header class="post-header">
                    <h1 itemprop="headline"><?= e($post['title']) ?></h1>
                    <?php if (!empty($post['thumbnail'])): ?>
                        <img itemprop="image" src="/media/out/<?= e($post['thumbnail']) ?>" class="floatingImg" alt="<?= e($post['title']) ?>">
                    <?php endif; ?>
                    <p>By: <span itemprop="author" itemscope itemtype="https://schema.org/Person"><span itemprop="name"><?= e($config['author']) ?></span></span></p>
                </header>
                <hr>
                <div itemprop="articleBody" class="articalbody">
                    <?= MarkdownExtra::defaultTransform($post['content']) ?>
                </div>
            <?php else: ?>
                <!-- محتوى الصفحة الرئيسية الافتراضي -->
                <h1 itemprop="headline">Welcome To My Blog Engine, phpMyEngine</h1>
                <img itemprop="image" src="/phpMyBlog_squar_logo_fits.png" alt="phpMyBlog Logo">
                <p>By: <span itemprop="author" itemscope itemtype="https://schema.org/Person"><span itemprop="name"><?= e($config['author']) ?></span></span></p>
                <hr>
                <div itemprop="articleBody" class="articalbody">
                    <h2>Introduction</h2>
                    <p><img src="/duke.gif" alt="duke" class="floatingImg"> I am Mohammed Saleh...</p>
                    <p>The idea starts when I made my own <a href="https://mohamedsaleh077.github.io" target="_blank">Portfolio</a>...</p>
                    <p>This Project is COMPLETELY OPEN SOURCE...</p>
                    <!-- كمل باقي المحتوى الثابت هنا -->
                </div>
            <?php endif; ?>
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
        });
    });
</script>
</body>
</html>