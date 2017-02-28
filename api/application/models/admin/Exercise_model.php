<?php

/**
 * Exercise_model short summary.
 *
 * Exercise_model description.
 *
 * @version 1.0
 * @author Administrator
 */
class Exercise_model extends CI_Model
{
    public function insert($data)
    {
        $this->db->insert('word_exercise',$data);

        $id=$this->db->insert_id();

        $this->db->update('word_exercise',array('sort_order'=>$id),array('id'=>$id));
    }

    public function delete($id)
    {
        $this->db->delete('word_exercise',array("id"=>$id));
    }

    public function update($data,$id)
    {
        $this->db->update('word_exercise',$data,array("id"=>$id));
    }

    public function get_list($word_id)
    {
        $query=$this->db->query("SELECT * FROM word_exercise WHERE word_id=$word_id ORDER BY sort_order");

        return $query->result_array();
    }

    public function get_item($id)
    {
        $query=$this->db->get_where('word_exercise',array('id'=>$id));

        return $query->row_array();
    }
}