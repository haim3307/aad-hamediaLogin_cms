let PostH = $('.grid-news-item').height();
let scrollFlag = 1;
postPage = 1;
$(window).scroll(function () {
	if (scrollFlag && $(window).scrollTop() + $(window).height() >= $(document).height() - (PostH * 3)) {
		console.log("load posts!");
		scrollFlag = 0;
		let q = postsQuery();
		console.log(q);
		$.ajax(q).then(function (res) {
			let posts = res;
			if (posts.length > 10 || posts.length === 0) return;
			PS.pushPosts(posts);
			scrollFlag = 1;

		});

	}
});

