<?php
$_GET['asArray'] = 1;
require_once 'class/DB.php';
$artsObj = new titlesTraffic();
$pageArticles = $artsObj->get_title_page_articles();
$res = $artsObj::$con->query("SELECT category_name FROM title_page");
$cates = $res->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>כותרות - עד המדינה!</title>
    <?php include_once 'main_layout/head.php'?>

</head>

<body>
    <div class="flex-container">
        <header>
            <?php include_once 'main_layout/header.php'?>
        </header>
        <main class="main">
            <?php foreach ($cates as $cate): ?>
            <?php if(isset($pageArticles[$cate['category_name']])): ?>
            <div class="title"> <a href="blank" class="title"> <?= $cate['category_name'] ?></a> </div>

            <div class="news-section" id='politicsTitles'>

                <ul class="grid-best-items">
                    <?php foreach ($pageArticles[$cate['category_name']] as $item): ?>
                        <?php include 'includes/bestItem.include.php'?>
                    <?php endforeach; ?>


                </ul>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </main>
        <footer><?php include_once 'main_layout/footer.php'?></footer>
    </div>
    <div class="rightWing1">
        <div class="sideAds">
            <div class="exitButton"><a href="#">X</a></div>
        </div>

    </div>
    <div class="leftWing1">
        <div class="sideAds">
            <div class="exitButton"><a href="#">X</a></div>
        </div>

    </div>
    <script src="_scripts/main.js"></script>

</body>

</html>
