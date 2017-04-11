angular.module('dachutimes', ['ionic', 'dachutimes.controllers'])

.run(function ($ionicPlatform, $http, $state, $rootScope, $ionicModal, $ionicPopup, $ionicLoading, $ionicActionSheet, $ionicHistory) {

//  $rootScope.siteUrl = "http://kuyxxword.ourapp.site:666";
    ////正式数据
    $rootScope.siteUrl = "http://xx.kaouyu.com";
    $rootScope.rootUrl = $rootScope.siteUrl + "/index.php/api";
    
    $ionicPlatform.ready(function () {
        initCordova();
    });

    InitIonic($rootScope, $ionicModal, $ionicPopup, $ionicLoading, $http, $state);

})

.config(function ($stateProvider, $urlRouterProvider, $ionicConfigProvider) {

    $ionicConfigProvider.views.swipeBackEnabled(false);
    $ionicConfigProvider.tabs.position('bottom'); //bottom
    $ionicConfigProvider.tabs.style('standard');

    $stateProvider

    .state('word_detail', {
        url: '/word_detail/:book_id/:unit_id/:word',
        templateUrl: 'templates/dy_word_detail.html',
        controller: 'word_detailCtrl'
    })

    .state('word_exercise', {
        url: '/word_exercise/:book_id/:unit_id/:word',
        templateUrl: 'templates/dy_word_exercise.html',
        controller: 'word_exerciseCtrl'
    })
    
    .state('wx_word_list', {
        url: '/wx_word_list/:book_id/:unit_id/:type',
        templateUrl: 'templates/wx_word_list.html',
        controller: 'wx_word_listCtrl'
    })

    //#region
	.state('more_apps', {
		url: '/more_apps',
		templateUrl: 'templates/more_apps.html',
		controller: 'more_appsCtrl'
	})
	//#endregion	

    .state('word_list', {
        url: '/word_list/:book_id/:unit_id',
        templateUrl: 'templates/word_list.html',
        controller: 'word_listCtrl'
    })


    //$urlRouterProvider.otherwise('/grade');
});

//#region Init Cordova 
function initCordova() {
    if (window.cordova && window.cordova.plugins.Keyboard) {
        cordova.plugins.Keyboard.hideKeyboardAccessoryBar(false);
        cordova.plugins.Keyboard.disableScroll(true);
    };
    if (window.StatusBar) {
        StatusBar.styleDefault();
    };
};
//#endregion

//#region Init Ionic
function InitIonic($rootScope, $ionicModal, $ionicPopup, $ionicLoading, $http, $state) {
    $rootScope.LoadingShow = function () {
        $ionicLoading.show({
            template: '<ion-spinner icon="spiral"></ion-spinner>'
        });
    };

    $rootScope.LoadingHide = function () {
        $ionicLoading.hide();
    };

    $rootScope.Alert = function (msg, okFunc) {
        var alertPopup = $ionicPopup.alert({
            template: msg,
            okText: '确定',
            okType: 'button-clear button-calm',
            cssClass: 'dc-popup'
        });

        if (okFunc) {
            alertPopup.then(function (res) {
                okFunc();
            });
        }
    };

    $rootScope.Confirm = function (msg, okText, cancelText, okFunc, cancelFunc) {
        var confirmPopup = $ionicPopup.confirm({
            template: msg,
            okText: (okText ? okText : '确定'),
            okType: 'button-clear button-assertive',
            cancelText: (cancelText ? cancelText : '取消'),
            cancelType: 'button-clear button-calm',
            cssClass: 'dc-popup'
        });
        confirmPopup.then(function (res) {
            if (res) {
                okFunc();
            } else {
                cancelFunc();
            }
        });
    };
    
    
    $rootScope.playWebWord = function(audio) {
		var v = document.getElementById("audio");
		v.src = $rootScope.siteUrl + "/upload/word/mp3/" + audio;
		v.loop = false;
		v.play();
	}
    
  
    $rootScope.playWord = function(audio, obj) {
	
		var v = document.getElementById("audio");
		v.src = $rootScope.siteUrl + "/upload/word/mp3/" + audio;
		v.loop = false;
		v.addEventListener('ended', function() {
			obj.attr("src", "img/xiaoxue_cut_07.png");
		}, false);
		v.play();
		obj.attr("src", "img/play_gif.gif");
	}
    
    $rootScope.playExercise = function(audio, obj) {
		var v = document.getElementById("audio");
		v.src = $rootScope.siteUrl + "/upload/exercise/mp3/" + audio;
		v.loop = false;
		v.addEventListener('ended', function() {
			obj.attr("src", "img/xiaoxue_cut_07.png");
		}, false);
		v.play();
		obj.attr("src", "img/play_gif.gif");
	}
    
};
//#endregion

//#region localStorage
function setStorage(key, value, isString) {
    if (isString) {
        window.localStorage[key] = value;
    } else {
        window.localStorage[key] = JSON.stringify(value);
    }
};

function getStorage(key, isString) {
    if (isString) {
        return window.localStorage[key] || '';
    } else {
        return JSON.parse(window.localStorage[key] || '{}');
    }
};
//#endregion

//#region img => base64
function convertImgToBase64(url, callback, outputFormat) {
    var canvas = document.createElement('CANVAS'),
		ctx = canvas.getContext('2d'),
		img = new Image;
    img.crossOrigin = 'Anonymous';
    img.onload = function () {
        canvas.height = img.height;
        canvas.width = img.width;
        ctx.drawImage(img, 0, 0);
        var dataURL = canvas.toDataURL(outputFormat || 'image/png');
        callback.call(this, dataURL);
        canvas = null;
    };
    img.src = url;
};
//#endregion

//#region Common
function isFloat(s) {
    return !isNaN(s);
}

function isEmail(s) {
    if (s.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1)
        return true;
    else
        return false;
}

function sortNameASC(x, y) {
    return (parseFloat(x.name) > parseFloat(y.name)) ? 1 : -1;
}

function sortTimeDESC(x, y) {
    return (Date.parse(x.time) > Date.parse(y.time)) ? -1 : 1;
}

function getNextId(a) {
    var m = 0;

    for (i = 0; i < a.length; i++) {
        if (a[i].id > m) {
            m = a[i].id;
        }
    }

    return (m + 1);
}

function getItemById(a, id) {
    var m = {};
    for (i = 0; i < a.length; i++) {
        if (a[i].id == id) {
            m = a[i];

            break;
        }
    }
    return m;
}

function DateAdd(d, day) {
    return new Date(Date.parse(d) + (86400000 * day));
}

function DateDiff(s, e) {
    //return new Date( + (86400000 * day));

    return (Date.parse(s) - Date.parse(e)) / 86400000;
}

function DateDiff2(s, e) {

    var s0 = new Date(Date.parse(s));
    var s1 = new Date();;
    s1.setFullYear(s0.getFullYear());
    s1.setMonth(s0.getMonth());
    s1.setDate(s0.getDate());

    var e0 = new Date(Date.parse(e));
    var e1 = new Date();;
    e1.setFullYear(e0.getFullYear());
    e1.setMonth(e0.getMonth());
    e1.setDate(e0.getDate());

    return (s1 - e1) / 86400000;
}

function DateFormat(d) {
    return d.getFullYear() + "年" + PadLeft((d.getMonth() + 1), 2) + "月" + PadLeft(d.getDate(), 2) + "日" + "  星期" + "日一二三四五六".charAt(d.getDay());
}

function PadLeft(v, length) {
    var s = "000000000" + v;
    return s.substr(s.length - length);
}

function versionId(v) {
    if (!v) {
        return 0;
    } else {
        v = v.replace(/\./g, '');

        return parseFloat(v);
    }
}

function CurentTime() {
    var now = new Date();

    var year = now.getFullYear(); //年  
    var month = now.getMonth() + 1; //月  
    var day = now.getDate(); //日  

    var hh = now.getHours(); //时  
    var mm = now.getMinutes(); //分  
    var ss = now.getSeconds(); //秒  

    var clock = year + "-";

    if (month < 10) clock += "0";
    clock += month + "-";

    if (day < 10) clock += "0";
    clock += day + " ";

    if (hh < 10) clock += "0";
    clock += hh + ":";

    if (mm < 10) clock += '0';
    clock += mm + ":";

    if (ss < 10) clock += '0';
    clock += ss;

    return (clock);
}
//#endregion

function playAudio(f) {
    var v = document.getElementById("audio");

    v.src = (f ? "audio/correct.mp3" : "audio/error.mp3");
    v.play();
}

function setTitle(title) {
    var body = document.getElementsByTagName('body')[0];
    document.title = title;
    var iframe = document.createElement("iframe");
    iframe.setAttribute("src", "img/transparent.png");
    iframe.addEventListener('load', function () {
        setTimeout(function () {
            iframe.removeEventListener('load');
            document.body.removeChild(iframe);
        }, 0);
    });
    document.body.appendChild(iframe);
}

//JS判断数组中是否包含某一项
Array.prototype.contains = function (obj) {
    var i = this.length;
    while (i--) {
        if (this[i] === obj) {
            return true;
        }
    }
    return false;
}