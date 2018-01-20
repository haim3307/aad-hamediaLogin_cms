<li class="bestTitlesItem">
    <div> <img src="<?= DOMAIN ?>_img/report/postFront/<?= $item['frontImg'] ?>"></div>

    <div class="bestTitlesItemDes">
        <a href="<?= DOMAIN ?>main_pages/article.php?artId=<?= $item['id'] ?>" class="overlay1"></a>
        <div class="topinfo">
            <div class="socialShare">
                <div>
                    <a href="#"><img src="<?= DOMAIN ?>_img/layout/googlesmall.svg"></a>
                </div>


                <div>
                    <a href="#"><img src="<?= DOMAIN ?>_img/layout/facebook.svg"></a>
                </div>
                <div>
                    <a href="#"><img src="<?= DOMAIN ?>_img/layout/twitersmall.svg"></a>
                </div>
            </div>
        </div>
        <div class="bottominfo">
            <div class="binfoTitle"><?= $item['title'] ?>
            </div>
            <div class="bdate"> <?= $item['reporterName']?> | 14:20 | 26/11/2016</div>
        </div>
    </div>

</li>