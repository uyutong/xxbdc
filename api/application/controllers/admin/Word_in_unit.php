<?php

/**
 * Word_in_unit short summary.
 *
 * Word_in_unit description.
 *
 * @version 1.0
 * @author Administrator
 */
class Word_in_unit extends CI_Controller
{
    public function __construct()
	{
		parent::__construct();

        $this->load->library('zip');

        $this->load->model('admin/Book_model');
        $this->load->model('admin/Unit_model');
        $this->load->model('admin/Word_in_unit_model');
	}

    public function index($book_id, $unit_id)
    {
        
        $data=array(
            'book'   => $this->Book_model->get_item($book_id),
            'unit'   => $this->Unit_model->get_item($unit_id),
            'words'  => $this->Word_in_unit_model->get_list($book_id, $unit_id)
                );

        $this->load->view('admin/word_in_unit_list',$data);
    }

    public function qrcode($book_id, $unit_id)
    {
        $unit_name =$this->Unit_model->get_item($unit_id)['name'];

        $result = $this->Word_in_unit_model->get_list($book_id, $unit_id);

        $path=$_SERVER['DOCUMENT_ROOT']."/upload/qrcode/".$book_id."_".$unit_id;

        if(!is_dir($path)){mkdir($path);}
        //if(!is_dir($path."/word")){mkdir($path."/word");}
        //if(!is_dir($path."/exercise")){mkdir($path."/exercise");}

        foreach($result as $row)
        {
            $filename1=$path."/".$row['en'].".png";
            $filename2=$path."/".$row['en']."_exercise.png";

            $this->get_qr_img(base_url()."phpqrcode.php?book_id=".$book_id."&unit_id=".$unit_id."&word=".$row['en']."&t=word",$filename1);
            $this->get_qr_img(base_url()."phpqrcode.php?book_id=".$book_id."&unit_id=".$unit_id."&word=".$row['en']."&t=exercise",$filename2);
        }

        $this->zip->read_dir($path,FALSE);

        $this->zip->download($unit_name.'.zip');
    }

    public function createimage($book_id, $unit_id)
    {
        ini_set("memory_limit","300M");

        $unit_name =$this->Unit_model->get_item($unit_id)['name'];

        $result = $this->Word_in_unit_model->get_list($book_id, $unit_id);

        $path=$_SERVER['DOCUMENT_ROOT']."/upload/print/".$book_id."_".$unit_id;

        if(!is_dir($path)){mkdir($path);}

        foreach($result as $row)
        {
            $filename=$path."/".$row['en'].".png";
            $filename2=$path."/".$row['en']."_exercise.png";

            $this->get_qr_img(base_url()."index.php/tools/createwordimg?book_id=".$book_id."&unit_id=".$unit_id."&word_id=".$row['word_id'],$filename);
            $this->get_qr_img(base_url()."index.php/tools/createexerciseimg?book_id=".$book_id."&unit_id=".$unit_id."&word_id=".$row['word_id'],$filename2);
        }

        $this->zip->read_dir($path,FALSE);

        $this->zip->download($unit_name.'.zip');
    }

    private function get_qr_img($url = "", $filename = "")
    {
        $hander = curl_init();
        $fp = fopen($filename,'wb');
        curl_setopt($hander,CURLOPT_URL,$url);
        curl_setopt($hander,CURLOPT_FILE,$fp);
        curl_setopt($hander,CURLOPT_HEADER,0);
        curl_setopt($hander,CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($hander,CURLOPT_TIMEOUT,60);
        curl_exec($hander);
        curl_close($hander);
        fclose($fp);
        Return true;
    }

    public function create($book_id, $unit_id,$word_id)
    {
        if($word_id)
        {
            $this->Word_in_unit_model->insert($book_id, $unit_id, $word_id);
        }

        echo "1";
    }

    public function delete($book_id, $unit_id,$id)
    {
        if($id)
        {
            $this->Word_in_unit_model->delete($book_id, $unit_id,$id);
        }

        redirect("admin/word_in_unit/index/".$book_id."/".$unit_id);

    }

    public function list_json($word_id)
    {
        $query = $this->db->query("SELECT a.*,b.grade,b.semester,b.`name`,b.publisher,c.`name` as unit_name FROM word_in_unit a,book b, unit c WHERE a.book_id=b.id AND a.unit_id=c.id AND a.word_id=".$word_id." ORDER BY b.sort_order,c.sort_order");

        $result=$query->result_array();

        echo json_encode($result);
    }
}