<?php
defined('app') or die(header('HTTP/1.0 403 Forbidden'));
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 27/01/2018
 * Time: 12:11
 */
?>
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
    <!--            <div class="allReportsWrapperL"></div>
                <div class="allReportsWrapperR"></div>-->
    <div class="allReportsWrapper">
        <div class="news-home" id="postsFeed" style="width: 100%;
    margin: 0 auto; max-width: 900px;">
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
                    border-radius: 100%;
                    background-color: #fff;
                    width: 80px;
                    height: 80px;
                    box-shadow: #8f0222 1px 5px 7px;
                    margin: 5px;
                }
                .profileFrame img{
                    height: 50px;

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

            <?php
            $posts = Social_web::get_posts(0);
            ?>
            <?php foreach ($posts as $post): ?>
                <post-item
                    post-id="<?= $post['id'] ?>"
                    main-user-id="<?= isset($_SESSION['front_user_id'])?$_SESSION['front_user_id']:'undefined' ?>"
                    title="<?= $post['title']; ?>"
                    author="<?= $post['author']; ?>"
                    added-date="<?= $post['added_date']; ?>"
                    user-id="<?= $post['uid']; ?>"
                    profile-img="<?= $post['profile_img']; ?>"
                    front-img="<?= $post['front_img']; ?>"
                    post-show="true"
                ></post-item>
            <?php endforeach; ?>
        </div>
        <script src="<?= DOMAIN ?>components/post-item.js"></script>

    </div>
</div>
<style>

    #editPostPop{
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
    #editorProfileImg{
        width: 30%;
    }
    #editorProfileImg img{
    ;
        height: 100%;
    }
    #editPostPop #editedContent{
        width: 70%;
    }

</style>

<script>
	let mainUserId = <?= isset($_SESSION['front_user_id'])?$_SESSION['front_user_id']:'undefined' ?>;
	class PostService{
		deleteListener(){
			$('.deletePost').on('click',AskToDeletePost);
		}
		askToDeletePost(id) {
			$.ajax({
				method:"POST",
				url: "api/index.php?action=delete_post",
				data: {
					post_id: id
				}
			}).then((res)=> {
				if(res === 'deleted') this.deletePost(id);
			});
		}
		deletePost(id){
			const $post = $(`*[post-id="${id}"]`);
			this.fadeOutPost($post);
			$post.remove();
		}
		editPost(e){

		}
		askToEditPost(){
			let edited = $(e.target).siblings('#editedContent').val();
			console.log(edited);
		}
		toggleEditPostPop(post){
			let html = editPostTpl(post);
			const existElement = $('#editPostPop');
			const $editedTpl = $(html);
			if(!existElement.next().length){
				$('#postsFeed').prepend($editedTpl);
			}else {
				existElement[0].outerHTML = html;
			}
		}
		fadeInPost($post){
			return $post.children('.grid-news-item').hide().fadeIn(1000);
		}
		fadeOutPost($post){
			return $post.children('.grid-news-item').fadeOut(1000);
		}
	}
	const PS = new PostService();
	function postTpl(post) {
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
	let postPage = 1, scrollFlag = 1;
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
