<?php

/**
 * Article short summary.
 *
 * Article description.
 *
 * @version 1.0
 * @author Administrator
 */
class Word extends CI_Controller
{
    public function __construct()
	{
		parent::__construct();

        $this->load->model('admin/Book_model');
        $this->load->model('admin/Unit_model');
        $this->load->model('admin/Word_model');
	}

    public function index()
    {
        $icount=$this->Word_model->get_count($this->input->post('title'));

        $this->load->library('pagination');

        $config = array(
               'base_url'       => site_url().'/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3),
               'total_rows'     => $icount,
               'per_page'       => 100,
               'num_links'      => 5,
               'first_link'     => FALSE,
               'last_link'      => FALSE,
               'full_tag_open'  => "<ul class='pagination'>",//关闭标签
               'full_tag_close' => '</ul>',
               'num_tag_open'   => '<li>',	//数字html
               'num_tag_close'  => '</li>',	//当前页html
               'cur_tag_open'   => "<li class='active'><a href='javascript:void(0),'>",
               'cur_tag_close'  => "</a></li>",
               'next_tag_open'  => '<li>',	//上一页下一页html
               'next_tag_close' => '</li>',
               'prev_tag_open'  => '<li>',
               'prev_tag_close' => '</li>',
               'prev_link'      => "<i class='iconfont'>&#xf3d2;</i>",
               'next_link'      => "<i class='iconfont'>&#xf3d3;</i>"
      );

        $this->pagination->initialize($config);
        $data=array(
                    'books' =>$this->Book_model->get_list(),
                    'units' =>$this->Unit_model->get_list(),
                    'words'       => $this->Word_model->get_list($config['per_page'],$this->uri->segment(4),$this->input->post('title')),
                    'words_count'  => $icount
                );

        $this->load->view('admin/word_list',$data);

    }

    public function list2()
    {

        $data=array(
                    'words' =>$this->Word_model->get_list2()
                );

        $this->load->view('admin/word_list2',$data);

    }

    public function create($id=false)
    {
        $data=array(
            'info'      => '',
            'word'   => array('id'=>'','en'=>'','zh'=>'','phonetic'=>'','audio_0'=>'','audio_1'=>'','audio_2'=>'','video'=>'','photo'=>''),
            );

        if($id)
        {
            $data['word']=$this->Word_model->get_item($id);
        }

        $this->load->view('admin/word_create',$data);
    }

    public function delete($id)
    {
        if($id)
        {
            $this->Word_model->delete($id);
        }

        redirect("admin/word/index");
    }

    public function save()
    {

        $inStr = array(
            'id' => $this->input->post('id'),
            'en' => $this->input->post('en'),
            'zh' => $this->input->post('zh'),
            'phonetic' => $this->input->post('phonetic'),
            'audio_0' => $this->input->post('audio_0'),
            'audio_1' => $this->input->post('audio_1'),
            'audio_2' => $this->input->post('audio_2'),
            'video' => $this->input->post('video'),
            'photo' => $this->input->post('photo')
            );

        $info='';
        if($inStr['en'] == "")
        {
            $info.="请输入单词<br>";
        }

        if($inStr['zh'] == "")
        {
            $info.="请输入单词汉语意思<br>";
        }

        
        if($info!='')
        {
            $data=array(
                'info'      => $info,
                'word'   => array('id'=>'','en'=>'','zh'=>'','phonetic'=>'','audio_0'=>'','audio_1'=>'','audio_2'=>'','video'=>'','photo'=>''),
                );

            $this->load->view('admin/word_create',$data);
        }
        else
        {

            if($inStr['en'])
            {
                $inStr['en']=trim($inStr['en']);
            }

            $id=$this->input->post('id');

            if($id)
            {
                $this->Word_model->update($inStr,$id);
            }
            else
            {
                $this->Word_model->insert($inStr);
            }

            redirect('admin/word/index');
        }
    }

    public function import()
    {
        $this->load->view('admin/word_import');
    }

    public function import_db($en, $media, $media_type)
    {    
        $en=urldecode($en);

        if($this->db->get_where('word',array('en'=>$en))->num_rows()>0)
        {
            if($media_type=="photo"&&$this->input->get('book_id'))
            {
                $sql="update word a INNER JOIN word_in_unit b on a.id=b.word_id SET a.photo='".$media."' where a.id=b.word_id and b.book_id='".$this->input->get('book_id')."' and a.en='".$en."'";
                
                $this->db->query($sql);
            }
            else if($media_type=="video"&&$this->input->get('book_id'))
            {
                $sql="update word a INNER JOIN word_in_unit b on a.id=b.word_id SET a.video='".$media."' where a.id=b.word_id and b.book_id='".$this->input->get('book_id')."' and a.en='".$en."'";

                $this->db->query($sql);
            }
            else
            {
                $this->db->update('word',array($media_type=>$media),array('en'=>$en));
            }
        }
        else
        {
            $this->db->insert('word',array('en'=>$en,$media_type=>$media));
        }
        echo "1";
    }
}