(function() {
	var can1, can2; //canvas1画布 ,   canvas2画布
	var ctx1, ctx2; //两个画笔
	var canWid, canHei; //画布的宽高
	var lastframetime, diffframetime = 0; // 上一帧动画的时间，   两帧时间差
	var postionOb, paopOb; //气泡位置对象 和  气泡对象
	var panelObj, backObj, numObj, playObj, explianObj, finishObj; //
	var mx, my; //鼠标的横纵坐标值。
	var words;
	var word;
	var letters = [];
	var _index = 0;
	var pagescope;
	var isShowLabel = false;
	var isShowFinish = false;
	var finish_index = 0;
	var qipap_speed = 8; //默认是吧 数字越大速度越快


	// **********************************************************************************全局函数、初始化、判断等*******
	// *****************************************************************************************************************
	window.bdc = {};
	/**
	 * 开始游戏 
	 * @param {Object} index 词汇数组下标	
	 * @param {Object} ws 词汇列表
	 * @param {Object} scope 操作域
	 * @param {Object} ppspeed 泡泡移动速度
	 */
	bdc.startgame = function(index, ws, scope, ppspeed) {
		bdc.init(index, ws, scope, ppspeed);
		lastframetime = Date.now();
		bdc.gameLoop();
	}
	bdc.init = function(index, ws, scope, ppspeed) {
		qipap_speed = ppspeed;
		pagescope = scope;
		letters = [];
		var h = screen.height;
		var w = screen.width;
		can1 = document.getElementsByTagName('canvas')[0];
		ctx1 = can1.getContext('2d'); //上面的canvas
		can1.setAttribute('width', w);
		can1.setAttribute('height', h);
		ctx1.fillRect(0, 0, w, h);
		can2 = document.getElementsByTagName('canvas')[1];
		ctx2 = can2.getContext('2d'); //下面的canvas
		can2.setAttribute('width', w);
		can2.setAttribute('height', h);
		ctx2.fillRect(0, 0, w, h);

		ctx1.fillStyle = 'black';
		ctx1.font = '30px 微软雅黑';
		ctx1.textAlign = 'center';
		//		can1.addEventListener('mousemove', bdc.onMouseMove, false);
		can1.addEventListener('click', bdc.onCanvasClick, false);
		canWid = can1.width;
		canHei = can1.height;

		mx = canWid * 0.5;
		my = canHei * 0.5;
		//
		words = ws;
		finish_index = index;

		_index = index;

		word = words[_index];
		for(var i = 0; i < word.en.length; i++) {
			if(word.en.substring(i, i + 1) != " ") {
				letters.push(word.en.substring(i, i + 1));
			}
		}

		postionOb = new postionObject(); //
		postionOb.init(); //

		paopOb = new paopObject();
		paopOb.init();

		panelObj = new panelObject();
		panelObj.init();

		backObj = new backObject();

		numObj = new numObject();

		playObj = new playObject();

		explianObj = new explianObject();

		finishObj = new finishObject();

		playWordAudio(panelObj.word.audio_1);

	}

	bdc.setData = function() {
		_index = _index + 1;
		word = words[_index];
		letters = [];
		for(var i = 0; i < word.en.length; i++) {
			if(word.en.substring(i, i + 1) != " ") {
				letters.push(word.en.substring(i, i + 1));
			}
		}
		paopOb.num = letters.length;
		paopOb.init();
		panelObj.word = word;
		panelObj.spell = word.en;
		panelObj.text = "";
		playWordAudio(panelObj.word.audio_1);

	}

	bdc.gameLoop = function() { //使用帧绘画，一直在画的东西
		requestAnimFrame(bdc.gameLoop);
		var now = Date.now(); //1970 00:00:00 到现在的毫秒数
		diffframetime = now - lastframetime;
		lastframetime = now;
		if(diffframetime > 40) {
			diffframetime = 40; //防止切换浏览器，differ时间变长，气泡长到无限大
		}
		ctx2.clearRect(0, 0, canWid, canHei); //清除画布2
		can2App.drawBackgorund();
		panelObj.draw();

		computePaopao(); //根据气泡出现个数再出生气泡
		paopOb.drawPaopao(); //画气泡部分

		ctx1.clearRect(0, 0, canWid, canHei); //清除画布1
		backObj.draw();
		playObj.draw();
		if(isShowLabel) {
			explianObj.draw();
		}

		if(isShowFinish) {
			finishObj.draw();
			if(words.length - finish_index > 0) {
				playGood();
			} else {
				playJiayou();
			}
		}

		numObj.draw((_index + 1) + "/" + words.length);
	}

	bdc.onCanvasClick = function(e) {
		var x = e.pageX;
		var y = e.pageY;

		var len1 = calLength2(backObj.startx + 30, backObj.starty + 24, x, y);
		var len2 = calLength2(playObj.startx + 54, playObj.starty + 45, x, y);

		if(len1 < 20) {
			history.go(-1);
		} else if(len2 < 40) {
			if(panelObj.word) {
				playWordAudio(panelObj.word.audio_1);
			}
		} else {
			for(var i = 0; i < paopOb.num; i++) {
				if(paopOb.alive[i] && paopOb.grow[i]) {
					var len = calLength2(paopOb.x[i], paopOb.y[i], x, y);
					if(len < 30) {
						if(letters[i] == panelObj.spell.substring(panelObj.text.length, panelObj.text.length + 1)) {
							paopOb.dead(i); //如果距离小于30，则被吃掉
							panelObj.text = panelObj.text + letters[i];
							//如果中间包含空格 自动处理
							if(panelObj.spell.substring(panelObj.text.length, panelObj.text.length + 1) == " " && panelObj.text.length < word.en.length - 2) {
								panelObj.text = panelObj.text + " ";
							}
							playAudio(true);

							if(panelObj.text.length == word.en.length) {
								pagescope.user_completed_task(word.book_id, word.unit_id, word.word_id);

								if(_index < words.length - 1) {

									isShowLabel = true;

									setTimeout(function() {

										isShowLabel = false;

										bdc.setData();
									}, 2000);

								} else {
									finishObj.jfe = 10;
									isShowFinish = true;
									setTimeout(function() {

										isShowFinish = false;
										history.go(-1);
										//										pagescope.showAlert();
										//										pagescope.openPopover($event);
									}, 4000);
								}
							}
						} else {
							playAudio(false);
						}
					}
				}
			}
		}

	}

	// *******************************************************画布2上绘制东西  （背景,气泡）**********************
	// ************************************	******************************************************************************
	window.can2App = {};
	can2App.drawBackgorund = function() {
		var img = new Image();
		img.src = "img/yx_bg.jpg";
		ctx2.drawImage(img, 0, 0, canWid, canHei);
	}

	//********************************************************************//定义气泡出现位置类****************************
	var postionObject = function() {
		this.num = 30;
		//start point, controll point , end point
		this.rootx = [];
		this.headx = [];
		this.heady = [];

	}
	postionObject.prototype.init = function() {
		for(var i = 0; i < this.num; i++) {

			this.rootx[i] = (Math.random() * canWid).toFixed(2);
			if(this.rootx[i] < 25) {
				this.rootx[i] = 25;
			}
			if(this.rootx[i] > canWid - 30) {
				this.rootx[i] = canWid - 30;
			}
			this.headx[i] = this.rootx[i];
			this.heady[i] = canHei;
		}
	}

	//#####################################拼写版
	var panelObject = function() {
		this.wdith = screen.width;
		this.height = 100;
		this.startx = 0;
		this.starty = 80;
		this.word = word;
		this.spell = word.en;
		this.text = "";
	}

	panelObject.prototype.init = function() {

	}
	panelObject.prototype.draw = function() {
			var pimg = new Image();
			pimg.src = "img/game_ping_bg.png";
			ctx2.save();
			ctx2.fillStyle = "black"
			if(this.spell.length > 10) {
				ctx2.font = "24px 微软雅黑";
			} else {
				ctx2.font = "35px 微软雅黑";
			}
			ctx2.drawImage(pimg, this.startx, this.starty, this.wdith, this.height);
			if(this.wdith * 0.5 - this.spell.length * 9.5 < 50) {
				ctx2.fillText(this.spell, 50, this.starty + 60);
			} else {
				ctx2.fillText(this.spell, this.wdith * 0.5 - this.spell.length * 9.5, this.starty + 60);

			}

			ctx2.restore();

			ctx2.save();
			ctx2.fillStyle = "#00c4ff"
			if(this.spell.length > 10) {
				ctx2.font = "24px 微软雅黑";
			} else {
				ctx2.font = "35px 微软雅黑";
			}
			if(this.wdith * 0.5 - this.spell.length * 9.5 < 50) {
				ctx2.fillText(this.text, 50, this.starty + 60);
			} else {
				ctx2.fillText(this.text, this.wdith * 0.5 - this.spell.length * 9.5, this.starty + 60);

			}

			ctx2.restore();

		}
		//********************************************************************//定义气泡类****************************
	var paopObject = function() {
		this.num = letters.length;
		this.x = [];
		this.y = [];
		this.size = []; //气泡大小（直径）
		this.type = []; //气泡的类型
		this.speed = []; //气泡漂浮速度
		this.grow = []; //气泡是否长大
		this.alive = []; //bool，是否活着
		this.orange = new Image();
		this.blue = new Image();
	}
	paopObject.prototype.init = function() {
		this.orange.src = 'img/game_qipap.png';
		this.blue.src = 'img/game_qipap.png';
		for(var i = 0; i < this.num; i++) {
			this.x[i] = this.y[i] = 0;
			this.speed[i] = Math.random() * 0.015 + 0.005; //[0.005  ,  0.02)
			this.alive[i] = false; //初始值都为false
			this.grow[i] = false; //初始为“未长大”;
			this.type[i] = "";
		}
	}
	paopObject.prototype.drawPaopao = function() {
		for(var i = 0; i < this.num; i++) {
			if(this.alive[i]) {
				//find an ane, grow, fly up...
				if(this.size[i] <= 60) { //长大状态
					this.grow[i] = false;
					this.size[i] += this.speed[i] * diffframetime * 30;
				} else { //已经长大,向上漂浮
					this.grow[i] = true;
					this.y[i] -= this.speed[i] * qipap_speed * diffframetime;
				}
				var pic = this.orange;
				if(this.type[i] == 'blue') pic = this.blue;
				ctx2.drawImage(pic, this.x[i] - this.size[i] * 0.5, this.y[i] - this.size[i] * 0.5, this.size[i], this.size[i]);
				ctx2.save();
				ctx2.fillStyle = "#743f00"
				ctx2.font = "30px 微软雅黑";
				ctx2.fillText(letters[i], this.x[i] - this.size[i] * 0.5 + 20, this.y[i] - this.size[i] * 0.5 + 40, 45, 45);
				ctx2.restore();
				if(this.y[i] < 8) {
					this.alive[i] = false;
				}
			}
		}
	}
	paopObject.prototype.born = function(i) {
		var aneId = Math.floor(Math.random() * postionOb.num);
		this.x[i] = postionOb.headx[aneId]; //气泡的横坐标
		this.y[i] = postionOb.heady[aneId]; // 气泡的总坐标
		this.size[i] = 0;
		this.alive[i] = true;
		var flag = Math.random();
		if(flag < 0.1) {
			this.type[i] = "blue";
		} else {
			this.type[i] = "orange";
		}
	}

	paopObject.prototype.dead = function(i) {
		this.alive[i] = false;
	}

	function computePaopao() { //计算屏幕上的气泡数量
		var count = 0;
		for(var i = 0; i < paopOb.num; i++) {
			if(paopOb.alive[i]) count++;
		}
		if(count < paopOb.num + 5) {
			bornPaopao(); //出生一个气泡
			return false;
		}
	}

	function bornPaopao() { //循环气泡，如果状态为false，则让它出生
		for(var i = 0; i < paopOb.num; i++) {
			if(!paopOb.alive[i]) {
				paopOb.born(i);
				return false;
			}
		}
	}

	//****************************************************************************************画布1上绘制东西****************
	// **********************************************************************************************************************
	window.can1App = {};

	//********************************************************************//定义数据类***************************

	var backObject = function() {
		this.wdith = 60;
		this.height = 35;
		this.startx = 20;
		this.starty = 20;
	}

	backObject.prototype.draw = function() {
		var bimg = new Image();
		bimg.src = "img/youxi_cut_03.png";
		ctx1.drawImage(bimg, this.startx, this.starty, this.wdith, this.height);
	}

	var numObject = function() {
		this.wdith = 60;
		this.height = 35;
	}

	numObject.prototype.draw = function(text) {
		ctx1.save();
		ctx1.fillStyle = "#743f00"
		ctx1.font = "21px 微软雅黑";
		ctx1.fillText(text, canWid - 50, 42);
		ctx1.restore();
	}

	var playObject = function() {
		this.wdith = 108;
		this.height = 90;
		this.startx = canWid * 0.5 - 54;
		this.starty = canHei - 160;
	}
	playObject.prototype.draw = function() {
		var bimg = new Image();
		bimg.src = "img/youxi_cut_17.png";
		ctx1.drawImage(bimg, this.startx, this.starty, this.wdith, this.height);
	}

	var explianObject = function() {
		this.wdith = 168;
		this.height = 50;
		this.startx = canWid * 0.5 - 84;
		this.starty = 190;
	}

	explianObject.prototype.draw = function() {
		//		var bimg = new Image();
		//		bimg.src = "img/yx_bt_bg.png";
		//		ctx1.drawImage(bimg, this.startx, this.starty, this.wdith, this.height);
		ctx1.fillStyle = "black"
		ctx1.font = "28px 微软雅黑";
		ctx1.fillText(word.zh, this.startx + 70, this.starty + 30);
	}

	var finishObject = function() {
		this.wdith = 278;
		this.height = 235;
		this.startx = canWid * 0.5 - this.wdith * 0.5;
		this.starty = canHei * 0.5 - this.height * 0.5;
		this.jfe = 0;
	}

	finishObject.prototype.draw = function() {
		var bimg = new Image();
		bimg.src = "img/jifen_bg_03.png";
		ctx1.drawImage(bimg, this.startx, this.starty, this.wdith, this.height);
		ctx1.fillStyle = "black"
		ctx1.font = "50px 微软雅黑";
		//		ctx1.fillText(" 非常棒     ", this.startx + 150, this.starty + this.height * 0.5 - 12);
		//		ctx1.fillText("您完成本单元单词挑战！ ", this.startx + 150, this.starty + this.height * 0.5 + 24);
		ctx1.fillText((words.length - finish_index) + "  ", this.startx + 150, this.starty + this.height * 0.5 + 20);

	}
})();