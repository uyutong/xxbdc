<?php

/**
 * Unit_model short summary.
 *
 * Unit_model description.
 *
 * @version 1.0
 * @author Administrator
 */
class Unit_model extends CI_Model
{
    public function insert($data)
    {
        $this->db->insert('unit',$data);

        $id=$this->db->insert_id();

        $this->db->update('unit',array('sort_order'=>$id),array('id'=>$id));
    }

    public function delete($id)
    {
        $this->db->trans_start();
        $this->db->delete('unit',array('id'=>$id));
        $this->db->delete('word_in_unit',array('unit_id'=>$id));
        $this->db->trans_complete();
    }

    public function update($data,$id)
    {
        $this->db->update('unit',$data,array('id'=>$id));
    }

    public function get_list($book_id=false)
	{
        $sql="SELECT *, (SELECT COUNT(id) from word_in_unit where unit_id=unit.id) AS word_total FROM unit";

        if($book_id)
        {
            $sql.=" WHERE book_id=$book_id";
        }
        $sql.="  ORDER BY sort_order";

        $query=$this->db->query($sql);

        return $query->result_array();
    }


    public function get_item($id)
    {
        $query=$this->db->get_where('unit',array('id'=>$id));

        return $query->row_array();
    }
}