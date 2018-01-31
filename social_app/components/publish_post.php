<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 31/01/2018
 * Time: 14:38
 */
?>
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
        #postsFeed{
            width: 100%;
            margin: 0 auto; max-width: 900px;
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
    <div class="ltr publishPost" style="display: flex; align-items: center; ">
        <input id="publishPostI" type="button" value="פרסם">
        <div style="flex: 1; direction: rtl; padding: 0 50px; display: grid; grid-column-gap: 1em; grid-template-columns: repeat(5,1fr)">
            <label for="post_image" style="text-align: center;"><i class="fa fa-image" title="העלה תמונה"></i></label>
            <input type="file" id="post_image" style="display: none;">
        </div>
    </div>
</section>
<script>
	$('#publishPostI').on('click', function () {
		let postContent = $('#postContent').val();
		let postImage = $('#post_image').val();
		if(!postImage){
			postImage = '';
		}
		if (postContent && postContent.length < 700) {

			$.ajax({
				method: 'POST',
				url: 'api/index.php',
				enctype: 'multipart/form-data',
				data: {
					action: 'add_post',
					content: postContent,
					post_image: postImage,
            <?php
            if($app_page === 'profile'){
                echo 'to_id:'.$user_id;
            }
            ?>
				}
			}).then((res) => {
				console.log('add_post:', res);
				let post = res;
				const $post = postTpl(post);
				const $feed = $('#postsFeed');
				$feed.prepend($post);
				PS.fadeInPost($post);
				$('#postContent')[0].value = '';

			});

		}
	});
</script>