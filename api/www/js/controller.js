var dcCtrl = angular.module('dachutimes.controllers', []);
dcCtrl
	//#region 扫描单词页
	.controller('word_detailCtrl', function($rootScope, $ionicModal, $scope, $state, $http, $stateParams) {

		//#region 获取单词
		$rootScope.LoadingShow();
		var url = $rootScope.rootUrl + "/word";
		var data = {
			"user_id": "0",
			"book_id": $stateParams.book_id,
			"unit_id": $stateParams.unit_id,
			"word": $stateParams.word
		};

		$http.post(url, data).success(function(response) {
			$rootScope.LoadingHide();
			if(response.error) {
				$rootScope.Alert(response.msg);
			} else {
				$scope.word = response;
				$(".video-box video").attr("src", "/upload/word/mp4/" + $scope.word.video);
				$('.video-box video').mediaelementplayer();
			}

		}).error(function(response, status) {
			$rootScope.LoadingHide();
			$rootScope.Alert('连接失败！[' + response + status + ']');
			return;
		});
		//#endregion

		$scope.playDetailWord1 = function(audio) {
			$rootScope.playWord(audio, $("#detail_paly_1"));
		}

		$scope.playDetailWord0 = function(audio) {
			$rootScope.playWord(audio, $("#detail_paly_0"));
		}

		$scope.goExercise = function() {
			$state.go("word_exercise", {
				"book_id": $stateParams.book_id,
				"unit_id": $stateParams.unit_id,
				"word": $stateParams.word
			})
		}

		//		$scope.play = function(audio) {
		//			var v = document.getElementById("audio");
		//			v.src = $rootScope.siteUrl + "/upload/word/mp3/" + audio;
		//			v.play();
		//		}
	})
	//#endregion

	////#region 扫描练习页
	//.controller('word_exerciseCtrl', function ($rootScope, $ionicModal, $scope, $state, $http, $stateParams, $ionicSlideBoxDelegate) {
	//
	//  //#region 获取单词
	//  $rootScope.LoadingShow();
	//  var url = $rootScope.rootUrl + "/word";
	//  var data = {
	//      "user_id": "0",
	//      "book_id": $stateParams.book_id,
	//      "unit_id": $stateParams.unit_id,
	//      "word": $stateParams.word
	//  };
	//
	//  $http.post(url, data).success(function (response) {
	//      $rootScope.LoadingHide();
	//
	//      if (response.error) {
	//          $rootScope.Alert(response.msg);
	//      }
	//      else {
	//          $scope.word = response;
	//
	//          $ionicSlideBoxDelegate.$getByHandle('slide_exercise').update();
	//
	//          angular.forEach($scope.word.exercises, function (exercise, idx) {
	//              exercise.myanswer = -1;
	//              //#region 习题处理
	//
	//              //#region 1. 看图选择题
	//              if (exercise.type == 1) {
	//                  var s = exercise.question.split('^');
	//
	//                  exercise.question_en = s[0];
	//
	//                  if (s.length >= 2)
	//                      exercise.question_zh = s[1];
	//              }
	//              //#endregion
	//
	//              //#region 2. 选图填空
	//              if (exercise.type == 2) {
	//                  var s = exercise.question.split('^');
	//                  exercise.question_en = s[0];
	//                  if (s.length >= 2)
	//                      exercise.question_zh = s[1];
	//
	//                  s = exercise.answer.split('^');
	//                  exercise.answer_index = s[0];
	//                  if (s.length >= 2)
	//                      exercise.answer_word = s[1];
	//              }
	//              //#endregion
	//
	//              //#endregion
	//          })
	//
	//          setTitle("趣味练习 1/" + $scope.word.exercises.length);
	//      }
	//
	//  }).error(function (response, status) {
	//      $rootScope.LoadingHide();
	//      $rootScope.Alert('连接失败！[' + response + status + ']');
	//      return;
	//  });
	//  //#endregion
	//
	//  //#region 习题提交
	//  $scope.choose_1 = function (i, exerciseIndex, item) {
	//
	//      $(".type_1_html").html(item.question_en.replace('___', '<span class="green">' + item.items.split('\n')[item.answer] + '</span>'));
	//
	//      item.myanswer = i;
	//
	//      item.answered = true;
	//
	//      if (item.myanswer == item.answer) {
	//
	//          //正确
	//          playAudio(true);
	//
	//          if (exerciseIndex < $scope.word.exercises.length - 1) {
	//              setTimeout(function () {
	//                  $ionicSlideBoxDelegate.$getByHandle('slide_exercise').next();
	//              }, 1000);
	//          }
	//      }
	//      else {
	//          //错误
	//          playAudio(false);
	//      }
	//
	//
	//  }
	//
	//  $scope.choose_2 = function (i, exerciseIndex, item) {
	//
	//      $(".type_2_html").html(item.question_en.replace('___', '<span class="green">' + item.answer_word + '</span>'));
	//
	//      item.myanswer = i;
	//
	//      item.answered = true;
	//
	//      if (item.myanswer == item.answer_index) {
	//
	//          //正确
	//          playAudio(true);
	//
	//          if (exerciseIndex < $scope.word.exercises.length - 1) {
	//              setTimeout(function () {
	//                  $ionicSlideBoxDelegate.$getByHandle('slide_exercise').next();
	//              }, 1000);
	//          }
	//      }
	//      else {
	//          //错误
	//          playAudio(false);
	//      }
	//
	//      
	//  }
	//  //#endregion
	//
	//  $scope.slide_change = function (i)
	//  {
	//      setTitle("趣味练习 " + (i + 1) + "/" + $scope.word.exercises.length);
	//  }
	//
	//  $scope.play = function (audio) {
	//      var v = document.getElementById("audio");
	//      v.src = $rootScope.siteUrl + "/upload/exercise/mp3/" + audio;
	//      v.play();
	//  }
	//})
	////#endregion

	//#region 扫描练习页
	.controller('word_exerciseCtrl', function($rootScope, $ionicModal, $scope, $state, $http, $stateParams, $ionicSlideBoxDelegate) {

		$scope.playExerciseWord = function(audio) {
			$rootScope.playWord(audio, $("#exercise_word_play"));
		}

		$scope.playExercise = function(audio) {
			$rootScope.playExercise(audio, $("#exercise_play"));
		}

		$scope.now_page = 0; //默认显示第一页单词拼写 

		var has_submit = 0; //单词拼写真确了几个
		//#region 获取单词
		$rootScope.LoadingShow();
		var url = $rootScope.rootUrl + "/word";
		var data = {
			"user_id": "0",
			"book_id": $stateParams.book_id,
			"unit_id": $stateParams.unit_id,
			"word": $stateParams.word
		};

		$http.post(url, data).success(function(response) {
			$rootScope.LoadingHide();
			if(response.error) {
				$rootScope.Alert(response.msg);
			} else {
				$scope.word = response;

				$scope.spell = $scope.word.en;
				$scope.questions = [];
				$scope.keys = [];
				$scope.letters = [];
				var has_submit = 0;

				function sortNumber(a, b) {
					return a < b
				}

				if($scope.spell.indexOf(" ") == -1 && $scope.spell.indexOf("'") == -1) {
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
						if(i % 3 == 0 && $scope.spell[i] != "'" && $scope.spell[i] != " ") {
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

				angular.forEach($scope.word.exercises, function(exercise, idx) {
					exercise.myanswer = -1;
					//#region 习题处理

					//#region 1. 看图选择题
					if(exercise.type == 1) {
						var s = exercise.question.split('^');

						exercise.question_en = s[0];

						if(s.length >= 2)
							exercise.question_zh = s[1];
					}
					//#endregion

					//#region 2. 选图填空
					if(exercise.type == 2) {
						var s = exercise.question.split('^');
						exercise.question_en = s[0];
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
					//#endregion

					//#region 5 
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
					//#endregion

					//#region 6 
					if(exercise.type == 6) {
						exercise.question = exercise.answer.split("^").sort(function() {
							return Math.random() - 0.5
						});
					}
					//#endregion

				})

				setTitle("趣味练习 1/" + ($scope.word.exercises.length + 1));

				setTimeout(function() {
					$scope.playExerciseWord($scope.word.audio_0);
				}, 500)

			}

		}).error(function(response, status) {
			$rootScope.LoadingHide();
			$rootScope.Alert('连接失败！[' + response + status + ']');
			return;
		});
		//#endregion

		$scope.chooseAnswer = function(index) {

			if(has_submit < $scope.keys.length) {
				if($scope.letters[index] == $scope.keys[has_submit].s) {
					playAudio(true);
					$('#spellw_' + $scope.keys[has_submit].id).html($scope.keys[has_submit].s);
					has_submit = has_submit + 1;
					if(has_submit == $scope.keys.length) {
						$scope.playExerciseWord($scope.word.audio_0);
					}
				} else {
					playAudio(false);
				}
			}
		}

		$scope.word_show = false;
		$scope.showWord = function() {
			$scope.word_show = true;
			setTimeout(function() {
				$scope.word_show = false;
				$scope.$apply();
			}, 500)
		}

		//#region 习题提交
		$scope.choose_1 = function(i, exerciseIndex, item) {

			$(".type_1_html").html(item.question_en.replace('___', '<span class="green">' + item.items.split('\n')[i] + '</span>'));

			item.myanswer = i;

			item.answered = true;

			if(item.myanswer == item.answer) {

				//正确
				playAudio(true);

				//			if(exerciseIndex < $scope.word.exercises.length - 1) {
				//				setTimeout(function() {
				//					$ionicSlideBoxDelegate.$getByHandle('slide_exercise').next();
				//				}, 1000);
				//			}
			} else {
				//错误
				playAudio(false);
			}

		}

		$scope.choose_2 = function(i, exerciseIndex, item) {

			$(".type_2_html").html(item.question_en.replace('___', '<span class="green">' + item.answer_word + '</span>'));

			item.myanswer = i;

			item.answered = true;

			if(item.myanswer == item.answer_index) {

				//正确
				playAudio(true);

				//				if(exerciseIndex < $scope.word.exercises.length - 1) {
				//					setTimeout(function() {
				//						$ionicSlideBoxDelegate.$getByHandle('slide_exercise').next();
				//					}, 1000);
				//				}
			} else {
				//错误
				playAudio(false);
			}

		}
		//#endregion

		$scope.choose_3 = function(index) {

			if(has_submit < $scope.exercise.keys.length) {
				if($scope.exercise.options[index] == $scope.exercise.keys[has_submit]) {
					playAudio(true);
					$('#key_' + has_submit).html($scope.exercise.keys[has_submit]);
					has_submit = has_submit + 1;
				} else {
					playAudio(false);
				}
			}

		}

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
					item.result[i] = (myanswers[i] == answers[i]);
				}
				$scope.answered++;
			}
		}

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
					item.result[i] = (myanswers[i] == answers[i]);
					$(".question_type_" + item.id + " .droppable .col").eq(i).addClass((myanswers[i] == answers[i] ? 'correct' : 'error'));
				}

				$scope.answered++;

			}
		}

		$scope.slide_change = function(i) {
			setTitle("趣味练习 " + (i + 1) + "/" + ($scope.word.exercises.length + 1));
		}

		$scope.init = function() {
			if($scope.exercise.type == 2) {
				setTimeout(function() {
					$scope.playExercise($scope.exercise.media);
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

		$scope.nextTest = function() {
			if($scope.now_page < $scope.word.exercises.length) {
				has_submit = 0; //单词拼写真确了几个
				$scope.exercise = $scope.word.exercises[$scope.now_page];
				$scope.init();
				$scope.now_page = $scope.now_page + 1;
				$scope.slide_change($scope.now_page);
				if($scope.exercise.type == 2) {
					setTimeout(function() {
						$scope.playExercise($scope.exercise.media)
					}, 500)
				}
			}
		}

	})
	//#endregion

	//#region 扫描练习页
	.controller('wx_word_listCtrl', function($rootScope, $ionicModal, $scope, $state, $http, $stateParams) {
		//type 0 课前 1 课后 
		$scope.type = $stateParams.type;
		$scope.getBook = function(user_id) {
			var url = $rootScope.rootUrl + "/books";
			var data = {
				"user_id": user_id
			};
			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();
				if(response.error) {
					$rootScope.Alert(response.msg);
				} else {
					$scope.bookList = response;
					for(var i = 0; i < $scope.bookList.length; i++) {
						if($scope.bookList[i].id == $stateParams.book_id) {
							$scope.book = $scope.bookList[i];
							break;
						}
					}
					$scope.getUnits(user_id, $stateParams.book_id);
				}

			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');
				return;
			});
		}

		$scope.getUnits = function(user_id, book_id) {

			$rootScope.LoadingShow();
			var url = $rootScope.rootUrl + "/units";
			var data = {
				"user_id": user_id,
				"book_id": book_id
			};
			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();
				if(response.error) {
					$rootScope.Alert(response.msg);
				} else {
					$scope.units = response;
					for(var i = 0; i < $scope.units.length; i++) {
						if($scope.units[i].id == $stateParams.unit_id) {
							$scope.unit = $scope.units[i];
							break;
						}
					}
					$scope.getWords($stateParams.unit_id);

				}

			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');
				return;
			});
		}

		$scope.getWords = function(unit_id) {
			$rootScope.LoadingShow();
			var url = $rootScope.rootUrl + "/words";
			var data = {
				"user_id": 21,
				"unit_id": unit_id
			};
			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();
				if(response.error) {
					$rootScope.Alert(response.msg);
				} else {
					$scope.word_list = response;
					$scope.word_list[0].show = true;
					setTimeout(function() {
						for(var i = 0; i < $scope.word_list.length; i++) {
							$(".video-box" + i + " video").attr("src", $rootScope.siteUrl + "/upload/word/mp4/" + $scope.word_list[i].video);
							$(".video-box" + i + " video").mediaelementplayer();
						}
					}, 1000)
				}
			});
		}

		$scope.getBook(21);

		$scope.playDetailWord1 = function(audio, index) {
			$rootScope.playWord(audio, $("#detail_paly_1_" + index));
		}

		$scope.playDetailWord0 = function(audio, index) {
			$rootScope.playWord(audio, $("#detail_paly_0_" + index));
		}

		$scope.doExercise = function(word) {
			$state.go("word_exercise", { "book_id": $stateParams.book_id, "unit_id": $stateParams.unit_id, "word": word })
		}

		$scope.ifShow = function(index) {
			$scope.word_list[index].show = undefined ? false : !$scope.word_list[index].show;
		}
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

					//					http://xx.kaouyu.com/upload/apk/3q5x.apk

				}
			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');
				return;
			});
		}
		//		$scope.versions(device.platform)
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

	.controller('word_listCtrl', function($rootScope, $scope, $state, $stateParams, $http, $ionicActionSheet, $interval) {
			
	    var radios = [];
		$rootScope.LoadingShow();
		var url = $rootScope.rootUrl + "/words";
		var data = {
			"user_id": 21,
			"unit_id": $stateParams.unit_id
		};
		
		$http.post(url, data).success(function(response) {
			$rootScope.LoadingHide();
			if(response.error) {
				$rootScope.Alert(response.msg);
			} else {
				$scope.word_list = response;

				if(radios.length != $scope.word_list.length * 2) {
					radios = [];
					for(var i = 0; i < $scope.word_list.length; i++) {
						radios.push({
							"id": i,
							"audio": $scope.word_list[i].audio_0,
							"type": "0"
						});
						radios.push({
							"id": i,
							"audio": $scope.word_list[i].audio_1,
							"type": "1"
						});
					}
				}
			}
		}).error(function(response, status) {
			$rootScope.LoadingHide();
			$rootScope.Alert('连接失败！[' + response + status + ']');
			return;
		});

		$scope.playing = false;
		$scope.is_reading_num = -1;
		$scope.read_button_name = "播放";
		var timer;
		
		$scope.playAudio = function() {
			if(!$scope.playing){
				$scope.playing = true;
				$scope.read_button_name = "停止";
				var j = 0;
				timer = $interval(function() {
					if(j == radios.length) {
						$scope.read_button_name = "播放";
						$scope.is_reading_num = -1
						$interval.cancel(timer); //停止并清除					
					} else if(j < radios.length) {
						$rootScope.playWebWord(radios[j].audio);
						$scope.is_reading_num = radios[j].id;
						j = j + 1;
					}
				}, 4000, radios.length + 1)
			} else {
				$scope.is_reading_num = -1;
				$scope.read_button_name = "播放";
				if(timer != undefined && $scope.playing) {
				    $interval.cancel(timer); //停止并清除
			    }
				$scope.playing = false;
		    }
        }
		
		$scope.play=function(index){
			if($scope.playing){
				$scope.is_reading_num = -1;
				$scope.read_button_name = "播放";
				if(timer != undefined && $scope.playing) {
				    $interval.cancel(timer); //停止并清除
			    }
				$scope.playing = false;
			}
			$rootScope.playWebWord($scope.word_list[index].audio_0);
		}
		
		/**
		 * 获取更多相关app
		 * @param {Object} platform
		 */
		$scope.version = function(platform,book_id) {
			$rootScope.LoadingShow();
			var url = $rootScope.rootUrl + "/version";
			var data = {
				"platform": platform,
				"book_id":book_id
			};
			$http.post(url, data).success(function(response) {
				$rootScope.LoadingHide();
				if(response.error) {
					$rootScope.Alert(response.msg);
				} else {
					$scope.apps = response;
					$scope.app = $scope.apps;
				}
			}).error(function(response, status) {
				$rootScope.LoadingHide();
				$rootScope.Alert('连接失败！[' + response + status + ']');
				return;
			});
		}
		$scope.version("android",$stateParams.book_id);
		
	})