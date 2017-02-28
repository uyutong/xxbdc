<?php
/**
 * BY：mahongtao， 2017.01.11
 * 微信授权回调页面
 * 需要在微信自定义菜单中设置为此页面
 * https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxda4d53c7b9a65e2f&redirect_uri=http://dachutimes.com:666/wx_redirect.php&response_type=code&scope=snsapi_base&state=10#wechat_redirect
 * state 规则为两位数，第一位数为第一级菜单编号，第二位数为二级菜单编号。
 **/

#region 常量定义
//新课标同步背单词
define("APPID",  "wxa15d78d04e5070ff");
define("APPSECRET",  "bb873de1918010c55ac5e1aaa7778c26");

//大楚时代
//define("APPID",  "wxda4d53c7b9a65e2f");
//define("APPSECRET",  "f271242b0fc0d75c4d1d1cb67e592ebc");

//鱿鱼说
//define("APPID",  "wx562735c835d5b677");
//define("APPSECRET",  "43848aacba662b45e02078e05151c688");
#endregion

#region 获取从微信传回的值
$code = $_GET['code'];
$state = $_GET['state'];
#endregion

if(isset($code)&&isset($state))
{
    $openid = get_openid($code);
    $access_token = get_access_token();
    $userinfo = get_user_info($access_token, $openid);

    $userinfo=json_decode($userinfo,true);

    $subscribe = $userinfo['subscribe'];
    $openid = $userinfo['openid'];
    $unionid = $userinfo['unionid'];

    $in_params=explode("^",$state);
    $book_id=$in_params[0];
    $unit_id=$in_params[1];
    $type=$in_params[2];
    $word=$in_params[3];

    if($subscribe)
    {
        //echo "已关注";
        //echo "<br>";
        //echo "unionid: ".$unionid;
        //echo "<br>";
        //echo $state;
        if($type=='exercise')
        {
            echo '<script type="text/javascript">location.href = "/www/#/word_exercise/'.$book_id.'/'.$unit_id.'/'.$word.'";</script>';
        }

        if($type=='word')
        {
            echo '<script type="text/javascript">location.href = "/www/#/word_detail/'.$book_id.'/'.$unit_id.'/'.$word.'";</script>';
        }
    }
    else
    {
        //echo "未关注";
        //echo "<br>";
        //echo "unionid: ".$unionid;
        //echo "<br>";
        //echo $state;
        //echo "<br>识别下图的二维码，关注服务号后，再继续……<br>";
        //echo "<img src='".get_qrcode($access_token, "QR_LIMIT_STR_SCENE",$state)."'>";

        //echo json_encode($userinfo);

        $status = user_insert_update($openid,$unionid);

        echo '<!DOCTYPE html>';
        echo '<html>';
        echo '<head>';
        echo '    <meta charset="utf-8">';
        echo '    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">';
        echo '    <meta name="format-detection" content="telephone=no" />';
        echo '    <title>新课标同步背单词</title>';
        echo '    <link href="www/css/ionic.min.css" rel="stylesheet">';
        echo '</head>';
        echo '<body>';
        echo '    <div class="content">';
        echo '        <div class="list card">';
        echo '            <div class="item text-center">';
        echo '                <h2>您还未关注 新课标同步背单词</h2>';
        echo '                <p>长按下面的二维码，识别后关注我们，精彩更多</p>';
        echo '            </div>';
        echo '            <div class="item item-image">';
        echo '                <img src="'.get_qrcode($access_token, "QR_LIMIT_STR_SCENE",$state).'" />';
        echo '            </div>';
        if($status<=3)
        {
            echo '            <div class="item">';
            if($type=='exercise')
            {
                echo '                <a class="button button-block button-assertive" href="/www/#/word_exercise/'.$book_id.'/'.$unit_id.'/'.$word.'">不想关注，继续</a>';
            }
            if($type=='word')
            {
                echo '                <a class="button button-block button-assertive" href="/www/#/word_detail/'.$book_id.'/'.$unit_id.'/'.$word.'">不想关注，继续</a>';
            }
            echo '            </div>';
        }
        echo '        </div>';
        echo '    </div>';
        echo '</body>';
        echo '</html>';
    }
}
else
{
    echo '<!DOCTYPE html><html><head><title>抱歉，出错了</title><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0"><link rel="stylesheet" type="text/css" href="https://res.wx.qq.com/connect/zh_CN/htmledition/style/wap_err1a9853.css"></head><body><div class="page_msg"><div class="inner"><span class="msg_icon_wrp"><i class="icon80_smile"></i></span><div class="msg_content"><h4>参数传递错误，请在微信客户端打开链接</h4></div></div></div></body></html>';
    exit;
}

#region 获取 openid
function get_openid($code)
{
    $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".APPID."&secret=".APPSECRET."&code=".$code."&grant_type=authorization_code";
    $res = https_request($url);
    $arr = json_decode($res, true);
    return $arr['openid'];
}
#endregion

#region 获取access token
function get_access_token()
{
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".APPID."&secret=".APPSECRET;
    $res = https_request($url);
    $arr = json_decode($res, true);
    return $arr['access_token'];
}
#endregion

#region 获取用户基本信息（通过 access_token, openId）
function get_user_info($access_token, $openid)
{
	$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
	$res = https_request($url);
    return $res;
}
#endregion

#region 获取关注公众号的带参数二维码
function get_qrcode($access_token, $scene_type, $scene_id)
{
    switch($scene_type)
    {
        case 'QR_LIMIT_STR_SCENE': //永久字符串参数
            $data = '{"action_name": "QR_LIMIT_STR_SCENE", "action_info": {"scene": {"scene_str": "'.$scene_id.'"}}}';
            break;
        case 'QR_LIMIT_SCENE': //永久
            $data = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
            break;
        case 'QR_SCENE':       //临时
            $data = '{"expire_seconds": 1800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
            break;
    }
    $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$access_token;

    $res = https_request($url, $data);

    $result = json_decode($res, true);

    //echo "ticket: ".$result["ticket"];
    //echo "<br>url: ".$result["url"];

    return "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($result["ticket"]);
}
#endregion

#region 创建/更新用户并返回用户的扫描次数
function user_insert_update($openid,$unionid)
{
    require  'medoo.php';

    $database = new medoo(array(
        'database_type' => 'mysql',
        'database_name' => 'kuyxxword',
        'server' => '127.0.0.1',
        'username' => 'uyutalk',
        'password' => 'UyuTAlk_DB-2)16',
        'charset' => 'utf8',
        'port' => 3356
    ));

    $status = $database->get("user", "status", array("unionid" => $unionid));

    if($status=="")
    {
        $database->insert("user", array("subscribe" => "0","openid" => $openid,"unionid" => $unionid,"status" => 1));

        $status=0;
    }
    else
    {
        $database->update("user", array("status[+]" => 1),array("unionid" => $unionid));
    }

    return $status+1;
}
#endregion

#region https 请求（支持GET和POST）
function https_request($url, $data = null)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}
#endregion

?>