//const currentDocument = document.currentScript.ownerDocument;
class PostItem extends HTMLElement{
	constructor(){
		super();
		this._complete = 0;
		this.mainUserId = this.getAttribute('main-user-id');
		this.show = this.getAttribute('post-show');
		let spt = this.getAttribute('show-posted-to');
		this.showPostedTo = spt?spt:false;
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
		};
		console.log(typeof this.getAttribute('posted-to'));
	}
	get complete(){
		return this._complete;
	}
	set complete(val){
		this._complete = val;
	}
	static get observedAttributes(){
		return ['complete'];
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
		}
	}
	connectedCallback(){
		const post = this.post;
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
							<div class="cool postAction"><i class="fas fa-thumbs-up fa-2x" style="padding-left: 10px;"></i><span>אהבתי</span></div>
							<div class="speak postAction"><i class="fas fa-comment fa-2x" style="padding-left: 10px;"></i><span>הגב</span></div>
							<div class="share postAction"><i class="fas fa-share fa-2x" style="padding-left: 10px;"></i><span>שתף</span></div>
					</div>
      </div>
		
		`;
		$(this).find('.postSettings').on('click',function () {
			$(this).find('.pSetsDropDown').toggleClass('postSetShow');
		});
		$(this).find('.modifyPost').on('click',function () {
			PS.toggleEditPostPop(post);
		});

	}
}
window.customElements.define('post-item', PostItem);