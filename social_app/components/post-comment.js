//const currentDocument = document.currentScript.ownerDocument;
class PostComment extends HTMLElement{
	notNull(val){
		return val && val != 'null' && val != 'undefined'
	}
	constructor(){
		super();
		let _class = this;
		this.comment = {
			id: this.getAttribute('comment-id'),
			content: this.getAttribute('content'),
			added_date: this.getAttribute('added-date'),
			uid: this.getAttribute('user-id'),
			profile_img: this.getAttribute('profile-img'),
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
	connectedCallback(){
		let _class = this;
		const comment = this.comment;
		let date = moment(comment.added_date, "YYYY-MM-DD").fromNow();
		this.innerHTML = `
				<div class="innerComment" data-comment-id="${comment.id}">
					<div class="profileFrame all-centered"><img src="_img/users/profiles/${comment.profile_img}" alt=""></div>
					<p class="comContent">
						<strong class="commenterName" style="float: right">
							<a style="text-decoration: none;" href="index.php?app-page=profile&username=${comment.commenter_name}">${comment.commenter_name}</a>
						</strong>
						<span>${comment.content}</span>			
					</p>
					<small class="comDate">${date}</small>
					<div class="commentActions">
						<span><a href="">אהבתי</a></span>
						<span><a href="">הגב</a></span>
					</div>
				</div>
		`;
		let $el = $(this);
	}
}
window.customElements.define('post-comment', PostComment);