'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var CRUD = function () {
    function CRUD($http, $state, $stateParams) {
        _classCallCheck(this, CRUD);

        this.editedArt = {};
        this.email_exp = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i;
        this.private_ph_exp = /^0[2-9]\d{7,8}$/;
        this.uname_exp = /^(?=.{8,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+$/;
        this.pass_exp = /^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/;
        this.artList = [];
        this.$http = $http;
        this.$state = $state;
        this.$stateParams = $stateParams;
    }

    _createClass(CRUD, [{
        key: 'getArtList',
        value: function getArtList(pageNum, query) {
            var _this = this;

            this.$http.get('../class/DB.php?list=art&feedPage=' + pageNum + '&art-len=1').then(function (res) {
                _this.artList = res.data['artArr'];
                _this.artsLen = res.data['artLen'];
                _this.artsQuan = res.data['artsQuan'];
                _this.artPages = Math.ceil(_this.artsLen / _this.artsQuan);
                _this.rangeArr = _this.range(_this.artPages - 1);
                /*            console.log(this.artList);
                            console.log(res.data);
                            console.log(this.rangeArr);*/
            });
        }
    }, {
        key: 'getCommentersList',
        value: function getCommentersList() {
            var _this2 = this;

            this.$http.get('../class/DB.php?list=commenters').then(function (res) {
                _this2.commentersList = res.data;
                console.log(_this2.commentersList);
            });
        }
    }, {
        key: 'getReporterList',
        value: function getReporterList() {
            var pageNum = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
            var query = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';

            console.log(query);
            return this.$http.get('../class/DB.php?list=reporters&page=' + pageNum + '&query=' + query).then(function (res) {
                //this.reportersList = res.data.commArr;
                return res.data;
            });
        }
    }, {
        key: 'getTagsList',
        value: function getTagsList() {
            var _this3 = this;

            this.$http.get('../class/DB.php?list=tags').then(function (res) {
                _this3.tags = res.data.commArr;
            });
        }
    }, {
        key: 'getComments',
        value: function getComments(commArr, scope) {
            var _this4 = this;

            console.log(scope);
            console.log('hi');
            if (scope.actID == 1) {
                this.commArr = 'approvedComm';
            } else if (scope.actID == -1) {
                this.commArr = 'unapprovedComm';
            } else if (scope.actID == 0) {
                this.commArr = 'pendingComm';
            } else {
                this.commArr = 'allCommentsComm';
            }
            return new Promise(function (resolve, reject) {
                var search = _this4.$stateParams['query'],
                    come = scope.actID != undefined ? '&comemode=' + scope.actID : '';
                console.log(scope.pageNum, search);
                if (scope.pageNum != undefined) come += '&page=' + scope.pageNum;
                if (search) come += '&search=' + search;
                console.log(search);
                console.log(come);
                _this4.$http.get('../class/DB.php?list=comments' + come).then(function (res) {
                    _this4[_this4.commArr] = res.data.commArr;
                    if (commArr == 'home') _this4.homeComments = res.data.commArr;
                    scope.totalLen = res.data.totalLen;
                    console.log(res.data);

                    if (search != undefined) {
                        scope.searchMode = true;
                        scope.resultNum = res.data['totalLen'];
                    }
                    resolve();
                });
            });
        }
    }, {
        key: 'changeCommStatus',
        value: function changeCommStatus(scope, status, id, date) {
            var _this5 = this;

            console.log(scope);
            var grandScope = scope.$parent,
                allComPage = void 0;
            if (allComPage = scope.page == 'allComments') {
                if (scope['comment']['activated'] == status) return;
            }
            console.log(grandScope);
            this.$http.post('../class/DB.php', {
                act: 'update-com-status',
                'com-status': status,
                'comm-id': id,
                'comm-date': date
            }).then(function (res) {
                console.log(res.data);
                if (!allComPage) {
                    _this5[_this5['page'] + 'Comm'] = _this5[_this5['page'] + 'Comm'].filter(function (comm) {
                        return comm.id != id;
                    });
                    _this5[_this5['page'] + 'Comm'].push(res.data);
                } else {
                    scope['comment']['activated'] = scope['comment']['activated'] == 1 ? -1 : 1;
                }
                _this5.rangeUpdate(grandScope);
            });
        }
    }, {
        key: 'rangeUpdate',
        value: function rangeUpdate(scope) {
            scope.pagesLen = Math.ceil(scope.totalLen / this['commQuan']);
            console.log(scope.totalLen, this['commQuan'], scope.pagesLen);
            scope.rangeArr = scope.crud.range(scope.pagesLen);
            scope.crud.rangeArr = scope.rangeArr;
        }
    }, {
        key: 'movePage',
        value: function movePage(scope, num) {
            console.log(scope);
            console.log(num);
            console.log(scope.page);
            console.log(scope.query);
            if (scope.query) this.$state.go('CMS.' + scope.page, { pageNum: num, query: scope.query });else this.$state.go('CMS.' + scope.page, { pageNum: num });
        }
    }, {
        key: 'range',
        value: function range(n) {
            console.log(this.$stateParams['pageNum']);
            var pageNum = this.$stateParams['pageNum'];
            var arr = [],
                i = 1;
            console.log(n);
            console.log(n > 9);

            if (n > 9) {
                while (i < n) {
                    if (i > pageNum - 5 && i < pageNum + 5 || i >= n - 3) {
                        arr.push(i);
                    }
                    i++;
                }
            } else {
                while (i < n) {
                    arr.push(i);
                    i++;
                }
            }
            return arr;
        }
    }, {
        key: 'updateCommQuan',
        value: function updateCommQuan(quan, scope) {
            var _this6 = this;

            console.log(this['commQuan']);
            this.$http({
                method: 'POST',
                url: '../class/DB.php',
                data: {
                    act: 'update-comm-quan',
                    'comm-quantity': quan
                }
            }).then(function (res) {
                console.log(res.data);
                console.log(scope);
                _this6.getCommAndApply(scope, scope.page + 'Comm');
                /*
                                this.rangeUpdate(scope);
                */
            });
        }
    }, {
        key: 'updateReptsQuan',
        value: function updateReptsQuan(quan, scope) {
            var _this7 = this;

            console.log(this['commQuan']);
            this.$http({
                method: 'POST',
                url: '../class/DB.php',
                data: {
                    act: 'update-comm-quan',
                    'comm-quantity': quan
                }
            }).then(function (res) {
                console.log(res);
                _this7.getReporterList(scope.pageNum).then(function (data) {
                    console.log(data);
                    scope.commArr = data.commArr;
                    scope.totalLen = data.totalLen;
                    scope.crud.commQuan = data.commQuan;
                    console.log(data.commQuan);
                    scope.crud.rangeUpdate(scope);
                });
            });
        }
    }, {
        key: 'saveNewTags',
        value: function saveNewTags(scope) {
            var tags = scope['newTags'];

            this.$http.post('../class/DB.php', {
                newTags: tags
            }).then(function (res) {
                console.log(res.data);
            });

            console.log();
        }
    }, {
        key: 'getCommAndApply',
        value: function getCommAndApply(scope, commArr) {
            var _this8 = this;

            console.log(scope);
            scope.crud.getComments(commArr, scope).then(function () {
                scope.commArr = scope.crud[commArr];
                scope.$watch('crud.' + commArr, function () {
                    scope.commArr = scope.crud[commArr];
                });
                scope.pagination = true;
                scope.loaded = true;
                _this8.rangeUpdate(scope);
                scope.$apply();
            });
        }
    }, {
        key: 'getTableQuan',
        value: function getTableQuan(table) {
            return this.$http({
                method: 'POST',
                url: '../class/DB.php',
                data: {
                    act: 'get-' + table + '-quan'
                }
            }).then(function (res) {
                console.log(res.data);
                return res.data;
            });
        }
    }, {
        key: 'goSearch',
        value: function goSearch(scope, searchText) {
            this.$state.go('CMS.' + scope.page, { pageNum: scope.pageNum, query: searchText });
            if (scope.page !== 'allReporters') this.getCommAndApply(scope, scope.page + 'Comm', searchText);
        }
    }, {
        key: 'goSearch1',
        value: function goSearch1(scope, searchText) {
            this.$state.go('CMS.' + scope.page, { pageNum: scope.pageNum, query: searchText });
        }
    }, {
        key: 'deleteComm',
        value: function deleteComm(scope, id, date, status, index) {
            var _this9 = this;

            console.log(index);
            console.log(id);
            this.$http.post('../class/DB.php', {
                act: 'delete-comm',
                'comm-id': id,
                'comm-index': index,
                'comm-date': date,
                'comm-status': status
            }).then(function (res) {
                _this9[_this9['page'] + 'Comm'] = _this9[_this9['page'] + 'Comm'].filter(function (comm) {
                    return comm.id != id;
                });
                _this9[_this9['page'] + 'Comm'].push(res.data);
                scope.totalLen--;
                console.log(res.data);
            });
        }
    }, {
        key: 'expand',
        value: function expand(scope) {
            if (scope.article.desc) {
                scope.article.showDesc = !scope.article.showDesc;
            } else {
                this.$http.get('../class/DB.php?id=' + scope.article.id + '&getDesc=1').then(function (res) {
                    scope.article.desc = res.data;
                    scope.article.showDesc = true;
                    console.log(res.data);
                });
            }
        }
    }, {
        key: 'update',
        value: function update(scope) {
            console.log(scope);
            //console.log(scope.testFile.size > 1.5*1000000);
            var flag = 1;
            if (scope.testFile && scope.testFile.size > 1.5 * 1000000) {
                toastr["error"]('גודל התמונה הוא עד 1.5 מגה');flag = 0;
            }
            if (!scope.article.title) {
                toastr["error"]('מלא/י את שדה הכותרת');flag = 0;
            }
            if (!scope.article.desc) {
                toastr["error"]('מלא/י את שדה התוכן');flag = 0;
            }
            if (!flag) return;

            this.$http({
                method: 'POST',
                url: '../class/DB.php',
                data: {
                    act: 'update-art-front',
                    artTitle: scope.article.title,
                    artId: scope.article.id,
                    artContent: scope.article.desc,
                    artFrontImg: scope.testFile,
                    artOldFrontImg: scope.article.frontImg
                }
            }).then(function (res) {
                console.log(res.data);
                if (res.data.mes == 'true') {
                    toastr["success"]("העלאה הושלמה");
                    toastr["success"]("עדכון הכתבה הושלם");
                    scope.article.frontImg = res.data.frontImgName;
                } else {
                    toastr["error"]("העלאה נכשלה");
                    toastr["error"]("עדכון הכתבה נכשל");
                }
                console.log(scope);
                console.log();
                scope.article.editMode = false;
                //scope.editMode = !scope.editMode;
            });
            /*
            $.ajax({
                method: 'POST',
                url: '../class/DB.php',
                data: {
                    title: scope.article.title,
                    id: scope.article.id
                },
                success: (res) => {
                    if (res === 'true') {
                      }
                }
            });*/
            //scope.editMode = !scope.editMode;
            console.log(scope.article.title);
        }
    }, {
        key: 'cancelUpdate',
        value: function cancelUpdate(scope) {
            scope.article.editMode = !scope.article.editMode;
            console.log(scope);
            console.log('ser:', this);
            scope.article = angular.copy(scope.article.backup);
        }
    }, {
        key: 'saveArtAndToggle',
        value: function saveArtAndToggle(scope) {
            scope.article.backup = angular.copy(scope.article);
            scope.editFImg = !scope.editFImg;
            scope.article.editMode = !scope.article.editMode;
        }
    }, {
        key: 'delete',
        value: function _delete(scope) {
            var _this10 = this;

            console.log(scope.article.id);
            console.log('hi');
            $.ajax({
                method: 'POST',
                url: '../class/DB.php',
                data: {
                    deleteIt: 1,
                    id: scope.article.id
                },
                success: function success(res) {
                    console.log(res);
                    if (res === 'deleted') {
                        _this10.artList = _this10.artList.filter(function (art) {
                            return art.id != scope.article.id;
                        });
                        scope.$apply();
                    }
                }
            });
        }
    }, {
        key: 'getArtById',
        value: function getArtById(scope, id) {
            var _this11 = this;

            this.$http.get('../class/DB.php?act=get-art-by-id&art-id=' + id + '&full-art=1').then(function (res) {
                if (res.data != "null") {
                    _this11.editedArt = res.data;
                    console.log(res.data);
                    $('#summerNote').summernote('code', res.data['full-art']);
                    scope.applyArt();
                    console.log(res);
                } else {
                    toastr["error"]('המאמר לא נמצא במערכת');
                }
            });
        }
    }, {
        key: 'changeArtStatus',
        value: function changeArtStatus(artId, status) {
            var _this12 = this;

            console.log('go');
            console.log(status == 1 ? 0 : 1);
            this.$http.post('../class/DB.php', {
                act: 'update-art-status',
                art_id: artId,
                art_status: status == 1 ? 0 : 1
            }).then(function (res) {
                if (res.data == 'true') {
                    _this12.editedArt.activated = status == 1 ? 0 : 1;
                    toastr["success"]('סטטוס המאמר : מפורסם');
                } else {
                    toastr["error"]('עדכון הסטטוס נכשל');
                }
                console.log(res.data);
                console.log('go');
            });
        }
    }, {
        key: 'checkAvailability',
        value: function checkAvailability(field, check, val) {
            var _this13 = this;

            console.log(check);
            if (check.$valid) {
                //console.log('check..');
                //['check_'+field]: val
                this.$http({
                    method: 'post',
                    url: '../class/Register.php',
                    data: 'check_' + field + '=' + val,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                }).then(function (res) {
                    console.log(res);
                    if (field == 'email') _this13.emailAvailability = res.data == '0';
                    if (field == 'uname') _this13.unameAvailability = res.data == '0';
                });
            }
        }
    }]);

    return CRUD;
}();

app.service('CRUD', CRUD);
app.service('errorHandler', function () {
    var errors = {
        logFailed: "הסיסמא או שם המשתמש אינם נכונים",
        usedEmail: "דואר זה כבר תפוס",
        unValidEmail: "הדואר אינו תקין",
        userMinLen: "שם המשתמש חייב להיות מעל 4 תווים",
        emptyUname: "שדה המשתמש אינו יכול להשאר ריק",
        emptyUemail: "שדה האימייל אינו יכול להשאר ריק",
        emptyUpass: "שדה הסיסמא אינו יכול להשאר ריק"
    };

    return {
        errors: errors,
        regErrors: [],
        logErrors: [],
        addMes: function addMes(mes, act) {
            var arr = this[act + 'Errors'];
            arr = new Set(arr);
            console.log(mes, act);
            arr.add(mes);
            console.log(arr);
            this[act + 'Errors'] = Array.from(arr);
        },
        deleteMes: function deleteMes(mes, act) {
            var arr = this[act + 'Errors'];
            arr = new Set(arr);
            arr.delete(mes);
            this[act + 'Errors'] = Array.from(arr);
        }
    };
});
//# sourceMappingURL=services.js.map