class PostService {
	askToDeletePost(id) {
		$.ajax({
			method: "POST",
			url: "api/index.php?action=delete_post",
			data: {
				post_id: id
			}
		}).then((res) => {
			console.log(res);
			if (res === 'deleted') this.deletePost(id);
		});
	}

	deletePost(id) {
		const $post = $(`*[post-id="${id}"]`);
		this.fadeOutPost($post);
		$post.remove();
	}

	fadeInPost($post) {
		return $post.find('.innerPost').hide().fadeIn(1000);
	}

	fadeOutPost($post) {
		return $post.children('.innerPost').fadeOut(1000);
	}

	postTpl(post) {
		return $(`
			<post-item
				post-id="${post.id}"
				main-user-id="${mainUserId}"
				title="${post.title}"
				added-date="${post.added_date}"
				user-id="${post.uid}"
				author="${post.name}"
				profile-img="${post.profile_img}"
				front-img="${post.front_img}"
				posted-to="${post.to}"
				posted-to-name="${post._to}"
				posted-liked="${post.liked}"
				likes = "${post.likes}"
				likers-list = '${post.likers_list}'
				likes-count = '${post.likes_count}'
				${page === 'profile' ?
				'show-posted-to = "true"'
				:
				``
				}
			></post-item>
		`)
	}
	askToEditPost(e,post) {
		console.log(post.id);
		console.log($(e.target.parentElement));
		let newTitle = $(e.target.parentElement).find('#editedContent').val();
		if(post.title === newTitle) return;
		$.ajax({
			method: "POST",
			url: "api/index.php?action=update_post",
			data: {
				post_id: post.id,
				post_title:newTitle
			}
		}).then((res) => {
			console.log(res);
			if(res.msg === 'updated'){
				$(`*[post-id=${post.id}]`).attr('title',res.post.title);
			}
			//if (res === 'deleted') this.deletePost(id);
		});
	}

	toggleEditPostPop(post) {
		let _class = this;
		let html = this.editPostTpl(post);
		const existElement = $('#editPostPop');

		const $editedTpl = $(html);
		if (!existElement.next().length) {
			console.log($('#postsFeed').prepend($editedTpl).find('#editPostPop').slideDown(500));
			//$('#postsFeed').prepend($editedTpl).target().slideDown(500);

		} else {
			existElement[0].outerHTML = html;
			$('#editPostPop').slideDown(500);
		}
		$('#cancelEditBtn').on('click',function () {
			$(this).parent().slideUp(500);
		});
		$('#saveEditBtn').on('click',function (e) {
			_class.askToEditPost(e,post);
			$(this).parent().slideUp(500);
		});
	}
	editPostTpl(post) {
		return `
			<div id="editPostPop">
					<div style="display: flex; height: 80%;">
							<div id="editorProfileImg all-centered" style="float: right; width: 30%;">
									<img src="_img/users/profiles/${post.profile_img}" style="width: auto; height: 30%; overflow: hidden;" alt="">
							</div>
							<textarea id="editedContent" style="float: right;  flex: 1 1 0; display: block;">${post.title}</textarea>
					</div>
	
					<input type="button" class="btn-prm" value="שמור" id="saveEditBtn">
					<input type="button" class="btn-prm" value="בטל" id="cancelEditBtn">
			</div>
	`;
	}

	pushPosts(posts) {
		for (let post of posts) {
			let $post = this.postTpl(post);
			$('#postsFeed').append($post);
			this.fadeInPost($post);
		}
	}
}
class CommentService{
	askToDeleteComm(id){
		$.ajax({
			method: "POST",
			url: "api/index.php?action=delete_comment",
			data: {
				comment_id: id
			}
		}).then((res) => {
			console.log(res);
			if (res === 'deleted') this.deleteComment(id);
		});
	}
	deleteComment(id){
		const $comment = $(`*[comment-id="${id}"]`);
		//this.fadeOutComment($comment);
		$comment.remove();
	}
	askToEditComm(comment){
		return $.ajax({
			method: "POST",
			url: "api/index.php?action=edit_comment",
			data: {
				comment_id: comment.id,
				comment_content: comment.content
			}
		});
	}
}
function follow(f_act,uid) {
	return $.ajax({
		url:'api/index.php',
		method: 'post',
		data: {
			action:'follow',
			uid,
		}
	});
}