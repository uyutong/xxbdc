<?php

/**
 * Login short summary.
 *
 * Login description.
 *
 * @version 1.0
 * @author Administrator
 */
class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('admin/Admin_model');
    }

    public function index()
    {
        $data['info']='';

        $this->load->view("admin/login",$data);
    }

    public function check()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        if($username==""||$password=="")
        {
            $data["info"]="请输入用户名和密码";

            $this->load->view('admin/login',$data);
        }
        else
        {
            $admin = $this->Admin_model->get_item($username,md5($password));

            if($admin==null)
            {
                $data["info"]="用户名或者密码错误";

                $this->load->view('admin/login',$data);
            }
            else
            {
                set_cookie("ADMINID",$admin->id,86500);
                set_cookie("ADMINNAME",$admin->username,86500);

                //echo get_cookie("ADMINNAME");

                redirect('admin/home');
            }
        }

    }

    public function logout()
    {
        delete_cookie('ADMINID');
        delete_cookie('ADMINNAME');

        redirect("admin/login/");
    }
}