class CRUD{
    constructor ($http, $state, $stateParams) {
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
    getArtList (pageNum,query) {
        this.$http.get('../class/DB.php?list=art&feedPage='+pageNum+'&art-len=1').then((res) => {
            this.artList = res.data['artArr'];
            this.artsLen = res.data['artLen'];
            this.artsQuan = res.data['artsQuan'];
            this.artPages = Math.ceil(this.artsLen / this.artsQuan);
            this.rangeArr = this.range(this.artPages-1);
/*            console.log(this.artList);
            console.log(res.data);
            console.log(this.rangeArr);*/
        });
    }
    getCommentersList () {
        this.$http.get('../class/DB.php?list=commenters').then((res) => {
            this.commentersList = res.data;
            console.log(this.commentersList);
        });
    }
    getReporterList (pageNum = 0,query='') {
        console.log(query);
        return this.$http.get('../class/DB.php?list=reporters&page='+pageNum+'&query='+query).then((res) => {
            //this.reportersList = res.data.commArr;
            return res.data;
        });
    }
    getTagsList(){
        this.$http.get('../class/DB.php?list=tags').then((res) => {
            this.tags = res.data.commArr;
        });
    }
    getComments (commArr, scope) {
        console.log(scope);
        console.log('hi');
        if (scope.actID == 1) {
            this.commArr = 'approvedComm';
        }
        else if (scope.actID == -1) {
            this.commArr = 'unapprovedComm';
        }
        else if (scope.actID == 0) {
            this.commArr = 'pendingComm';
        }
        else {
            this.commArr = 'allCommentsComm';
        }
        return new Promise((resolve, reject) => {
            let search = this.$stateParams['query'], come = scope.actID != undefined ? '&comemode=' + scope.actID : '';
            console.log(scope.pageNum, search);
            if (scope.pageNum != undefined) come += '&page=' + scope.pageNum;
            if (search) come += '&search=' + search;
            console.log(search);
            console.log(come);
            this.$http.get('../class/DB.php?list=comments' + come).then((res) => {
                this[this.commArr] = res.data.commArr;
                if (commArr == 'home') this.homeComments = res.data.commArr;
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
    changeCommStatus (scope, status, id, date) {
        console.log(scope);
        let grandScope = scope.$parent, allComPage;
        if (allComPage = scope.page == 'allComments') {
            if (scope['comment']['activated'] == status) return;
        }
        console.log(grandScope);
        this.$http.post('../class/DB.php', {
            act: 'update-com-status',
            'com-status': status,
            'comm-id': id,
            'comm-date': date
        }).then((res) => {
            console.log(res.data);
            if (!allComPage) {
                this[this['page'] + 'Comm'] = this[this['page'] + 'Comm'].filter(function (comm) {
                    return comm.id != id;
                });
                this[this['page'] + 'Comm'].push(res.data);
            } else {
                scope['comment']['activated'] = scope['comment']['activated'] == 1 ? -1 : 1;
            }
            this.rangeUpdate(grandScope);

        });
    }
    rangeUpdate (scope) {
        scope.pagesLen = Math.ceil((scope.totalLen) / this['commQuan']);
        console.log(scope.totalLen, this['commQuan'], scope.pagesLen);
        scope.rangeArr = scope.crud.range(scope.pagesLen);
        scope.crud.rangeArr = scope.rangeArr;
    }
    movePage (scope, num) {
        console.log(scope);
        console.log(num);
        console.log(scope.page);
        console.log(scope.query);
        if (scope.query) this.$state.go('CMS.' + scope.page, {pageNum: num, query: scope.query});
        else this.$state.go('CMS.' + scope.page, {pageNum: num});
    }
    range (n) {
			console.log(this.$stateParams['pageNum']);
			const pageNum = this.$stateParams['pageNum'];
			let arr = [], i = 1;
			console.log(n);
			console.log(n>9);

				if(n>9){
					while (i < n) {
						if((i > pageNum-5 && i < pageNum+5 )|| i >= n-3){
							arr.push(i);
						}
						i++;
					}
				}else {
					while (i < n) {
						arr.push(i);
						i++;
					}
				}
        return arr;
    }
    updateCommQuan (quan, scope) {
        console.log(this['commQuan']);
        this.$http({
            method: 'POST',
            url: '../class/DB.php',
            data: {
                act: 'update-comm-quan',
                'comm-quantity': quan
            }
        }).then((res) => {
            console.log(res.data);
            console.log(scope);
            this.getCommAndApply(scope, scope.page + 'Comm');
            /*
                            this.rangeUpdate(scope);
            */

        });
    }
    updateReptsQuan (quan, scope) {
        console.log(this['commQuan']);
        this.$http({
            method: 'POST',
            url: '../class/DB.php',
            data: {
                act: 'update-comm-quan',
                'comm-quantity': quan
            }
        }).then((res)=>{
            console.log(res);
            this.getReporterList(scope.pageNum).then((data)=>{
                console.log(data);
                scope.commArr = data.commArr;
                scope.totalLen = data.totalLen;
                scope.crud.commQuan = data.commQuan;
                console.log(data.commQuan);
                scope.crud.rangeUpdate(scope);
            });
        });

    }
    saveNewTags (scope) {
        let tags = scope['newTags'];

        this.$http.post('../class/DB.php', {
            newTags: tags
        }).then((res) => {
            console.log(res.data);
        });

        console.log();
    }
    getCommAndApply (scope, commArr) {
        console.log(scope);
        scope.crud.getComments(commArr, scope).then(() => {
            scope.commArr = scope.crud[commArr];
            scope.$watch('crud.' + commArr, function () {
                scope.commArr = scope.crud[commArr];
            });
            scope.pagination = true;
            scope.loaded = true;
            this.rangeUpdate(scope);
            scope.$apply();
        });
    }
    getTableQuan (table) {
        return this.$http({
            method: 'POST',
            url: '../class/DB.php',
            data: {
                act: 'get-'+ table +'-quan'
            }
        }).then(function (res) {
            console.log(res.data);
            return res.data;

        });
    }
    goSearch (scope, searchText) {
        this.$state.go('CMS.' + scope.page, {pageNum: scope.pageNum, query: searchText});
        if(scope.page !== 'allReporters')this.getCommAndApply(scope, scope.page + 'Comm', searchText);
    }
    goSearch1 (scope,searchText) {
        this.$state.go('CMS.' + scope.page, {pageNum: scope.pageNum, query: searchText});
    }
    deleteComm (scope, id, date, status, index) {
        console.log(index);
        console.log(id);
        this.$http.post('../class/DB.php', {
            act: 'delete-comm',
            'comm-id': id,
            'comm-index': index,
            'comm-date': date,
            'comm-status': status
        }).then((res) => {
            this[this['page'] + 'Comm'] = this[this['page'] + 'Comm'].filter(function (comm) {
                return comm.id != id;
            });
            this[this['page'] + 'Comm'].push(res.data);
            scope.totalLen--;
            console.log(res.data);
        });
    }
    expand (scope) {
        if (scope.article.desc) {
            scope.article.showDesc = !scope.article.showDesc;

        }
        else {
            this.$http.get('../class/DB.php?id=' + scope.article.id + '&getDesc=1').then((res) => {
                scope.article.desc = res.data;
                scope.article.showDesc = true;
                console.log(res.data);
            });
        }


    }
    update (scope) {
        console.log(scope);
        //console.log(scope.testFile.size > 1.5*1000000);
        let flag = 1;
        if(scope.testFile && scope.testFile.size > (1.5*1000000)){
            toastr["error"]('גודל התמונה הוא עד 1.5 מגה');flag = 0;}
        if(!scope.article.title){
            toastr["error"]('מלא/י את שדה הכותרת');flag = 0;}
        if(!scope.article.desc){
            toastr["error"]('מלא/י את שדה התוכן');flag = 0;}
        if(!flag) return;

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
        }).then((res) => {
            console.log(res.data);
            if(res.data.mes == 'true'){
                toastr["success"]("העלאה הושלמה");
                toastr["success"]("עדכון הכתבה הושלם");
                scope.article.frontImg = res.data.frontImgName;

            }else {
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
    cancelUpdate (scope) {
        scope.article.editMode = !scope.article.editMode;
        console.log(scope);
        console.log('ser:',this);
        scope.article = angular.copy(scope.article.backup);
    }
    saveArtAndToggle (scope) {
        scope.article.backup = angular.copy(scope.article);
        scope.editFImg = !scope.editFImg;
        scope.article.editMode = !scope.article.editMode;
    }
    delete (scope) {
    	console.log(scope.article.id);
    	console.log('hi');
        $.ajax({
            method: 'POST',
            url: '../class/DB.php',
            data: {
                deleteIt: 1,
                id: scope.article.id
            },
            success: (res) => {
                console.log(res);
                if (res === 'deleted') {
									this.artList = this.artList.filter(art => art.id != scope.article.id);
                    scope.$apply();
                }
            }
        });
    }
    getArtById (scope,id) {

        this.$http.get('../class/DB.php?act=get-art-by-id&art-id='+id+'&full-art=1').then(res => {
            if(res.data != "null"){
                this.editedArt = res.data;
                console.log(res.data);
                $('#summerNote').summernote('code',res.data['full-art']);
                scope.applyArt();
                console.log(res);
            }else {
                toastr["error"]('המאמר לא נמצא במערכת');
            }
        });
    }
    changeArtStatus  (artId,status) {
        console.log('go');
        console.log(status == 1?0:1);
        this.$http.post('../class/DB.php',{
            act: 'update-art-status',
            art_id: artId,
            art_status: status == 1?0:1
        }).then( (res) => {
            if(res.data == 'true'){
                this.editedArt.activated = status  == 1?0:1;
                toastr["success"]('סטטוס המאמר : מפורסם');
            }else{
                toastr["error"]('עדכון הסטטוס נכשל');

            }
            console.log(res.data);
            console.log('go');
        });
    }
    checkAvailability (field,check,val) {
        console.log(check);
        if(check.$valid){
            //console.log('check..');
            //['check_'+field]: val
            this.$http({
                method: 'post',
                url: '../class/Register.php',
                data: 'check_'+field+'='+val,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }).then((res)=>{
                console.log(res);
                if(field == 'email')this.emailAvailability = res.data == '0';
                if(field == 'uname') this.unameAvailability = res.data == '0';
            });
        }
    }




}
app.service('CRUD',CRUD);
app.service('errorHandler', function () {
    let errors = {
        logFailed: "הסיסמא או שם המשתמש אינם נכונים",
        usedEmail: "דואר זה כבר תפוס",
        unValidEmail: "הדואר אינו תקין",
        userMinLen: "שם המשתמש חייב להיות מעל 4 תווים",
        emptyUname: "שדה המשתמש אינו יכול להשאר ריק",
        emptyUemail: "שדה האימייל אינו יכול להשאר ריק",
        emptyUpass: "שדה הסיסמא אינו יכול להשאר ריק",
    };

    return {
        errors: errors,
        regErrors: [],
        logErrors: [],
        addMes(mes, act) {
            let arr = this[act + 'Errors'];
            arr = new Set(arr);
            console.log(mes, act);
            arr.add(mes);
            console.log(arr);
            this[act + 'Errors'] = Array.from(arr);
        },
        deleteMes(mes, act) {
            let arr = this[act + 'Errors'];
            arr = new Set(arr);
            arr.delete(mes);
            this[act + 'Errors'] = Array.from(arr);
        }

    }
});