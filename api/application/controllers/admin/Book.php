<?php

/**
 * Book short summary.
 *
 * Book description.
 *
 * @version 1.0
 * @author Administrator
 */
class Book extends CI_Controller
{
    public function __construct()
	{
		parent::__construct();

        $this->load->model('admin/Book_model');
	}

    public function index()
    {
        $data=array(
                    'books'       => $this->Book_model->get_list()
                );

        $this->load->view('admin/book_list',$data);

    }

    public function create($id=false)
    {
        $data=array(
            'info'      => '',
            'book'   => array('id'=>'','grade'=>'','semester'=>'','name'=>'','publisher'=>'','photo'=>'','status'=>'','sort_order'=>''),
            );

        if($id)
        {
            $data['book']=$this->Book_model->get_item($id);
        }

        $this->load->view('admin/book_create',$data);
    }

    public function delete($id)
    {
        if($id)
        {
            $this->Book_model->delete($id);
        }

        redirect("admin/book");
    }

    public function save()
    {

        $inStr = array(
                'grade' => $this->input->post('grade'),
                'semester' => $this->input->post('semester'),
                'name' => $this->input->post('name'),
                'publisher' => $this->input->post('publisher'),
                'photo' => $this->input->post('photo'),
                );


        $info='';
        if($inStr['name'] == "")
        {
            $info.="请填写书名<br>";
        }

        if($inStr['photo'] == "")
        {
            $info.="请上传封面<br>";
        }

        if($info!='')
        {
            $data=array(
                'info'      => $info,
                 'book'   => array('id'=>'','grade'=>'','semester'=>'','name'=>'','publisher'=>'','photo'=>'','status'=>'','sort_order'=>''),
                );

            $this->load->view('admin/book_create',$data);
        }
        else
        {
            if($inStr['name'])
            {
                $inStr['name']=trim($inStr['name']);
            }

            $id=$this->input->post('id');

            if($id)
            {
                $this->Book_model->update($inStr,$id);
            }
            else
            {
                $this->Book_model->insert($inStr);
            }

            redirect('admin/book');
        }
    }
}