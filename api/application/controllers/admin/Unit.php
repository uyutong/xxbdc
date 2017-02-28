<?php

/**
 * Book short summary.
 *
 * Book description.
 *
 * @version 1.0
 * @author Administrator
 */
class Unit extends CI_Controller
{
    public function __construct()
	{
		parent::__construct();

        $this->load->library('zip');

        $this->load->model('admin/Book_model');
        $this->load->model('admin/Unit_model');
	}

    public function index($book_id)
    {
        $data=array(
                    'book'        => $this->Book_model->get_item($book_id),
                    'units'       => $this->Unit_model->get_list($book_id)
                );

        $this->load->view('admin/unit_list',$data);

    }

    public function create($book_id, $id=false)
    {
        $data=array(
            'info'   => '',
            'book'   => $this->Book_model->get_item($book_id),
            'unit'   => array('id'=>'','book_id'=>'','name'=>'','name_zh'=>'','photo'=>'','sort_order'=>'')
            );

        if($id)
        {
            $data['unit']=$this->Unit_model->get_item($id);
        }

        $this->load->view('admin/unit_create',$data);
    }

    public function delete($book_id, $id)
    {
        if($id)
        {
            $this->Unit_model->delete($id);
        }

        redirect("admin/unit/index/".$book_id);
    }

    public function save($book_id)
    {

        $inStr = array(
                'book_id' => $book_id,
                'name' => $this->input->post('name'),
                'name_zh' => $this->input->post('name_zh'),
                'photo' => $this->input->post('photo'),
                'word_color' => $this->input->post('word_color'),
                'bg_color' => $this->input->post('bg_color'),
                );


        $info='';
        if($inStr['name'] == "")
        {
            $info.="请填写单元名称<br>";
        }

        if($inStr['photo'] == "")
        {
            $info.="请上传单元图片<br>";
        }

        if($info!='')
        {
            $data=array(
                'info'   => $info,
                'book'   => $this->Book_model->get_item($book_id),
                'unit'   => array('id'=>'','book_id'=>'','name'=>'','name_zh'=>'','photo'=>'','sort_order'=>'')
                );

            $this->load->view('admin/unit_create',$data);
        }
        else
        {
            if($inStr['name'])
            {
                $inStr['name']=trim($inStr['name']);
            }

            if($inStr['word_color'])
            {
                $inStr['word_color']=trim($inStr['word_color']);
                $inStr['word_color']=trim($inStr['word_color'],"#");
            }

            if($inStr['bg_color'])
            {
                $inStr['bg_color']=trim($inStr['bg_color']);
                $inStr['bg_color']=trim($inStr['bg_color'],"#");
            }

            $id=$this->input->post('id');

            if($id)
            {
                $this->Unit_model->update($inStr,$id);
            }
            else
            {
                $this->Unit_model->insert($inStr);
            }

            redirect('admin/unit/index/'.$book_id);
        }
    }

    public function resource($book_id)
    {
        ini_set("memory_limit","900M");

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
}