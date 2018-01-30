<?php
define('app', true);

$app_pages = ['home','profile','friends'];
if(isset($_GET['app-page']) && in_array($_GET['app-page'],$app_pages)){
    $app_page = $_GET['app-page'];
}else{
    $app_page = $app_pages[0];
}
$app_title = '';
$app_title_link = '';
switch ($app_page){
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
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>דף הבית - עד המדינה!</title>
    <?php include_once 'main_layout/head.php' ?>
    <script src="https://use.fontawesome.com/3c576bb39d.js"></script>
    <style>
        .postSetShow{
            display: block !important;
            opacity: 1 !important;
            transition: 0.7s all;
        }
        .grid-news-item{
            position: relative;
        }
        .profileFrame{
            overflow: hidden;
            border-radius: 100%;
            background-color: #fff;
            width: 80px;
            height: 80px;
            box-shadow: #8f0222 1px 5px 7px;
            margin: 5px;
        }
        .profileFrame img{
            height: 80px;
            width: auto;

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
        <header style="background:linear-gradient(to bottom, #eeeeee 0%, #cccccc 100%); height: 50px;">
            <nav>
                <style>
                    .app-nav {
                        display: flex;
                        justify-content: space-around;
                        padding: 10px 0;
                    }
                    .app-nav a i{
                        color: #eeeeee;
                    }
                    .app-nav a i.active_app_page{
                        color: #8f0222;
                    }
                </style>
                <ul class="app-nav">
                    <li><a href="<?= DOMAIN ?>"> <i class="fa fa-newspaper-o fa-2x <?= $app_page == $app_pages[0]?'active_app_page':'' ?>" aria-hidden="true" title="פיד"></i></a></li>
                    <li><a href="index.php?app-page=profile"><i class="fa fa-user fa-2x <?= $app_page == $app_pages[1]?'active_app_page':'' ?>" title="פרופיל"></i> </a></li>
                    <li><a href="index.php?app-page=friends"><i class="fa fa-users fa-2x <?= $app_page == $app_pages[2]?'active_app_page':'' ?>" title="חברים"></i></a></li>
                    <li><a href="#"></a></li>
                </ul>
            </nav>
        </header>
        <hr>
        <hr>

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
            default :
                include 'social_app/home.php';
                break;
        }

        ?>
        <script src="<?= DOMAIN ?>components/post-item.js"></script>
        <style>

            #editPostPop {
                position: fixed;
                z-index: 10;
                top: 0;
                right: 0;
                left: 0;
                bottom: 0;
                margin: auto;
                width: 50vw;
                height: 30vw;
                background-color: #eeeeee;
            }

            #editorProfileImg {
                width: 30%;
            }

            #editorProfileImg img {;
                height: 100%;
            }

            #editPostPop #editedContent {
                width: 70%;
            }

        </style>

        <script>
let mainUserId = <?= isset($_SESSION['front_user_id']) ? $_SESSION['front_user_id'] : 'undefined' ?>;

class PostService {
    deleteListener() {
        $('.deletePost').on('click', AskToDeletePost);
    }

    askToDeletePost(id) {
        $.ajax({
            method: "POST",
            url: "api/index.php?action=delete_post",
            data: {
                post_id: id
            }
        }).then((res) => {
            if (res === 'deleted') this.deletePost(id);
        });
    }

    deletePost(id) {
        const $post = $(`*[post-id="${id}"]`);
        this.fadeOutPost($post);
        $post.remove();
    }

    editPost(e) {

    }

    askToEditPost() {
        let edited = $(e.target).siblings('#editedContent').val();
        console.log(edited);
    }

    toggleEditPostPop(post) {
        let html = editPostTpl(post);
        const existElement = $('#editPostPop');
        const $editedTpl = $(html);
        if (!existElement.next().length) {
            $('#postsFeed').prepend($editedTpl);
        } else {
            existElement[0].outerHTML = html;
        }
    }

    fadeInPost($post) {
        return $post.children('.grid-news-item').hide().fadeIn(1000);
    }

    fadeOutPost($post) {
        return $post.children('.grid-news-item').fadeOut(1000);
    }
}

const PS = new PostService();

function postTpl(post) {
	console.log(post);
	return $(`
        <post-item
           post-id="${post.id}"
           main-user-id="${mainUserId}"
           title="${post.title}"
           author="${post.author}"
           added-date="${post.added_date}"
           user-id="${post.uid}"
           profile-img="${post.profile_img}"
           front-img="${post.front_img}"
           posted-to="${post.to}"
           posted-to-name="${post._to}"
           ${page === 'profile'?
			'show-posted-to = "true"'
			:
            ``
			}

        ></post-item>



        `);
}

function editPostTpl(post) {
    return `
    <div id="editPostPop">
        <div style="display: flex; height: 80%;">
            <div id="editorProfileImg all-centered" style="float: right; width: 30%;">
                <img src="_img/users/profiles/${post.profile_img}" style="width: auto; height: 30%; overflow: hidden;" alt="">
            </div>
            <textarea id="editedContent" style="float: right;  flex: 1 1 0; display: block;">
                ${post.title}
            </textarea>
        </div>

        <input type="button" value="שמור" onclick="PostService.askToEditPost(event)">
    </div>
  `;
}

$('#publishPostI').on('click', function () {
    let postContent = $('#postContent').val();
    let postImage = $('#post_image').val();
    if(!postImage){
		postImage = '';
    }
    if (postContent && postContent.length < 700) {

        $.ajax({
            method: 'POST',
            url: 'class/Social_web.php',
			enctype: 'multipart/form-data',
			data: {
                act: 'new_post',
                content: postContent,
                post_image: postImage
            }
        }).done(function (res) {
            console.log('add_post:', res);
            let post = JSON.parse(res);
            const $post = postTpl(post);
            const $feed = $('#postsFeed');
            $feed.prepend($post);
            PS.fadeInPost($post);
            $('#postContent')[0].value = '';

        });

    }
});

function pushPosts(posts) {
    for (let post of posts) {
        let $post = postTpl(post);
        $('#postsFeed').append($post);
        PS.fadeInPost($post);
    }
}

let PostH = $('.grid-news-item').height();
let scrollFlag = 1;
postPage = 1;
$(window).scroll(function () {
    if (scrollFlag && $(window).scrollTop() + $(window).height() >= $(document).height() - (PostH * 3)) {
        console.log("load posts!");
        scrollFlag = 0;
        let q = postsQuery();
        console.log(q);
        $.ajax(q).then(function (res) {
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
    </main>
    <footer><?php include_once 'main_layout/footer.php' ?></footer>
</div>

</body>

</html>