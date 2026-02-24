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
    <link rel="stylesheet" href="./style.css">
    <link rel="shortcut icon" href="./phpMyBlog_squar_logo.png" type="image/x-icon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
<div class="container">
    <header>
        <a href="/"><img src="./phpMyBlog_logo.png" alt="phpMyBlog Logo"></a>
        <form action="">
            <div class="form-group">
                <input type="text" name="search" id="search" required placeholder="Search under construction" maxlength="255">
                <input type="submit" value="Search!">
            </div>
        </form>
    </header>
    <main>
        <aside>
            <p class="headTitle">
                Categories
            </p>
            <div id="categories">
                <a href="/content/">Show All Content</a>
            </div>
        </aside>
        <article itemscope itemtype="https://schema.org/BlogPosting">
            <h1 itemprop="headline">Welcome To My Blog Engine, phpMyEngine</h1>
            <img itemprop="image" src="./phpMyBlog_squar_logo_fits.png" alt="phpMyBlog Logo">
            <p>By: <span itemprop="author" itemscope itemtype="https://schema.org/Person">
                <span itemprop="name">Mohammed Saleh</span>
            </span></p>
            <hr>
            <div itemprop="articleBody" class="articalbody">
            <h2>Introduction</h2>
            <p>
                <img src="./duke.gif" alt="duke" class="floatingImg">
                I am Mohammed Saleh, a student learning about creating interactive websites. I decided recently to talk
                about what knowledge I have and share any new experience. I want to master writing more and be more efficient.
            </p>
            <p>
                <img src="www.gif" alt="www icon" class="floatingImg">
                The idea starts when I made my own <a href="https://mohamedsaleh077.github.io" target="_blank">Portfolio</a>. I want to
                have my own space, my own digital garden. I am sharing already my knowledge either in a github repo, as
                I did with <a href="https://github.com/mohamedsaleh077/myhomelap" target="_blank">MyHomeLap</a>. I decided to make a website
                to host all my talk and be indexed in search engines. ofc, I tried to make it with the old style of the
                internet. I navigated to <a href="https://www.webdesignmuseum.org/" target="_blank">Web Design Museum</a>
                and started to steal ideas.
            </p>
            <p>
                <img src="msbob2.gif" alt="bob icon" class="floatingImg">
                This Project is COMPLETELY OPEN SOURCE under GPL3 license. I tried to make it easy to deploy as I can.
                lemme explain how can you deploy it. if you want to contribute, some knowledge in HTML, CSS, JQuery, PHP
                and MySQL will help! I am not using any frameworks, just libraries, even the ORM and MVC is by me.
            </p>
            <h3>Instruction to host your own version of this Blog Engine</h3>
            <ul>
                <img src="smile.gif" alt="smile icon" class="floatingImg">
                <li>Download the <a href="https://github.com/mohamedsaleh077/phpmyblog" target="_blank">source code</a> from GitHub.</li>
                <li>Use any host supports PHP and MySQL, upload the files in /website/ and edit the file located in:</li>
                <code>
                    /website/app/config.ini
                </code>
                <li>
                    navigate to https://yourownphpmyblog.com/admin and add credentials, this will create an account
                automatically. do not forget to secure it.
                </li>
                <li>Make your own custom CSS and HTML, you will need this two files:</li>
                <code>
                    /website/app/Views/home.php
                    /website/public/style.css
                </code>
            </ul>
            <p>That's it! I hope you all enjoy my Blog!</p>
            <hr>
            <h3>Notes</h3>
            <ul>
                <img src="mm.gif" alt="mm icon" class="floatingImg">
                <li>I am not responsible for any problem happen to you, I do not provide support on this project, I am
                still a student and the code may not be the best, I am happy to see any help and contributions in the github.
                but if you got hacked or can't use it. do not ask me to waste my time! this is a personal project and not
                for everyone. and I am not getting any profits from it.</li>
                <li>for SEO, I am from the group that hates AI, but since SEO is for google, ask their AI, I am sure he
                knows what is going and he who rank our sites :)</li>
            </ul>
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
    })
</script>


</body>
</html>