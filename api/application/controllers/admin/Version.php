<?php

/**
 * Version short summary.
 *
 * Version description.
 *
 * @version 1.0
 * @author Administrator
 */
class Version extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('admin/Book_model');
        $this->load->model('admin/Version_model');
    }

    public function index($book_id)
    {

        $data=array(
            'book'        => $this->Book_model->get_item($book_id),
            'versions'       => $this->Version_model->get_list($book_id)
            );

        $this->load->view('admin/version_list',$data);
    }

    public function delete($id)
    {
        if($id)
        {
            $this->Version_model->delete($id);
        }
    }

    public function create($book_id, $id=false)
    {
        $data=array(
            'info'       => '',
            'book'       => $this->Book_model->get_item($book_id),
            'version'   => array('id'=>'','book_id'=>'','version'=>'','platform'=>'','url'=>'','info'=>'','size'=>'','release_time'=>'','status'=>'')
            );

        if($id)
        {
            $data['version']=$this->Version_model->get_item($id);
        }

        $this->load->view('admin/version_create',$data);
    }

    public function save($book_id)
    {


        $inStr = array(
            'book_id' => $book_id,
            'version' => $this->input->post('version'),
            'platform' => $this->input->post('platform'),
            'url' => $this->input->post('url'),
            'info' => $this->input->post('info'),
            'size' => $this->input->post('size'),
            'release_time' => $this->input->post('release_time'),
            'status' => $this->input->post('status')
            );
        

        $id=$this->input->post('id');

        if($id)
        {
            $this->Version_model->update($inStr,$id);
        }
        else
        {
            $this->Version_model->insert($inStr);
        }

        redirect('admin/version/index/'.$book_id);

    }

}