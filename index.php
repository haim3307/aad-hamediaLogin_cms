<?php
//session_start();
//require_once 'class/DB.php';
require_once 'class/Social_web.php';
require_once 'class/Login.php';
/*$con = new titlesTraffic();
$arts = $con->get_articles();*/


if (isset($_POST['site_login'])) {
    Login::login();
}else{
    Login::isLoggedIn();
}
if (isset($_GET['act']) && $_GET['act'] === 'logout') {
    $out = Login::logout();
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
        <?php if(isset($_SESSION['front_user_id'])): ?>
        <section class="uploadPost">
            <style>
                .uploadPost{
                    background-color: #fff;
                }
                .uploadPost .publishPost input[type=button]{
                    width: 20%;
                    height: 40px;
                }
                .ltr{
                    direction: ltr;
                }
                .row{
                    display: flex;
                }
                .post textarea{
                    flex: 1;
                    margin-top: 30px;
                    border-radius: 3%;
                    border: 0;
                }

                .profileFrame{
                    border-radius: 100%;
                    background-color: #fff;
                    width: 80px;
                    height: 80px;
                    box-shadow: #8f0222 1px 5px 7px;
                    margin: 5px;
                }
                .profileFrame img{
                    width: 50px;
                    height: 50px;

                }
                .publishPost input[type="button"]{
                    background: linear-gradient(to bottom, #a90329 0%, #8f0222 44%, #6d0019 100%);
                    color: white;
                    border-radius: 10px;
                }

            </style>
            <div class="row post">
                <div class="profileFrame all-centered">
                    <?php if(isset($_SESSION['front_profile_img']) && !empty($_SESSION['front_profile_img'])): ?>
                        <img src="_img/users/profiles/<?= $_SESSION['front_profile_img']; ?>" alt="">
                    <?php else: ?>
                        <i style="float: right;" class="far fa-user-circle fa-3x"></i>
                    <?php endif; ?>
                </div>
                <textarea name="" id="postContent" placeholder="על מה אתה חושב היום?" rows="8"></textarea>
            </div>
            <div class="ltr publishPost"> <input id="publishPostI" type="button" value="פרסם"> </div>
            <script>
                function postTpl(post) {
				  let img = post.front_img?`<a class="toArt" href="article.php?artId=${post.id}"><img src="_img/report/postFront/${post.front_img}" alt=""></a>`:``;
                  let postTpl = `

                        <div class="grid-news-item addedPost" data-post-id="${post.id}">
                            <p class="artAuth">
                                <i style="float: right;" class="far fa-user-circle fa-3x"></i>
                                <strong style="float: right;">${post.author}</strong>

                            </p>
                            <div class="artDate">
                                <div style="display: flex; align-items: center;">
                                    <i style="padding: 7px;" class="far fa-clock"></i>
                                        ${post.added_date}
                                </div>
                            </div>

                            <h4 class="artTitle"> ${post.title} </h4>
                            ${img}
                            <div class="postActions d-flex">
                                <div class="cool postAction"><i class="fas fa-thumbs-up fa-2x" style="padding-left: 10px;"></i><span>אהבתי</span></div>
                                <div class="speak postAction"><i class="fas fa-comment fa-2x" style="padding-left: 10px;"></i><span>הגב</span></div>
                                <div class="share postAction"><i class="fas fa-share fa-2x" style="padding-left: 10px;"></i><span>שתף</span></div>
                            </div>
                        </div>





                        `;
									return postTpl;
				}


                $('#publishPostI').on('click',function () {
					let postContent = $('#postContent').val();
					if(postContent && postContent.length < 700){

                        $.ajax({
                            method: 'POST',
                            url: 'class/Social_web.php',
                            data: {
                            	act: 'new_post',
                                content: postContent
                            }
                        }).done(function (res) {
                            console.log('add_post:',res);
                            let post = JSON.parse(res);
                            $('#postsFeed').prepend(postTpl(post));
							$(`*[data-post-id="${post.id}"]`).fadeIn(0);
							//$(`*[data-post-id="${post.id}"]`).animate({opacity:1},300);
							$(`*[data-post-id="${post.id}"]`).addClass('fadeInPost');
							$('#postContent')[0].value = '';
                            /*
                            *  <div class="grid-news-item">
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
                                    <a class="toArt" href="article.php?artId=${post.id}"><img src="_img/report/postFront/${post.front_img || 'BIBI.jpg'}" alt=""></a>
                                <div class="postActions d-flex">
                                    <div class="cool postAction"><i class="fas fa-thumbs-up fa-2x" style="padding-left: 10px;"></i><span>אהבתי</span></div>
                                    <div class="speak postAction"><i class="fas fa-comment fa-2x" style="padding-left: 10px;"></i><span>הגב</span></div>
                                    <div class="share postAction"><i class="fas fa-share fa-2x" style="padding-left: 10px;"></i><span>שתף</span></div>
                                </div>
                            </div>
                            * */
                        });

					}
				});
            </script>
        </section>
        <?php endif; ?>
        <div class="allReportsWrapperBig">
            <div class="allReportsWrapperL"></div>
            <div class="allReportsWrapperR"></div>
            <div class="allReportsWrapper">
                <div class="news-home" id="postsFeed" style="width: 100%;
    margin: 0 auto; max-width: 900px;">
                    <?php
                    echo '<pre style="direction: ltr;">';
                    $posts = Social_web::get_posts(0)->fetchAll(PDO::FETCH_ASSOC);
                    echo '</pre>';
                    ?>
                    <?php foreach ($posts as $row): ?>
                        <div class="grid-news-item">
                            <p class="artAuth">
                                <i style="float: right;" class="far fa-user-circle fa-3x"></i>
                                <strong style="float: right;"><?= $row['author'] ?></strong>

                            </p>
                            <div class="artDate">
                                <div style="display: flex; align-items: center;">
                                    <i style="padding: 7px;" class="far fa-clock"></i>
                                    <?= $row['added_date'] ?>
                                </div>
                            </div>

                            <h4 class="artTitle"><?= $row['title'] ?></h4>
                            <?php if(isset($row['front_img']) && $row['front_img']): ?>
                                <a class="toArt" href="main_pages/article.php?artId=<?= $row['id'] ?>">
                                    <img src="_img/report/postFront/<?= $row['front_img']; ?>"
                                         alt="">
                                </a>
                            <?php endif; ?>
                            <?php if(isset($row['official']) && $row['official']): ?>

                            <div class="postDesc">
                                <p>
                                    <?= $row['description'] ?>
                                    <span><a href="main_pages/article.php?artId=<?= $row['id'] ?>">המשך לקרוא..</a></span>
                                </p>
                            </div>
                            <?php endif; ?>

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


                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </main>
    <footer><?php include_once 'main_layout/footer.php' ?></footer>
</div>
<script>
    let PostH = $('.grid-news-item').height();
	let postPage = 1, scrollFlag = 1;
	//console.log('post height:',PostH);
	$(window).scroll(function () {
		if (scrollFlag && $(window).scrollTop() + $(window).height() >= $(document).height() - (PostH * 3)) {
			console.log("load posts!");
			scrollFlag = 0;

			$.ajax('class/Social_web.php?list=posts&act=get-posts&feed-page=' + (++postPage)).then(function (res) {
				let posts = $.parseJSON(res);
				//console.log(posts.length);
				if (posts.length > 10 || posts.length === 0) return;
				for (let post of posts) {
					console.log(post.front_img);
					$('#postsFeed').append(postTpl(post));
				}

				scrollFlag = 1;

			});

		}
	});
</script>
</body>

</html>