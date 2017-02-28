<?php

/**
 * Api short summary.
 *
 * Api description.
 *
 * @version 1.0
 * @author M.H.T
 */
class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('zip');

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
        header('Access-Control-Allow-Methods: GET, POST, PUT');
    }

    #region 基础数据
    public function books()
    {
        $user_id= $this->get_post_stream('user_id');

        if(empty($user_id))
        {
            echo $this->set_error_josn("参数传递错误！");
            return;
        }

        $unionid = $this->db->get_where("user",array("id"=>$user_id))->row_array()['unionid'];

        if(empty($unionid))
        {
            echo $this->set_error_josn("用户不存在！");
            return;
        }

        $remote_result=$this->https_request("http://yz.kaouyu.com/xyanzheng/checkusercode/index.php",array("appid"=>"2000000001","appkey"=>"5a917784509d55bc9419e7bae64dff6c","openid"=>$unionid),"http://yz.kaouyu.com");
       
        $remote_result=json_decode($remote_result,true);

        $query = $this->db->query("SELECT id,grade,semester,`name`,publisher,photo,active_book_id,(SELECT COUNT(id) FROM unit where book_id=book.id) AS unit_total,(SELECT COUNT(id) FROM word_in_unit where book_id=book.id) as word_total FROM book WHERE `status`=1 ORDER BY sort_order");

        $result = $query->result_array();

        foreach($result as $idx => $row)
        {
            if($remote_result['code']=="0" && $remote_result['msg']=="SUCCESS" && isset($remote_result['rs'][$row['active_book_id']]['active']) && $remote_result['rs'][$row['active_book_id']]['active'])
            {
                $result[$idx]['active']=true;
            }
            else
            {
                $result[$idx]['active']=false;
            }
        }

        echo json_encode($result);
    }

    public function book_active()
    {
        $user_id= $this->get_post_stream('user_id');
        $book_id= $this->get_post_stream('book_id');
        $code= $this->get_post_stream('code');

        if(empty($user_id)||empty($book_id)||empty($code))
        {
            echo $this->set_error_josn("参数传递错误！");
            return;
        }

        #region 获取远程书本id和用户unionid
        $active_book_id = $this->db->get_where("book",array("id"=>$book_id))->row_array()['active_book_id'];
        $unionid = $this->db->get_where("user",array("id"=>$user_id))->row_array()['unionid'];

        if(empty($active_book_id))
        {
            echo $this->set_error_josn("书本不存在！");
            return;
        }

        if(empty($unionid))
        {
            echo $this->set_error_josn("用户不存在！");
            return;
        }
        #endregion

        #region 获取远程返回结果
        $remote_result=$this->https_request("http://yz.kaouyu.com/xyanzheng/validate/index.php",array("appid"=>"2000000001","appkey"=>"5a917784509d55bc9419e7bae64dff6c","code"=>$code,"openid"=>$unionid),"http://yz.kaouyu.com");
        $remote_result=json_decode($remote_result,true);
        #endregion

        if($remote_result['code']=="0"&&$remote_result['msg']=="SUCCESS") 
        {
            #region 验证码有效
            $remote_book_id=$remote_result['rs']['id'];

            if($remote_book_id==$active_book_id)
            {
                #region 验证码书本id和将要激活的书本id一致 -> 激活验证码
                $remote_result=$this->https_request("http://yz.kaouyu.com/xyanzheng/activate/index.php",array("appid"=>"2000000001","appkey"=>"5a917784509d55bc9419e7bae64dff6c","code"=>$code,"openid"=>$unionid,"agent"=>"xxbdc APP"),"http://yz.kaouyu.com");
                $remote_result=json_decode($remote_result,true);
                if($remote_result['code']=="0"&&$remote_result['msg']=="SUCCESS")
                {
                    echo json_encode( $remote_result['rs']);
                }
                else
                {
                    echo $this->set_error_josn("激活失败，请联系管理员！");
                    return;
                }
                #endregion
            }
            else
            {
                #region 验证码书本id和将要激活的书本id不一致
                echo $this->set_error_josn("激活码对应的书本不匹配！");
                return;
                #endregion
            }
            #endregion
        }
        else
        {
            #region 验证码无效
            echo $this->set_error_josn("错误的验证码！");
            return;
            #endregion
        }
    }

    public function book_status()
    {
        $user_id= $this->get_post_stream('user_id');
        $book_id= $this->get_post_stream('book_id');

        if(empty($user_id)||empty($book_id))
        {
            echo $this->set_error_josn("参数传递错误！");
            return;
        }

        #region 获取远程书本id和用户unionid
        $active_book_id = $this->db->get_where("book",array("id"=>$book_id))->row_array()['active_book_id'];
        $unionid = $this->db->get_where("user",array("id"=>$user_id))->row_array()['unionid'];

        if(empty($active_book_id))
        {
            echo $this->set_error_josn("书本不存在！");
            return;
        }

        if(empty($unionid))
        {
            echo $this->set_error_josn("用户不存在！");
            return;
        }
        #endregion

        //$active_book_id=1;

        #region 获取远程返回结果
        $remote_result=$this->https_request("http://yz.kaouyu.com/xyanzheng/checkusercode/index.php",array("appid"=>"2000000001","appkey"=>"5a917784509d55bc9419e7bae64dff6c","openid"=>$unionid,"bookid"=>$active_book_id),"http://yz.kaouyu.com");
        //echo $remote_result;
        $remote_result=json_decode($remote_result,true);

        if($remote_result['code']=="0" && $remote_result['msg']=="SUCCESS" && isset($remote_result['rs'][$active_book_id]['active']) && $remote_result['rs'][$active_book_id]['active'])
        {
            echo json_encode($remote_result['rs'][$active_book_id]);
        }
        else
        {
            echo $this->set_error_josn("该本书没激活！");
        }
        #endregion
    }

    public function units()
    {
        $user_id= $this->get_post_stream('user_id');
        $book_id= $this->get_post_stream('book_id');

        if(empty($user_id)||empty($book_id))
        {
            echo $this->set_error_josn("参数传递错误！");
            return;
        }

        $query=$this->db->query("SELECT *,(SELECT COUNT(id) from word_in_unit where unit_id=unit.id) AS word_total,(SELECT COUNT(id) FROM user_record WHERE user_id=".$user_id." AND unit_id=unit.id AND task1>0  AND task2>0  AND task3>0 ) as word_completed_total,(SELECT COUNT(id) FROM user_record WHERE user_id=".$user_id." AND unit_id=unit.id AND game>0) as game_completed_total from unit WHERE book_id=".$book_id." ORDER BY sort_order");

        $result = $query->result_array();
        
        echo json_encode($result);
    }

    public function words()
    {
        $user_id= $this->get_post_stream('user_id');
        $unit_id= $this->get_post_stream('unit_id');

        if(empty($user_id)||empty($unit_id))
        {
            echo $this->set_error_josn("参数传递错误！");
            return;
        }

        $query=$this->db->query("SELECT a.book_id,a.unit_id,a.word_id, b.en,b.zh,b.phonetic,b.audio_0,b.audio_1,b.video,b.photo,c.task1,c.task2,c.task3,c.game FROM word_in_unit a LEFT JOIN word b on a.word_id=b.id LEFT JOIN user_record c on c.unit_id=a.unit_id and c.word_id=a.word_id  and c.user_id='$user_id' WHERE a.unit_id=$unit_id ORDER BY a.sort_order;");

        $result = $query->result_array();

        foreach($result as $index => $row)
        {
            #region 例句
            $query=$this->db->query("SELECT id,word_id,en,zh,audio FROM word_example where type=1 AND word_id=".$row["word_id"]." ORDER BY sort_order");
            $result[$index]["examples"]=$query->result_array();
            #endregion

            #region 组合
            $query=$this->db->query("SELECT id,word_id,en,zh,audio FROM word_example where type=2 AND word_id=".$row["word_id"]." ORDER BY sort_order");
            $result[$index]["collocations"]=$query->result_array();
            #endregion

            #region 练习
            $query=$this->db->query("SELECT *,(SELECT point from user_record_exercise where user_id=".$user_id." and exercise_id=word_exercise.id) as point FROM word_exercise where word_id=".$row["word_id"]." ORDER BY sort_order");
            $result[$index]["exercises"]=$query->result_array();
            #endregion
        }

        echo json_encode($result);
    }

    public function word()
    {
        $user_id= $this->get_post_stream('user_id');
        $book_id= $this->get_post_stream('book_id');
        $unit_id= $this->get_post_stream('unit_id');

        $word= $this->get_post_stream('word');

        if(empty($word))
        {
            echo $this->set_error_josn("参数传递错误！");
            return;
        }

        if(empty($user_id))
        {
            $user_id=0;
        }

        if($book_id && $unit_id)
        {
            $query=$this->db->query("SELECT a.book_id,a.unit_id,a.word_id, b.en,b.zh,b.phonetic,b.audio_0,b.audio_1,b.video,b.photo,c.task1,c.task2,c.task3,c.game FROM word_in_unit a LEFT JOIN word b on a.word_id=b.id LEFT JOIN user_record c on c.unit_id=a.unit_id and c.word_id=a.word_id  and c.user_id='$user_id' WHERE a.unit_id=$unit_id and b.en=? ORDER BY a.sort_order;",array('en'=>$word));

            if($query->num_rows()>0)
            {
                $result = $query->row_array();

                #region 例句
                $query=$this->db->query("SELECT id,word_id,en,zh,audio FROM word_example where type=1 AND word_id=".$result["word_id"]." ORDER BY sort_order");
                $result["examples"]=$query->result_array();
                #endregion

                #region 组合
                $query=$this->db->query("SELECT id,word_id,en,zh,audio FROM word_example where type=2 AND word_id=".$result["word_id"]." ORDER BY sort_order");
                $result["collocations"]=$query->result_array();
                #endregion

                #region 练习
                $query=$this->db->query("SELECT * FROM word_exercise where word_id=".$result["word_id"]." ORDER BY sort_order");
                $result["exercises"]=$query->result_array();
                #endregion

                echo json_encode($result);
            }
            else
            {
                echo $this->set_error_josn("您查询的单词不存在！");
            }
        }
        else
        {
            $query=$this->db->get_where('word',array('en'=>$word));

            if($query->num_rows()>0)
            {
                $result = $query->row_array();

                #region 例句
                $query=$this->db->query("SELECT id,word_id,en,zh,audio FROM word_example where type=1 AND word_id=".$result["id"]." ORDER BY sort_order");
                $result["examples"]=$query->result_array();
                #endregion

                #region 组合
                $query=$this->db->query("SELECT id,word_id,en,zh,audio FROM word_example where type=2 AND word_id=".$result["id"]." ORDER BY sort_order");
                $result["collocations"]=$query->result_array();
                #endregion

                #region 练习
                $query=$this->db->query("SELECT * FROM word_exercise where word_id=".$result["id"]." ORDER BY sort_order");
                $result["exercises"]=$query->result_array();
                #endregion

                echo json_encode($result);
            }
            else
            {
                echo $this->set_error_josn("您查询的单词不存在！");
            }
        }
    }

    public function resource()
    {
        ini_set("memory_limit","300M");

        $book_id= $this->get_post_stream('book_id');

        if(empty($book_id))
        {
            echo $this->set_error_josn("参数传递错误！");
            return;
        }

        $source_root=$_SERVER['DOCUMENT_ROOT']."/upload";

        $path=$_SERVER['DOCUMENT_ROOT']."/upload/resource/book_".$book_id;
        $path_exercise=$path."/exercise";
        $path_exercise_img=$path_exercise."/img";
        $path_exercise_mp3=$path_exercise."/mp3";

        $path_unit=$path."/unit";

        $path_word=$path."/word";
        $path_word_example=$path_word."/example";
        $path_word_img=$path_word."/img";
        $path_word_mp3=$path_word."/mp3";
        $path_word_mp4=$path_word."/mp4";

        if(!is_dir($path)){mkdir($path);}
        if(!is_dir($path_exercise)){mkdir($path_exercise);}
        if(!is_dir($path_exercise_img)){mkdir($path_exercise_img);}
        if(!is_dir($path_exercise_mp3)){mkdir($path_exercise_mp3);}
        if(!is_dir($path_unit)){mkdir($path_unit);}
        if(!is_dir($path_word)){mkdir($path_word);}
        if(!is_dir($path_word_example)){mkdir($path_word_example);}
        if(!is_dir($path_word_img)){mkdir($path_word_img);}
        if(!is_dir($path_word_mp3)){mkdir($path_word_mp3);}
        if(!is_dir($path_word_mp4)){mkdir($path_word_mp4);}

        #region 单元
        $result=$this->db->query("SELECT photo FROM unit where book_id=?", array($book_id))->result_array();
        foreach($result as $row)
        {
            $photo=$row['photo'];

            if($photo){@copy($source_root."/unit/".$photo, $path_unit."/".$photo);}
        }
        #endregion

        #region 单词
        $result=$this->db->query("SELECT audio_0,audio_1,audio_2,video,photo from word a, word_in_unit b where a.id=b.word_id and b.book_id=?", array($book_id))->result_array();
        foreach($result as $row)
        {
            $audio_0=$row['audio_0'];
            $audio_1=$row['audio_1'];
            //$audio_2=$row['audio_2'];
            $video=$row['video'];
            $photo=$row['photo'];

            if($audio_0){@copy($source_root."/word/mp3/".$audio_0, $path_word_mp3."/".$audio_0);}
            if($audio_1){@copy($source_root."/word/mp3/".$audio_1, $path_word_mp3."/".$audio_1);}
            if($video){@copy($source_root."/word/mp4/".$video, $path_word_mp4."/".$video);}
            if($photo){@copy($source_root."/word/img/".$photo, $path_word_img."/".$photo);}
        }
        #endregion

        #region 练习
        $result=$this->db->query("SELECT type, items,media from word_exercise a,word_in_unit b where a.word_id=b.word_id and b.book_id=?", array($book_id))->result_array();
        foreach($result as $row)
        {
            $type=$row['type'];
            $items=$row['items'];
            $media=$row['media'];

            if($media){@copy($source_root."/exercise/mp3/".$media, $path_exercise_mp3."/".$media);}

            if($items)
            {
                foreach(explode("\n",$items) as $item)
                {
                    $item=trim($item);

                    if($item)
                    {
                        if($type=="2"||$type=="6")
                        {
                            @copy($source_root."/exercise/img/".$item, $path_exercise_img."/".$item);
                        }

                        if($type=="5")
                        {
                            @copy($source_root."/exercise/mp3/".$item, $path_exercise_mp3."/".$item);
                        }
                    }
                }
            }
        }
        #endregion

        $this->zip->read_dir($path,FALSE);

        $this->zip->download('book_'.$book_id.'.zip');
    }

    public function stt()
    {
        $audio=@$_FILES['audio']['name'];
        $text=$this->input->post("text");
        $format=$this->input->post("format");

        if(empty($audio)||empty($text))
        {
            echo $this->set_error_josn("参数传递错误！");
            return;
        }

        if(empty($format))
        {
            $format="amr";
        }

        $audio_file=$_FILES["audio"]["tmp_name"];

        $url = "http://vop.baidu.com/server_api";

        //put your params here
        $cuid = "Mark-kuy-xxword";
        $apiKey = "ljDE2SkeFDseI8WDSpaL5tbO";
        $secretKey = "b38d8d16b422874588a460413c86323f";

        $auth_url = "https://openapi.baidu.com/oauth/2.0/token?grant_type=client_credentials&client_id=".$apiKey."&client_secret=".$secretKey;
        $response=$this->https_request($auth_url);
        $response = json_decode($response, true);
        $token = $response['access_token'];

        $audio = file_get_contents($audio_file);
        $base_data = base64_encode($audio);
        $array = array(
                "format" => $format,
                "rate" => 8000,
                "channel" => 1,
                "lan" => "en",
                "token" => $token,
                "cuid"=> $cuid,
                "len" => filesize($audio_file),
                "speech" => $base_data,
                );
        $json_array = json_encode($array);
        $content_len = "Content-Length: ".strlen($json_array);
        $header = array ($content_len, 'Content-Type: application/json; charset=utf-8');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_array);
        $response = curl_exec($ch);
        if(curl_errno($ch))
        {
            //print curl_error($ch);
        }
        curl_close($ch);

        $response = json_decode($response, true);

        $baidu_results=@$response['result'];
        $rate=0;
        $return_text="";

        if($baidu_results)
        {
            foreach($baidu_results as $r)
            {
                $r=str_replace(",","",$r);
                $r=trim($r);
                similar_text(strtolower( $text),strtolower($r),$percent);

                if($percent>$rate)
                {
                    $rate=$percent;
                    $return_text=$r;
                }

            }
        }

        if($rate==0)
        {
            echo $this->set_error_josn("未识别出来，请重新再试");
        }
        else
        {
            $result=array('in_text'=>$text,'out_text'=>$return_text,'rate'=>$rate);

            echo json_encode($result);
        }

    }

    public function versions()
    {
        $platform= $this->get_post_stream('platform');

        if(empty($platform))
        {
            echo $this->set_error_josn("参数传递错误！");
            return;
        }

        $result=array();

        $books=$this->db->query("SELECT id FROM book ORDER BY sort_order")->result_array();

        foreach($books as $book)
        {
            $item=$this->db->query("SELECT a.*,b.grade,b.semester,b.`name`,b.publisher,b.photo FROM app_version a, book b WHERE a.book_id=b.id AND a.book_id=? AND a.platform=?", array('book_id'=>$book['id'],'platform'=>$platform))->row_array();

            if($item)
            {
                array_push($result,$item);
            }
        }

        echo json_encode($result);
    }

    public function version()
    {
        $platform= $this->get_post_stream('platform');
        $book_id= $this->get_post_stream('book_id');

        if(empty($platform)||empty($book_id))
        {
            echo $this->set_error_josn("参数传递错误！");
            return;
        }

        $result=$this->db->query("SELECT a.*,b.grade,b.semester,b.`name`,b.publisher,b.photo FROM app_version a, book b WHERE a.book_id=b.id AND a.book_id=? AND a.platform=?", array('book_id'=>$book_id,'platform'=>$platform))->row_array();

        if($result)
        {
            echo json_encode($result);
        }
        else
        {
            echo $this->set_error_josn("无记录！");
        }
    }
    #endregion

    #region 用户相关
    public function user_register()
    {
        $subscribe= $this->get_post_stream('subscribe');
        $openid= $this->get_post_stream('openid');
        $nickname= $this->get_post_stream('nickname');
        $sex= $this->get_post_stream('sex');
        $language= $this->get_post_stream('language');
        $city= $this->get_post_stream('city');
        $province= $this->get_post_stream('province');
        $country= $this->get_post_stream('country');
        $headimgurl= $this->get_post_stream('headimgurl');
        $subscribe_time= $this->get_post_stream('subscribe_time');
        $unionid= $this->get_post_stream('unionid');
        $remark= $this->get_post_stream('remark');
        $groupid= $this->get_post_stream('groupid');


        if(empty($unionid))
        {
            echo $this->set_error_josn("参数传递错误！");
            return;
        }

        $data=array(
            'subscribe'=>$subscribe,
            'openid'=>$openid,
            'nickname'=>$nickname,
            'sex'=>$sex,
            'language'=>$language,
            'city'=>$city,
            'province'=>$province,
            'country'=>$country,
            'headimgurl'=>$headimgurl,
            'subscribe_time'=>$subscribe_time,
            'unionid'=>$unionid,
            'remark'=>$remark,
            'groupid'=>$groupid,
            );

        if($this->db->get_where('user',array('unionid'=>$unionid))->num_rows()>0)
        {
            $this->db->update('user',$data,array('unionid'=>$unionid));

            $result=$this->db->get_where('user',array('unionid'=>$unionid))->row_array();

            echo json_encode($result);
        }
        else
        {
            $this->db->insert('user',$data);

            $user_id=$this->db->insert_id();

            $result=$this->db->get_where('user',array('id'=>$user_id))->row_array();

            echo json_encode($result);
        }
    }

    public function user()
    {
        $user_id= $this->get_post_stream('user_id');
        $unionid= $this->get_post_stream('unionid');

        if(empty($user_id) && empty($unionid))
        {
            echo $this->set_error_josn("参数传递错误！");
            return;
        }

        if($user_id && $this->db->get_where('user',array('id'=>$user_id))->num_rows()>0)
        {
            $result=$this->db->get_where('user',array('id'=>$user_id))->row_array();

            echo json_encode($result);
        }
        else if($unionid && $this->db->get_where('user',array('unionid'=>$unionid))->num_rows()>0)
        {
            $result=$this->db->get_where('user',array('unionid'=>$unionid))->row_array();

            echo json_encode($result);
        }
        else
        {
            echo $this->set_error_josn("用户不存在！");
        }
    }

    public function user_set_book()
    {
        $user_id= $this->get_post_stream('user_id');
        $book_id= $this->get_post_stream('book_id');

        if(empty($user_id) && empty($book_id))
        {
            echo $this->set_error_josn("参数传递错误！");
            return;
        }

        if($this->db->get_where('user',array('id'=>$user_id))->num_rows()>0)
        {
            $this->db->update('user',array('book_id'=>$book_id),array('id'=>$user_id));

            $result=$this->db->get_where('user',array('id'=>$user_id))->row_array();

            echo json_encode($result);
        }
        else
        {
            echo $this->set_error_josn("用户不存在！");
        }
    }

    public function user_completed_task()
    {
        $user_id= $this->get_post_stream('user_id');
        $book_id= $this->get_post_stream('book_id');
        $unit_id= $this->get_post_stream('unit_id');
        $word_id= $this->get_post_stream('word_id');

        $task1= $this->get_post_stream('task1');
        $task2= $this->get_post_stream('task2');
        $task3= $this->get_post_stream('task3');
        $game= $this->get_post_stream('game');

        if(empty($user_id)||empty($book_id)||empty($unit_id)||empty($word_id))
        {
            echo $this->set_error_josn("参数传递错误！");
            return;
        }

        $data= $where=array(
           'user_id'=>$user_id,
           'book_id'=>$book_id,
           'unit_id'=>$unit_id,
           'word_id'=>$word_id,
           );

        if($task1){$data['task1']=$task1;}
        if($task2){$data['task2']=$task2;}
        if($task3){$data['task3']=$task3;}
        if($game){$data['game']=$game;}

        if($this->db->get_where('user_record',$where)->num_rows()>0)
        {
            $this->db->update('user_record',$data,$where);
        }
        else
        {
            $this->db->insert('user_record',$data);
        }

        $result=$this->db->get_where('user_record',$where)->row_array();

        echo json_encode($result);
    }

    public function user_completed_exercise()
    {
        $user_id= $this->get_post_stream('user_id');
        $book_id= $this->get_post_stream('book_id');
        $unit_id= $this->get_post_stream('unit_id');
        $word_id= $this->get_post_stream('word_id');
        $exercise_id= $this->get_post_stream('exercise_id');
        $point= $this->get_post_stream('point');


        if(empty($user_id)||empty($book_id)||empty($unit_id)||empty($word_id)||empty($exercise_id)||empty($point))
        {
            echo $this->set_error_josn("参数传递错误！");
            return;
        }

        $data= $where=array(
           'user_id'=>$user_id,
           'book_id'=>$book_id,
           'unit_id'=>$unit_id,
           'word_id'=>$word_id,
           'exercise_id'=>$exercise_id,
           );

        $data['point']=$point;


        if($this->db->get_where('user_record_exercise',$where)->num_rows()>0)
        {
            $this->db->update('user_record_exercise',$data,$where);
        }
        else
        {
            $this->db->insert('user_record_exercise',$data);
        }

        $result=$this->db->get_where('user_record_exercise',$where)->row_array();

        echo json_encode($result);
    }

    public function user_game_order()
    {
        $user_id= $this->get_post_stream('user_id');
        $book_id= $this->get_post_stream('book_id');

        if(empty($book_id))
        {
            echo $this->set_error_josn("参数传递错误！");
            return;
        }

        if(empty($user_id))
        {
            $result=$this->db->query('SELECT id,nickname,sex,headimgurl,unionid,book_id,(SELECT COUNT(id) FROM user_record WHERE book_id='.$book_id.' AND game=1 AND user_id=`user`.id) AS game_completed_total FROM `user` ORDER BY game_completed_total DESC LIMIT 30;')->result_array();

            echo json_encode($result);
        }
        else
        {
            $current_user=array();

            $result=$this->db->query('SELECT id,nickname,sex,headimgurl,unionid,book_id,(SELECT COUNT(id) FROM user_record WHERE book_id='.$book_id.' AND game=1 AND user_id=`user`.id) AS game_completed_total FROM `user` ORDER BY game_completed_total DESC;')->result_array();

            foreach($result as $idx => $row)
            {
                if($row['id']==$user_id)
                {
                    $current_user['game_completed_total']=$row['game_completed_total'];
                    $current_user['sort_order']=$idx+1;
                    break;
                }
            }

            if(isset($current_user['sort_order']))
            {
                echo json_encode($current_user);
            }
            else
            {
                echo $this->set_error_josn("暂无排名");
            }
        }

    }

    public function user_point()
    {
        $user_id= $this->get_post_stream('user_id');

        if(empty($user_id))
        {
            echo $this->set_error_josn("参数传递错误！");
            return;
        }

        $task1_completed_total=$this->db->query("SELECT SUM(task1) as total FROM user_record WHERE task1>0 AND user_id=".$user_id)->row_array()['total'];
        $task2_completed_total=$this->db->query("SELECT SUM(task2) as total FROM user_record WHERE task2>0 AND user_id=".$user_id)->row_array()['total'];
        $task3_completed_total=$this->db->query("SELECT SUM(task3) as total FROM user_record WHERE task3>0 AND user_id=".$user_id)->row_array()['total'];
        $game_completed_total=$this->db->query("SELECT COUNT(id) as total FROM user_record WHERE game=1 AND user_id=".$user_id)->row_array()['total'];
        $exercise_completed_total=$this->db->query("SELECT SUM(point) as total FROM user_record_exercise WHERE user_id=".$user_id)->row_array()['total'];

        $result=array(
            'task1_completed_total'=>$task1_completed_total,
            'task2_completed_total'=>$task2_completed_total,
            'task3_completed_total'=>$task3_completed_total,
            'game_completed_total'=>$game_completed_total,
            'exercise_completed_total'=>$exercise_completed_total,
            'point'=>$task1_completed_total+$task2_completed_total+$task3_completed_total+$game_completed_total+$exercise_completed_total
            );

        echo json_encode($result);
    }
    #endregion

    #region 通用函数
    private function get_post_stream($key)
    {
        $value=$this->input->get_post($key);

        if(isset($value))
        {
            return $value;
        }
        else
        {
            $data = file_get_contents('php://input');

            $data = json_decode($data,true);

            if($data && key_exists($key,$data))
            {
                return $data[$key];
            }
            else
            {
                return null;
            }
        }
    }

    private function set_error_josn($msg)
    {
        $arr=array('error'=>1,'msg'=>$msg);

        return json_encode($arr);
    }

    function https_request($url, $data = null, $referer = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data))
        {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        if(!empty($referer))
        {
            curl_setopt($curl, CURLOPT_REFERER, $referer);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($curl);
        curl_close($curl);

        return $output;
    }
    #endregion
}