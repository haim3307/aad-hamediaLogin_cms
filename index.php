<?php
require_once 'class/DB.php';
$con = new titlesTraffic();
$arts = $con->get_articles();
function login()
{
    if (!isset($_POST['user_name'])) {
        return;
    }
    if (!isset($_POST['user_pass'])) {
        return;
    }
    require_once 'class/Connection.php';
    $uname = $_POST['user_name'];
    $upass = $_POST['user_pass'];
    $stmt = Connection::query(
        "SELECT name FROM front_users WHERE name=:name AND password=:password",
        [':name' => $uname, ':password' => $upass]
    );
    if ($stmt && $stmt->rowCount() == 1) {
        session_start();
        $_SESSION['front_user_name'] = $uname;
        $_SESSION['loggedInBlog'] = 1;
    }

}

if (isset($_POST['site_login'])) {
    login();
}
if (isset($_GET['act']) && $_GET['act'] === 'logout') {
    session_start();
    session_destroy();
    header('location:index.php');
}

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
        <div class="title"><a href="index.php" class="title"> דף הבית</a></div>
        <div class="dateOfPublish">היום</div>
        <section class="uploadPost">
            <div class="profileFrame">
                <img src="" alt="">
            </div>
            <textarea name="" id="" style="display: block;width: 100%;"></textarea>
        </section>
        <div class="allReportsWrapperBig">
            <div class="allReportsWrapperL"></div>
            <div class="allReportsWrapperR"></div>
            <div class="allReportsWrapper">
                <div class="news-home" id="postsFeed">
                    <?php while ($row = $arts->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="grid-news-item">
                            <p class="artAuth">
                                <i style="float: right;" class="far fa-user-circle fa-3x"></i>
                                <strong style="float: right;"><?= $row['reporterName'] ?></strong>

                            </p>
                            <div class="artDate">
                                <div style="display: flex; align-items: center;">
                                    <i style="padding: 7px;" class="far fa-clock"></i>
                                    <?= $row['addedDate'] ?>
                                </div>
                            </div>

                            <h4 class="artTitle"><?= $row['title'] ?></h4>
                            <a class="toArt" href="main_pages/article.php?artId=<?= $row['id'] ?>">
                                <img src="_img/report/postFront/<?= (isset($row['frontImg']) ? $row['frontImg'] : 'BIBI.jpg'); ?>"
                                     alt="">
                            </a>
                            <div class="postDesc">
                                <p>
                                    <?= $row['description'] ?>
                                    <span><a href="main_pages/article.php?artId=<?= $row['id'] ?>">המשך לקרוא..</a></span>
                                </p>
                            </div>
                            <div class="postActions d-flex">
                                <div class="cool postAction">
                                    <i class="fas fa-thumbs-up fa-2x" style="padding-left: 10px;"></i>
                                    <span>אהבתי</span></div>
                                <div class="speak postAction">
                                    <!--                                    <i class="fas fa-comment fa-2x" style="padding-left: 10px;"></i><span>הגב</span>
                                    -->
                                </div>
                                <div class="share postAction">
                                    <i class="fas fa-share fa-2x" style="padding-left: 10px;"></i>
                                    <span>שתף</span>
                                </div>
                            </div>

                        </div>


                    <?php endwhile; ?>
                </div>

            </div>
        </div>
    </main>
    <footer><?php include_once 'main_layout/footer.php' ?></footer>
</div>
<script>let PostH = $('.grid-news-item').height();
	let postPage = 1, scrollFlag = 1;
	//console.log('post height:',PostH);
	$(window).scroll(function () {
		//console.log('scrollTop:',$(window).scrollTop());
		//console.log('scrollTop+wHeight:',$(window).scrollTop() + $(window).height());
		//console.log('docHeight',$(document).height());
		//console.log('condition:',scrollFlag && $(window).scrollTop() + $(window).height() >= $(document).height()-3000);
		if (scrollFlag && $(window).scrollTop() + $(window).height() >= $(document).height() - (PostH * 3)) {
			console.log("load posts!");
			scrollFlag = 0;
			$.ajax('class/db.php?list=art&feedPage=' + (++postPage)).then(function (res) {
				let result = $.parseJSON(res);
				let posts = result.artArr;
				//console.log(posts.length);
				if (posts.length > 10 || posts.length === 0) return;
				for (let post of posts) {
					$('#postsFeed').append(`

                        <div class="grid-news-item">
                            ${post.id}
                            <p class="artAuth">
                                <i style="float: right;" class="far fa-user-circle fa-3x"></i>
                                <strong style="float: right;">${post.reporterName}</strong>

                            </p>
                            <div class="artDate">
                                <div style="display: flex; align-items: center;">
                                    <i style="padding: 7px;" class="far fa-clock"></i>
                                        ${post.addedDate}
                                </div>
                            </div>

                            <h4 class="artTitle"> ${post.title} </h4>
                                <a class="toArt" href="article.php?artId=${post.id}"><img src="_img/report/postFront/${post.frontImg || 'BIBI.jpg'}" alt=""></a>
                            <div class="postActions d-flex">
                                <div class="cool postAction"><i class="fas fa-thumbs-up fa-2x" style="padding-left: 10px;"></i><span>אהבתי</span></div>
                                <div class="speak postAction"><i class="fas fa-comment fa-2x" style="padding-left: 10px;"></i><span>הגב</span></div>
                                <div class="share postAction"><i class="fas fa-share fa-2x" style="padding-left: 10px;"></i><span>שתף</span></div>
                            </div>
                        </div>





                        `);
				}

				scrollFlag = 1;

			});
		}
	});
</script>
</body>

</html>