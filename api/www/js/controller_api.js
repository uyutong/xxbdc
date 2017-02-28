
dcCtrl
.controller('apiCtrl', function ($rootScope, $scope, $state, $http, $ionicActionSheet) {

    //获取所有book/unit
    $scope.books = function () {
        var url = $rootScope.rootUrl + "/books";

        var data = {
            "user_id": "1"
        };

        $http.post(url, data).success(function (response) {
            if (response.error) {
                $rootScope.Alert(response.msg);
            }
            else {
                alert(JSON.stringify(response));
            }

        }).error(function (response, status) {
            $rootScope.LoadingHide();
            $rootScope.Alert('连接失败！[' + response + status + ']');
            return;
        });
    }

    //用户注册，需要输入从微信返回的用户数据，详见：http://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140839&token=&lang=zh_CN
    $scope.user_register = function () {
        var url = $rootScope.rootUrl + "/user_register";
        var data = {
            "subscribe": 1,
            "openid": "o6_bmjrPTlm6_2sgVt7hMZOPfL2M",
            "nickname": "Band",
            "sex": 1,
            "language": "zh_CN",
            "city": "广州",
            "province": "广东",
            "country": "中国",
            "headimgurl": "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0",
            "subscribe_time": 1382694957,
            "unionid": " o6_bmasdasdsad6_2sgVt7hMZOPfL",
            "remark": "",
            "groupid": 0
        };

        $http.post(url, data).success(function (response) {
            if (response.error) {
                $rootScope.Alert(response.msg);
            }
            else {
                alert(JSON.stringify(response));
            }

        }).error(function (response, status) {
            $rootScope.LoadingHide();
            $rootScope.Alert('连接失败！[' + response + status + ']');
            return;
        });
    }

    //获取用户信息，传入用户id
    $scope.user = function () {
        var url = $rootScope.rootUrl + "/user";
        var data = {
            "user_id": "1"
        };

        $http.post(url, data).success(function (response) {
            if (response.error) {
                $rootScope.Alert(response.msg);
            }
            else {
                alert(JSON.stringify(response));
            }

        }).error(function (response, status) {
            $rootScope.LoadingHide();
            $rootScope.Alert('连接失败！[' + response + status + ']');
            return;
        });
    }
    
})
