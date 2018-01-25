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
                        <img src="_img/users/profiles/<?= $_SESSION['front_profile_img']; ?>" alt="<?= $_SESSION['front_user_name']; ?>">
                    <?php else: ?>
                        <i style="float: right;" class="far fa-user-circle fa-3x"></i>
                    <?php endif; ?>
                </div>
                <textarea name="" id="postContent" placeholder="על מה אתה חושב היום?" rows="8"></textarea>
            </div>
            <div class="ltr publishPost"> <input id="publishPostI" type="button" value="פרסם"> </div>
        </section>
        <?php endif; ?>
        <div class="allReportsWrapperBig">
            <div class="allReportsWrapperL"></div>
            <div class="allReportsWrapperR"></div>
            <div class="allReportsWrapper">
                <div class="news-home" id="postsFeed" style="width: 100%;
    margin: 0 auto; max-width: 900px;">
                    <?php
                    $posts = Social_web::get_posts(0);
                    ?>

                </div>

            </div>
        </div>
    </main>
    <footer><?php include_once 'main_layout/footer.php' ?></footer>
</div>
<style>
    .postSetShow{
        display: block !important;
        opacity: 1 !important;
        transition: 0.7s all;
    }
    .grid-news-item{
        position: relative;
    }
    .postSettings{
        position: absolute;
        left: 0;
        top: 10px;
    }
    .postSettings .expendPostSets{
        padding: 10px;
        position: relative;
    }
    .postSettings .pSetsDropDown{
        position: absolute;
        left: 0;
        top: 33px;
        display: none;
        opacity: 0;
        transition: 0.7s all;
    }
    .postSettings .pSetsDropDown li{
        min-height: 20px;
        min-width: 115px;
        padding: 5px 15px;
        background-color: #eeeeee;
        margin-bottom: 5px;
    }
</style>
<script>
	class PostService{
		deleteListener(){
			$('.deletePost').on('click',AskToDeletePost);
		}
		AskToDeletePost() {
			$.ajax({
				method:"POST",
				url: "class/Social_web.php",
				data: {
				}
			});
		}
		deletePost(id){
            $.ajax({
                method:"POST",
                url: "api/index.php?action=delete_post",
                data: {
                	post_id: id
                }
            }).then(function(res){
            	if(res === 'deleted'){
					$(`*[data-post-id="${id}"]`).remove();
                }
            });
		}
	}
	const PS = new PostService();
    let mainUserId = <?= isset($_SESSION['front_user_id'])?$_SESSION['front_user_id']:'undefined' ?>;
	function postTpl(post) {
		console.log(mainUserId,typeof mainUserId);
		console.log(post.uid,typeof post.uid);
		console.log(post.proflie_img);
		console.log(post);
		return `

        <div class="grid-news-item addedPost" data-post-id="${post.id}">
            <div class="postSettings">
                <a title="הגדרות" class="expendPostSets">
                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                    <ul class="pSetsDropDown">
                    <li class="hidePost">הסתר</li>`+
                    (mainUserId == post.uid?`
                    <li class="deletePost" onclick="PS.deletePost(${post.id})">
                    מחק פוסט
</li>
                    <li class="modifyPost">שנה תוכן</li>
                    `:'')

                    +`</ul>
                </a>
            </div>
            <div class="artAuth">
                <div class="profileFrame all-centered">
                `+
                (!post.profile_img?
                  `<i style="float: right;" class="far fa-user-circle fa-3x"></i>`:
                  `
                    <img src="_img/users/profiles/${post.profile_img}" alt="">
                  `
                )

                    +`
                </div>
                <strong style="margin-top: 25px;">${post.author}</strong>
            </div>

            <div class="artDate">
                <div style="display: flex; align-items: center;">
                    <i style="padding: 7px;" class="far fa-clock"></i>
                        ${post.added_date}
                </div>
            </div>

            <h4 class="artTitle"> ${post.title} </h4>
            `+
            (post.front_img?`
            <a class="toArt" href="article.php?artId=${post.id}">
                <img src="_img/report/postFront/${post.front_img}" alt="">
            </a>
            `:'')
            +`<div class="postActions d-flex">
                <div class="cool postAction"><i class="fas fa-thumbs-up fa-2x" style="padding-left: 10px;"></i><span>אהבתי</span></div>
                <div class="speak postAction"><i class="fas fa-comment fa-2x" style="padding-left: 10px;"></i><span>הגב</span></div>
                <div class="share postAction"><i class="fas fa-share fa-2x" style="padding-left: 10px;"></i><span>שתף</span></div>
            </div>
        </div>





        `;
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

			});

		}
	});
	let posts = [];
    posts = <?= json_encode($posts); ?>;
	pushPosts(posts);
    function pushPosts(posts) {
    	console.log(Array.isArray(posts));
    	console.log(posts);
        for (let post of posts) {
        	let $post = $(postTpl(post));
			$post.children('.postSettings').on('click',function () {
                $(this).find('.pSetsDropDown').toggleClass('postSetShow');
            });
            $('#postsFeed').append($post);

			$post.addClass('fadeInPost');
        }
    }
    let PostH = $('.grid-news-item').height();
	let postPage = 1, scrollFlag = 1;
	//console.log('post height:',PostH);
	$(window).scroll(function () {
		if (scrollFlag && $(window).scrollTop() + $(window).height() >= $(document).height() - (PostH * 3)) {
			console.log("load posts!");
			scrollFlag = 0;
			$.ajax(`api/index.php?action=get_posts&feed_page=${++postPage}`).then(function (res) {
				console.log(res);
				let posts = res;
				console.log(posts);
				if (posts.length > 10 || posts.length === 0) return;
                pushPosts(posts);
				scrollFlag = 1;

			});

		}
	});


</script>
</body>

</html>