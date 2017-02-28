<?php

/**
 * Password short summary.
 *
 * Password description.
 *
 * @version 1.0
 * @author Administrator
 */
class Password extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('admin/Admin_model');
    }

    public function index()
    {
        $data['info']='';

        $this->load->view("admin/password",$data);
    }

    public function save()
    {
        $old_password=$this->input->post('old_password');
        $password1=$this->input->post('password1');
        $password2=$this->input->post('password2');

        if($old_password==""||$password1==""||$password2=="")
        {
            $data['info']="旧密码，新密码，确认密码都需要填写";

            $this->load->view('admin/password',$data);
        }
        else if($password1!=$password2)
        {
            $data['info']="两次新密码不一致，请核实";

            $this->load->view('admin/password',$data);
        }
        else
        {
            $admin=$this->Admin_model->get_item_id(get_cookie('ADMINID'));

            if($admin->password!=md5($old_password))
            {
                $data['info']="旧密码错误。";

                $this->load->view('admin/password',$data);
            }
            else
            {
                if($this->Admin_model->update($admin->id,$password1))
                {
                    $data['info']="密码修改成功。";
                }
                else
                {
                    $data['info']="密码修改失败。";
                }
                
                $this->load->view('admin/password',$data);
            }
        }
    }
}