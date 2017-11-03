var dcCtrl = angular.module('dachutimes.controllers', []);
dcCtrl
	.controller('homeCtrl', function($rootScope, $scope, $state, $http, $ionicActionSheet) {

		//#region 苹果审核人员用 勿删
		var info = {
			"id": "24",
			"subscribe": null,
			"openid": "oVl0IwD3qn9_3GHx2qOGXBJlxRUc",
			"nickname": "\u9a6c\u6d2a\u6d9b",
			"sex": "1",
			"language": "zh_CN",
			"city": "Mentougou",
			"province": "Beijing",
			"country": "CN",
			"headimgurl": "http:\/\/wx.qlogo.cn\/mmopen\/XiaYa0IAAlP8OZom5WMCCVl1icLibz9F6yE85NXOpZZ1NNsJ5G65nnkzgoN8fA07WibKM0hmpI56FviaafZk6MWbbPlDFfpFjTXxN\/0",
			"subscribe_time": null,
			"unionid": "ocffVt08HworeoxlzULVlOFdkYY4",
			"remark": null,
			"groupid": null,
			"register_time": "2017-02-22 21:43:59",
			"status": "0",
			"book_id": $rootScope.bookId
		};

		if($rootScope.isIOS) {
			var url = $rootScope.rootUrl + "/version";
			var data = {
				"platform": "ios",
				"book_id": $rootScope.bookId
			};
			$http.post(url, data).success(function(response) {
				if(response.error) {

				} else {

					setTimeout(function() {

						if(response.status == 0 && response.version == $rootScope.currentVersion) {

							setStorage("userinfo", info);
						}

						$state.go("login");

					}, 1000);
				}
			}).error(function(response, status) {
				return;
			});
		} else {
			$state.go("login");
		}
		//#endregion 苹果审核人员用 勿删

	})

	.controller('loginCtrl', function($rootScope, $scope, $state, $http, $ionicActionSheet) {

		$scope.iflogin = true;
		
//		setStorage("userinfo", {
//								"id": "24",
//								"subscribe": null,
//								"openid": "oVl0IwD3qn9_3GHx2qOGXBJlxRUc",
//								"nickname": "\u9a6c\u6d2a\u6d9b",
//								"sex": "1",
//								"language": "zh_CN",
//								"city": "Mentougou",
//								"province": "Beijing",
//								"country": "CN",
//								"headimgurl": "http:\/\/wx.qlogo.cn\/mmopen\/XiaYa0IAAlP8OZom5WMCCVl1icLibz9F6yE85NXOpZZ1NNsJ5G65nnkzgoN8fA07WibKM0hmpI56FviaafZk6MWbbPlDFfpFjTXxN\/0",
//								"subscribe_time": null,
//								"unionid": "ocffVt08HworeoxlzULVlOFdkYY4",
//								"remark": null,
//								"groupid": null,
//								"register_time": "2017-02-22 21:43:59",
//								"status": "0",
//								"book_id": $rootScope.bookId
//							});

		
		$scope.getBook = function(bookId) {

			$rootScope.LoadingShow();
			//获取所有book/unit
			var url = $rootScope.rootUrl + "/books";
			var data = {
				//				"user_id": userId, //没有登录时没有userId的暂拿着我的id21来获取book
				"book_id": bookId
			};

			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();
				if(response.error) {
					$rootScope.Alert(response.msg);
				} else {
					if(response) {
						$rootScope.mybook = response;
						var userinfo = getStorage("userinfo");
						if(userinfo.id) {
							$scope.iflogin = true;
							$rootScope.userinfo = userinfo;
							if($rootScope.userinfo.book_id != $rootScope.bookId) {
								$scope.setBook($rootScope.userinfo.id, $rootScope.bookId);
							} else {
								setTimeout(function() {

									$state.go("tab.dy_home")
								}, 2000)
							}
						} else {
							$scope.iflogin = false;
						}
					}
				}
			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');

				return;
			});
		}

		//#region 苹果审核人员用 勿删
		if($rootScope.isIOS) {

			var url = $rootScope.rootUrl + "/version";
			var data = {
				"platform": "ios",
				"book_id": $rootScope.bookId
			};
			$http.post(url, data).success(function(response) {
				if(response.error) {} else {
					setTimeout(function() {

						if(response.status == 0 && response.version == $rootScope.currentVersion) {
							setStorage("userinfo", {
								"id": "24",
								"subscribe": null,
								"openid": "oVl0IwD3qn9_3GHx2qOGXBJlxRUc",
								"nickname": "\u9a6c\u6d2a\u6d9b",
								"sex": "1",
								"language": "zh_CN",
								"city": "Mentougou",
								"province": "Beijing",
								"country": "CN",
								"headimgurl": "http:\/\/wx.qlogo.cn\/mmopen\/XiaYa0IAAlP8OZom5WMCCVl1icLibz9F6yE85NXOpZZ1NNsJ5G65nnkzgoN8fA07WibKM0hmpI56FviaafZk6MWbbPlDFfpFjTXxN\/0",
								"subscribe_time": null,
								"unionid": "ocffVt08HworeoxlzULVlOFdkYY4",
								"remark": null,
								"groupid": null,
								"register_time": "2017-02-22 21:43:59",
								"status": "0",
								"book_id": $rootScope.bookId
							});

							var userinfo = getStorage("userinfo");
						}

						$scope.getBook($rootScope.bookId);

					}, 1000);
				}
			}).error(function(response, status) {

				//#region 勿删 ios第一次启动需要用户同意允许联网

				$rootScope.Alert('您的设备没有联网(或者设置中没有打开允许使用数据网络)，请联网后再试，谢谢！', function() {

					location.reload();

					return;

				});

				//#endregion

				return;
			});
		} else {

			$scope.getBook($rootScope.bookId);

		}
		//#endregion 苹果审核人员用 勿删

		$scope.setBook = function(userId, bookId) {
			$rootScope.LoadingShow();
			//获取所有book/unit
			var url = $rootScope.rootUrl + "/user_set_book";
			var data = {
				"user_id": userId,
				"book_id": bookId
			};
			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();
				if(response.error) {
					$rootScope.Alert(response.msg);
				} else {
					$rootScope.userinfo.book_id = bookId;
					setStorage("userinfo", angular.copy($rootScope.userinfo));
					setTimeout(function() {

						$state.go("tab.dy_home")
					}, 2000)
				}
			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');
				return;
			});
		}

		$scope.wxLogin = function() {

			$rootScope.LoadingShow();
			Wechat.isInstalled(function(installed) {
				if(installed) {

					Wechat.auth("snsapi_userinfo", function(response) {
						//#region 通过code获取access_token..     
						var url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" + $rootScope.mybook.wx_appid + "&secret=" + $rootScope.mybook.wx_appsecret + "&code=" + response.code + "&grant_type=authorization_code";

						$http.get(url).success(function(response) {
							//#region 获取用户个人信息（UnionID机制）
							var url = "https://api.weixin.qq.com/sns/userinfo?access_token=" + response.access_token + "&openid=" + response.openid;

							$http.get(url).success(function(wx_response) {
								$rootScope.LoadingHide();
								var unionid = wx_response.unionid;
								var url = $rootScope.rootUrl + "/user/";
								var data = {
									"unionid": unionid,
								};
								$.post(url, data, function(response) {
									if(response.error) {
										$rootScope.LoadingShow();
										var url = $rootScope.rootUrl + "/user_register/";
										$.post(url, wx_response, function(response) {
											$rootScope.LoadingHide();
											if(response.error) {
												$rootScope.Alert(response.msg);
											} else {
												$rootScope.userinfo = response;
												$scope.setBook($rootScope.userinfo.id, $rootScope.bookId);
											}
										}, "json")

									} else {
										$rootScope.userinfo = response;
										setStorage("userinfo", $rootScope.userinfo);
										if($rootScope.userinfo.book_id != $rootScope.bookId) {
											$scope.setBook($rootScope.userinfo.id, $rootScope.bookId);
										} else {
											setTimeout(function() {

												$state.go("tab.dy_home")
											}, 2000)
										}
									}

								}, "json")
							}).error(function(response, status) {
								$rootScope.LoadingHide();
								return;
							});
							//#endregion

						}).error(function(response, status) {
							$rootScope.LoadingHide();
							return;
						});
						//#endregion

					}, function(reason) {
						$rootScope.LoadingHide();
						$rootScope.Alert("Failed: " + reason);
					});

				} else {
					$rootScope.LoadingHide();
					$rootScope.Confirm("微信没有安装，请先安装微信.", "去安装微信?", "", function() {
						window.open('https://itunes.apple.com/cn/app/wechat/id414478124', '_system', 'location=yes');
					}, function() {});
				}

			}, function(reason) {
				$rootScope.Alert("Failed: " + reason);
			});
		}

	})

	.controller('gradeCtrl', function($rootScope, $scope, $state, $http, $ionicActionSheet) {

		$rootScope.LoadingShow();
		//获取所有book/unit
		var url = $rootScope.rootUrl + "/books";
		var data = {
			"user_id": $rootScope.userinfo.id
		};

		$http.post(url, data).success(function(response) {
			$rootScope.LoadingHide();
			if(response.error) {
				$rootScope.Alert(response.msg);
			} else {
				$rootScope.bookList = response;
			}
		}).error(function(response, status) {
			$rootScope.LoadingHide();
			$rootScope.Alert('连接失败！[' + response + status + ']');
			return;
		});

		$scope.now_num = 0;
		$scope.gradeSelect = function(gid, grade) {
			$scope.now_num = gid;
			$rootScope.grade = grade;
		}

		$scope.bookSelect = function(num) {
			if(num == 0) {
				$rootScope.semester = "上册";
			} else {
				$rootScope.semester = "下册";
			}
			$state.go("book")
		}
	})

	.controller('bookCtrl', function($rootScope, $scope, $state, $http, $ionicActionSheet) {

		$scope.books = [];
		for(var i = 0; i < $rootScope.bookList.length; i++) {
			if($rootScope.bookList[i].grade == $rootScope.grade && $rootScope.bookList[i].semester == $rootScope.semester) {
				$scope.books.push($rootScope.bookList[i])
			}
		}

		$scope.goHome = function(book) {
			if(book) {
				$rootScope.mybook = book;
				//http://kuyxxword.ourapp.site:666/index.php/api/user_set_book?user_id=1&book_id=1
				$rootScope.LoadingShow();
				//获取所有book/unit
				var url = $rootScope.rootUrl + "/user_set_book";
				var data = {
					"user_id": $rootScope.userinfo.id,
					"book_id": $rootScope.mybook.id
				};

				$http.post(url, data).success(function(response) {
					$rootScope.LoadingHide();
					if(response.error) {
						$rootScope.Alert(response.msg);
					} else {
						$rootScope.userinfo.book_id = $rootScope.mybook.id;
						setStorage("userinfo", angular.copy($rootScope.userinfo));

						$state.go("tab.dy_home");
					}
				}).error(function(response, status) {
					$rootScope.LoadingHide();
					$rootScope.Alert('连接失败！[' + response + status + ']');
					return;
				});
			} else {
				$rootScope.Alert("教材错误!");
			}
		}
	})
	//
	.controller('dy_homeCtrl', function($rootScope, $scope, $state, $http, $ionicActionSheet, $ionicPopup, $ionicLoading, $cordovaNetwork, $cordovaAppVersion, $cordovaFileTransfer, $cordovaFileOpener2) {

		///////////////
		$scope.mstop = function() {
			window.plugins.audioRecorderAPI.stop(function(msg) {
				// success
				alert('ok: ' + msg);
			}, function(msg) {
				// failed
				alert('ko: ' + msg);
			});
		}
		$scope.mstart = function() {
			window.plugins.audioRecorderAPI.record(function(msg) {
				// complete
				alert('ok: ' + msg);
			}, function(msg) {
				// failed
				alert('ko: ' + msg);
			}, 30); // record 30 seconds
		}
		$scope.mplay = function() {
			window.plugins.audioRecorderAPI.playback(function(msg) {
				// complete
				alert('ok: ' + msg);
			}, function(msg) {
				// failed
				alert('ko: ' + msg);
			});
		}
		///////////////

		$scope.ifsearch = false;
		$scope.units = [];
		$scope.wordSum = 0;
		$scope.learned = 0;

		//		if(!$rootScope.mybook) {
		//			$rootScope.mybook = $rootScope.userinfo.mybook;
		//		}

		$scope.getUnits = function(book_id) {
			$rootScope.LoadingShow();
			var url = $rootScope.rootUrl + "/units";
			var data = {
				"user_id": $rootScope.userinfo.id,
				"book_id": book_id
			};
			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();
				if(response.error) {
					$rootScope.Alert(response.msg);
				} else {
					$scope.units = response;
					angular.forEach($scope.units, function(item) {
						$scope.wordSum = $scope.wordSum + parseInt(item.word_total);
						$scope.learned = $scope.learned + parseInt(item.word_completed_total);
					})
					//检查是否有新版本

					if(device.platform === 'Android') {
						$scope.version("android", book_id);
					} else {
						$scope.version("ios", book_id);
					}
				}

			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');
				return;
			});
		}

		/**
		 * 教材激活状态
		 * @param {Object} user_id
		 * @param {Object} book_id
		 * @param {Object} code
		 */
		$scope.bookStatus = function(user_id, book_id) {
			$rootScope.LoadingShow();
			var url = $rootScope.rootUrl + "/book_status";
			var data = {
				"user_id": user_id,
				"book_id": book_id,
			};
			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();
				if(response.error) {
					//				$rootScope.Alert(response.msg);
					$rootScope.userinfo.active = false;
				} else {
					$rootScope.userinfo.active = true;
				}
				$scope.getUnits($rootScope.mybook.id);
			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');
				return;
			});
		}

		$scope.bookStatus($rootScope.userinfo.id, $rootScope.userinfo.book_id);

		//扫描单词或练习二维码  
		$scope.scancode = function() {
			cordova.plugins.barcodeScanner.scan(
				function(result) {

					//				alert("We got a barcode\n" +
					//					"Result: " + result.text + "\n" +
					//					"Format: " + result.format + "\n" +
					//					"Cancelled: " + result.cancelled);
					//http://xx.kaouyu.com/www/#/word_detail/2/39/is
					//http://xx.kaouyu.com/www/#/word_exercise/2/39/ear

					if(result.text.split("?").length == 2) {
						//			
						var scan_arr = result.text.split("?")[1].split("&");

						if(scan_arr.length == 3 && scan_arr[2].indexOf("unit") == -1) {

							var unit = scan_arr[1].substring(8, scan_arr[1].length);

							$rootScope.LoadingShow();
							var url = $rootScope.rootUrl + "/words";
							var data = {
								"user_id": $rootScope.userinfo.id,
								"unit_id": unit
							};

							$http.post(url, data).success(function(response) {
								$rootScope.LoadingHide();
								if(response.error) {
									$rootScope.Alert(response.msg);
								} else {
									$rootScope.words = response;
									var word;
									var type;
									if(scan_arr[2].indexOf("word") >= 0) {
										type = "word";
										word = scan_arr[2].substring(5, scan_arr[2].length)
									} else
									if(scan_arr[2].indexOf("exercise") >= 0) {
										type = "exercise";
										word = scan_arr[2].substring(9, scan_arr[2].length)
									}

									for(var i = 0; i < $rootScope.words.length; i++) {
										if($rootScope.words[i].en == word) {
											if(type == 'word') {
												$state.go("word_detail", {
													'_index': i
												})
											}
											if(type == 'exercise') {
												$state.go("word_read", {
													'_index': i
												})
											}
											break;
										}
									}
								}

							}).error(function(response, status) {
								$rootScope.LoadingHide();
								$rootScope.Alert('连接失败！[' + response + status + ']');
								return;
							});

						} else {
							//							var unit_id = scan_arr[2].substring()
							var unit = scan_arr[1].substring(8, scan_arr[1].length);

							for(var i = 0; i < $scope.units.length; i++) {
								if($scope.units[i].id == unit) {
									$scope.unitSelect(unit, i);
								}
							}
						}

					}

				},
				function(error) {
					//$rootScope.Alert("Scanning failed: " + error);
				}, {
					"preferFrontCamera": false, // iOS and Android
					"showFlipCameraButton": false, // iOS and Android
					"showTorchButton": true, // iOS and Android
					"prompt": "扫描烤鱿鱼背单词手册每个单词或练习的二维码", // supported on Android only
					"formats": "QR_CODE,PDF_417", // default: all but PDF_417 and RSS_EXPANDED
					"orientation": "portrait" // Android only (portrait|landscape), default unset so it rotates with the device
				}
			);

		}

		$scope.unitSelect = function(unit_id, index) {

			if(index > 0 && !$rootScope.userinfo.active) {
				$state.go("me_appvcode")
			} else {
				var u = unit_id;
				$state.go("word_list", {
					'unit_id': unit_id,
					'index': index + 1
				})
			}
		}

		//单词查询
		$scope.wordSearch = function() {

			$rootScope.searchword = null;

			if($('#word_seach').val().length > 0) {
				$scope.getWord = function(word) {
					var url = $rootScope.rootUrl + "/word";
					var data = {
						"user_id": $rootScope.userinfo.id,
						"word": word
					};
					$http.post(url, data).success(function(response) {
						$rootScope.LoadingHide();

						if(response.error) {
							$rootScope.Alert(response.msg);
						} else {
							$rootScope.searchword = response;
							$state.go("word_detail", {
								'_index': -1
							})
						}

					}).error(function(response, status) {
						$rootScope.LoadingHide();
						$rootScope.Alert('连接失败！[' + response + status + ']');
						return;
					});
				}
				$scope.getWord($('#word_seach').val());
			}

		}

		/**
		 * 获取app信息
		 * @param {Object} platform
		 * @param {Object} book_id
		 */
		$scope.version = function(platform, book_id) {
			$rootScope.LoadingShow();
			var url = $rootScope.rootUrl + "/version";
			var data = {
				"platform": platform,
				"book_id": book_id
			};
			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();
				if(response.error) {
					//$rootScope.Alert(response.msg);
				} else {
					$rootScope.app = response;
					var date = new Date();
					//此页面不设置缓存点击就刷新了 避免提示升级次数过多 暂设定为一天一次 只看date.getDate 不一样就提示升级
					if(getStorage("gxdate", true) === date.getDate() + "") {} else {
						$scope.checkUpdate();
					}
				}
			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');
				return;
			});
		}

		$scope.checkUpdate = function() {

			if(device.platform === 'Android') {
				// Android升级
				var type = $cordovaNetwork.getNetwork();

				// 1.0.0 => 10000
				//			var AppVersionCode = '10001'; // 获取的服务器版本
				//获取本地APP版本
				$cordovaAppVersion.getVersionNumber().then(function(version) {
					// 0.0.1 => 00001 => 1
					var nowVersionNum = parseInt(version.toString().replace(new RegExp(/(\.)/g), '0'));
					// 10000
					var newVersionNum = parseInt($rootScope.app.version.replace(new RegExp(/(\.)/g), '0'));

					if(newVersionNum > nowVersionNum && $rootScope.app.status === '1') {
						if(type === 'wifi') {
							$ionicPopup.confirm({
								title: '版本升级',
								template: $rootScope.app.info + "<br>大小:" + $rootScope.app.size,
								cancelText: '取消',
								okText: '升级'
							}).then(function(res) {
								if(res) {
									var success = function(message) {
										alert("success = " + message);
									};
									var fail = function(message) {
										alert("fail = " + message);
									};
									cordova.exec(success, fail, "OpenLink", "url", [$rootScope.app.url]);
								}
							});
						} else {
							$ionicPopup.confirm({
								title: '建议您在WIFI条件下进行升级，是否确认升级？',
								template: $rootScope.app.info + "<br>大小:" + $rootScope.app.size,
								cancelText: '取消',
								okText: '升级'
							}).then(function(res) {
								if(res) {
									//									$scope.UpdateForAndroid();
									var success = function(message) {
										alert("success = " + message);
									};
									var fail = function(message) {
										alert("fail = " + message);
									};
									cordova.exec(success, fail, "OpenLink", "url", [$rootScope.app.url]);
								}
							});
						}
						//设置今天不在提示升级
						var date = new Date();
						setStorage("gxdate", date.getDate() + "", true);
					}
				});

				// 无网络时
				$rootScope.$on('$cordovaNetwork:offline', function(event, networkState) {
					$ionicLoading.show({
						template: '网络异常，不能连接到服务器！'
					});
					setTimeout(function() {
						$ionicLoading.hide()
					}, 2000);
				})
			} else {

			}

		}
		$scope.UpdateForAndroid = function() {
			$ionicLoading.show({
				template: "已经下载：0%"
			});
			//var url = 'http://xx.kaouyu.com/upload/apk/android-3q3s-release.apk'; // 下载地址
			//    		var targetPath = "/sdcard/Download/kaouyu.apk";
			var trustHosts = true;
			var options = {};
			window.requestFileSystem(LocalFileSystem.PERSISTENT, 0, function(fs) {
				fs.root.getFile('rjbxxbdc.apk', {
						create: true,
						exclusive: false
					},
					function(fileEntry) {
						$cordovaFileTransfer.download($rootScope.app.url, fileEntry.toURL(), options, trustHosts).then(function(result) {
							$cordovaFileOpener2.open(fileEntry.toURL(), 'application/vnd.android.package-archive').then(function() {
								// 成功
							}, function(err) {
								console.log(err);
							});
							$ionicLoading.hide();
						}, function(err) {
							$ionicLoading.show({
								template: "下载失败"
							});
							$ionicLoading.hide();
						}, function(progress) {
							//							console.log("progress", progress.loaded + "---" + progress.total)
							setTimeout(function() {
								var downloadProgress = (progress.loaded / progress.total) * 100;
								$ionicLoading.show({
									template: "已经下载：" + Math.floor(downloadProgress) + "%"
								});
								if(downloadProgress > 99) {
									$ionicLoading.hide();
								}
							});
						});
					}, onErrorCreateFile);

			}, onErrorLoadFs);

		}
		//文件创建失败回调
		function onErrorCreateFile(error) {
			console.log("文件创建失败！")
		}
		//FileSystem加载失败回调
		function onErrorLoadFs(error) {
			console.log("文件系统加载失败！")
		}

	})

	.controller('word_listCtrl', function($rootScope, $scope, $state, $stateParams, $http, $ionicActionSheet, $interval) {

		$rootScope.jifenadd = 0;
		var unit_id = $stateParams.unit_id;
		$scope.index = $stateParams.index;
		$rootScope.list_unit_id = unit_id;
		$rootScope.list_index = $scope.index;
		$scope.tab_num = -1;
		$scope.word_list = [];
		$scope.word_list_0 = [];
		$scope.word_list_1 = [];
		$scope.word_list_2 = [];

		if(getStorage("show_cup_tips") != 1) {
			$scope.isshow_cup = true;
		} else {
			$scope.isshow_cup = false;
		}

		$scope.showCup = function() {
			$scope.isshow_cup = !$scope.isshow_cup;
		}

		$scope.closeShowCup = function() {
			$scope.isshow_cup = false;
			setStorage("show_cup_tips", 1);
		}

		$rootScope.LoadingShow();
		var url = $rootScope.rootUrl + "/words";
		var data = {
			"user_id": $rootScope.userinfo.id,
			"unit_id": unit_id
		};

		$http.post(url, data).success(function(response) {
			$rootScope.LoadingHide();
			if(response.error) {
				$rootScope.Alert(response.msg);
			} else {
				$scope.word_list = response;
				for(var i = 0; i < $scope.word_list.length; i++) {
					$scope.word_list[i].if_do = true; //是否单词所有的练习都做过
					$scope.word_list[i].if_finish = true; //是否所有的练习拿到满分
					if($scope.word_list[i].task1 == null || $scope.word_list[i].task2 == null || $scope.word_list[i].task3 == null || $scope.word_list[i].task1 == "0" || $scope.word_list[i].task2 == "0" || $scope.word_list[i].task3 == "0") {
						$scope.word_list[i].if_do = false; //是否单词所有的练习都做过
						$scope.word_list[i].if_finish = false; //是否所有的练习拿到满分
					} else {
						if(parseInt($scope.word_list[i].task1) == 3 && parseInt($scope.word_list[i].task2) == 3 && parseInt($scope.word_list[i].task3) == 3) {
							$scope.word_list[i].if_finish = true; //是否所有的练习拿到满分
						} else {
							$scope.word_list[i].if_finish = false; //是否所有的练习拿到满分
						}
					}
					if($scope.word_list[i].if_do && $scope.word_list[i].exercises.length > 0) {
						for(var j = 0; j < $scope.word_list[i].exercises.length; j++) {
							if($scope.word_list[i].exercises[j].point == null || $scope.word_list[i].exercises[j].point == "") {
								$scope.word_list[i].if_do = false; //是否所有的练习拿到满分
								$scope.word_list[i].if_finish = false; //是否所有的练习拿到满分
							} else if(parseInt($scope.word_list[i].exercises[j].point) != 3) {
								$scope.word_list[i].if_finish = false; //是否所有的练习拿到满分
							}
						}
					}
					if($scope.word_list[i].if_do && $scope.word_list[i].if_finish) {
						$scope.word_list_2.push($scope.word_list[i]);
					} else if($scope.word_list[i].if_do && !$scope.word_list[i].if_finish) {
						$scope.word_list_1.push($scope.word_list[i]);
					} else {
						$scope.word_list_0.push($scope.word_list[i]);
					}
				}
				$rootScope.words = $scope.word_list;
			}

		}).error(function(response, status) {
			$rootScope.LoadingHide();
			$rootScope.Alert('连接失败！[' + response + status + ']');
			return;
		});

		$scope.wordSelect = function(num) {

			if(timer != undefined && $scope.recording) {
				$interval.cancel(timer); //停止并清除
				mediaRec.stopRecord();
			}
			if(timer2 != undefined && $scope.playing) {
				$interval.cancel(timer2); //停止并清除
				mediaRec.stop();
			}

			if(num == -1) {
				$scope.tab_num = num;
				$rootScope.words = $scope.word_list;
			} else if(num == 0) {
				if($scope.word_list_0.length > 0) {
					$scope.tab_num = num;
					$rootScope.words = $scope.word_list_0;
				}
			} else if(num == 1) {
				if($scope.word_list_1.length > 0) {
					$scope.tab_num = num;
					$rootScope.words = $scope.word_list_1;
				}
			} else if(num == 2) {
				if($scope.word_list_2.length > 0) {
					$scope.tab_num = num;
					$rootScope.words = $scope.word_list_2;
				}
			}
		}

		var mediaRec;
		var src = "follow.wav";
		if($rootScope.isIOS) {
			src = audioRecord;
		}
		$scope.playing = false;
		$scope.recording = false;
		$scope.is_reading_num = -1;
		$scope.read_button_name = "带我读";
		$scope.play_button_name = "播放";

		var timer;
		var radios = [];
		$scope.is_canplay = false;

		$scope.followAudio = function() {
			if(!$scope.recording) {
				$scope.playing = false;
				$scope.recording = true;
				//				if(mediaRec == undefined) {

				mediaRec = new Media(src,
					// success callback
					function() {
						console.log("followAudio():Audio Success");
					},
					// error callback
					function(err) {
						console.log("followAudio():Audio Error: " + err.code);
					}
				);
				//				}

				// Record audio
				//ios https://stackoverflow.com/questions/24731601/ios-how-to-play-alert-sound-in-speaker-when-recording
				mediaRec.startRecord();
				$scope.read_button_name = "停止带读";
				$scope.is_canplay = false;
				$scope.show_record = true;

				if(radios.length != $rootScope.words.length * 2) {
					radios = [];
					for(var i = 0; i < $rootScope.words.length; i++) {
						radios.push({
							"id": i,
							"audio": $rootScope.words[i].audio_0,
							"type": "0"
						});
						radios.push({
							"id": i,
							"audio": $rootScope.words[i].audio_1,
							"type": "1"
						});
					}
				}

				var j = 0;
				timer = $interval(function() {
					if(j == radios.length) {
						mediaRec.stopRecord()
						$scope.read_button_name = "带我读";
						$scope.is_reading_num = -1
						$scope.recording = false;
						$scope.is_canplay = true;
						$interval.cancel(timer); //停止并清除					
					} else if(j < radios.length) {
						playWordAudio(radios[j].audio);
						$scope.is_reading_num = radios[j].id;
						j = j + 1;
					}

				}, 3000, radios.length + 1)
			} else {

				$scope.is_reading_num = -1;
				if(timer != null) {
					$interval.cancel(timer); //停止并清除
				}
				mediaRec.stopRecord();
				$scope.read_button_name = "带我读";
				$scope.recording = false;
				$scope.is_canplay = true;
			}
		}
		// Play audio
		//

		var timer2;
		$scope.playFollowAudio = function() {
			if(mediaRec && $scope.recording == false) {
				if(!$scope.playing) {
					$scope.playing = true;
					mediaRec.play();
					$scope.play_button_name = "停止播放";
					var m = 0;
					timer2 = $interval(function() {
						if(m >= mediaRec.getDuration().toFixed(0)) {
							$scope.playing = false;
							$scope.play_button_name = "播放";
							$interval.cancel(timer2); //停止并清除
						} else {
							m++;
						}
					}, 1000, 60)
				} else {
					$interval.cancel(timer2); //停止并清除
					$scope.playing = false;
					mediaRec.stop();
					$scope.play_button_name = "播放";
					$scope.is_canplay = false;
				}
			}

		}

		$scope.goTest = function(_index) {
			if(timer != undefined && $scope.recording) {
				$interval.cancel(timer); //停止并清除
				mediaRec.stopRecord();
			}

			if(timer2 != undefined && $scope.playing) {
				$interval.cancel(timer2); //停止并清除
				mediaRec.stop();
			}

			$state.go("word_detail", {
				'_index': _index
			})
		}

		$scope.goDyHome = function() {
			if(timer != undefined && $scope.recording) {
				$interval.cancel(timer); //停止并清除
				mediaRec.stopRecord();
			}
			if(timer2 != undefined && $scope.playing) {
				$interval.cancel(timer2); //停止并清除
				mediaRec.stop();
			}

			$state.go("tab.dy_home")
		}

	})

	//#region 扫描单词页
	.controller('word_detailCtrl', function($rootScope, $ionicModal, $scope, $state, $http, $stateParams) {

		$scope.isplay1 = false;
		$scope.isplay0 = false;
		$scope.isplayVideo = false;

		$scope.index = parseInt($stateParams._index);

		if($scope.index == -1) {
			$scope.word = $rootScope.searchword;
		} else {
			$scope.word = $rootScope.words[$scope.index];
		}

		$rootScope.if_do = $scope.word.if_do;
		$rootScope.if_finish = $scope.word.if_finish;

		if($scope.word.zh.split('/').length - 1 == 2 && $scope.word.zh.indexOf('/') > 10) {

			setTimeout(function() {
				$('#explain_word').html($scope.word.zh.replace('/', '<br>/'));
			}, 100);
		}

		$scope.initGrade = function() {
			if($scope.word.task1 != null) {
				$scope.word.task1 = parseInt($scope.word.task1);
			}
			$scope.scoreObjArr = [];
			for(var j = 0; j < 3; j++) {
				if(j < $scope.word.task1) {
					$scope.scoreObjArr.push({
						"ifget": true
					})
				} else {
					$scope.scoreObjArr.push({
						"ifget": false
					})
				}
			}
		}

		$scope.initGrade();

		$(".video-box video").attr("src", $rootScope.siteUrl + "/upload/word/mp4/" + $scope.word.video);

		$('.video-box video').mediaelementplayer();

		setTimeout(function() {
			$scope.playDetailWord1($scope.word.audio_1)
		}, 500);

		$scope.nextTest = function(str) {

			//			if($scope.isplay1 && $scope.isplay0) {
			//				$rootScope.jifenadd = $rootScope.jifenadd + 1;
			//				//					$rootScope.words[$scope.index].task1 = "3";
			//				$scope.word.task1 = "3";
			//				$scope.initGrade();
			//				$rootScope.userCompletedTask($rootScope.userinfo.id, $scope.word.book_id, $scope.word.unit_id, $scope.word.word_id, 3, null, null);
			//				$scope.word.task1 = parseInt($scope.word.task1);
			//
			//				$state.go("word_read", {
			//					'_index': $scope.index
			//				})
			//			} else if($scope.isplay1 || $scope.isplay0) {
			//				//					$rootScope.jifenadd = $rootScope.jifenadd + 1;
			//				if($rootScope.words[$scope.index].task1 == "2") {
			//
			//				} else {
			//					//						$rootScope.words[$scope.index].task1 = "2";
			//					$scope.word.task1 = "2";
			//					$scope.initGrade()
			//					$rootScope.userCompletedTask($rootScope.userinfo.id, $scope.word.book_id, $scope.word.unit_id, $scope.word.word_id, 2, null, null);
			//
			//				}
			//
			//				$state.go("word_read", {
			//					'_index': $scope.index
			//				})
			//
			//				//				} else {
			//				//					$rootScope.Confirm("您必须完成此练习才能获得小星星哦", "继续本练习", "下一练习", function() {}, function() {
			//				//						$state.go("word_read", {
			//				//							'_index': $scope.index
			//				//						})
			//				//					})
			//				//				}
			//
			//			} else {
			$state.go("word_read", {
				'_index': $scope.index
			})
			//			}

		}

		$scope.playDetailWord1 = function(audio) {
			$rootScope.playWord(audio, $("#detail_paly_1"));
			$scope.isplay1 = true;
			if($scope.word.task1 < 2) {
				$rootScope.jifenadd = $rootScope.jifenadd + (2 - $scope.word.task1);
				$scope.word.task1 = 2;
				$scope.initGrade()
				$rootScope.userCompletedTask($rootScope.userinfo.id, $scope.word.book_id, $scope.word.unit_id, $scope.word.word_id, 2, null, null);
			}

		}
		$scope.playDetailWord0 = function(audio) {
			$rootScope.playWord(audio, $("#detail_paly_0"));
			$scope.isplay0 = true;

			if($scope.isplay1 && $scope.isplay0 && $scope.word.task1 < 3) {
				$rootScope.jifenadd = $rootScope.jifenadd + (3 - $scope.word.task1);
				$scope.word.task1 = 3;
				$scope.initGrade();
				$rootScope.userCompletedTask($rootScope.userinfo.id, $scope.word.book_id, $scope.word.unit_id, $scope.word.word_id, 3, null, null);
				showStarImgAnimate();
			} else if($scope.isplay1 || $scope.isplay0) {

				if($scope.word.task < 2) {
					$rootScope.jifenadd = $rootScope.jifenadd + (2 - $scope.word.task1);
					$scope.word.task1 = 2;
					$scope.initGrade()
					$rootScope.userCompletedTask($rootScope.userinfo.id, $scope.word.book_id, $scope.word.unit_id, $scope.word.word_id, 2, null, null);
					showStarImgAnimate();
				}
			}
		}

	})
	//#endregion
	.controller('word_test1Ctrl', function($rootScope, $scope, $state, $stateParams, $http, $ionicActionSheet, $ionicPopover, $interval) {
		$scope.index = parseInt($stateParams._index);
		$scope.word = $rootScope.words[$scope.index];
		$scope.if_do_ = false;
		$scope.if_finish_ = false;
		$scope.now_page = 0;
		$scope.ifShowJifen = false;
		var has_submited = 0;
		$scope.wrong_times = 0;
		$scope.button_name = "继续";
		$scope.playTestExercise = function(audio) {
			$rootScope.playExercise(audio, $("#exercise_play"));
		}

		if($scope.word.exercises.length == 1) {
			$scope.button_name = "下一个单词";
		}

		angular.forEach($scope.word.exercises, function(exercise, idx) {
			exercise.myanswer = -1;
			//#region 习题处理

			//#region 1. 看图选择题
			if(exercise.type == 1) {
				var s = exercise.question.split('^');
				exercise.question_en = s[0];
				exercise.question_en = exercise.question_en.replace('___', '______')
				if(s.length >= 2)
					exercise.question_zh = s[1];
			}
			//#endregion

			//#region 2. 选图填空
			if(exercise.type == 2) {
				var s = exercise.question.split('^');
				exercise.question_en = s[0];
				exercise.question_en = exercise.question_en.replace('___', '______')
				if(s.length >= 2)
					exercise.question_zh = s[1];

				s = exercise.answer.split('^');
				exercise.answer_index = s[0];
				if(s.length >= 2)
					exercise.answer_word = s[1];
			}
			//#endregion

			//#region 3 句子排序
			if(exercise.type == 3) {
				exercise.keys = exercise.answer.split('^');
				exercise.options = exercise.answer.split('^');

				function sortNumber(a, b) {
					return a < b
				}
				exercise.options.sort(sortNumber);
			}

			if(exercise.type == 5) {

				exercise.question = exercise.answer.split("^").sort(function() {
					return Math.random() - 0.5
				});

				var items_formats = [];

				angular.forEach(exercise.items.split('\n'), function(data, index) {
					var s = data.split('^');
					var zh = [];
					var zh0 = '';
					var zh1 = '';
					var en = '';

					zh = s[0].split('____');
					if(s.length >= 2) {
						en = s[1];
					}

					zh0 = zh[0];
					if(zh.length >= 2) {
						zh1 = zh[1];
					}

					items_formats.push({
						zh0: zh0,
						zh1: zh1,
						en: en
					});

				});
				exercise.items_formats = items_formats;
			}

			if(exercise.type == 6) {
				exercise.question = exercise.answer.split("^").sort(function() {
					return Math.random() - 0.5
				});
			}

			//#endregion
		})

		$scope.exercise = $scope.word.exercises[$scope.now_page];

		//积分展示 星星展示
		$scope.initGrade = function() {

			if($scope.exercise.point != null || $scope.exercise.point != undefined) {
				$scope.exercise.point = parseInt($scope.exercise.point);
			} else {
				$scope.exercise.point = 0;
			}

			$scope.scoreObjArr = [];
			for(var j = 0; j < 3; j++) {
				if(j < $scope.exercise.point) {
					$scope.scoreObjArr.push({
						"ifget": true
					})
				} else {
					$scope.scoreObjArr.push({
						"ifget": false
					})
				}
			}

		}
		$scope.initGrade(); //设置用户积分情况即星星获取情况

		$scope.init = function() {
			if($scope.exercise.type == 2) {
				setTimeout(function() {
					$scope.playTestExercise($scope.exercise.media);
				}, 500)
			}

			if($scope.exercise.type == 5) {

				setTimeout(function() {
					$(".question_type_" + $scope.exercise.id + " .draggable label").draggable({
						revert: true,
						scroll: false,
						drag: function(event, ui) {
							//                                  $ionicSlideBoxDelegate.$getByHandle('slide_exercise').enableSlide(false);
						}
					});
					$(".question_type_" + $scope.exercise.id + " .droppable .item").droppable({
						hoverClass: "hover",
						accept: ".draggable label",
						drop: function(event, ui) {
							//                                  $ionicSlideBoxDelegate.$getByHandle('slide_exercise').enableSlide(true);

							if($(this).find(".answer_box").text() != "") {
								return;
							}

							$(this).find(".answer_box").text(ui.draggable.text()).parent().one("click", function() {
								if($scope.exercise.myanswer == -1) {
									var txt = $(this).find(".answer_box").text()

									$(".question_type_" + $scope.exercise.id + " .draggable label").each(function() {
										if($(this).text() == txt) {
											$(this).css("visibility", "visible");
											return false;
										}
									});

									$(this).find(".answer_box").text("");
								}

							});

							ui.draggable.css("visibility", "hidden");
						}
					});
				}, 666);

			}

			//#region 6. 图片匹配
			if($scope.exercise.type == 6) {
				setTimeout(function() {
					$(".question_type_" + $scope.exercise.id + " .draggable label").draggable({
						revert: true,
						scroll: false,
						drag: function(event, ui) {
							//						$ionicSlideBoxDelegate.$getByHandle('slide_exercise').enableSlide(false);
						}
					});
					$(".question_type_" + $scope.exercise.id + " .droppable .col").droppable({
						hoverClass: "hover",
						accept: ".draggable label",
						drop: function(event, ui) {
							//						$ionicSlideBoxDelegate.$getByHandle('slide_exercise').enableSlide(true);

							if($(this).find(".answer_box").text() != "") {
								return;
							}
							$(this).find(".answer_box").text(ui.draggable.text()).parent().one("click", function() {
								if($scope.exercise.myanswer == -1) {
									var txt = $(this).find(".answer_box").text()

									$(".question_type_" + $scope.exercise.id + " .draggable label").each(function() {
										if($(this).text() == txt) {
											$(this).css("visibility", "visible");
											return false;
										}
									});
									$(this).find(".answer_box").text("");
								}
							});

							ui.draggable.css("visibility", "hidden");
						}
					});
				}, 666);

				//			$scope.tip();
			}
			//#endregion

		}
		$scope.init();

		//根据用户点击或者拖拽错误次数设置用户积分（星星获取情况） 一次正确三颗星 错一次 两颗星 错误大于一次 一颗星
		$scope.complateGrade = function() {

			if($scope.wrong_times == 0 && $scope.exercise.point < 3) {
				$rootScope.jifenadd = $rootScope.jifenadd + (3 - $scope.exercise.point);
				$scope.exercise.point = 3;
				$rootScope.userCompletedExercise($rootScope.userinfo.id, $scope.word.book_id, $scope.word.unit_id, $scope.word.word_id, $scope.exercise.id, 3);
				$scope.initGrade();
				showStarImgAnimate();
			} else if($scope.wrong_times == 1 && $scope.exercise.point < 2) {
				$rootScope.jifenadd = $rootScope.jifenadd + (2 - $scope.exercise.point);
				$scope.exercise.point = 2;
				$rootScope.userCompletedExercise($rootScope.userinfo.id, $scope.word.book_id, $scope.word.unit_id, $scope.word.word_id, $scope.exercise.id, 2);
				$scope.initGrade();
				showStarImgAnimate();
			} else if($scope.wrong_times > 1 && $scope.exercise.point < 1) {
				$rootScope.jifenadd = $rootScope.jifenadd + 1;
				$scope.exercise.point = 1;
				$rootScope.userCompletedExercise($rootScope.userinfo.id, $scope.word.book_id, $scope.word.unit_id, $scope.word.word_id, $scope.exercise.id, 1);
				$scope.initGrade();
				showStarImgAnimate();
			}
			//			if($scope.now_page == $scope.word.exercises.length - 1) {
			//				$scope.cupcount();
			//			}

		}

		//#region 看图选择
		$scope.choose_1 = function(i, exerciseIndex, item) {

			$(".type_1_html").html(item.question_en.replace('______', '<span class="green">' + item.items.split('\n')[i] + '</span>').replace(/[\r\n]/g, ""));

			item.myanswer = i;
			item.answered = true;
			if(item.myanswer == item.answer) {
				//正确
				playAudio(true);

				//				if(exerciseIndex < $scope.word.exercises.length - 1) {
				//					setTimeout(function() {
				//
				//					}, 1000);
				//				}
				$scope.complateGrade();

			} else {
				//错误
				$scope.wrong_times = $scope.wrong_times + 1;
				playAudio(false);
			}

		}

		//听录音选图片
		$scope.choose_2 = function(i, exerciseIndex, item) {

			$(".type_2_html").html(item.question_en.replace('______', '<span class="green">' + item.answer_word + '</span>').replace(/[\r\n]/g, ""));
			item.myanswer = i;
			item.answered = true;
			if(item.myanswer == item.answer_index) {
				//正确
				playAudio(true);
				//				if(exerciseIndex < $scope.word.exercises.length - 1) {
				//					setTimeout(function() {}, 1000);
				//				}

				$scope.complateGrade();

			} else {
				//错误
				playAudio(false);
				$scope.wrong_times = $scope.wrong_times + 1;

			}
		}

		//句子排序
		$scope.choose_3 = function(index) {

			if(has_submited < $scope.exercise.keys.length) {
				if($scope.exercise.options[index] == $scope.exercise.keys[has_submited]) {
					$('#key_' + has_submited).html($scope.exercise.keys[has_submited]);
					has_submited = has_submited + 1;
					playAudio(true);
					if(has_submited == $scope.exercise.keys.length) {
						$scope.complateGrade();
					}

				} else {
					playAudio(false);
					$scope.wrong_times = $scope.wrong_times + 1;
				}
			}

		}

		//拖拽单词
		$scope.choose_5 = function(exerciseIndex, item) {
			item.myanswer = $(".question_type_" + item.id + " .answer_box").map(function() {
				return $(this).text()
			}).get().join("^");

			if(item.myanswer.length != item.answer.length) {
				$rootScope.Alert("请完成所有单词和选项的匹配。");
				item.myanswer = -1;
			} else {
				var myanswers = item.myanswer.split('^');
				var answers = item.answer.split('^');

				item.result = [];

				for(i = 0; i < myanswers.length; i++) {
					if(myanswers[i] != answers[i]) {
						$scope.wrong_times = $scope.wrong_times + 1;
					}
					item.result[i] = (myanswers[i] == answers[i]);
				}

				$scope.answered++;

				$(".draggable").hide();
				if($scope.wrong_times > 0) {
					playAudio(false);
				} else {
					playAudio(true);
				}
				$scope.complateGrade();

			}

		}
		//拖拽单词到图
		$scope.choose_6 = function(exerciseIndex, item) {
			item.myanswer = $(".question_type_" + item.id + " .answer_box").map(function() {
				return $(this).text()
			}).get().join("^");

			if(item.myanswer.length != item.answer.length) {
				$rootScope.Alert("请匹配完所有的单词和图片。");
				item.myanswer = -1;
			} else {
				var myanswers = item.myanswer.split('^');
				var answers = item.answer.split('^');

				item.result = [];

				for(i = 0; i < myanswers.length; i++) {
					if(myanswers[i] != answers[i]) {
						$scope.wrong_times = $scope.wrong_times + 1;
					}
					item.result[i] = (myanswers[i] == answers[i]);
					$(".question_type_" + item.id + " .droppable .col").eq(i).addClass((myanswers[i] == answers[i] ? 'correct' : 'error'));
				}

				$scope.answered++;
				if($scope.wrong_times > 0) {
					playAudio(false);
				} else {
					playAudio(true);
				}
				$scope.complateGrade();

			}

		}

		//    #endregion 

		$scope.cupcount = function() {

			if($rootScope.if_do && $rootScope.if_finish) {
				$state.go("word_detail", {
					'_index': $scope.index
				})
			} else {

				if(!$rootScope.if_do) {

					if(parseInt($scope.word.task1) > 0 && parseInt($scope.word.task2) > 0 && parseInt($scope.word.task3) > 0) {

						if($scope.word.exercises.length > 0) {
							var if_do = true;
							var if_finish = true;

							for(var j = 0; j < $scope.word.exercises.length; j++) {
								if($scope.word.exercises[j].point == null || $scope.word.exercises[j].point == 0) {
									if_do = false;
									$state.go("word_detail", {
										'_index': $scope.index
									})
								}
							}
							if(if_do) {
								if(parseInt($scope.word.task1) == 3 && parseInt($scope.word.task2) == 3 && parseInt($scope.word.task3) == 3) {
									for(var j = 0; j < $scope.word.exercises.length; j++) {
										if($scope.word.exercises[j].point != 3) {
											if_finish = false;
											$scope.if_do_ = if_do;
											$scope.if_finish_ = if_finish;
											setTimeout(function() {
												$state.go("word_detail", {
													'_index': $scope.index
												})
											}, 3000)
											break;
										}
									}

									if(if_finish) {
										//获得功成杯
										//获得名就杯
										$scope.if_do_ = if_do;
										$scope.if_finish_ = if_finish;

										setTimeout(function() {
											$state.go("word_detail", {
												'_index': $scope.index
											})
										}, 3000)

									}

								} else {
									$scope.if_do_ = if_do;
									$scope.if_finish_ = false;
									setTimeout(function() {
										$state.go("word_detail", {
											'_index': $scope.index
										})
									}, 3000)
								}
							}

						}

					} else {
						$state.go("word_detail", {
							'_index': $scope.index
						})
					}
				} else {

					if(parseInt($scope.word.task1) == 3 && parseInt($scope.word.task2) == 3 && parseInt($scope.word.task3) == 3) {
						if($scope.word.exercises.length > 0) {
							var if_finish = true;
							for(var j = 0; j < $scope.word.exercises.length; j++) {
								if(parseInt($scope.word.exercises[j].point) != 3) {
									if_finish = false;
									break;
								}
							}
							if(!if_finish) {
								$state.go("word_detail", {
									'_index': $scope.index
								})
							} else {
								//获得名就杯
								$scope.if_finish_ = if_finish;
								setTimeout(function() {
									$state.go("word_detail", {
										'_index': $scope.index
									})
								}, 3000)
							}

						}

					} else {
						$state.go("word_detail", {
							'_index': $scope.index
						})
					}
				}

			}

		}

		//    #endregion 

		// 统计最后一个单词学习获得奖杯情况
		$scope.cupcount2 = function() {

			if($rootScope.if_do && $rootScope.if_finish) {
				$scope.ifShowJifen = true;
			} else {

				if(!$rootScope.if_do) {

					if(parseInt($scope.word.task1) > 0 && parseInt($scope.word.task2) > 0 && parseInt($scope.word.task3) > 0) {

						if($scope.word.exercises.length > 0) {
							var if_do = true;
							var if_finish = true;

							for(var j = 0; j < $scope.word.exercises.length; j++) {
								if($scope.word.exercises[j].point == null || $scope.word.exercises[j].point == 0) {
									if_do = false;
									$scope.ifShowJifen = true;
									break;
								}
							}
							if(if_do) {
								if(parseInt($scope.word.task1) == 3 && parseInt($scope.word.task2) == 3 && parseInt($scope.word.task3) == 3) {
									for(var j = 0; j < $scope.word.exercises.length; j++) {
										if($scope.word.exercises[j].point != 3) {
											if_finish = false;
											$scope.if_do_ = if_do;
											$scope.if_finish_ = if_finish;
											setTimeout(function() {
												$scope.if_do_ = false;
												$scope.ifShowJifen = true;
												$scope.$apply();
											}, 3000)
											break;
										}
									}

									if(if_finish) {
										//获得功成杯
										//获得名就杯
										$scope.if_do_ = if_do;
										$scope.if_finish_ = if_finish;

										setTimeout(function() {
											$scope.if_do_ = false;
											$scope.if_finish_ = false;
											$scope.ifShowJifen = true;
											$scope.$apply();
										}, 3000)

									}

								} else {
									$scope.if_do_ = if_do;
									$scope.if_finish_ = false;
									setTimeout(function() {
										$scope.if_do_ = false;
										$scope.ifShowJifen = true;
										$scope.$apply();
									}, 3000)
								}
							}

						}

					} else {
						$scope.ifShowJifen = true;
					}
				} else {

					if(parseInt($scope.word.task1) == 3 && parseInt($scope.word.task2) == 3 && parseInt($scope.word.task3) == 3) {
						if($scope.word.exercises.length > 0) {
							var if_finish = true;
							for(var j = 0; j < $scope.word.exercises.length; j++) {
								if(parseInt($scope.word.exercises[j].point) != 3) {
									if_finish = false;
									break;
								}
							}
							if(!if_finish) {
								$scope.ifShowJifen = true;

							} else {
								//获得名就杯
								$scope.if_finish_ = if_finish;
								setTimeout(function() {
									$scope.if_finish_ = false;
									$scope.ifShowJifen = true;
									$scope.$apply();
								}, 3000)
							}

						}

					} else {
						$scope.ifShowJifen = true;
					}
				}

			}

		}

		$scope.nextTest = function() {
			$scope.wrong_times = 0;

			//解决多个排序题内容不刷新问题
			if($scope.exercise.type == 3) {
				$('span[id^="key_"]').each(function() {
					$(this).html("&#12288;");
				});
			}

			if($scope.now_page < $scope.word.exercises.length - 1) {
				has_submited = 0;
				$scope.now_page = $scope.now_page + 1;
				$scope.exercise = $scope.word.exercises[$scope.now_page];
				if($scope.now_page == $scope.word.exercises.length - 1) {
					$scope.button_name = "下一个单词";
				}
				$scope.init();
				$scope.initGrade(); //设置用户积分情况即星星获取情况

			} else {
				
				if($scope.index < $rootScope.words.length - 1) {
					$scope.index = $scope.index + 1;
					$scope.cupcount();
				} else {
					$scope.cupcount2();
					if($scope.ifShowJifen) {
						var jifen_sum = 0;
						for(var i = 0; i < $rootScope.words.length; i++) {
							if($rootScope.words[i].task1) {
								jifen_sum = jifen_sum + parseInt($rootScope.words[i].task1);
							}
							if($rootScope.words[i].task2) {
								jifen_sum = jifen_sum + parseInt($rootScope.words[i].task2);
							}
							if($rootScope.words[i].task3) {
								jifen_sum = jifen_sum + parseInt($rootScope.words[i].task3);
							}
							if($rootScope.words[i].exercises) {
								for(var j = 0; j < $rootScope.words[i].exercises.length; j++) {
									if($rootScope.words[i].exercises[j].point) {
										jifen_sum = jifen_sum + parseInt($rootScope.words[i].exercises[j].point);
									}
								}
							}
						}
						$scope.jifensum = jifen_sum;
						//						if($rootScope.jifenadd > 0) {
						//							playGood();
						//						} else {
						//							playJiayou();
						//						}
					}

				}
			}

		}

		//		$scope.popover = $ionicPopover.fromTemplateUrl('my-popover.html', {
		//			scope: $scope
		//		});
		//
		//		// .fromTemplateUrl() 方法
		//		$ionicPopover.fromTemplateUrl('my-popover.html', {
		//			scope: $scope
		//		}).then(function(popover) {
		//			$scope.popover = popover;
		//		});
		//
		//		$scope.openPopover = function($event) {
		//			$scope.popover.show($event);
		//		};
		//		$scope.closePopover = function() {
		//			$scope.popover.hide();
		//		};
		//		// 清除浮动框
		//		$scope.$on('$destroy', function() {
		//			$scope.popover.remove();
		//		});
		//		// 在隐藏浮动框后执行
		//		$scope.$on('popover.hidden', function() {
		//			// 执行代码
		//		});
		//		// 移除浮动框后执行
		//		$scope.$on('popover.removed', function() {
		//			// 执行代码
		//		});
		$scope.studyWord = function() {

			$state.go("tab.dy_home")
		}
		$scope.startGame = function() {
			$state.go("tab.yx_home")
		}

	})

	.controller('word_readCtrl', function($rootScope, $scope, $state, $stateParams, $http, $ionicActionSheet, $interval) {

		$scope.recorded = false;
		$scope.playing = false;
		$scope.score = 0;
		$scope.seconds = 0;
		$('#progress_bar').css("backgroundColor", "#ffffff")
		$scope.index = parseInt($stateParams._index);
		$scope.word = $rootScope.words[$scope.index];
		$scope.time_long = 3;
		$scope.per_progress = 23;

		if($scope.word.en.length > 8) {
			$scope.time_long = 5;
			$scope.per_progress = 14;
		}

		$scope.isFinished = false;
		//	if($rootScope.words[$scope.index].task2 == "1") {
		//		$scope.isFinished = true;
		//	}

		$scope.initGrade = function() {
			if($scope.word.task2 != null && $scope.word.task2 != undefined) {
				$scope.word.task2 = parseInt($scope.word.task2);
			} else {
				$scope.word.task2 = 0;
			}

			$scope.scoreObjArr = [];
			for(var j = 0; j < 3; j++) {
				if(j < $scope.word.task2) {
					$scope.scoreObjArr.push({
						"ifget": true
					})
				} else {
					$scope.scoreObjArr.push({
						"ifget": false
					})
				}
			}
		}

		$scope.initGrade();

		if($scope.word.zh.split('/').length - 1 == 2 && $scope.word.zh.indexOf('/') > 10) {

			setTimeout(function() {
				$('#explain_word1').html($scope.word.zh.replace('/', '<br>/'));
			}, 100);
		}

		$scope.nextTest = function(str) {

			//			if(!$scope.isFinished) {
			//				$rootScope.Confirm("您必须完成此练习才能获得小星星哦", "继续本练习", "下一练习", function() {}, function() {
			//					$state.go("word_spell", {
			//						'_index': $scope.index
			//					})
			//				})
			//			} else {
			$state.go("word_spell", {
				'_index': $scope.index
			})
			//			}

		}
		// Record audio
		//mp3不支持
		var remoreAudioUrl = "";
		var mediaRec;
		var src = "audio.amr";

		if($rootScope.isIOS) {
			src = audioRecord;
		}

		$scope.clicked = false;
		$scope.recordAudio = function() {
			$scope.if_low_show = false;

			if(!$scope.clicked) {
				$scope.recorded = false;
				$scope.clicked = true;
				$scope.seconds = 0;
				$scope.count = 0;

				mediaRec = new Media(src,
					// success callback
					function() {},
					// error callback
					function(err) {
						$scope.clicked = false;
					}
				);
				// Record audio
				mediaRec.startRecord();

				$("#read_record").attr("src", "img/record_gif.gif")
				var timer = $interval(function() {
					$scope.count++;
					if($scope.count == 1) {} else if($scope.count > $scope.time_long) {
						$scope.seconds = $scope.time_long;
					} else {
						$scope.seconds = $scope.count - 1;
					}

					$('.progress_bar').width(($scope.per_progress * $scope.seconds) + '%').css("backgroundColor", "#0c7cd6");
					if($scope.count == $scope.time_long + 1) {

						$("#read_record").attr("src", "img/xiaoxue_cut_09.png")
						$scope.recorded = true;
						$scope.clicked = false;

						mediaRec.stopRecord();
						$scope.uploadAudio($scope.word.en);
					}

					if($scope.count > $scope.time_long) {
						$interval.cancel(timer); //停止并清除
					}

				}, 1000, 10);

			}
		}
		// Play audio
		//

		$scope.uploadAudio = function(word) {  

			$scope.if_low_show = false;

			$rootScope.LoadingShow();
			var fileURL = cordova.file.externalRootDirectory + "audio.amr";

			if($rootScope.isIOS) {
				fileURL = iosFileURL;
			}

			var options = new FileUploadOptions();  
			options.fileKey = "audio";  
			options.fileName = fileURL.substr(fileURL.lastIndexOf('/') + 1);  
			options.mimeType = "audio/x-amr";    //上传参数
			if($rootScope.isIOS) {
				options.mimeType = "audio/wav";
			}
			var params = {};  
			params.text = word;
			params.format = 'amr';
			if($rootScope.isIOS) {
				options.format = "wav";
			}
			options.params = params;   
			var ft = new FileTransfer();   //上传地址
			var SERVER = $rootScope.rootUrl + "/stt";

			ft.upload(fileURL, encodeURI(SERVER), function(r) {     
				$rootScope.LoadingHide();
				var response = JSON.parse(r.response)
				if(response.rate) {
					$scope.score = parseInt(response.rate);
				} else {
					$scope.score = 0;
				}

				if($scope.score < 60) {
					$scope.if_low_show = true;

					//					$rootScope.Confirm("您的发音不是很标准哦 或者 没有录好!", "继续发音练习", "下一练习", function() {
					//
					//						$scope.recordAudio();
					//
					//					}, function() {
					//						$state.go("word_spell", {
					//							'_index': $scope.index
					//						})
					//					})
				} else {
					if($scope.score >= 90 && $scope.word.task2 < 3) {
						$rootScope.jifenadd = $rootScope.jifenadd + (3 - $scope.word.task2);
						//							$rootScope.words[$scope.index].task2 = "3";
						$rootScope.userCompletedTask($rootScope.userinfo.id, $scope.word.book_id, $scope.word.unit_id, $scope.word.word_id, null, 3, null);
						$scope.word.task2 = 3;
						$scope.initGrade();
						showStarImgAnimate();
					} else if($scope.score >= 80 && $scope.word.task2 < 2) {
						$rootScope.jifenadd = $rootScope.jifenadd + (2 - $scope.word.task2);
						$rootScope.userCompletedTask($rootScope.userinfo.id, $scope.word.book_id, $scope.word.unit_id, $scope.word.word_id, null, 2, null);
						$scope.word.task2 = 2;
						$scope.initGrade();
						showStarImgAnimate();
					} else if($scope.score >= 60 && $scope.word.task2 < 1) {
						$rootScope.jifenadd = $rootScope.jifenadd + 1;
						$rootScope.userCompletedTask($rootScope.userinfo.id, $scope.word.book_id, $scope.word.unit_id, $scope.word.word_id, null, 1, null);
						$scope.word.task2 = 1;
						$scope.initGrade();
						showStarImgAnimate();
					}

				}

			}, function(error) {     
				$rootScope.LoadingHide();
				alert("上传失败!请稍后再试 ");  
			}, options);   

		}

		$scope.playRecordAudio = function() {

			if(!$scope.playing) {
				$scope.seconds = 0;
				$scope.count = 0;
				$scope.playing = true;

				mediaRec.play();

				$("#read_play").attr("src", "img/play_gif.gif")
				var timer2 = $interval(function() {
					$scope.count++;
					if($scope.count == 1) {} else if($scope.count > $scope.time_long) {
						$scope.seconds = $scope.time_long;
					} else {
						$scope.seconds = $scope.count - 1;
					}
					$('.progress_bar').width(($scope.per_progress * $scope.seconds) + '%').css("backgroundColor", "#0c7cd6");
					if($scope.seconds == $scope.time_long) {
						$scope.playing = false;
						mediaRec.stop();
						$("#read_play").attr("src", "img/xiaoxue_cut_07.png")
					}
					if($scope.count > $scope.time_long) {
						$interval.cancel(timer2); //停止并清除
					}
				}, 1000, 10);
			}
		}

		$scope.playReadWord1 = function(audio) {
			$rootScope.playWord(audio, $("#read_paly_1"));
		}
		$scope.playReadWord0 = function(audio) {
			$rootScope.playWord(audio, $("#read_paly_0"));
		}

	})

	.controller('word_spellCtrl', function($rootScope, $scope, $state, $stateParams, $http, $ionicActionSheet) {

		$scope.index = parseInt($stateParams._index);
		$scope.word = $rootScope.words[$scope.index];
		$scope.spell = $scope.word.en;

		var has_submit;
		//	if($rootScope.words[$scope.index].task3 == "1") {
		//		$scope.isFinished = true;
		//	}

		$scope.initGrade = function() {
			if($scope.word.task3 != null && $scope.word.task3 != undefined) {
				$scope.word.task3 = parseInt($scope.word.task3);
			} else {
				$scope.word.task3 = 0;
			}
			$scope.scoreObjArr = [];
			for(var j = 0; j < 3; j++) {
				if(j < $scope.word.task3) {
					$scope.scoreObjArr.push({
						"ifget": true
					})
				} else {
					$scope.scoreObjArr.push({
						"ifget": false
					})
				}
			}
		}

		$scope.initGrade();

		$scope.initData = function() {
			has_submit = 0;
			$scope.questions = [];
			$scope.keys = [];
			$scope.letters = [];
			$scope.wrong_times = 0;

			function sortNumber() {
				return Math.random() - 0.5;
			}

			if($scope.spell.indexOf(" ") == -1 && $scope.spell.indexOf("'") == -1 && $scope.spell.indexOf("-") == -1 && $scope.spell.indexOf(".") == -1 && $scope.spell.indexOf("…") == -1 && $scope.spell.indexOf("’")) {
				for(var i = 0; i < $scope.spell.length; i++) {
					$scope.keys.push({
						"id": i,
						"s": $scope.spell[i]
					});
					$scope.questions.push("#");
					if(!$scope.letters.contains($scope.spell.substring(i, i + 1))) {
						$scope.letters.push($scope.spell.substring(i, i + 1));
						$scope.letters = $scope.letters.sort(sortNumber);
					}
				}
			} else {
				for(var i = 0; i < $scope.spell.length; i++) {
					if(i % 3 == 0 && $scope.spell[i] != "'" && $scope.spell[i] != " " && $scope.spell[i] != "-" && $scope.spell[i] != "." && $scope.spell[i] != "…" && $scope.spell[i] != "’") {
						$scope.keys.push({
							"id": i,
							"s": $scope.spell[i]
						});
						$scope.questions.push("#");
					} else {
						$scope.questions.push($scope.spell[i]);
					}
				}
				for(var j = 0; j < $scope.keys.length; j++) {
					if(!$scope.letters.contains($scope.keys[j].s)) {
						$scope.letters.push($scope.keys[j].s);
						$scope.letters = $scope.letters.sort(sortNumber);
					}
				}

			}

		}

		$scope.initData();

		if($scope.word.zh.split('/').length - 1 == 2 && $scope.word.zh.indexOf('/') > 10) {

			setTimeout(function() {
				$('#explain_word2').html($scope.word.zh.replace('/', '<br>/'));
			}, 100);
		}

		$scope.reSpell = function() {
			$scope.initData();
		}

		$scope.chooseAnswer = function(index) {

			if(has_submit < $scope.keys.length) {
				if($scope.letters[index] == $scope.keys[has_submit].s) {
					playAudio(true);
					$('#spellw_' + $scope.keys[has_submit].id).html($scope.keys[has_submit].s);
					has_submit = has_submit + 1;
					if(has_submit == $scope.keys.length) {
						//						$scope.isFinished = true;
						$scope.playSpellWord($scope.word.audio_0);

						if($scope.wrong_times == 0 && $scope.word.task3 < 3) {
							$rootScope.jifenadd = $rootScope.jifenadd + (3 - $scope.word.task3);
							//						$rootScope.words[$scope.index].task3 = "3";
							$rootScope.userCompletedTask($rootScope.userinfo.id, $scope.word.book_id, $scope.word.unit_id, $scope.word.word_id, null, null, 3);
							$scope.word.task3 = 3;
							$scope.initGrade();
							showStarImgAnimate();
						} else if($scope.wrong_times == 1 && $scope.word.task3 < 2) {
							$rootScope.jifenadd = $rootScope.jifenadd + (2 - $scope.word.task3);
							//						$rootScope.words[$scope.index].task3 = "2";
							$rootScope.userCompletedTask($rootScope.userinfo.id, $scope.word.book_id, $scope.word.unit_id, $scope.word.word_id, null, null, 2);
							$scope.word.task3 = 2;
							$scope.initGrade();
							showStarImgAnimate();
						} else if($scope.wrong_times > 1 && $scope.word.task3 < 1) {
							$rootScope.jifenadd = $rootScope.jifenadd + 1;
							//						$rootScope.words[$scope.index].task3 = "1";
							$rootScope.userCompletedTask($rootScope.userinfo.id, $scope.word.book_id, $scope.word.unit_id, $scope.word.word_id, null, null, 1);
							$scope.word.task3 = 1;
							$scope.initGrade();
							showStarImgAnimate();
						}
					}
				} else {
					$scope.wrong_times = $scope.wrong_times + 1;
					playAudio(false);
				}
			}

		}

		$scope.playSpellWord = function(audio) {
			$rootScope.playWord(audio, $("#spell_play"));
		}

		$scope.word_show = false;
		$scope.showWord = function() {
			$scope.word_show = true;
			setTimeout(function() {
				$scope.word_show = false;
				$scope.$apply();
			}, 500)
		}

		$scope.nextTest = function(str) {

			//		if(!$scope.isFinished) {
			//			//			$rootScope.Alert("您必须完成此练习才能获得小星星哦");
			//
			//			$rootScope.Confirm("您必须完成此练习才能获得小星星哦", "继续本练习", "下一练习", function() {
			//
			//			}, function() {
			//				if($scope.word.exercises.length > 0) {
			//					$state.go("word_test1", {
			//						'_index': $scope.index
			//					})
			//				} else {
			//					if($scope.index < $rootScope.words.length - 1) {
			//						$scope.index = $scope.index + 1;
			//						$state.go("word_detail", {
			//							'_index': $scope.index
			//						})
			//					}
			//				}
			//			})
			//
			//		} else {
			if($scope.word.exercises.length > 0) {
				$state.go("word_test1", {
					'_index': $scope.index
				})
			} else {
				if($scope.index < $rootScope.words.length - 1) {
					$scope.index = $scope.index + 1;
					$state.go("word_detail", {
						'_index': $scope.index
					})
				}
			}
			//		}

		}

	})

	.controller('yx_homeCtrl', function($rootScope, $scope, $state, $http, $ionicActionSheet) {

		$scope.units = [];
		$scope.sum_score = 0;
		$scope.getUnits = function(book_id) {
			var url = $rootScope.rootUrl + "/units";
			var data = {
				"user_id": $rootScope.userinfo.id,
				"book_id": book_id
			};
			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();

				if(response.error) {
					$rootScope.Alert(response.msg);
				} else {
					$scope.units = response;
					//				angular.forEach($scope.units, function(item) {
					//					$scope.sum_score = $scope.sum_score + parseInt(item.game_completed_total);
					//				})
				}

			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');
				return;
			});
		}

		$scope.user_game_order = function(book_id) {
			$scope.game_completed_total = 0;
			var url = $rootScope.rootUrl + "/user_game_order";
			var data = {
				"user_id": $rootScope.userinfo.id,
				"book_id": book_id
			};
			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();

				if(response.error) {
//					$rootScope.Alert(response.msg);
				} else {
					$scope.game_completed_total = response.game_completed_total;
					$scope.sort_order = response.sort_order;
				}

			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');
				return;
			});
		}

		if($rootScope.mybook) {
			$rootScope.LoadingShow();
			$scope.getUnits($rootScope.mybook.id);
			$scope.user_game_order($rootScope.mybook.id);
		}

		$scope.gameTop10 = function() {
			$state.go("me_gametop10")
		}

		$scope.startGame = function(unit_id, index, _index) {
			if(_index > 0 && !$rootScope.userinfo.active) {
				$state.go("me_appvcode")
			} else {
				$state.go("yx_main", {
					"unit_id": unit_id,
					"index": index
				})
			}
		}

	})

	.controller('yx_mainCtrl', function($rootScope, $scope, $state, $stateParams, $http, $ionicActionSheet, $ionicPopup, $ionicPopover) {

		$scope.user_completed_task = function(book_id, unit_id, word_id) {
			$rootScope.LoadingShow();
			var url = $rootScope.rootUrl + "/user_completed_task";
			var data = {
				"user_id": $rootScope.userinfo.id,
				"book_id": book_id,
				"unit_id": unit_id,
				"word_id": word_id,
				"game": "1"
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

		//  alert（警告） 对话框
		$scope.showAlert = function() {
			var alertPopup = $ionicPopup.alert({
				title: '恭喜您完成了本单元的游戏',
				template: '<img src="img.">'
			});
			alertPopup.then(function(res) {
				history.go(-1);
			});
		};

		var unit_id = $stateParams.unit_id;
		var _index = parseInt($stateParams.index);

		$rootScope.LoadingShow();
		var url = $rootScope.rootUrl + "/words";
		var data = {
			"user_id": $rootScope.userinfo.id,
			"unit_id": unit_id
		};

		$http.post(url, data).success(function(response) {
			$rootScope.LoadingHide();
			if(response.error) {
				$rootScope.Alert(response.msg);
			} else {
				$scope.words = response;
				if($rootScope.times >= 0) {
					$rootScope.times = $rootScope.times + 1;
				} else {
					$rootScope.times = 0;
				}

				if(_index == $scope.words.length) _index = 0;
				//
				bdc.startgame(_index, $scope.words, $scope, 6);
			}

		}).error(function(response, status) {
			$rootScope.LoadingHide();
			$rootScope.Alert('连接失败！[' + response + status + ']');
			return;
		});

	})

	.controller('me_homeCtrl', function($rootScope, $scope, $state, $http, $ionicActionSheet) {
		$scope.point = 0;
		$scope.active_info = "";
		if($rootScope.userinfo.active) {
			$scope.active_info = "已激活";
		}
		$scope.user_point = function() {
			var url = $rootScope.rootUrl + "/user_point";
			var data = {
				"user_id": $rootScope.userinfo.id,
			};
			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();

				if(response.error) {
					$rootScope.Alert(response.msg);
				} else {
					//{
					//  "task1_completed_total": "54",
					//  "task2_completed_total": "16",
					//  "task3_completed_total": "23",
					//  "game_completed_total": "23",
					//  "point": 116
					//}
					$scope.point = response.point;
				}

			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');
				return;
			});
		}

		$scope.user_point();

		$scope.appVcode = function() {
			$state.go("me_appvcode")
		}

		$scope.myVcode = function() {
			$state.go("my_vcode")
		}
		$scope.myOrder = function() {
			$state.go("my_order")
		}

		$scope.gameTop10 = function() {
			$state.go("me_gametop10")
		}

		$scope.moreApp = function() {
			$state.go("more_apps");
		}

		$scope.bookSelect = function() {
			$state.go("grade")
		}

		$scope.aboutUs = function() {
			$state.go("me_aboutus")
		}
		$scope.openTaobao = function(url) {

			if(device.platform === 'iOS') {
				window.open(url, '_blank');
			} else if(device.platform === 'Android') {
				var success = function(message) {
					alert("success = " + message);
				};
				var fail = function(message) {
					alert("fail = " + message);
				};
				cordova.exec(success, fail, "OpenTaobaoLink", "taobaolink", [url]);
			}
		}

		$scope.showPointTips = function() {
			$rootScope.Alert("单词学习环节每得一颗星积分加1,游戏环节每完成一个词积分加1,即将提供积分兑换功能,感谢您的使用!");
		}

		$scope.wechatShare = function() {

			Wechat.isInstalled(function(installed) {
				if(installed) {
					Wechat.share({
						message: {
							title: "新课标同步背单词",
							description: "新课标同步背单词",
							mediaTagName: "新课标同步背单词",
							messageExt: "新课标同步背单词",
							messageAction: "<action>dotalist</action>",
							media: {
								type: Wechat.Type.IMAGE,
								image: "www/img/gongzhonghao.jpg"
							}
						},
						scene: Wechat.Scene.SESSION // share to Timeline
					}, function() {
						$rootScope.Alert("已分享");
					}, function(reason) {
						$rootScope.Alert("失败: " + reason);
					})
				} else {
					$rootScope.Alert("请安装微信客户端！")
				}
			}, function(reason) {
				$rootScope.Alert("Failed: " + reason);
			});
		}

		$scope.wechatShareApk = function() {

			Wechat.isInstalled(function(installed) {
				if(installed) {
					Wechat.share({
						message: {
							title: "小学新课标英语同步背单词APP",
							description: "给您提供不一样的背单词体验",
							mediaTagName: "给您提供不一样的背单词体验",
							messageExt: "给您提供不一样的背单词体验",
							messageAction: "<action>dotalist</action>",
							media: {
								type: Wechat.Type.WEBPAGE,
								webpageUrl: $rootScope.app.open_qq_url
							}
						},
						scene: Wechat.Scene.SESSION // share to Timeline
					}, function() {
						$rootScope.Alert("已分享");
					}, function(reason) {
						$rootScope.Alert("失败: " + reason);
					})
				} else {
					$rootScope.Alert("请安装微信客户端！")
				}
			}, function(reason) {
				$rootScope.Alert("Failed: " + reason);
			});
		}

		//下载图片
		$scope.saveVcode = function() {

			window.requestFileSystem(LocalFileSystem.PERSISTENT, 0, function(fs) {
				var url = "file:///android_asset/www/img/gongzhonghao.jpg";
				fs.root.getFile('xkbtbbdc.jpg', {
						create: true,
						exclusive: false
					},
					function(fileEntry) {
						download(fileEntry, url);
					}, onErrorCreateFile);

			}, onErrorLoadFs);
		}

		//下载文件
		function download(fileEntry, uri) {
			var fileTransfer = new FileTransfer();
			var fileURL = fileEntry.toURL();

			fileTransfer.download(
				uri,
				fileURL,
				function(entry) {
					$rootScope.Alert("已保存到sd卡根目录xkbtbbdc.jpg");
					console.log("下载成功！");
					console.log("文件保存位置: " + entry.toURL());
				},
				function(error) {
					console.log("下载失败！");
					console.log("error source " + error.source);
					console.log("error target " + error.target);
					console.log("error code" + error.code);
				},
				true, {
					//				headers: {
					//				    "Authorization": "Basic dGVzdHVzZXJuYW1lOnRlc3RwYXNzd29yZA=="
					//				}
				}
			);
		}

		//文件创建失败回调
		function onErrorCreateFile(error) {
			console.log("文件创建失败！")
		}

		//FileSystem加载失败回调
		function onErrorLoadFs(error) {
			console.log("文件系统加载失败！")
		}

		$scope.logout = function() {
			$rootScope.Confirm("确定要退出登录吗?", "取消", "确定", function() {}, function() {
				setStorage("userinfo", {});
				$state.go("login");

			})
		}

	})

	.controller('me_aboutusCtrl', function($rootScope, $ionicModal, $scope, $state, $http, $ionicActionSheet) {
		var now = new Date();
		$scope.year = now.getFullYear(); //年  
	})
	.controller('more_appsCtrl', function($rootScope, $ionicModal, $scope, $state, $http, $ionicActionSheet) {
		/**
		 * 获取更多相关app
		 * @param {Object} platform
		 */
		$scope.versions = function(platform) {
			$rootScope.LoadingShow();
			var url = $rootScope.rootUrl + "/versions";
			var data = {
				"platform": platform,
			};
			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();
				if(response.error) {
					$rootScope.Alert(response.msg);
				} else {
					$scope.apps = response;
					//				    var temp_array = [];
					//				    angular.forEach($scope.apps,function(item){
					//				    	if(item.publisher.indexOf('三年级起点')>0 && $rootScope.bookPublish.indexOf('三年级起点')>0){
					//				    		temp_array.push(item);
					//				    	}else if(item.publisher.indexOf('一年级起点')>0 && $rootScope.bookPublish.indexOf('一年级起点')>0){
					////				    		if(parseInt(item.id)>=parseInt($rootScope.app.id)&&temp_array.length<8){
					//				    			temp_array.push(item);
					////				    		}
					//				    	}
					//				    })
					//				    $scope.apps = temp_array;
				}
			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');
				return;
			});
		}
		//$scope.versions(device.platform)
		$scope.versions("android")
		$scope.downloadApk = function(index) {
			if(device.platform === 'iOS') {
				window.open($scope.apps[index].url, '_blank');
			} else if(device.platform === 'Android') {
				var success = function(message) {
					alert("success = " + message);
				};
				var fail = function(message) {
					alert("fail = " + message);
				};
				cordova.exec(success, fail, "OpenLink", "url", [$scope.apps[index].url]);
			}
		}
	})

	.controller('me_appvcodeCtrl', function($rootScope, $ionicModal, $scope, $state, $http, $ionicActionSheet) {

		var price = parseInt($rootScope.mybook.price);
		var sale_price = parseInt($rootScope.mybook.sale_price);
		$scope.change_price = price / 100;
		$scope.change_sale_price = sale_price / 100;

		$scope.vcode = function() {
			cordova.plugins.barcodeScanner.scan(
				function(result) {
					if(result.text) {
						if(result.text.split('?code=').length == 2) {
							$scope.bookActive($rootScope.userinfo.id, $rootScope.userinfo.book_id, result.text.split('?code=')[1]);
						}
					} else {
						$rootScope.Alert("扫描出错!");
					}
				},
				function(error) {
					//$rootScope.Alert("Scanning failed: " + error);
				}, {
					"preferFrontCamera": false, // iOS and Android
					"showFlipCameraButton": false, // iOS and Android
					"showTorchButton": true, // iOS and Android
					"prompt": "扫描烤鱿鱼小学单词激活二维码", // supported on Android only
					"formats": "QR_CODE,PDF_417", // default: all but PDF_417 and RSS_EXPANDED
					"orientation": "portrait" // Android only (portrait|landscape), default unset so it rotates with the device
				}
			);
		}

		$scope.vcodeByText = function() {
			if($('#vcode_text').val().length > 0) {
				$scope.bookActive($rootScope.userinfo.id, $rootScope.userinfo.book_id, $.trim($('#vcode_text').val()));
			}
		}

		/**
		 * 激活教材使用权
		 * @param {Object} user_id
		 * @param {Object} book_id
		 * @param {Object} code
		 */
		$scope.bookActive = function(user_id, book_id, code) {
			$rootScope.LoadingShow();
			var url = $rootScope.rootUrl + "/book_active";
			var data = {
				"user_id": user_id,
				"book_id": book_id,
				"code": code
			};
			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();
				if(response.error) {
					$rootScope.Alert(response.msg);
					$rootScope.userinfo.active = false;
				} else {
					$rootScope.userinfo.active = true;
					$rootScope.Alert("已成功激活，感谢您的使用！");
					$rootScope.goBack();
				}
			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');
				return;
			});
		}

		$scope.openTaobao = function(url) {

			if(device.platform === 'iOS') {
				window.open(url, '_blank');
			} else if(device.platform === 'Android') {
				var success = function(message) {
					alert("success = " + message);
				};
				var fail = function(message) {
					alert("fail = " + message);
				};
				cordova.exec(success, fail, "OpenTaobaoLink", "taobaolink", [url]);
			}
		}

		$scope.unifiedorder = function(userId, bookId) {
			$rootScope.LoadingShow();
			var url = $rootScope.rootUrl + "/unifiedorder";
			var data = {
				"user_id": userId,
				"book_id": bookId,
			};
			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();
				if(response.error) {
					$rootScope.Alert(response.msg);
				} else {
					setStorage("out_trade_no", response.out_trade_no,true);

					//{"appid":"wx83797cbb8b3ed830","partnerid":"1445757902","prepayid":"wx201703271007183fe1a941870532825354","package":"Sign=WXPay","noncestr":"7fcf20f3baee206808e7076f9df63e55","timestamp":1490580438,"sign":"10492DE2690F195B3E2588D06E11BD97","out_trade_no":"170327100718b14u1"}
					var params = {
						mch_id: response.partnerid, // merchant id
						prepay_id: response.prepayid, // prepay id returned from server
						nonce: response.noncestr, // nonce string returned from server
						timestamp: response.timestamp, // timestamp
						sign: response.sign, // signed string
					};
					Wechat.sendPaymentRequest(params, function() {
						$scope.queryorder($rootScope.userinfo.id, $rootScope.mybook.id, response.out_trade_no);
					}, function(reason) {
						$rootScope.Alert("支付失败: " + reason);
					});
				}
			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');
				return;
			});
		}

		$scope.queryorder = function(userId, bookId, out_trade_no) {
			$rootScope.LoadingShow();
			var url = $rootScope.rootUrl + "/queryorder";
			var data = {
				"user_id": userId,
				"book_id": bookId,
				"out_trade_no": out_trade_no
			};
			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();
				if(response.return_code == 'SUCCESS') {
					$rootScope.userinfo.active = true;
					$rootScope.Alert("支付成功并激活成功");
					//					setTimeout(function(){
					//			            $state.go("my_order")
					//					},2000)
				} else {
					$rootScope.Alert(response.return_msg);
				}
			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');
				return;
			});
		}
		
		
		
		$scope.orderPayedValidate = function() {
			$rootScope.LoadingShow();
			var url = $rootScope.rootUrl + "/queryorder";
			var data = {
				"user_id": $rootScope.userinfo.id,
				"book_id": $rootScope.mybook.id,
				"out_trade_no": getStorage("out_trade_no",true)
			};
			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();
				if(response.return_code == 'SUCCESS') {
					$rootScope.userinfo.active = true;
					$rootScope.Alert("支付成功并激活成功");
					//					setTimeout(function(){
					//			            $state.go("my_order")
					//					},2000)
				} else {
					$rootScope.Alert("支付成功即刻激活!");
				}
			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');
				return;
			});
		}
		
        if(!$rootScope.userinfo.active){
			if(getStorage("out_trade_no",true).length>0)
			{
				$scope.orderPayedValidate();
			}
		}
		
		

		$scope.showLeft = true;
		$scope.showRight = false;
		$scope.show1 = function() {
			$scope.showLeft = true;
			$scope.showRight = false;
		}
		$scope.show2 = function() {
			$scope.showLeft = false;
			$scope.showRight = true;
			setTimeout(function() {
				$('#appvcode_detail').html($rootScope.mybook.detail);
			}, 1500);
		}
		setTimeout(function() {
			$('#appvcode_detail').html($rootScope.mybook.detail);
		}, 1500);
	})

	.controller('me_gametop10Ctrl', function($rootScope, $ionicModal, $scope, $state, $http, $ionicActionSheet) {
		$scope.user_game_order = function() {
			$rootScope.LoadingShow();
			var url = $rootScope.rootUrl + "/user_game_order";
			var data = {
				"book_id": $rootScope.mybook.id,
			};
			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();
				if(response.error) {
					$rootScope.Alert(response.msg);
				} else {
					$scope.order_list = response;
				}
			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');
				return;
			});
		}
		$scope.user_game_order();
	})

	.controller('my_vcodeCtrl', function($rootScope, $ionicModal, $scope, $state, $http, $ionicActionSheet, $cordovaClipboard) {
		$scope.book_status = function() {
			var url = $rootScope.rootUrl + "/book_status";
			var data = {
				"user_id": $rootScope.userinfo.id,
			};
			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();
				if(response.error) {
					$rootScope.Alert(response.msg);
				} else {
					$scope.dataList = response;
				}
			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');
				return;
			});
		}

		$scope.book_status();

		$scope.wechatShareCode = function(code, name) {

			Wechat.isInstalled(function(installed) {
				if(installed) {
					Wechat.share({
						text: "验证码(" + code + ")只适用于" + name + "APP     打开APP下载列表http://xx.kaouyu.com/www/#/more_apps",
						scene: Wechat.Scene.SESSION // share to Timeline
					}, function() {
						$rootScope.Alert("已分享");
					}, function(reason) {
						$rootScope.Alert("失败: " + reason);
					})
				} else {
					$rootScope.Alert("请安装微信客户端！")
				}
			}, function(reason) {
				$rootScope.Alert("Failed: " + reason);
			});
		}

	})
	.controller('my_orderCtrl', function($rootScope, $ionicModal, $scope, $state, $http, $ionicActionSheet) {
		$scope.myorder = function() {
			var url = $rootScope.rootUrl + "/myorder";
			var data = {
				"user_id": $rootScope.userinfo.id,
			};
			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();
				if(response.error) {
					$rootScope.Alert(response.msg);
				} else {
					$scope.dataList = response;
				}
			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');
				return;
			});
		}
		$scope.myorder();
	})