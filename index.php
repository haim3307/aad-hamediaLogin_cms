<?php
define('app',true);
?>
<!DOCTYPE html>
<html>

<head>
    <title>דף הבית - עד המדינה!</title>
    <?php include_once 'main_layout/head.php' ?>

</head>

<body>
<div class="flex-container">
    <header>
        <?php include_once 'main_layout/header.php' ?>
    </header>
    <main class="main">
        <div class="title"><a href="<?= DOMAIN ?>index.php?app-page=home" class="title"> דף הבית</a></div>
        <header style="background:linear-gradient(to bottom, #eeeeee 0%, #cccccc 100%); height: 50px;">
            <nav>
                <style>
                    .app-nav{
                        display: flex;
                        justify-content: space-around;
                    }
                </style>
                <ul class="app-nav">
                    <li><a href="<?= DOMAIN ?>">פיד</a></li>
                    <li><a href="index.php?app-page=profile">פרופיל</a></li>
                    <li><a href="index.php?app-page=friends">חברים</a></li>
                    <li><a href="#"></a></li>
                </ul>
            </nav>
        </header>
        <?php
            if(isset($_GET['app-page'])){
                switch ($_GET['app-page']){
                    case 'home':
                        include 'social_app/home.php';
                        break;
                    case 'profile':
                        include 'social_app/profile.php';
                        break;
                    case 'friends':
                        include 'social_app/friends.php';
                        break;
                    default :
                        include 'social_app/home.php';
                        break;
                }
            }
            else include 'social_app/home.php';

        ?>
    </main>
    <footer><?php include_once 'main_layout/footer.php' ?></footer>
</div>

</body>

</html>