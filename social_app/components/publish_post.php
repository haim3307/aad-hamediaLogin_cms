<section class="uploadPost">
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
				console.log(res.post && res.msg === 'added');
				if(res.post && res.msg === 'added'){
                    let post = res.post;
                    const $post = PS.postTpl(post);
                    const $feed = $('#postsFeed');
                    $feed.prepend($post);
                    PS.fadeInPost($post);
                    $('#postContent')[0].value = '';
				}else if(res.msg === 'empty'){
					console.log('empty post');
                }


			});

		}
	});
</script>