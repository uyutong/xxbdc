<?php

/**
 * Unit_model short summary.
 *
 * Unit_model description.
 *
 * @version 1.0
 * @author Administrator
 */
class Example_model extends CI_Model
{
    public function insert($data)
    {
        $this->db->insert('word_example',$data);

        $id=$this->db->insert_id();

        $this->db->update('word_example',array('sort_order'=>$id),array('id'=>$id));
    }

    public function delete($id)
    {
        $this->db->delete('word_example',array('id'=>$id));
    }

    public function update($data,$id)
    {
        $this->db->update('word_example',$data,array('id'=>$id));
    }

    public function get_list($type, $word_id)
	{
        $query=$this->db->query("SELECT * FROM word_example WHERE type=$type AND word_id=$word_id ORDER BY sort_order");

        return $query->result_array();
    }


    public function get_item($id)
    {
        $query=$this->db->get_where('word_example',array('id'=>$id));

        return $query->row_array();
    }
}