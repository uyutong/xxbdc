<?php

/**
 * Book_model short summary.
 *
 * Book_model description.
 *
 * @version 1.0
 * @author Administrator
 */
class Book_model extends CI_Model
{
    public function insert($data)
    {
        $this->db->insert('book',$data);

        $id=$this->db->insert_id();

        $this->db->update('book',array('sort_order'=>$id),array('id'=>$id));
    }

    public function delete($id)
    {
        $this->db->trans_start();
        $this->db->delete('book',array('id'=>$id));
        $this->db->delete('unit',array('book_id'=>$id));
        $this->db->delete('word_in_unit',array('book_id'=>$id));
        $this->db->trans_complete();
    }

    public function update($data,$id)
    {
        $this->db->update('book',$data,array('id'=>$id));
    }

    public function get_list()
	{
        $query=$this->db->query("SELECT *,(SELECT COUNT(id) FROM unit where book_id=book.id) AS unit_total,(SELECT COUNT(id) FROM word_in_unit where book_id=book.id) AS word_total FROM book ORDER BY sort_order");

        return $query->result_array();
    }


    public function get_item($id)
    {
        $query=$this->db->get_where('book',array('id'=>$id));

        return $query->row_array();
    }
}