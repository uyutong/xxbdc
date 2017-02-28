<?php

/**
 * Book short summary.
 *
 * Book description.
 *
 * @version 1.0
 * @author Administrator
 */
class Example extends CI_Controller
{
    public function __construct()
	{
		parent::__construct();

        $this->load->model('admin/Word_model');
        $this->load->model('admin/Example_model');
	}

    public function index($type, $word_id)
    {
        $data=array(
                    'word'        => $this->Word_model->get_item($word_id),
                    'examples'    => $this->Example_model->get_list($type, $word_id)
                );

        $this->load->view('admin/example_list',$data);

    }

    public function create($type, $word_id, $id=false)
    {
        $data=array(
            'info'   => '',
            'word'        => $this->Word_model->get_item($word_id),
            'example'   => array('id'=>'','word_id'=>'','en'=>'','zh'=>'','audio'=>'','type'=>'','sort_order'=>'')
            );

        if($id)
        {
            $data['example']=$this->Example_model->get_item($id);
        }

        $this->load->view('admin/example_create',$data);
    }

    public function delete($type, $word_id, $id)
    {
        if($id)
        {
            $this->Example_model->delete($id);
        }

        redirect("admin/example/index/".$type."/".$word_id);
    }

    public function save($type, $word_id)
    {

        $inStr = array(
                'word_id' => $word_id,
                'en' => $this->input->post('en'),
                'zh' => $this->input->post('zh'),
                'audio' => $this->input->post('audio'),
                'type' => $type
                );


        $info='';
        if($inStr['en'] == "")
        {
            $info.="请填写英文<br>";
        }


        if($info!='')
        {
            $data=array(
                'info'   => $info,
                'word'        => $this->Word_model->get_item($word_id),
                'example'   => array('id'=>'','word_id'=>'','en'=>'','zh'=>'','audio'=>'','type'=>'','sort_order'=>'')
                );

            $this->load->view('admin/example_create',$data);
        }
        else
        {
            $id=$this->input->post('id');

            if($id)
            {
                $this->Example_model->update($inStr,$id);
            }
            else
            {
                $this->Example_model->insert($inStr);
            }

            redirect("admin/example/index/".$type."/".$word_id);
        }
    }
}