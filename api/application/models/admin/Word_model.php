<?php

/**
 * Article_model short summary.
 *
 * Article_model description.
 *
 * @version 1.0
 * @author Administrator
 */
class Word_model extends CI_Model
{
    public function insert($data)
    {
        $this->db->insert('word',$data);
    }

    public function delete($id)
    {
        $this->db->trans_start();
        $this->db->delete('word',array('id'=>$id));
        $this->db->delete('word_in_unit',array('word_id'=>$id));
        $this->db->trans_complete();
    }

    public function update($data,$id)
    {
        $this->db->update('word',$data,array('id'=>$id));
    }

    public function get_list($num = FALSE, $offset = FALSE, $title = FALSE)
	{
        $sql="SELECT *,(SELECT COUNT(id) FROM word_example where type=1 AND word_id=word.id) AS example_total,(SELECT COUNT(id) FROM word_example where type=2 AND word_id=word.id) AS collocation_total,(SELECT COUNT(id) FROM word_exercise where word_id=word.id) AS exercise_total,(SELECT COUNT(id) FROM word_in_unit where word_id=word.id) AS in_unit_total FROM word";
        

        if($title)
        {
            $sql.=" WHERE en LIKE '%".str_replace("'","\'", $title)."%'";
        }

        //$sql.=" ORDER BY en ASC";
        $sql.=" ORDER BY id DESC";

        //echo $sql;

        if($num!=FALSE || $offset!=FALSE)
        {
            if($offset==FALSE)
            {
                $offset=0;
            }

            $sql.=" LIMIT ".$offset.",".$num;
        }

        $query=$this->db->query($sql);

        return $query->result_array();

    }

    public function get_list2()
	{
        $sql="SELECT a.id as book_id, a.grade, a.semester,a.publisher,b.id as unit_id, b.`name`,b.name_zh,c.id as word_id,c.en,c.zh from book a, unit b, word c, word_in_unit d where a.id=d.book_id and b.id=d.unit_id and c.id=d.word_id ";

        if($this->input->get_post("orderby")=="book")
        {
            $sql.=" ORDER BY a.sort_order, b.sort_order,d.sort_order";
        }
        else
        {
            $sql.=" ORDER BY c.en";
        }

        $query=$this->db->query($sql);

        return $query->result_array();
    }

    public function get_count( $title = FALSE)
	{
        $sql="SELECT id FROM word";


        if($title)
        {
            $sql.=" WHERE en LIKE '%".str_replace("'","\'", $title)."%'";
        }

        $query=$this->db->query($sql);

        return $query->num_rows();

    }

    public function get_item($id)
    {
        $query=$this->db->get_where('word',array('id'=>$id));

        return $query->row_array();
    }
}