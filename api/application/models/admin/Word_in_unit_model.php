<?php

/**
 * Article_model short summary.
 *
 * Article_model description.
 *
 * @version 1.0
 * @author Administrator
 */
class Word_in_unit_model extends CI_Model
{
    public function insert($book_id,$unit_id,$word_id)
    {
        if($this->db->get_where("word_in_unit",array("book_id"=>$book_id,"unit_id"=>$unit_id,"word_id"=>$word_id))->num_rows()==0)
        {
            $this->db->insert('word_in_unit',array("book_id"=>$book_id,"unit_id"=>$unit_id,"word_id"=>$word_id));

            $id=$this->db->insert_id();

            $this->db->update('word_in_unit',array('sort_order'=>$id),array('id'=>$id));
        }
    }

    public function delete($book_id,$unit_id,$id)
    {
        $this->db->delete('word_in_unit',array('id'=>$id));
    }

    public function get_list($book_id, $unit_id)
	{
        $query=$this->db->query("SELECT a.*,b.en,b.zh,b.phonetic,b.audio_0,b.audio_1,b.audio_2,b.video,b.photo,(SELECT COUNT(id) FROM word_example where type=1 AND word_id=b.id) AS example_total,(SELECT COUNT(id) FROM word_example where type=2 AND word_id=b.id) AS collocation_total,(SELECT COUNT(id) FROM word_exercise where word_id=b.id) AS exercise_total FROM word_in_unit a LEFT JOIN word b on a.word_id=b.id WHERE a.unit_id=$unit_id ORDER BY a.sort_order");

        return $query->result_array();
    }
}