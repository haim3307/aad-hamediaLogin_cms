//const currentDocument = document.currentScript.ownerDocument;
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
		this.mainUserId = this.getAttribute('main-user-id');
		this.show = this.getAttribute('post-show');
		let spt = this.getAttribute('show-posted-to');
		this.showPostedTo = spt?spt:false;
		let likers_list = this.getAttribute('likers-list');
		let likes_count = this.getAttribute('likes-count');
		console.log(likers_list);
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
				$(this).find('.artTitle').html(newVal);
				break;
		}

	}
	likeTpl(post){
		console.log(post.liked_post);
		console.log(this.notNull(post.liked_post));
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
				).join()
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
		this.innerHTML = `

			<div class="grid-news-item" data-post-id="${post.id}">
					<div class="postSettings">
							<a title="הגדרות" class="expendPostSets">
									<i class="fa fa-caret-down" aria-hidden="true"></i>
									<ul class="pSetsDropDown">
									<li class="hidePost">הסתר</li>
											${this.mainUserId === post.uid?`
											<li class="deletePost" onclick="PS.askToDeletePost(${post.id})">
											מחק פוסט
											</li>
											<li class="modifyPost">
											שנה תוכן
											</li>
											`:''}
									</ul>
							</a>
					</div>
					<div class="artAuth">
							<div class="profileFrame all-centered">
							${!post.profile_img?
							`<i style="float: right;" class="far fa-user-circle fa-3x"></i>`:
							`<img src="_img/users/profiles/${post.profile_img}" alt="">`
							}
							</div>
							<strong style="margin-top: 25px;">${post.author}</strong>
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
											${post.added_date}
							</div>
					</div>

					<h4 class="artTitle"> ${post.title} </h4>
					${post.front_img?`
					<a class="toArt" href="article.php?artId=${post.id}">
							<img src="_img/report/postFront/${post.front_img}" alt="">
					</a>
					`:''}
					<div class="postActions d-flex">
							<div class="cool postAction"><i class="fa fa-thumbs-up fa-2x 
							${_class.notNull(post.liked_post)
							?'liked':'unliked'}" style="padding-left: 10px;"></i><span>אהבתי</span></div>
							<div class="speak postAction"><i class="fas fa-comment fa-2x" style="padding-left: 10px;"></i><span>הגב</span></div>
							<div class="share postAction"><i class="fas fa-share fa-2x" style="padding-left: 10px;"></i><span>שתף</span></div>
					</div>
					<div class="postLikers">
						${likeTpl}
					
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
		$el.find('.cool i').on('click',function () {
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
				}else if('unliked'){
					_class.post.liked_post = undefined;
					_class.post.likes_count--;
					$(this).removeClass('liked');
					$el.find('.postLikers').html(_class.likeTpl(_class.post));
				}

			});
		});
	}
}
window.customElements.define('post-item', PostItem);