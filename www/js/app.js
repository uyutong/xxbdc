angular.module('dachutimes', ['ionic', 'dachutimes.controllers', 'ngCordova'])

	.run(function($ionicPlatform, $http, $state, $rootScope, $ionicModal, $ionicPopup, $ionicLoading, $ionicActionSheet) {

		//$rootScope.siteUrl = "http://kuyxxword.ourapp.site:666";
		$rootScope.siteUrl = "http://xx.kaouyu.com";
		//$rootScope.rootUrl = "http://kuyxxword.ourapp.site:666/index.php/api";
		$rootScope.rootUrl = "http://xx.kaouyu.com/index.php/api";
		$rootScope.bookId = 4;
		$rootScope.bookName = "三年级上册";
		$rootScope.bookPublish = "（人教版 三年级起点）";
		////正式数据
		//$rootScope.rootUrl = "http://kuyxxword.ourapp.site:66/index.php/api";
		$ionicPlatform.ready(function() {
		    initCordova();

		    cordova.getAppVersion.getVersionNumber(function (version) {
		        $rootScope.currentVersion = version;
		    });

		    

		});
		
		InitIonic($rootScope, $ionicModal, $ionicPopup, $ionicLoading, $http, $state);
		
		/**
		 * 更新用户学习
		 * @param {Object} user_id
		 * @param {Object} book_id
		 * @param {Object} unit_id
		 * @param {Object} word_id
		 * @param {Object} task1  "1,2,3"
		 * @param {Object} task2  "1,2,3"
		 * @param {Object} task3  "1,2,3"
		 */

		$rootScope.userCompletedTask = function(user_id, book_id, unit_id, word_id, task1, task2, task3) {
			
			$rootScope.LoadingShow();
			var url = $rootScope.rootUrl + "/user_completed_task";
			var data = {
				"user_id": user_id,
				"book_id": book_id,
				"unit_id": unit_id,
				"word_id": word_id,
				"task1": task1,
				"task2": task2,
				"task3": task3,
			};
			
			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();
				if(response.error) {
					$rootScope.Alert(response.msg);
				} else {
					
				}
			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');
				return;
			});
		}

		/**
		 * 更新用户练习
		 * @param {Object} user_id
		 * @param {Object} book_id
		 * @param {Object} unit_id
		 * @param {Object} word_id
		 * @param {Object} exercise_id  "11"
		 * @param {Object} point  "1,2,3"
		 */
		$rootScope.userCompletedExercise = function(user_id, book_id, unit_id, word_id, exercise_id, point) {
			$rootScope.LoadingShow();
			var url = $rootScope.rootUrl + "/user_completed_exercise";
			var data = {
				"user_id": user_id,
				"book_id": book_id,
				"unit_id": unit_id,
				"word_id": word_id,
				"exercise_id": exercise_id,
				"point": point
			};

			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();
				if(response.error) {
					$rootScope.Alert(response.msg);
				} else {}
			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');
				return;
			});
		}
		
		$rootScope.goWordList = function() {
			$state.go("word_list", {
				'unit_id': $rootScope.list_unit_id,
				'index': $rootScope.list_index
			})
		}

		var ua = navigator.userAgent;
		$rootScope.isIOS = ua.match(/(iPhone|iPod|iPad)/);
		$rootScope.isAndroid = ua.match(/Android/);
		
	})

	.config(function($stateProvider, $urlRouterProvider, $ionicConfigProvider) {

		//	$ionicConfigProvider.views.maxCache(0);禁止页面缓存
		$ionicConfigProvider.views.swipeBackEnabled(false);
		$ionicConfigProvider.tabs.position('bottom'); //bottom
		$ionicConfigProvider.tabs.style('standard');

		$stateProvider

			.state('api', {
				url: '/api',
				templateUrl: 'templates/api.html',
				controller: 'apiCtrl'
			})

			.state('login', {
				url: '/login',
				templateUrl: 'templates/login.html',
				controller: 'loginCtrl',
				cache: false
			})

			.state('grade', {
				url: '/grade',
				templateUrl: 'templates/grade_select.html',
				controller: 'gradeCtrl'
			})
			//#region tab
			.state('book', {
				url: '/book',
				templateUrl: 'templates/book_select.html',
				controller: 'bookCtrl',
				cache: false,
			})
			//#endregion
			
			//#region tab
			.state('tab', {
				url: "/tab",
				abstract: true,
				templateUrl: "templates/tab.html"
			})
			//#endregion
			
			//#region 
			.state('tab.dy_home', {
				cache: false,
				url: '/dy_home',
				views: {
					'tab-dy': {
						templateUrl: 'templates/dy_home.html',
						controller: 'dy_homeCtrl'
					}
				}
			})
			//#endregion
			
			//#region 
			.state('tab.yx_home', {
				cache: false,
				url: '/yx_home',
				views: {
					'tab-yx': {
						templateUrl: 'templates/yx_home.html',
						controller: 'yx_homeCtrl'
					}
				}
			})
			//#endregion

			//#endregion
			.state('yx_main', {
				url: '/yx_main/:unit_id/:index',
				templateUrl: 'templates/yx_main.html',
				controller: 'yx_mainCtrl',
				cache: false
			})
			//#endregion

			//#region 
			.state('tab.me_home', {
				cache: false,
				url: '/me_home',
				views: {
					'tab-me': {
						templateUrl: 'templates/me_home.html',
						controller: 'me_homeCtrl'
					}
				}
			})
			//#endregion
			.state('word_list', {
				url: '/word_list/:unit_id/:index',
				templateUrl: 'templates/dy_word_list.html',
				controller: 'word_listCtrl',
				cache: false
			})
			//#endregion

			//#endregion
			.state('word_test1', {
				url: '/word_test1/:_index',
				templateUrl: 'templates/dy_word_test1.html',
				controller: 'word_test1Ctrl',
				cache: false
			})
			//#endregion

			//#endregion
			.state('word_detail', {
				url: '/word_detail/:_index',
				templateUrl: 'templates/dy_word_detail.html',
				controller: 'word_detailCtrl',
				cache: false
			})
			//#endregion

			//#region
			.state('word_read', {
				url: '/word_read/:_index',
				templateUrl: 'templates/dy_word_read.html',
				controller: 'word_readCtrl',
				cache: false
			})
			//#endregion

			//#region
			.state('word_spell', {
				url: '/word_spell/:_index',
				templateUrl: 'templates/dy_word_spell.html',
				controller: 'word_spellCtrl',
				cache: false
			})
			//#endregion

			//#region
			.state('me_appvcode', {
				url: '/me_appvcode',
				templateUrl: 'templates/me_app_vcode.html',
				controller: 'me_appvcodeCtrl',
				cache: false
			})
			//#endregion

			//#region
			.state('me_gametop10', {
				url: '/me_gametop10',
				templateUrl: 'templates/me_game_top10.html',
				controller: 'me_gametop10Ctrl',
				cache: false
			})
			//#endregion

			//#region
			.state('me_aboutus', {
				url: '/me_aboutus',
				templateUrl: 'templates/me_about_us.html',
				controller: 'me_aboutusCtrl'
			})
			//#endregion

			//#region
			.state('more_apps', {
				url: '/more_apps',
				templateUrl: 'templates/more_apps.html',
				controller: 'more_appsCtrl'
			})
		   //#endregion	
		   
		   //#region
			.state('my_order', {
				url: '/my_order',
				templateUrl: 'templates/my_order.html',
				controller: 'my_orderCtrl',
				cache: false
			})
		   //#endregion	
		   
		   //#region
			.state('my_vcode', {
				url: '/my_vcode',
				templateUrl: 'templates/my_vcode.html',
				controller: 'my_vcodeCtrl',
				cache: false
	
			})
		   //#endregion	
		   
        .state('home', {
            url: '/home',
            templateUrl: 'templates/home.html',
            controller: 'homeCtrl',
            cache: false

        })
		   
		$urlRouterProvider.otherwise('/login');

	});

//#region Init Cordova 
function initCordova() {
	if(window.cordova && window.cordova.plugins.Keyboard) {
		cordova.plugins.Keyboard.hideKeyboardAccessoryBar(false);
		cordova.plugins.Keyboard.disableScroll(true);
	};
	if(window.StatusBar) {
		StatusBar.styleDefault();
	};
	if(window.cordova && window.cordova.InAppBrowser) {
		window.open = window.cordova.InAppBrowser.open;
	}
};
//#endregion

//#region Init Ionic
function InitIonic($rootScope, $ionicModal, $ionicPopup, $ionicLoading, $http, $state) {
	$rootScope.LoadingShow = function() {
		$ionicLoading.show({
			template: '<ion-spinner icon="spiral"></ion-spinner>'
		});
	};

	$rootScope.LoadingHide = function() {
		$ionicLoading.hide();
	};

	$rootScope.Alert = function(msg, okFunc) {
		var alertPopup = $ionicPopup.alert({
			template: msg,
			okText: '确定',
			okType: 'button-clear button-calm',
			cssClass: 'dc-popup'
		});

		if(okFunc) {
			alertPopup.then(function(res) {
				okFunc();
			});
		}
	};

	$rootScope.Confirm = function(msg, okText, cancelText, okFunc, cancelFunc) {
		var confirmPopup = $ionicPopup.confirm({
			template: msg,
			okText: (okText ? okText : '确定'),
			okType: 'button-clear button-assertive',
			cancelText: (cancelText ? cancelText : '取消'),
			cancelType: 'button-clear button-calm',
			cssClass: 'dc-popup'
		});
		confirmPopup.then(function(res) {
			if(res) {
				okFunc();
			} else {
				cancelFunc();
			}
		});
	};

	$rootScope.goBack = function() {
		history.go(-1);
	}

	$rootScope.playWord = function(audio, obj) {
		var v = document.getElementById("audio");
		v.src = "upload/word/mp3/" + audio;
		v.loop = false;
		v.addEventListener('ended', function() {
			obj.attr("src", "img/xiaoxue_cut_07.png");
		}, false);
		v.addEventListener('error', function(e) {
			if(e) {
				v.src = $rootScope.siteUrl + "/upload/word/mp3/" + audio;
				v.play();
			}
		}, false)
		v.play();
		obj.attr("src", "img/play_gif.gif");
        
        console.log("upload/word/mp3/" + audio);
	}

	$rootScope.playWebWord = function(audio) {
		var v = document.getElementById("audio");
		v.src = $rootScope.siteUrl + "/upload/word/mp3/" + audio;
		v.play();

	}

	$rootScope.playExercise = function(audio, obj) {
		var v = document.getElementById("audio");
		v.src = "upload/exercise/mp3/" + audio;
		v.loop = false;
		v.addEventListener('ended', function() {
			obj.attr("src", "img/xiaoxue_cut_07.png");
		}, false);
		
		v.addEventListener('error', function(e) {
			if(e) {
				v.src = $rootScope.siteUrl + "/upload/exercise/mp3/" + audio;
				v.play();
			}
		}, false)
		v.play();
		obj.attr("src", "img/play_gif.gif");
	}
};
//#endregion

//#region localStorage
function setStorage(key, value, isString) {
	if(isString) {
		window.localStorage[key] = value;
	} else {
		window.localStorage[key] = JSON.stringify(value);
	}
};

function getStorage(key, isString) {
	if(isString) {
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
	img.onload = function() {
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
	if(s.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1)
		return true;
	else
		return false;
}

function sortNameASC(x, y) {
	return(parseFloat(x.name) > parseFloat(y.name)) ? 1 : -1;
}

function sortTimeDESC(x, y) {
	return(Date.parse(x.time) > Date.parse(y.time)) ? -1 : 1;
}

function getNextId(a) {
	var m = 0;

	for(i = 0; i < a.length; i++) {
		if(a[i].id > m) {
			m = a[i].id;
		}
	}

	return(m + 1);
}

function getItemById(a, id) {
	var m = {};
	for(i = 0; i < a.length; i++) {
		if(a[i].id == id) {
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

	return(Date.parse(s) - Date.parse(e)) / 86400000;
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

	return(s1 - e1) / 86400000;
}

function DateFormat(d) {
	return d.getFullYear() + "年" + PadLeft((d.getMonth() + 1), 2) + "月" + PadLeft(d.getDate(), 2) + "日" + "  星期" + "日一二三四五六".charAt(d.getDay());
}

function PadLeft(v, length) {
	var s = "000000000" + v;
	return s.substr(s.length - length);
}

function versionId(v) {
	if(!v) {
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

	if(month < 10) clock += "0";
	clock += month + "-";

	if(day < 10) clock += "0";
	clock += day + " ";

	if(hh < 10) clock += "0";
	clock += hh + ":";

	if(mm < 10) clock += '0';
	clock += mm + ":";

	if(ss < 10) clock += '0';
	clock += ss;

	return(clock);
}
//#endregion

function playAudio(f) {
	var v = document.getElementById("audio");
	v.src = (f ? "audio/correct.mp3" : "audio/error.mp3");
	v.play();
}

function playGood() {
	var v = document.getElementById("audio");
	v.src = "audio/good.mp3";
	v.play();
}

function playJiayou() {
	var v = document.getElementById("audio");
	v.src = "audio/laodajiayou.mp3";
	v.play();
}

function playWordAudio(audio) {
	var v = document.getElementById("audio");
	v.src = "upload/word/mp3/" + audio;
	v.play();
}

function showStarImgAnimate() {
	setTimeout(function() {
		var star_img = $(".star_img");
		star_img.animate({
			width: '25px',
			opacity: '0.4'
		}, "slow");
		star_img.animate({
			width: '20px',
			opacity: '0.8'
		}, "slow");
		star_img.animate({
			width: '25px',
			opacity: '0.4'
		}, "slow");
		star_img.animate({
			width: '20px',
			opacity: '0.8'
		}, "slow");

	}, 200);

}

//JS判断数组中是否包含某一项
Array.prototype.contains = function(obj) {
	var i = this.length;
	while(i--) {
		if(this[i] === obj) {
			return true;
		}
	}
	return false;
}
