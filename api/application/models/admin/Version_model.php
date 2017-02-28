<?php

/**
 * Version_model short summary.
 *
 * Version_model description.
 *
 * @version 1.0
 * @author Administrator
 */
class Version_model extends CI_Model
{
    public function insert($data)
    {
        $this->db->insert('app_version',$data);

        //$id=$this->db->insert_id();

        //$this->db->update('app_version',array('sort_order'=>$id),array('id'=>$id));
    }

    public function delete($id)
    {
        $this->db->delete('app_version',array("id"=>$id));
    }

    public function update($data,$id)
    {
        $this->db->update('app_version',$data,array("id"=>$id));
    }

    public function get_list($book_id)
    {
        $query=$this->db->query("SELECT * FROM app_version WHERE book_id=$book_id");

        return $query->result_array();
    }

    public function get_item($id)
    {
        $query=$this->db->get_where('app_version',array('id'=>$id));

        return $query->row_array();
    }
}