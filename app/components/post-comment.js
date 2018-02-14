//const currentDocument = document.currentScript.ownerDocument;
class PostComment extends HTMLElement{
	notNull(val){
		return val && val != 'null' && val != 'undefined'
	}
	constructor(){
		super();
		let _class = this;
		let profile_img = this.getAttribute('profile-img');
		this.comment = {
			id: this.getAttribute('comment-id'),
			content: this.getAttribute('content'),
			added_date: this.getAttribute('added-date'),
			uid: this.getAttribute('user-id'),
			profile_img: this.notNull(profile_img)?profile_img:'default_profile.jpg',
			commenter_name: this.getAttribute('commenter-name')
		};
	}
/*	get post_liked(){
		return this.getAttribute('post_liked');
	}
	set post_liked(val){
		return this.setAttribute('post_liked',val);
	}*/
	static get observedAttributes(){
		/*'posted-liked'*/
		return ['content'];
	}
	attributeChangedCallback(name, oldVal, newVal){
		console.log(name);
		switch (name){
			case 'comment-liked':
				console.log('liked-change');
				break;
/*			case 'content':
				this.post.content = newVal;
				break;*/
		}

	}
	contentTpl(comment){

		return !this.editMode?`<span>${comment.content.replace("\n",'<br>')}</span>`:`
		<textarea class="editedComment" style="display: block; width: 100%; height: auto;">${comment.content}</textarea>
		<div class="commentActions">
		<button class="cancelEdit" style="background: red; color: white;">ביטול</button>
		<button class="saveEdit" style="background: greenyellow; color: white;">שמור</button>
		</div>
		`
	}
	connectedCallback(){
		let _class = this;
		const comment = this.comment;

		let date = moment(comment.added_date, "YYYY-MM-DD hh:mm:ss").fromNow();
		/*<li class="hideComm">הסתר</li>*/
		this.innerHTML = `
				<style>
				.cSetsDropDown{
				}
				.commentSettings{
				    top: 25px;
				}
				</style>
				<div class="innerComment" data-comment-id="${comment.id}">
					${mainUserId == comment.uid?
					`
					<div class="postSettings commentSettings">
							<a title="הגדרות" class="expendPostSets expendCommSets">
									<i class="fa fa-caret-down" aria-hidden="true"></i>
									<ul class="pSetsDropDown cSetsDropDown">
											<li class="deleteComm" onclick="COMMS.askToDeleteComm(${comment.id})">
											מחק תגובה
											</li>
											<li class="modifyComm">
											שנה תוכן
											</li>
									</ul>
							</a>
					</div>
					`:''}

					<div class="profileFrame all-centered"><img src="_img/users/profiles/${comment.profile_img}" alt=""></div>
					<p class="comContent">
						<strong class="commenterName" style="float: right">
							<a style="text-decoration: none;" href="index.php?app-page=profile&username=${comment.commenter_name}">${comment.commenter_name}</a>
						</strong>
						<span class="commentMain">
						${this.contentTpl(comment)}		
						</span>
					</p>
					<small class="comDate">${date}</small>
					<div class="commentActions">
<!--						<span><a href="">אהבתי</a></span>
						<span><a href="">הגב</a></span>-->
					</div>
				</div>
		`;
		let $el = $(this);
		$el.find('.expendCommSets').on('click',function () {
			console.log('hihihi');
			console.log($(this).children('.cSetsDropDown'));
			$(this).children('.cSetsDropDown').toggleClass('postSetShow');
		});
		$el.find('.modifyComm').on('click',function () {
			_class.comment.prevContent = _class.comment.content;
			_class.editMode = true;
			let $main = $el.find('.commentMain');
			$main.html( _class.contentTpl(comment));
			$main.find('.editedComment').on('keyup',function (e) {
				_class.comment.content = e.target.value;
			});
			$main.find('.cancelEdit').on('click',function () {
				_class.editMode = false;
				$main.html( _class.contentTpl(comment));
			});
			$main.find('.saveEdit').on('click',function () {
				if(!_class.comment.content){
					COMMS.askToDeleteComm(comment.id);
				}else {
					if(!_class.comment.prevContent == _class.comment.content){
						COMMS.askToEditComm(_class.comment).then((res)=>{
							console.log(res);
							if(res) {
								_class.comment.content = res;
								_class.editMode = false;
								$main.html( _class.contentTpl(_class.comment));
							}else {
								console.log('error',res);
							}
						},(err)=>console.log(err));
					}else {
						_class.editMode = false;
						$main.html( _class.contentTpl(_class.comment));
					}

				}

			});
		});
	}
}
window.customElements.define('post-comment', PostComment);