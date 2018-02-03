<?php
require_once 'class/Login.php';
define('app', true);
$is_logged = Login::isLoggedIn();
//var_dump($is_logged);
$app_pages = ['home', 'profile', 'friends','settings'];
if (isset($_GET['app-page']) && in_array($_GET['app-page'], $app_pages)) {
    $app_page = $_GET['app-page'];
} else {
    $app_page = $app_pages[0];
}
$app_title = '';
$app_title_link = '';
switch ($app_page) {
    case $app_pages[0]:
        $app_title = 'דף הבית';
        $app_title_link = 'index.php?app-page=home';

        break;
    case $app_pages[1]:
        $app_title = 'פרופיל';
        $app_title_link = 'index.php?app-page=home';
        break;
    case $app_pages[2]:
        $app_title = 'חברים';
        $app_title_link = 'index.php?app-page=home';
        break;
    case $app_pages[3]:
        $app_title = 'הגדרות';
        $app_title_link = 'index.php?app-page=settings';
        break;
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>דף הבית - עד המדינה!</title>
    <?php include_once 'main_layout/head.php' ?>
    <script src="https://use.fontawesome.com/3c576bb39d.js"></script>
    <link rel="stylesheet" href="styles/social_app.css">
<!--    <script src="social_app/lib/webcomponents-hi-sd-ce.js"></script>-->
</head>

<body>
<div class="flex-container">
    <header>
        <?php include_once 'main_layout/header.php' ?>
    </header>
    <main class="main">
        <div class="title">
            <a href="<?= $app_title_link ?>" class="title"><?= $app_title ?></a>
        </div>
        <header class="app-header">
            <nav>
                <ul class="app-nav">
                    <li><a href="<?= DOMAIN ?>"> <i
                                    class="fa fa-newspaper-o fa-2x <?= $app_page == $app_pages[0] ? 'active_app_page' : '' ?>"
                                    aria-hidden="true" title="פיד"></i></a></li>
                    <li><a href="index.php?app-page=profile"><i
                                    class="fa fa-user fa-2x <?= $app_page == $app_pages[1] ? 'active_app_page' : '' ?>"
                                    title="פרופיל"></i> </a></li>
                    <li><a href="index.php?app-page=friends"><i
                                    class="fa fa-users fa-2x <?= $app_page == $app_pages[2] ? 'active_app_page' : '' ?>"
                                    title="חברים"></i></a></li>
                    <li><a href="index.php?app-page=settings"><i
                                    class="fa fa-cog fa-2x <?= $app_page == $app_pages[3] ? 'active_app_page' : '' ?>"
                                    title="הגדרות"></i></a></li>
                </ul>
            </nav>
        </header>
        <hr>
        <hr>
        <?php if ($is_logged): ?>
            <?php
            switch ($app_page) {
                case 'home':
                    include 'social_app/home.php';
                    break;
                case 'profile':
                    include 'social_app/profile.php';
                    break;
                case 'friends':
                    include 'social_app/friends.php';
                    break;
                case 'settings':
                    include 'social_app/settings.php';
                    break;
                default :
                    include 'social_app/home.php';
                    break;
            }
        else:
            include 'social_app/home.php';
            ?>
        <?php endif; ?>
        <script src="social_app/services/PostService.js"></script>
        <script>
          let mainUserId = <?= isset($_SESSION['front_user_id']) ? $_SESSION['front_user_id'] : 'undefined' ?>;
          const PS = new PostService();
        </script>
        <script src="<?= DOMAIN ?>social_app/components/post-item.js"></script>
        <script type="module" src="social_app/main.js"></script>
    </main>
    <footer><?php include_once 'main_layout/footer.php' ?></footer>
</div>

</body>

</html>