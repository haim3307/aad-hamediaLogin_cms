class PostItem extends HTMLElement{
	notNull(val){
		return val && val != 'null' && val != 'undefined'
	}
	constructor(){
		super();
		let _class = this;
/*
		this._complete = 0;
*/
		this.commentPage = 0;
		this.firstCommentsPush = true;
		this.mainUserId = this.getAttribute('main-user-id');
		this.show = this.getAttribute('post-show');
		let spt = this.getAttribute('show-posted-to');
		this.showPostedTo = spt?spt:false;
		let likers_list = this.getAttribute('likers-list');
		let likes_count = this.getAttribute('likes-count');
		this.post = {
			id: this.getAttribute('post-id'),
			title: this.getAttribute('title'),
			author: this.getAttribute('author'),
			added_date: this.getAttribute('added-date'),
			uid: this.getAttribute('user-id'),
			profile_img: this.getAttribute('profile-img'),
			front_img: this.getAttribute('front-img'),
			posted_to: this.getAttribute('posted-to'),
			posted_to_name: this.getAttribute('posted-to-name'),
			liked_post: this.getAttribute('posted-liked'),
			likers_list: JSON.parse(_class.notNull(likers_list)?likers_list:'[]'),
			likes_count: this.notNull(likes_count)?likes_count:0
		};
/*		console.log(typeof this.getAttribute('posted-to'));
		console.log(this.post.posted_to,this.post.uid);
		console.log(this.getAttribute('posted-to') === this.getAttribute('user-id'));
		console.log(this.post.liked_post);*/
/*	console.log('from item:');
	console.log(this.getAttribute('likers-list'));
	console.log(JSON.parse(this.getAttribute('likers-list')));*/
	}
	get complete(){
		return this._complete;
	}
	set complete(val){
		this._complete = val;
	}
	get post_liked(){
		return this.getAttribute('post_liked');
	}
	set post_liked(val){
		return this.setAttribute('post_liked',val);
	}
	static get observedAttributes(){
		/*'posted-liked'*/
		return ['title'];
	}
	attributeChangedCallback(name, oldVal, newVal){
		console.log(name);
		switch (name){
			case 'post-show':
				console.log(name);
				console.log(newVal);
				if(newVal == 'true'){
					this.show = newVal;
					this.style.display = 'block';
				}else {
					this.style.display = 'none';
				}
				break;
			case 'posted-liked':
				console.log('liked-change');
				break;
			case 'title':
				this.post.title = newVal;
				$(this).find('.artTitle').html(newVal.replace("\n",'<br>'));
				break;
		}

	}
	likeTpl(post){
		return `
				<span class="likesStatus">
				${this.notNull(post.likes_count) && post.likes_count != '0'?
			`
				<a href="">
				<i class="fa fa-thumbs-up" ></i>
					${this.notNull(post.liked_post)?
				`את/ה${post.likes_count && post.likes_count > 1?` ועוד ${post.likes_count-1}`
					:''}`
				:post.likes_count
				}
					
				
				</a>
				${post.likers_list.length?
				`
					<ul class="likersList">
					${post.likers_list.map(liker =>
					`<li><a href="index.php?app-page=profile&username=${liker.name}">${liker.name}</a></li>`
				).join('')
					}
					</ul>
					`:''
				}
				`:''
			}
				</span>
		`;
	}
	connectedCallback(){
		let _class = this;
		const post = this.post;
		let likeTpl = this.likeTpl(this.post);
		/*									<li class="hidePost">הסתר</li>*/
		this.innerHTML = `

			<div class="innerPost" data-post-id="${post.id}">
					<div class="grid-news-item">
					
					${this.mainUserId === post.uid?`
					<div class="postSettings">
							<a title="הגדרות" class="expendPostSets">
									<i class="fa fa-caret-down" aria-hidden="true"></i>
									<ul class="pSetsDropDown">
											<li class="deletePost" onclick="PS.askToDeletePost(${post.id})">
											מחק פוסט
											</li>
											<li class="modifyPost">
											שנה תוכן
											</li>
									</ul>
							</a>
					</div>
					`:''}
					
					<div class="artAuth">
							<div class="profileFrame all-centered">
							<img src="_img/users/profiles/${post.profile_img?post.profile_img:'default_profile.jpg'}" alt="">
							</div>
							<strong style="margin-top: 25px;"><a href="index.php?app-page=profile&username=${post.author}">${post.author}</a></strong>
							${this.showPostedTo?
								`
								<span style="margin-top: 25px; margin-right: 10px;">
											<span><i class="fa fa-angle-left"></i></span>
											<span>
												<a href="${post.posted_to && post.posted_to != 'null'?'index.php?app-page=profile&username='+post.posted_to_name:'index.php'}">
												${post.posted_to != this.mainUserId
													?
													post.posted_to && post.posted_to != 'null'?post.posted_to_name:'פיד'
													:
													'הציר שלי'
												}
												</a>
											</span>
								</span>
								`:''
							}
					</div>

					<div class="artDate">
							<div style="display: flex; align-items: center;">
									<i style="padding: 7px;" class="far fa-clock"></i>
											${moment(post.added_date, "YYYY-MM-DD hh:mm:ss").fromNow()}
							</div>
					</div>

					<h4 class="artTitle"> ${post.title.replace("\n",'<br>')} </h4>
					${post.front_img?`
					<a class="toArt" href="article.php?artId=${post.id}">
							<img src="_img/report/postFront/${post.front_img}" alt="">
					</a>
					`:''}

					<div class="postLikers">
						${likeTpl}
					
					</div>
					</div>
					<div class="postActions d-flex">
							<div class="cool postAction ${_class.notNull(post.liked_post)
			?'liked':'unliked'}"><i class="fa fa-thumbs-up fa-2x" style="padding-left: 10px;"></i><span>אהבתי</span></div>
							<div class="speak postAction"><i class="fas fa-comment fa-2x" style="padding-left: 10px;"></i><span>הגב</span></div>
							<div class="share postAction"><i class="fas fa-share fa-2x" style="padding-left: 10px;"></i><span>שתף</span></div>
					</div>
					<div class="postComments">
						<div class="commentsContStatus">
						
						</div>
						<div class="postCommentsList" style="margin-bottom: 20px;">
	
	
						</div>
						<div class="checkNewComments"><i class="fa fa-refresh" title="טען תגובות נוספות"></i><span class="noNewComments">אין תגובות חדשות</span><span></span></div>
						<div class="yourComment" style="display: flex; align-items: center; height: 50px;">
							<div class="profileFrame all-centered yourProfileImg" style="height: 50px; width: 50px;"><img style="height: 100%" src="_img/users/profiles/${profile_img}" alt=""></div>
							<textarea class="yourCommentText" style="flex:1; border-radius: 47px; font-family: alef;
							padding: 14px 14px 1px; height: 50px;" placeholder="הקלד תגובתך כאן.."></textarea>
						</div>	
					</div>
					</div>

		`;
		let $el = $(this);
		$el.find('.postSettings').on('click',function () {
			$(this).find('.pSetsDropDown').toggleClass('postSetShow');
		});
		$el.find('.modifyPost').on('click',function () {
			PS.toggleEditPostPop(post);
		});
		$el.find('.cool').on('click',function () {
			$.ajax({
				url: 'api/index.php',
				method: 'post',
				data: {
					'action':'like_post',
					'post_id': post.id,
					'already_liked':post.liked_post
				}
			}).then((res)=>{
				console.log(_class.post);
				console.log(_class.likeTpl(_class.post));
				if(res === 'liked'){
					_class.post.liked_post = true;
					_class.post.likes_count++;
					$(this).addClass('liked');
					$el.find('.postLikers').html(_class.likeTpl(_class.post));
				}else if(res === 'unliked'){
					_class.post.liked_post = undefined;
					_class.post.likes_count--;
					$(this).removeClass('liked');
					$el.find('.postLikers').html(_class.likeTpl(_class.post));
				}

			});
		});

		$el.find('.speak').on('click',function () {
			_class.commentPage = 0;
			_class.getComments(post).then((res)=> {
				console.log(res);
				let $commentsCont = $el.find('.postComments');
				let $commentsList = $commentsCont.find('.postCommentsList');
				$commentsList.html(_class.postCommentsTpl(res));
				console.log(res[res.length-1]);
				if(res.length) {
					_class.firstCommentDate = res[0]['added_date'];
					_class.lastCommentDate = res[res.length-1]['added_date'];
				}				let $commentsContStatus = $commentsCont.find('.commentsContStatus');
				console.log(_class.postCommentsContStatusTpl(res));
				$commentsContStatus.html(_class.postCommentsContStatusTpl(res));
				function clickPrevComments() {
					console.log('click');
					++_class.commentPage;
					console.log(_class.commentPage);
					_class.firstCommentsPush = false;
					$.ajax({
						url: 'api/index.php?action=get_comments&post_id='+post.id+'&post_comments_page='+_class.commentPage+'&first_comment_added_date='+_class.firstCommentDate,
						method: 'get',
/*						data: {
							'action':'get_comments',
							'post_id': post.id,
							'post_comments_page': this.commentPage
						},*/
						success: function (res2) {
							console.log('res:',res2);
							$commentsContStatus.html(_class.postCommentsContStatusTpl(res2));
							$commentsCont.find('.prevComments').on('click',clickPrevComments);
							$commentsList.prepend(_class.postCommentsTpl(res2));
							console.log(_class.commentPage);
						},
						error: function (err) {
							console.log(err);
						}
					});

				}
				$commentsCont.find('.prevComments').on('click',clickPrevComments);
				$commentsCont.find('.checkNewComments').on('click',function () {
					console.log(_class.lastCommentDate);
					$.ajax({
						//&post_comments_page='+_class.commentPage
						url: 'api/index.php?action=get_new_comments&post_id='+post.id+'&last_comment_date='+_class.lastCommentDate,
						method: 'get',
						success: (res) => {
							console.log(res);
							if(res.length) {
								_class.firstCommentDate = res[0]['added_date'];
								_class.lastCommentDate = res[res.length-1]['added_date'];
							}else {
								$(this).find('.noNewComments').fadeIn(500).delay(500).fadeOut(500);
							}

							$commentsList.append(_class.postCommentsTpl(res));
						},
						error: function (err) {
							console.log(err);
						}
					})
				});
				let $yourComment = $commentsCont.find('.yourCommentText');

				$yourComment.on('keydown',function (e) {
					let comment = e.target.value;
					if(comment){
						if(e.key == 'Enter' && !e.shiftKey){
							e.preventDefault();
							$.ajax({
								url: 'api/index.php',
								method: 'post',
								data: {
									'action':'add_comment',
									'post_id': post.id,
									'post_comment': comment
								}
							}).then((res)=> {
									console.log(res);
									let newComment = res;
									if(_class.notNull(newComment)){
										$commentsList.append(_class.postCommentTpl(newComment));
										$(this)[0].value = '';
									}
							});
						}
					}

				});
				//$commentsList.find('')
				$commentsCont.slideToggle(500);
			});
		});

	}
	postCommentTpl(comment){
		return `			
			<post-comment 
			comment-id="${comment.id}"
			content="${comment.content}"
			added-date="${comment.added_date}"
			user-id="${comment.uid}"
			profile-img="${comment.profile_img}"
			commenter-name="${comment.commenter_name}"
			></post-comment>
		`;
	}
	postCommentsTpl(comments) {
		return `${comments.length ?
			comments.reverse().map((comment) => this.postCommentTpl(comment)).join('\n') :
			''
			}`;
	}
	getComments(post) {
		console.log(this.commentPage);
		return $.ajax({
			url: 'api/index.php',
			method: 'get',
			data: {
				'action':'get_comments',
				'post_id': post.id,
				'post_comments_page': this.commentPage
			}
		});
	}
	postCommentsContStatusTpl(res){
		console.log(res);
		console.log(res.length);
		if(res.length){
			this.commentsStatus = 'part';
		}else {
			this.commentsStatus = 'empty';
			this.noCommentsStat = this.commentPage > 1?'more':'none';
		}
		console.log(this.commentsStatus);
		let hr = `<hr style="width: 90%; margin: 0 auto;">`;
		if(this.firstCommentsPush){
			return res.length?`<a class="prevComments">טען תגובות קודמות</a>`+hr:
				`<p style="padding-right: 14px; display: flex; justify-content: space-between;">אין תגובות להצגה </p>`+hr;
		}
		else {
			return res.length?`<a class="prevComments">טען תגובות קודמות</a>`+hr:'';
		}

	}

}
window.customElements.define('post-item', PostItem);