class UserItem extends HTMLElement {
	constructor() {
		super();
		this.user = {
			id: this.getAttribute('user-id'),
			name: this.getAttribute('user-name'),
			profile_img: this.getAttribute('user-profile-img')
		};
		this.followStatus = this.getAttribute('followed-by-you');
		this.searchMode = this.getAttribute('search-mode');
		this.className = 'user_item';
		this.followedByYou = this.followStatus == '1';

	}
	followBtnTpl(){
		return `<input type="button" id="remove_follow" value="${!this.followedByYou?'עקוב':'הסר עוקב'}">`;
	}
	connectedCallback() {
		this.innerHTML =
			`
                    <span class="profileFrame all-centered"><img src="_img/users/profiles/${this.user.profile_img?this.user.profile_img:'default_profile.jpg'}" alt=""></span>
                    <div data-following-id="${this.user.id}">
                        <span><a href="index.php?app-page=profile&username=${this.user.name}">${this.user.name}</a></span><br>
                        <span class="followSpan">${this.followBtnTpl()}</span>
                    </div>
                `;
		const _class = this,$user = $(this),$follow = $user.find('.followSpan');
		let fc = !this.searchMode ? followClick:searchModeClick;
		function followClick() {
			follow('false',_class.user.id).then((res)=>{
				if(res === 'false'){
					$user.remove();
				}

			});
		}
		function searchModeClick () {
			follow(!_class.followedByYou,_class.user.id).then((res)=>{
				console.log(res);
				_class.followedByYou = res == 'true';
				$user.find('.followSpan').html(_class.followBtnTpl());

			});
		}
		$follow.on('click', fc);

	}
}

window.customElements.define('user-item', UserItem);