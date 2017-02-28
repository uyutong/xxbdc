<?php

/**
 * 17hg.com
 * url: http://api.myechinese.com/weixin/token.php
 * Token: mark
 * EncodingAESKey: 7hGFeZ11fzSTbgswmndOeByAR8EJmWrs7kjNmmMxova
 * AppID: wxda4d53c7b9a65e2f
 * AppSecret: f271242b0fc0d75c4d1d1cb67e592ebc
 **/

require  'medoo.php';

//明文访问
if(isset($_GET['action']))
{
    $action = $_GET['action'];

    #region 创建自定义菜单
    if($action=="create_menu")
    {
        $weixin = new class_weixin();

        //$data ='{"button":[{"type":"view","name":"经典悦读","key":"经典悦读","url":"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxda4d53c7b9a65e2f&redirect_uri=http://dachutimes.com:666/weixin/redirect.php&response_type=code&scope=snsapi_base&state=10#wechat_redirect"},{"name":"鱿鱼电台","sub_button":[{"type":"click","name":"电影音乐","key":"电影音乐"},{"type":"click","name":"材米油盐","key":"材米油盐"},{"type":"click","name":"名人轶事","key":"名人轶事"},{"type":"click","name":"翻来翻趣","key":"翻来翻趣"}]},{"type":"click","name":"一日三词","key":"一日三词"}]}';
        //$data = '{ "button": [{ "type": "view", "name": "鱿鱼电台", "key": "10", "url": "http://uyutalk.kaouyu.com/index.php/weixin/index#/menu/10" }, { "type": "click", "name": "一日三词", "key": "20" }, { "type": "click", "name": "来个段子", "key": "30" }] }';
        //$data = '{ "button": [{ "type": "view", "name": "鱿鱼电台", "key": "10", "url": "http://dachutimes.com.cn/index.php/weixin/index#/menu/10" }, { "type": "click", "name": "一日三词", "key": "20" }, { "type": "click", "name": "来个段子", "key": "30" }] }';
        $data='{ "button": [{ "name": "App下载", "sub_button": [{ "type": "view", "name": "三年级起点", "key": "三年级起点", "url": "http://mp.weixin.qq.com/s?__biz=MzIxNjczMzIwMw==&mid=100000002&idx=1&sn=de41fa1a0dcd332e90d1b98ea88cb01f&chksm=1785c27e20f24b68ad3447bcb6cfbd26c417fde4cd5732a0f0542bd3f6ec89fc5c69a077aad5" }] }]}';
        echo $weixin->create_menu($data);
    }
    #endregion

    #region 获取自定义菜单
    if($action=="get_menu")
    {
        $weixin = new class_weixin();

        echo $weixin->get_menu();
    }
    #endregion

    #region 获取用户组
    if($action=="get_group")
    {
        $weixin = new class_weixin();

        echo json_encode( $weixin->get_group());
    }
    #endregion

    #region 获取所有用户组的二维码
    if($action=="get_group_qr")
    {
        $weixin = new class_weixin();

        $groups=$weixin->get_group()["groups"];


        echo "<table border=1>";
        foreach($groups as $group)
        {
            echo "    <tr>";
            echo "        <td>".$group["id"]."</td>";
            echo "        <td>".$group["name"]."</td>";
            echo "        <td><img src='".$weixin->create_qrcode("QR_LIMIT_SCENE",$group["id"])."'></td>";
            echo "    </tr>";
        }
        echo "</table>";
    }
    #endregion

    #region 创建特殊标识的二维码
    if($action=="get_test_qr")
    {
        $weixin = new class_weixin();

        echo "<br><img src='".$weixin->create_qrcode("QR_LIMIT_STR_SCENE","book_id=2&unit_id=38&word=mark")."'><br>";
    }
    #endregion

    if($action=="get_word")
    {
        $item=get_word('57',"let's")[0];
        //$item=get_word('38','book')[0];

        echo $item['zh'];
    }

}
else //微信访问
{
    define("TOKEN", "mark");

    $wechatObj = new wechatCallbackAPI();

    if (!isset($_GET['echostr']))
    {
        writelog();

        $wechatObj->responseMsg();
    }
    else
    {
        writelog();

        $wechatObj->valid();
    }
}

class wechatCallbackAPI
{
    #region 验证签名
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if($tmpStr == $signature)
        {
            echo $echoStr;
            exit;
        }
    }
    #endregion

    #region 响应消息
    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        if (!empty($postStr))
        {
            $this->logger("R \r\n".$postStr);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            if (($postObj->MsgType == "event") && ($postObj->Event == "subscribe" || $postObj->Event == "unsubscribe")){
                //过滤关注和取消关注事件
            }else{

            }

            //消息类型分离
            switch ($RX_TYPE)
            {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    if (strstr($postObj->Content, "第三方")){
                        $result = $this->relayPart3("http://www.fangbei.org/test.php".'?'.$_SERVER['QUERY_STRING'], $postStr);
                    }else{
                        $result = $this->receiveText($postObj);
                    }
                    break;
                case "image":
                    $result = $this->receiveImage($postObj);
                    break;
                case "location":
                    $result = $this->receiveLocation($postObj);
                    break;
                case "voice":
                    $result = $this->receiveVoice($postObj);
                    break;
                case "video":
                    $result = $this->receiveVideo($postObj);
                    break;
                case "link":
                    $result = $this->receiveLink($postObj);
                    break;
                default:
                    $result = "unknown msg type: ".$RX_TYPE;
                    break;
            }
            $this->logger("T \r\n".$result);
            echo $result;
        }else {
            echo "";
            exit;
        }
    }
    #endregion

    //接收事件消息
    private function receiveEvent($object)
    {
        $content = "";
        switch ($object->Event)
        {
            case "subscribe":

                $content = "Hi，欢迎关注~新课标同步背单词~。 ";

                #region 用户来自场景扫描
                if(!empty($object->EventKey))
                {
                    $open_id=$object->FromUserName;

                    $weixin = new class_weixin();
                    $result=$weixin->user_to_group($open_id,100);

                    $state=str_replace("qrscene_","",$object->EventKey);


                    $in_params=explode("^",$state);
                    $book_id=$in_params[0];
                    $unit_id=$in_params[1];
                    $type=$in_params[2];
                    $word=$in_params[3];

                    $item=get_word($unit_id,$word)[0];

                    if($type=='exercise')
                    {
                        $url= 'http://xx.kaouyu.com/www/#/word_exercise/'.$book_id.'/'.$unit_id.'/'.$word;
                        $desc=$word." ".$item['phonetic']."\n".$item['zh']."\n点击做练习。";
                    }

                    if($type=='word')
                    {
                        $url= 'http://xx.kaouyu.com/www/#/word_detail/'.$book_id.'/'.$unit_id.'/'.$word;
                        $desc=$word." ".$item['phonetic']."\n".$item['zh']."\n点击看手势，听发音。";
                    }

                    //$content=$url;
                    $content = array();
                    $content[] = array("Title"=>"欢迎关注~新课标同步背单词~", "Description"=>$desc, "PicUrl"=>"http://xx.kaouyu.com/upload/word/img/".$item['photo'], "Url" => $url);
                }
                #endregion

                break;
            case "unsubscribe":
                $content = "取消关注";
                break;
            case "CLICK":
                switch ($object->EventKey)
                {
                    case "COMPANY":
                        $content = array();
                        $content[] = array("Title"=>"北京大楚时代软件有限公司", "Description"=>"", "PicUrl"=>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
                        break;
                    default:

                        //user_insert_update($object->FromUserName);

                        //获取openid
                        //$menu_name = $object->EventKey;
                        //$openid  = $object->FromUserName;

                        //if($menu_name=="来个段子")
                        //{
                        //    //$content="来个段子";

                        //    $sql = "SELECT *,UNIX_TIMESTAMP(create_time) as create_time_second FROM article WHERE menu_id=30 ORDER BY RAND() LIMIT 1";
                        //    $db=new mysqli("127.0.0.1:3356","uyutalk","UyuTAlk_DB-2)16","uyutalk");
                        //    $db->set_charset("utf8");
                        //    $result=$db->query($sql);
                        //    $row=$result->fetch_row();

                        //    if(empty( $row[0]))
                        //    {
                        //        $content="没有更多了,小鱿努力更新汇总.";
                        //    }
                        //    else
                        //    {
                        //        $content = array();
                        //        $content[] = array("Title"=>$row[5], "Description"=>$row[6], "PicUrl"=>"http://uyutalk.kaouyu.com/upload/img/".$row[12], "Url" =>"http://uyutalk.kaouyu.com/r.php?id=".$row[0]."&menuid=".$menuid."&openid=".$openid);
                        //    }
                        //}

                        //if($menu_name=="最新三词")
                        //{
                        //    //$content="最新三词";

                        //    $sql = "SELECT *,UNIX_TIMESTAMP(create_time) as create_time_second FROM article WHERE menu_id=20 ORDER BY id DESC LIMIT 1";
                        //    $db=new mysqli("127.0.0.1:3356","uyutalk","UyuTAlk_DB-2)16","uyutalk");
                        //    $db->set_charset("utf8");
                        //    $result=$db->query($sql);
                        //    $row=$result->fetch_row();

                        //    if(empty( $row[0]))
                        //    {
                        //        $content="没有更多了,小鱿努力更新汇总.";
                        //    }
                        //    else
                        //    {
                        //        $content = array();
                        //        $content[] = array("Title"=>$row[5], "Description"=>$row[6], "PicUrl"=>"http://uyutalk.kaouyu.com/upload/img/".$row[12], "Url" =>"http://uyutalk.kaouyu.com/r.php?id=".$row[0]."&menuid=".$menuid."&openid=".$openid);
                        //    }
                        //}

                        break;
                }
                break;
            case "VIEW":

                //user_insert_update($object->FromUserName);

                $content = "跳转链接 ".$object->EventKey;
                break;
            case "SCAN":
                $open_id=$object->FromUserName;
                $weixin = new class_weixin();
                $item = $weixin->get_user_info($open_id);
                $content .= $item;
                $content .= "\n------------------------\n来自二维码场景 ".$object->EventKey;
                break;
            case "LOCATION":
                $content = "上传位置：纬度 ".$object->Latitude.";经度 ".$object->Longitude;
                break;
            case "scancode_waitmsg":
                if ($object->ScanCodeInfo->ScanType == "qrcode"){
                    $content = "扫码带提示：类型 二维码 结果：".$object->ScanCodeInfo->ScanResult;
                }else if ($object->ScanCodeInfo->ScanType == "barcode"){
                    $codeinfo = explode(",",strval($object->ScanCodeInfo->ScanResult));
                    $codeValue = $codeinfo[1];
                    $content = "扫码带提示：类型 条形码 结果：".$codeValue;
                }else{
                    $content = "扫码带提示：类型 ".$object->ScanCodeInfo->ScanType." 结果：".$object->ScanCodeInfo->ScanResult;
                }
                break;
            case "scancode_push":
                $content = "扫码推事件";
                break;
            case "pic_sysphoto":
                $content = "系统拍照";
                break;
            case "pic_weixin":
                $content = "相册发图：数量 ".$object->SendPicsInfo->Count;
                break;
            case "pic_photo_or_album":
                $content = "拍照或者相册：数量 ".$object->SendPicsInfo->Count;
                break;
            case "location_select":
                $content = "发送位置：标签 ".$object->SendLocationInfo->Label;
                break;
            default:
                $content = "receive a new event: ".$object->Event;
                break;
        }

        if(is_array($content))
        {
            if (isset($content[0]['PicUrl'])){
                $result = $this->transmitNews($object, $content);
            }else if (isset($content['MusicUrl'])){
                $result = $this->transmitMusic($object, $content);
            }
        }else{
            $result = $this->transmitText($object, $content);
        }
        return $result;
    }

    //接收文本消息
    private function receiveText($object)
    {
        $keyword = trim($object->Content);
        //多客服人工回复模式
        if (strstr($keyword, "请问在吗") || strstr($keyword, "在线客服")){
            //$result = $this->transmitService($object);
            //return $result;
        }

        //自动回复模式
        if (strstr($keyword, "文本")){
            //$content = "这是个文本消息";
        }else if (strstr($keyword, "表情")){
            //$content = "中国：".$this->bytes_to_emoji(0x1F1E8).$this->bytes_to_emoji(0x1F1F3)."\n仙人掌：".$this->bytes_to_emoji(0x1F335);
        }else if (strstr($keyword, "单图文")){
            //$content = array();
            //$content[] = array("Title"=>"单图文标题",  "Description"=>"单图文内容", "PicUrl"=>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
        }else if (strstr($keyword, "图文") || strstr($keyword, "多图文")){
            //$content = array();
            //$content[] = array("Title"=>"多图文1标题", "Description"=>"", "PicUrl"=>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
            //$content[] = array("Title"=>"多图文2标题", "Description"=>"", "PicUrl"=>"http://d.hiphotos.bdimg.com/wisegame/pic/item/f3529822720e0cf3ac9f1ada0846f21fbe09aaa3.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
            //$content[] = array("Title"=>"多图文3标题", "Description"=>"", "PicUrl"=>"http://g.hiphotos.bdimg.com/wisegame/pic/item/18cb0a46f21fbe090d338acc6a600c338644adfd.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
        }else if (strstr($keyword, "音乐")){
            //$content = array();
            //$content = array("Title"=>"最炫民族风", "Description"=>"歌手：凤凰传奇", "MusicUrl"=>"http://121.199.4.61/music/zxmzf.mp3", "HQMusicUrl"=>"http://121.199.4.61/music/zxmzf.mp3");
        }
        else if(is_numeric($keyword))
        {
            $group_id=(int)$keyword;

            if($group_id>=100&&$group_id<=200)
            {
                //$content=$keyword;

                $open_id=$object->FromUserName;

                $weixin = new class_weixin();

                $result=$weixin->user_to_group($open_id,$group_id);

                $content="谢谢您的支持。";
            }
        }
        else{
            //更新或者添加用户信息
            //user_insert_update($object->FromUserName);

        }

        if(is_array($content)){
            if (isset($content[0])){
                $result = $this->transmitNews($object, $content);
            }else if (isset($content['MusicUrl'])){
                $result = $this->transmitMusic($object, $content);
            }
        }else{
            $result = $this->transmitText($object, $content);
        }
        return $result;
    }

    //接收图片消息
    private function receiveImage($object)
    {
        $content = array("MediaId"=>$object->MediaId);
        $result = $this->transmitImage($object, $content);
        return $result;
    }

    //接收位置消息
    private function receiveLocation($object)
    {
        $content = "你发送的是位置，经度为：".$object->Location_Y."；纬度为：".$object->Location_X."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    //接收语音消息
    private function receiveVoice($object)
    {
        if (isset($object->Recognition) && !empty($object->Recognition)){
            $content = "你刚才说的是：".$object->Recognition;
            $result = $this->transmitText($object, $content);
        }else{
            $content = array("MediaId"=>$object->MediaId);
            $result = $this->transmitVoice($object, $content);
        }
        return $result;
    }

    //接收视频消息
    private function receiveVideo($object)
    {
        $content = array("MediaId"=>$object->MediaId, "ThumbMediaId"=>$object->ThumbMediaId, "Title"=>"", "Description"=>"");
        $result = $this->transmitVideo($object, $content);
        return $result;
    }

    //接收链接消息
    private function receiveLink($object)
    {
        $content = "你发送的是链接，标题为：".$object->Title."；内容为：".$object->Description."；链接地址为：".$object->Url;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    //回复文本消息
    private function transmitText($object, $content)
    {
        if (!isset($content) || empty($content)){
            return "";
        }

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);

        return $result;
    }

    //回复图文消息
    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return "";
        }
        $itemTpl = "
<item>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <PicUrl><![CDATA[%s]]></PicUrl>
            <Url><![CDATA[%s]]></Url>
        </item>
";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[news]]></MsgType>
    <ArticleCount>%s</ArticleCount>
    <Articles>
$item_str    </Articles>
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

    //回复音乐消息
    private function transmitMusic($object, $musicArray)
    {
        if(!is_array($musicArray)){
            return "";
        }
        $itemTpl = "<music>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <MusicUrl><![CDATA[%s]]></MusicUrl>
        <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
    </music>";

        $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[music]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复图片消息
    private function transmitImage($object, $imageArray)
    {
        $itemTpl = "<image>
        <MediaId><![CDATA[%s]]></MediaId>
    </image>";

        $item_str = sprintf($itemTpl, $imageArray['MediaId']);

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[image]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复语音消息
    private function transmitVoice($object, $voiceArray)
    {
        $itemTpl = "<voice>
        <MediaId><![CDATA[%s]]></MediaId>
    </voice>";

        $item_str = sprintf($itemTpl, $voiceArray['MediaId']);
        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[voice]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复视频消息
    private function transmitVideo($object, $videoArray)
    {
        $itemTpl = "<video>
            <mediaid><![CDATA[%s]]></mediaid>
            <thumbmediaid><![CDATA[%s]]></thumbmediaid>
            <title><![CDATA[%s]]></title>
            <description><![CDATA[%s]]></description>
        </video>
";

        $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[video]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复多客服消息
    private function transmitService($object)
    {
        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[transfer_customer_service]]></MsgType>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复第三方接口消息
    private function relayPart3($url, $rawData)
    {
        $headers = array("Content-Type: text/xml; charset=utf-8");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $rawData);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    //字节转Emoji表情
    function bytes_to_emoji($cp)
    {
        if ($cp > 0x10000){       # 4 bytes
            return chr(0xF0 | (($cp & 0x1C0000) >> 18)).chr(0x80 | (($cp & 0x3F000) >> 12)).chr(0x80 | (($cp & 0xFC0) >> 6)).chr(0x80 | ($cp & 0x3F));
        }else if ($cp > 0x800){   # 3 bytes
            return chr(0xE0 | (($cp & 0xF000) >> 12)).chr(0x80 | (($cp & 0xFC0) >> 6)).chr(0x80 | ($cp & 0x3F));
        }else if ($cp > 0x80){    # 2 bytes
            return chr(0xC0 | (($cp & 0x7C0) >> 6)).chr(0x80 | ($cp & 0x3F));
        }else{                    # 1 byte
            return chr($cp);
        }
    }

    //日志记录
    private function logger($log_content)
    {
        if(isset($_SERVER['HTTP_APPNAME'])){   //SAE
            sae_set_display_errors(false);
            sae_debug($log_content);
            sae_set_display_errors(true);
        }else if($_SERVER['REMOTE_ADDR'] != "127.0.0.1"){ //LOCAL
            $max_size = 1000000;
            $log_filename = "log.xml";
            if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
            file_put_contents($log_filename, date('Y-m-d H:i:s')." ".$log_content."\r\n", FILE_APPEND);
        }
    }
}


class class_weixin
{
	var $appid = "";
	var $appsecret = "";

    //构造函数，获取Access Token
	//public function __construct($appid = "wxda4d53c7b9a65e2f", $appsecret = "f271242b0fc0d75c4d1d1cb67e592ebc") //大楚时代
    //public function __construct($appid = "wx562735c835d5b677", $appsecret = "43848aacba662b45e02078e05151c688") //鱿鱼说
    public function __construct($appid = "wxa15d78d04e5070ff", $appsecret = "bb873de1918010c55ac5e1aaa7778c26") //新课标同步背单词
	{
        if($appid){
            $this->appid = $appid;
        }
        if($appsecret){
            $this->appsecret = $appsecret;
        }

        //hardcode
        $this->lasttime = 1395049256;
        $this->access_token = "nRZvVpDU7LxcSi7GnG2LrUcmKbAECzRf0NyDBwKlng4nMPf88d34pkzdNcvhqm4clidLGAS18cN1RTSK60p49zIZY4aO13sF-eqsCs0xjlbad-lKVskk8T7gALQ5dIrgXbQQ_TAesSasjJ210vIqTQ";

        if (time() > ($this->lasttime + 7200)){
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appsecret;
            $res = $this->https_request($url);
            $result = json_decode($res, true);
            //save to Database or Memcache
            $this->access_token = $result["access_token"];
            $this->lasttime = time();
        }
	}

    //获取关注者列表
	public function get_user_list($next_openid = NULL)
    {
		$url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$this->access_token."&next_openid=".$next_openid;
        $res = $this->https_request($url);
        return json_decode($res, true);
	}

    //获取用户基本信息
	public function get_user_info($openid)
    {
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$this->access_token."&openid=".$openid."&lang=zh_CN";
		$res = $this->https_request($url);
        //return json_decode($res, true);
        return $res;
	}

    #region 创建菜单
    public function create_menu($data)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->access_token;
        $res = $this->https_request($url, $data);
        //return json_decode($res, true);
        return $res;
    }

    public function get_menu()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token=".$this->access_token;
        $res = $this->https_request($url);
        //return json_decode($res, true);
        return $res;
    }
    #endregion

    //发送客服消息，已实现发送文本，其他类型可扩展
	public function send_custom_message($touser, $type, $data)
    {
        $msg = array('touser' =>$touser);
        switch($type)
        {
			case 'text':
				$msg['msgtype'] = 'text';
				$msg['text']    = array('content'=> urlencode($data));
				break;
        }
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$this->access_token;
		return $this->https_request($url, urldecode(json_encode($msg)));
	}

    //生成参数二维码
    public function create_qrcode($scene_type, $scene_id)
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
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$this->access_token;
        $res = $this->https_request($url, $data);
        
        $result = json_decode($res, true);
        echo "ticket: ".$result["ticket"];
        echo "<br>url: ".$result["url"];
        return "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($result["ticket"]);

       
    }


    #region 用户和组

    #region 把用户移到组
    public function user_to_group($open_id,$group_id)
    {
        $data = '{"openid":"'.$open_id.'","to_groupid":'.$group_id.'}';
        $url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=".$this->access_token;
        $res = $this->https_request($url, $data);
        return json_decode($res, true);
    }
    #endregion

    #region 组
    public function create_group($name)
    {
        $data = '{"group": {"name": "'.$name.'"}}';
        $url = "https://api.weixin.qq.com/cgi-bin/groups/create?access_token=".$this->access_token;
        $res = $this->https_request($url, $data);
        return json_decode($res, true);
    }

    public function update_group($openid, $to_groupid)
    {
        $data = '{"openid":"'.$openid.'","to_groupid":'.$to_groupid.'}';
        $url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=".$this->access_token;
        $res = $this->https_request($url, $data);
        return json_decode($res, true);
    }

    public function get_group()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/groups/get?access_token=".$this->access_token;
        $res = $this->https_request($url);
        return json_decode($res, true);
    }
    #endregion

    #endregion

    //上传多媒体文件
    public function upload_media($type, $file)
    {
        $data = array("media"  => "@".dirname(__FILE__).'\\'.$file);
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=".$this->access_token."&type=".$type;
        $res = $this->https_request($url, $data);
        return json_decode($res, true);
    }

    //地理位置逆解析
    public function location_geocoder($latitude, $longitude)
    {
        $url = "http://api.map.baidu.com/geocoder/v2/?ak=B944e1fce373e33ea4627f95f54f2ef9&location=".$latitude.",".$longitude."&coordtype=gcj02ll&output=json";
        $res = $this->https_request($url);
        $result = json_decode($res, true);
        return $result["result"]["addressComponent"];
    }

    //https请求（支持GET和POST）
    protected function https_request($url, $data = null)
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
}

function writelog($str = "")
{
    $url='http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];

    $open=fopen("log.txt","a");

    fwrite($open , date('y-m-d h:i:s',time())."\t".$url."\t".$str."\r\n");

    fclose($open);
}

function get_word($unit_id,$word)
{
    $database = new medoo(array(
        'database_type' => 'mysql',
        'database_name' => 'kuyxxword',
        'server' => '127.0.0.1',
        'username' => 'uyutalk',
        'password' => 'UyuTAlk_DB-2)16',
        'charset' => 'utf8',
        'port' => 3356
    ));

    //$database = new medoo(array(
    //    'database_type' => 'mysql',
    //    'database_name' => 'kuyxxword',
    //    'server' => '192.168.1.10',
    //    'username' => 'mahongtao',
    //    'password' => 'mht2010',
    //    'charset' => 'utf8'
    //));

    return $database->query("SELECT b.en,b.zh,b.phonetic,b.photo FROM word_in_unit a LEFT JOIN word b on a.word_id=b.id WHERE a.unit_id=$unit_id and b.en='".str_replace("'","\'", $word)."'")->fetchAll();
}

function curl_post_url($url, $content, $timeout = 10) {
    $post = '';
    if (is_array ( $content )) {
        foreach ( $content as $k => $v ) {
            $post .= $k . '=' . $v . '&';
        }
    } else {
        $post = $content;
    }
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
    curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
    curl_setopt ( $ch, CURLOPT_HEADER, 0 );
    curl_setopt ( $ch, CURLOPT_POST, 1 );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post );
    curl_setopt ( $ch, CURLOPT_URL, $url );
    // curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
    // curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    // curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
    $ret = curl_exec ( $ch );
    curl_close ( $ch );
    return $ret;
}

function xmltoarray( $xml )
{
    $arr = xml_to_array($xml);
    $key = array_keys($arr);
    return $arr[$key[0]];
}

function xml_to_array( $xml )
{
    $reg = "/<(\\w+)[^>]*?>([\\x00-\\xFF]*?)<\\/\\1>/";
    if(preg_match_all($reg, $xml, $matches))
    {
        $count = count($matches[0]);
        $arr = array();
        for($i = 0; $i < $count; $i++)
        {
            $key= $matches[1][$i];
            $val = xml_to_array( $matches[2][$i] );  // 递归
            if(array_key_exists($key, $arr))
            {
                if(is_array($arr[$key]))
                {
                    if(!array_key_exists(0,$arr[$key]))
                    {
                        $arr[$key] = array($arr[$key]);
                    }
                }else{
                    $arr[$key] = array($arr[$key]);
                }
                $arr[$key][] = $val;
            }else{
                $arr[$key] = $val;
            }
        }
        return $arr;
    }else{
        return $xml;
        }
    }
?>