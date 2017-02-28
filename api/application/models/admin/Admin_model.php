<?php

/**
 * Admin_model short summary.
 *
 * Admin_model description.
 *
 * @version 1.0
 * @author Administrator
 */
class Admin_model extends CI_Model
{

    public function get_item($username,$password)
    {
        $query=$this->db->get_where('admin',array('username'=>$username,'password'=>$password));

        return $query->row();
    }

    public function get_item_id($id)
    {
        $query=$this->db->get_where('admin',array('id'=>$id));
        
        return $query->row();
    }

    public function update($id,$newpassword)
    {
        $this->db->where('id',$id);

        $this->db->update('admin',array('password'=>md5($newpassword)));

        return $this->db->affected_rows();
    }

}