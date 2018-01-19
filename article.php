<?php
require_once 'class/DB.php';
//echo '<pre>';
//print_r($_GET);
$article = $artObj->getArticle((int)$_GET['artId'], null);
$simularArts = $artObj->simular_articles($article['addedDate']);

$path = 'articles/html/' . $article['article'];
?>
<!doctype html>
<html lang="en">
<head>
    <?php include 'main_layout/head.php' ?>
    <style>
        .artPage {
            display: grid;
            grid-template-columns: 1fr 300px;
        }

        .col-best-items {
            display: grid;
            grid-auto-rows: 250px;
        }

        .bestTitlesItem {
            height: auto;
        }

        .shareTo {
            flex: 1 1 30%;
            display: flex;
            margin: 10px;
        }

        .social {
            display: flex;
            justify-content: flex-end;
            direction: rtl;
            height: 60px;
            flex: 1 1 40%;
            align-items: flex-end;
        }

        .reportername {
            flex: 1 1 60%;
            font-size: 12pt;
            align-items: center;
            display: flex;
        }

        .likebutton {
            height: 50px;
            width: 50px;
            margin: 5px;
        }

        @media (min-width: 810px) {
            .page {
                position: relative;
                min-height: 2000px;
                border: 2px outset black;
                box-shadow: 2px 0px 3px 2px;
                margin: 0px 20px 50px 20px;
                padding: 20px;
                background: linear-gradient(to left, #ECE9E6, #FFFFFF);
                direction: rtl;
            }
        }

        .menuBanner {
            max-height: 263px;
        }

        .mobileToSimulars {
            display: none;
            text-align: left;
            font-size: 10em;
        }

        .report-suggestions {
            display: block;
            cursor: pointer;
        }

        @media (max-width: 810px) {
            .page{
                background: linear-gradient(to left, #ECE9E6, #FFFFFF);
                min-height: 3000px;
            }
            .artPage {
                display: flex;
                overflow-x: hidden;
            }

            .report-suggestions {
                display: none;
                transform: translateX(-300px);
                transition: 0.7s all;
            }

            .mobileToSimulars {
                display: block;
                transform: translateY(-50vh);
                color: black;
/*
                position: absolute;
                top: 200px;
                bottom: 0;
                left: 20px;
                height: 30px;*/
            }

            .aligned {
                display: block;
                transform: translateX(0);
                position: absolute;
                width: 70vw;
                max-width: 300px;
                left: 0;
                background-color: #eeeeee;

            }

            .shareTo {
                display: none;
            }

            article > p > img {
                width: 100% !important;
            }
        }

    </style>
</head>
<body>

<div class="flex-container">
    <?php include 'main_layout/header.php' ?>
    <article class="artPage">
        <main class="page">
            <div class="shareTo">
                <div class="reportername">
                    <h2>08.01.17 | חיים טרגנו | 23:04</h2></div>
                <div class="social">
                    <img class="likebutton" src="_img/layout/googlep.jpg">
                    <img class="likebutton" src="_img/layout/Facebook.jpg">
                    <img class="likebutton" src="_img/layout/Twiter.jpg">
                </div>
            </div>
            <div style="min-height: 1000px;">
                <article>
                    <h1><?= $article['title'] ?></h1>
                    <?php include_once "$path" ?>

                </article>
            </div>
            <div class="mobileToSimulars">
                <
            </div>
            <div class="comments">

            </div>
        </main>
        <aside>


            <section class="report-suggestions">
                <div class="resizeSimulars">
                    x
                </div>
                <div class="report-suggestions-title">כתבות דומות</div>
                <div class="col-best-items">
                    <?php while ($item = $simularArts->fetch_assoc()): ?>
                        <?php include 'includes/bestItem.include.php' ?>
                    <?php endwhile; ?>
                </div>
                <div class="resizeSimulars">
                    x
                </div>


            </section>


        </aside>

    </article>
    <footer> Copyright HTProjects 2017 &copy;</footer>
    <script>
			$('.mobileToSimulars').on('click', function () {
				$('.report-suggestions').addClass('aligned');
				$('.resizeSimulars').on('click', function () {
					$('.report-suggestions').removeClass('aligned');
				});
				console.log('ho');
			});
    </script>
</div>
</body>
</html>

