let app = angular.module('admin', ["ui.router", "ng-file-model"]).run(($rootScope, $state) => {
	/*$http({
			method: 'POST',
			url: 'tpl/login/checkLogin.php',
			headers:{
					'Content-Type': 'application/x-www-form-urlencoded'
			}
	}).then(function (res) {
			console.log(res);
			if(res.data == '1'){
					console.log('yes');
					//$state.go('CMS.home', {});
					$rootScope.logged = true;
			}else {
					console.log('not logged');
			}
	});*/
	$rootScope.$on('$stateChangeStart', (event) => {
		console.log(event);
	});
	moment.locale('he');
});
app.config(($stateProvider, $urlRouterProvider) => {
	$urlRouterProvider.otherwise("/");
	$stateProvider
		.state('CMS', {
			templateUrl: "tpl/cms/cms.php",
			controller: function ($scope, $http, $state, $location, $rootScope, CRUD) {
				console.log('fuck');
				$scope.crud = CRUD;
				$scope.crud.getTableQuan().then(function (data) {
					$scope.crud.commQuan = data;
				});
				console.log($scope.crud.commQuan);
				$scope.logout = function () {
					$http({
						method: 'POST',
						url: 'tpl/login/logout.php',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded'
						}
					}).then(function (res) {
						$state.go('start.login', {});
						$rootScope.logged = false;
					});
				};
				console.log('CMS');
				$('.collpseableSide').children().on('click', function (e) {
					e.stopPropagation();
				});
				$('.link').on('click', function () {
					var $clicked = $(this).children('.collpseableSide');
					$('.collpseableSide').not($clicked).slideUp(500);
					$clicked.slideToggle(500);
				});
			}
		})
		.state('CMS.home', {
			url: '/',
			templateUrl: 'tpl/cms/home.html',
			controller: function ($scope, CRUD, $http) {
				$scope.crud = CRUD;
				$scope.crud.getArtList(0);
				$scope.crud.getCommentersList();
				$scope.actID = null;
				$http.get('../class/DB.php?list=comments&page=1').then(function (res) {
					$scope.homeComm = res.data.commArr;
					console.log(res.data.commArr);
				});
				//console.log($scope.crud);
			}
		})
		.state('CMS.new-article', {
			url: '/article/{artId:int}/{act:string}',
			params: {
				act: {value: 'new', squash: true},
				artId: {value: null, squash: true}
			},
			controller: function ($scope, $http, $stateParams) {
				$scope.action = $stateParams['act'];
				$scope.artId = $stateParams['artId'];
				$scope.applyArt = function () {
					$scope.art = {};
					$scope.art.artTitle = $scope.crud.editedArt.title;
					$scope.art.artReporter = $scope.crud.editedArt.reporterName;
					$scope.art.newArticleHTML = $scope.crud.editedArt.article;
					console.log($scope.art.description);
					console.log($scope.crud.editedArt.description);
					$scope.art.artDesc = $scope.crud.editedArt.description;
				};
				if ($scope.action === 'all-to-edit') {
					$scope.applyArt();
				}
				else if ($scope.action === 'edit') {
					if ($scope.artId) {
						$scope.crud.getArtById($scope, $scope.artId);
					}
					else {
						toastr["error"]('no articles ID');
					}
				}
				$scope.validateInput = function () {
					//if(!$scope.frontImg)
				};
				$scope.submitNewArticle = function (valid) {
					//if(!valid) return;
					var query = $('#formTitles').serialize();
					var summerHTML = $('#summerNote').summernote('code');
					console.log(query);
					//console.log(summerHTML);
					console.log($scope.frontImg);
					console.log($scope.art.tagSelect);
					console.log($scope.crud.editedArt.frontImg);
					var imageName = $scope.frontImg ? $scope.frontImg.name : $scope.crud.editedArt.frontImg;
					//if(!$scope.validateInput()) return;
					if ($scope.action == 'edit') {
						console.log($scope.crud.editedArt.article);
						$http({
							method: 'POST',
							url: '../class/DB.php?act=edit-art&' + query,
							data: {
								article: summerHTML,
								frontImg: $scope.frontImg,
								tags: $scope.art.tagSelect,
								artFileName: $scope.crud.editedArt.article,
								frontImgName: imageName,
								artId: $scope.crud.editedArt.id
							},
						}).then(function (res) {
							console.log(res);
							console.log(res.data);
							if (res.data == 'update success')
								toastr["success"]('המאמר נערך בהצלחה!');
							else if (res.data == 'query failed')
								toastr["error"]('יצירת המאמר נכשלה , כשל במאגר הנתונים');
							else {
								toastr["error"]('עריכת המאמר נכשלה');
							}
						});
					}
					else {
						$http({
							method: 'POST',
							url: '../class/DB.php?act=create-art&' + query,
							data: {
								article: summerHTML,
								frontImg: $scope.frontImg,
								tags: $scope.art.tagSelect
							},
						}).then(function (res) {
							console.log(res);
							console.log(res.data);
							if (res.data == 'upload success')
								toastr["success"]('המאמר נוצר בהצלחה!');
							else if (res.data == 'query failed')
								toastr["error"]('יצירת המאמר נכשלה , כשל במאגר הנתונים');
							else {
								toastr["error"]('יצירת המאמר נכשלה');
							}
						});
					}
				};
				$scope.tagSet = new Set();
				$scope.removeTag = function (val) {
					$scope.tagSet.delete(val);
					$scope.displayTags = Array.from($scope.tagSet);
				};
				$scope.displayTags = [];
				$('#summerNote').summernote({
					placeHolder: 'כתוב את המאמר שלך',
					tabsize: 2,
					height: 300,
					onImageUpload: function (files, editor, $editable) {
						console.log('hi');
						var data = new FormData();
						data.append('summer_file', files);
						data.append('user', 'haim307');
						$.ajax({
							url: "../class/DB.php",
							dataType: 'script',
							cache: false,
							contentType: false,
							processData: false,
							data: data,
							type: "POST",
							success: function (result) {
								console.log(result);
								$('#summernote').summernote("insertImage", data, 'filename');
							}
						});
					}
				});
				/*$scope.uploadFile = function ($event) {
						console.log($('#formTitles').serialize());
						let a = $('#formTitles').serialize();
				};*/
			},
			templateUrl: 'tpl/cms/articles/create_or_edit_article.html'
		})
		.state('CMS.articles', {
			url: '/articles/{pageNum:int}/{query}',
			params: {
				pageNum: {value: 0, squash: true},
				query: {value: null, squash: true}
			},
			controller: function ($scope, CRUD, $http, $stateParams) {
				console.log($stateParams['pageNum']);
				$scope.crud = CRUD;
				$scope.crud.getArtList($stateParams['pageNum']);
				$scope.editFImg = false;
				$scope.saveImage = function ($event) {
					alert('hi');
					console.log($event.target.file);
					$scope.newFront = $event.target.file;
				};
				$scope.fullEdit = function (obj) {
					$scope.crud.editedArt = obj;
				};
			},
			templateUrl: 'tpl/cms/articles/articles.html'
		})
		.state('CMS.pages', {
			url: '/pages/',
			params: {},
			controller: function ($scope, CRUD, $http, $stateParams) {
				$scope.crud = CRUD;
				$scope.getCateIdLists = function () {
					$http.get('../class/DB.php?list=cates_id_lists').then((res) => {
						$scope.selectedArts = res.data;
						console.log(res.data);
					});
				};
				$scope.getCateIdLists();
				$scope.quickArtSearch = (e) => {
					let val = e.target.value;
					console.log(val);
					$http.get(`../class/DB.php?act=quick-art-search&qt=${val}`).then((res) => {
						console.log(res);
						$scope.quickResults = res.data;
					});
				};

				$scope.quickResults = [];
				$scope.selectedArts = [];
				$scope.tab = 'search';
				$scope.addToList = (item,category) => {
					let map = new Map($scope.selectedArts[category]);
					map.set(item.id, item);
					$scope.selectedArts[category] = Array.from(map);

				};
				$scope.sendSelectedCates = (cate) => {
					//console.log($("#sortable"+cate));

					let idsInOrder = $('#sortable'+cate).sortable("toArray");
				$http.get(`../class/DB.php?act=update-arts-id&order-ids=${idsInOrder}&category=${cate}`).then((res) => {
					console.log(res);
					console.log(res.data);
					//$scope.quickResults = res.data;
					let oldArr = new Map($scope.selectedArts[cate]),newArr = [];
					for(let x=0; x<oldArr.size;x++){
						let oldItem = oldArr.get(idsInOrder[x]);
						newArr.push([oldItem.id,oldItem]);
					}
					$scope.selectedArts[cate] = newArr;
				});
				console.log(idsInOrder);
				};
				$scope.titlesCategories = ['politics','economy','tech'];
				//console.log($el);
				setTimeout( ()=>{
					const elList = $(".sortable");
					for(let el of elList){
						$(el).sortable();
						$(el).disableSelection();
					}
					},1000 );
				$scope.deleteCateArt = (id,cate,cateID) => {
					$http.delete(`../class/DB.php?act=delete-cate-art&art-id=${id}&category=${cateID}`).then((res) => {
						console.log(res.data);
						let map = new Map($scope.selectedArts[cate]);
						map.delete(id);
						//let oldArr = new Map($scope.selectedArts[cate]),newArr = [];
/*						for(let x=0; x<oldArr.size;x++){
							let oldItem = oldArr.get(idsInOrder[x]);
							newArr.push([oldItem.id,oldItem]);
						}*/
						$scope.selectedArts[cate] = Array.from(map);
					});
				};

			},
			templateUrl: 'tpl/cms/pages/titles.html'
		})
		.state('CMS.allComments', {
			url: '/comments/{pageNum:int}/{query}',
			params: {
				pageNum: {value: 1, squash: true},
				query: {value: null, squash: true}
			},
			controller: function ($scope, CRUD, $stateParams) {
				$scope.pageNum = $stateParams['pageNum'];
				$scope.query = $stateParams['query'];
				console.log($stateParams['query']);
				$scope.crud = CRUD;
				$scope.crud.page = 'allComments';
				$scope.page = 'allComments';
				$scope.actID = null;
				$scope.crud.getTableQuan('comm').then(function (res) {
					$scope.crud.commQuan = res;
					console.log($scope.query);
					if ($scope.query) $scope.crud.goSearch($scope, $scope.query);
					else $scope.crud.getCommAndApply($scope, $scope.page + 'Comm');
				});
				console.log($scope);
			},
			templateUrl: 'tpl/cms/comments/comments.template.html',
		})
		.state('CMS.approved', {
			url: '/comments/approved/{pageNum:int}/{query}',
			params: {
				pageNum: {value: 1, squash: true},
				query: {value: null, squash: true}
			},
			controller: function ($scope, CRUD, $stateParams) {
				$scope.pageNum = $stateParams['pageNum'];
				$scope.crud = CRUD;
				$scope.crud.page = 'approved';
				$scope.page = 'approved';
				$scope.actID = 1;
				$scope.crud.getTableQuan('comm').then(function (res) {
					$scope.crud.commQuan = res;
					$scope.crud.getCommAndApply($scope, $scope.page + 'Comm');
				});
			},
			templateUrl: 'tpl/cms/comments/comments.template.html',
		})
		.state('CMS.unapproved', {
			url: '/comments/unapproved/{pageNum:int}/{query}',
			params: {
				pageNum: {value: 1, squash: true},
				query: {value: null, squash: true}
			},
			controller: function ($scope, CRUD, $stateParams) {
				$scope.pageNum = $stateParams['pageNum'];
				$scope.crud = CRUD;
				$scope.actID = -1;
				$scope.crud.page = 'unapproved';
				$scope.page = 'unapproved';
				$scope.crud.getTableQuan('comm').then(function (res) {
					$scope.crud.commQuan = res;
					$scope.crud.getCommAndApply($scope, $scope.page + 'Comm');
				});
			},
			templateUrl: 'tpl/cms/comments/comments.template.html'
		})
		.state('CMS.pending', {
			url: '/comments/pending-comments/{pageNum:int}/{query}',
			params: {
				pageNum: {value: 1, squash: true},
				query: {value: null, squash: true}
			},
			controller: function ($scope, CRUD, $stateParams) {
				$scope.pageNum = $stateParams['pageNum'];
				$scope.crud = CRUD;
				$scope.crud.page = 'pending';
				$scope.page = 'pending';
				$scope.actID = 0;
				$scope.crud.getTableQuan('comm').then((res) => {
					$scope.crud.commQuan = res;
					$scope.crud.getCommAndApply($scope, $scope.page + 'Comm');
				});
			},
			templateUrl: 'tpl/cms/comments/comments.template.html'
		})
		.state('CMS.allReporters', {
			url: '/reporters/{pageNum:int}/{query}',
			params: {
				pageNum: {value: 0, squash: true},
				query: {value: '', squash: true}
			},
			controller: function ($scope, CRUD, $stateParams) {
				$scope.crud = CRUD;
				$scope.crud.page = 'allReporters';
				$scope.page = 'allReporters';
				$scope.pageNum = $stateParams['pageNum'];
				$scope.query = $stateParams['query'];
				$scope.crud.getReporterList($scope.pageNum, $scope.query).then((data) => {
					$scope.commArr = data.commArr;
					$scope.loaded = true;
					console.log(data);
					$scope.pagination = true;
					$scope.totalLen = data.totalLen;
					$scope.crud.commQuan = data.commQuan;
					$scope.crud.rangeUpdate($scope);
				});
			},
			templateUrl: 'tpl/cms/reporters/all_reporters.html'
		})
		.state('CMS.reporter', {
			url: '/reporter/{repId:int}/{query}',
			params: {
				repId: {value: null, squash: true},
				query: {value: null, squash: true}
			},
			controller: function ($scope, CRUD, $stateParams) {
				$scope.pageNum = $stateParams['repId'];
				$scope.crud = CRUD;
				$scope.crud.page = 'reporter';
				$scope.page = 'reporter';
				$scope.log = (req) => console.log(req);
			},
			templateUrl: 'tpl/cms/reporters/add_or_edit_reporter.html'
		})
		.state('CMS.tags', {
			url: '/tags',
			templateUrl: 'tpl/cms/tags.html',
			controller: function ($scope, CRUD) {
				$scope.crud = CRUD;
				$scope.crud.getTagsList();
			}
		})
		.state('CMS.commenters', {
			url: '/commenters',
			templateUrl: 'tpl/cms/commenters.html',
			controller: function ($scope, CRUD) {
				$scope.crud = CRUD;
				$scope.crud.getCommentersList();
			},
		})
		.state('CMS.settings', {
			url: '/settings',
			templateUrl: 'tpl/cms/settings.html'
		})
});
app.filter('myDate', () => date => moment(date, "YYYY-MM-DD").fromNow());
app.filter('longDate', () => (date) => moment(date).format('LLLL'));
app.filter('status', () => (status) => status == 1 ? 'פעיל' : 'לא פעיל');
