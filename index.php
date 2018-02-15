<?php
require_once 'class/Login.php';
define('app', true);
Login::setSession();
$is_logged = Login::isLoggedIn();
if(!function_exists('old')){
    function old($val){
        return isset($_REQUEST[$val]) && $_REQUEST[$val]?$_REQUEST[$val]:'';
    }
}
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
    <?php include_once 'main_layout/head.php'?>

<!--
--> <script>moment.locale('he');</script>
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
            <script>
              let profile_img = '<?= $_SESSION['front_profile_img']; ?>';
              let mainUserId = <?= isset($_SESSION['front_user_id']) ? $_SESSION['front_user_id'] : 'undefined' ?>;
            </script>

            <?php
            switch ($app_page) {
                case 'home':
                    include 'app/home.php';
                    break;
                case 'profile':
                    include 'app/profile.php';
                    break;
                case 'friends':
                    include 'app/friends.php';
                    break;
                case 'settings':
                    include 'app/settings.php';
                    break;
                default :
                    include 'app/home.php';
                    break;
            }
        else:
            include 'app/home.php';
            ?>
        <?php endif; ?>
        <script src="app/services/PostService.js"></script>
        <script>
          const PS = new PostService();
          const COMMS = new CommentService();
        </script>
        <script src="<?= DOMAIN ?>app/components/post-item.js"></script>
        <script src="<?= DOMAIN ?>app/components/post-comment.js"></script>
        <script type="module" src="app/main.js"></script>
    </main>
    <footer><?php include_once 'main_layout/footer.php' ?></footer>
</div>

</body>

</html>