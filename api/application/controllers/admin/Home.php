<?php

/**
 * Home short summary.
 *
 * Home description.
 *
 * @version 1.0
 * @author Administrator
 */
class Home extends CI_Controller
{
    public function index()
    {
        //if(! get_cookie('ADMINID'))
        //{
        //    redirect("admin/login");
        //}

        $this->load->library('calendar');

        $this->load->view('admin/home');
    }
}