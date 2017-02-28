<?php

/**
 * Exercise short summary.
 *
 * Exercise description.
 *
 * @version 1.0
 * @author Administrator
 */
class Exercise extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('admin/Word_model');
        $this->load->model('admin/Exercise_model');
    }

    public function index($word_id)
    {

        $data=array(
            'word'        => $this->Word_model->get_item($word_id),
            'exercise'       => $this->Exercise_model->get_list($word_id)
            );

        $this->load->view('admin/exercise_list',$data);
    }

    public function delete($id)
    {
        if($id)
        {
            $this->Exercise_model->delete($id);
        }
    }

    public function create($word_id, $id=false)
    {
        $data=array(
            'info'       => '',
            'word'       => $this->Word_model->get_item($word_id),
            'exercise'   => array('id'=>'','word_id'=>$word_id,'type'=>'','question'=>'','items'=>'','answer'=>'','media'=>'','sort_order'=>'')
            );

        if($id)
        {
            $data['exercise']=$this->Exercise_model->get_item($id);
        }

        $this->load->view('admin/exercise_create',$data);
    }

    public function save($word_id)
    {


        $inStr = array(
                'word_id' => $word_id,
                'type' => $this->input->post('type'),
                'question' => $this->input->post('question'),
                'items' => $this->input->post('items'),
                'answer' => $this->input->post('answer'),
                'media' => $this->input->post('media'),
                );

        if($inStr['question'])
        {
            $inStr['question']=str_replace('_________','___',$inStr['question']);
            $inStr['question']=str_replace('________','___',$inStr['question']);
            $inStr['question']=str_replace('_______','___',$inStr['question']);
            $inStr['question']=str_replace('______','___',$inStr['question']);
            $inStr['question']=str_replace('_____','___',$inStr['question']);
            $inStr['question']=str_replace('____','___',$inStr['question']);

            $inStr['question']=trim($inStr['question']);
        }

        if($inStr['items'])
        {
            $inStr['items']=str_replace('_________','___',$inStr['items']);
            $inStr['items']=str_replace('________','___',$inStr['items']);
            $inStr['items']=str_replace('_______','___',$inStr['items']);
            $inStr['items']=str_replace('______','___',$inStr['items']);
            $inStr['items']=str_replace('_____','___',$inStr['items']);
            $inStr['items']=str_replace('____','___',$inStr['items']);

            $inStr['items']=trim($inStr['items']);
        }

        $id=$this->input->post('id');

        if($id)
        {
            $this->Exercise_model->update($inStr,$id);
        }
        else
        {
            $this->Exercise_model->insert($inStr);
        }

        redirect('admin/exercise/index/'.$word_id);

    }

    public function import_db($en, $media, $media_type)
    {
        $en=urldecode($en);

        $word=$this->db->query("select id from word where en='".$en."' or REPLACE(en,' ','')='".$en."' or REPLACE(en,'-','')='".$en."'")->row_array();

        if($word['id'])
        {
            if($media_type=="media")
            {
                if($this->db->get_where('word_exercise',array('word_id'=>$word['id'],'type'=>'2'))->num_rows()>0)
                {
                    $this->db->update('word_exercise',array('media'=>$media),array('word_id'=>$word['id'],'type'=>'2'));
                }
                else
                {
                    $this->db->insert("word_exercise",array('word_id'=>$word['id'],'type'=>'2','media'=>$media));
                }
            }
        }
        
        echo "1";
    }
}