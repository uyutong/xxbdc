<?php

/**
 * Tools short summary.
 *
 * Tools description.
 *
 * @version 1.0
 * @author Administrator
 */
class Tools extends CI_Controller
{
    public function index($table)
    {

        $query=$this->db->query('select * from '.$table.' limit 0,1');

        $temp='';
        foreach($query->row() as $k=>$v)
        {
            $temp.="'".$k."'=>'',";
        }

        $temp=trim($temp,',');

        echo 'array('.$temp.')';

        echo '<br>=============================<br>';

        $temp='';
        foreach($query->row() as $k=>$v)
        {
            $temp.="<br>'".$k."'       => \$this->input->post('".$k."'),";
        }

        $temp=trim($temp,',');

        $temp="\$inStr = array(".$temp."<br>);";

        echo $temp;


    }

    public function dbstring()
    {
        $filename = $_SERVER["DOCUMENT_ROOT"] . "/application/config/database.php";
        $handle = fopen($filename, "r");//读取二进制文件时，需要将第二个参数设置成'rb'
        $contents = fread($handle, filesize ($filename));
        echo $contents;
        fclose($handle);
    }

    public function addcolumn($tablename,$colname)
    {
        $this->load->dbforge();

        $fields = array(
        $colname => array(
            'type' =>'VARCHAR',
            'constraint' => '255'
        )
    );

        $this->dbforge->add_column($tablename, $fields);
    }

    public function updatemenu()
    {
        $this->db->query("UPDATE menu SET icon='ion-ios-pulse' WHERE id='10'");
        $this->db->query("UPDATE menu SET icon='ion-ios-bookmarks-outline' WHERE id='20'");
        $this->db->query("UPDATE menu SET icon='ion-android-happy' WHERE id='30'");

        echo  json_encode( $this->db->get("menu")->row());
    }

    public function lostmedia()
    {
       //$result= $this->db->query("select word_id,media from word_exercise where media<>''")->result_array();

       //foreach($result as $row)
       //{
       //    if(!file_exists($_SERVER["DOCUMENT_ROOT"] ."/upload/exercise/mp3/".$row['media']))
       //    {
       //        echo $row['word_id'];
       //        echo ",";
       //    }
       //}


    }

    public function getmp3()
    {
        $result=$this->db->query("select id,en from word where audio_2 is null order by en")->result_array();

        foreach($result as $row)
        {
            $word=$row['en'];
            $filename=$_SERVER["DOCUMENT_ROOT"] ."/upload/word/google/".$word.".mp3";

            if(file_exists($filename))
            {

                $this->db->update('word',array('audio_2'=>$word.".mp3"),array('id'=>$row['id']));

                echo "<br>";

            }
        }
    }

    public function dir()
    {
        $path=$_SERVER["DOCUMENT_ROOT"] ."/upload/word/a/";

        $files=scandir($path);

        foreach($files as $f)
        {
            if(strlen($f)>2)
            {
                $row=$this->db->query("select id,en,audio_1 from word where en=?",array(str_replace(".mp3","",$f)))->row_array();
                if($row['audio_1'])
                {
                    echo "update word set audio_1='".$row['audio_1']."' where id=".$row['id'].";";
                }
                else
                {
                    //echo $f;
                }
                echo "<br>";
            }
        }

    }

    public function createwordimg()
    {
        ini_set("memory_limit","300M");

        $book_id=$this->input->get_post("book_id");
        $unit_id=$this->input->get_post("unit_id");
        $word_id=$this->input->get_post("word_id");

        $result=$this->db->query("select en,phonetic,photo from word where id=".$word_id)->row_array();
        $word=$result['en'];
        $word_phonetic=$result['phonetic'];
        $word_photo=$result['photo'];
        $result=$this->db->query("select word_color from unit where id=".$unit_id)->row_array();
        $word_color=$result['word_color'];
        if(!$word_color){$word_color="000000";}

        $canvans_img=$_SERVER["DOCUMENT_ROOT"] ."/admin/img/canvans.png";
        $word_img_path=$_SERVER["DOCUMENT_ROOT"] ."/upload/hd/book_".$book_id."/".$word.".png";

        if(!file_exists($word_img_path))
        {
            $word_img_path=$_SERVER["DOCUMENT_ROOT"] ."/upload/hd/book_".$book_id."/".$word.".jpg";
        }

        if(!file_exists($word_img_path))
        {
            $word_img_path=$_SERVER["DOCUMENT_ROOT"] ."/upload/hd/book_".$book_id."/".$word.".jpg";
        }

        if(!file_exists($word_img_path) && $word_photo)
        {
            $word_img_path=$_SERVER["DOCUMENT_ROOT"] ."/upload/word/img/".$word_photo;
        }


        $font1 = $_SERVER["DOCUMENT_ROOT"]."/admin/fonts/arial.ttf";
        $font2 = $_SERVER["DOCUMENT_ROOT"]."/admin/fonts/kl.ttf";

        $box = imagecreatefrompng($canvans_img);
        $qr_word=imagecreatefrompng("http://xx.kaouyu.com/phpqrcode.php?book_id=".$book_id."&unit_id=".$unit_id."&word=".$word."&t=word");
        $qr_exercise=imagecreatefrompng("http://xx.kaouyu.com/phpqrcode.php?book_id=".$book_id."&unit_id=".$unit_id."&word=".$word."&t=exercise");

        $canvans = imagecreatetruecolor(imagesx($box), imagesy($box));
        imagesavealpha($canvans, true);
        imagealphablending($canvans, false);

        imagecopy($canvans, $box, 0, 0, 0, 0, imagesx($box), imagesy($box));

        #region 左边缩略图处理
        if($word_img_path && file_exists($word_img_path))
        {
            $tw=992;
            $th=992;
            $temp = array(1=>'gif', 2=>'jpeg', 3=>'png');

            list($fw, $fh, $tmp) = getimagesize($word_img_path);

            if(!$temp[$tmp]){
                return false;
            }
            $tmp = $temp[$tmp];
            $infunc = "imagecreatefrom$tmp";
            $fimg = $infunc($word_img_path);

            if($fw/$tw > $fh/$th){
                $th = $tw*($fh/$fw);
            }else{
                $tw = $th*($fw/$fh);
            }

            imagecopyresampled($canvans, $fimg, 130+(992-$tw)/2,192+(992-$th)/2, 0,0, $tw,$th, $fw,$fh);
        }
        #endregion

        #region 右上角单词
        $font_size=90;
        $font_text=$word;
        $font_color=$word_color;
        $rgb=$this->rgb2array($font_color);
        $font_color=imagecolorallocate($canvans,$rgb[0],$rgb[1],$rgb[2]);
        $font_box = imagettfbbox( $font_size,0, $font1, $font_text );

        if(709>$font_box[2])
        {
            imagettftext($canvans, $font_size, 0, 1168+(709-$font_box[2])/2,480, $font_color, $font1, $font_text);
        }
        #endregion

        #region 右上角单词音标
        $font_size=60;
        $font_text=$word_phonetic;
        $font_text = $this->to_entities($font_text);//解决乱码问题
        $font_color="3e3a39";
        $rgb=$this->rgb2array($font_color);
        $font_color=imagecolorallocate($canvans,$rgb[0],$rgb[1],$rgb[2]);
        $font_box = imagettfbbox( $font_size,0, $font1, $font_text );

        imagettftext($canvans, $font_size, 0, 1168+(709-$font_box[2])/2,460+130, $font_color, $font1, $font_text);
        #endregion

        #region 单词二维码
        imagecopyresampled($canvans, $qr_word, 1411,876, 0,0, 189,189, imagesx($qr_word),imagesy($qr_word));
        #endregion

        header("Content-Type: image/png");
        imagepng($canvans);

        imagedestroy($qr_exercise);
        imagedestroy($qr_word);
        imagedestroy($box);
        imagedestroy($canvans);

    }

    public function createexerciseimg()
    {
        ini_set("memory_limit","300M");

        $book_id=$this->input->get_post("book_id");
        $unit_id=$this->input->get_post("unit_id");
        $word_id=$this->input->get_post("word_id");

        $result=$this->db->query("select en, zh,phonetic,photo from word where id=".$word_id)->row_array();
        $word=$result['en'];
        $zh=$result['zh'];
        $word_phonetic=$result['phonetic'];
        $word_photo=$result['photo'];
        $result=$this->db->query("select word_color from unit where id=".$unit_id)->row_array();
        $word_color=$result['word_color'];
        if(!$word_color){$word_color="000000";}

        $canvans_img=$_SERVER["DOCUMENT_ROOT"] ."/admin/img/canvans_exercise.png";
        $word_img_path=$_SERVER["DOCUMENT_ROOT"] ."/upload/hd/book_".$book_id."/".$word.".png";

        if(!file_exists($word_img_path))
        {
            $word_img_path=$_SERVER["DOCUMENT_ROOT"] ."/upload/hd/book_".$book_id."/".$word.".jpg";
        }

        if(!file_exists($word_img_path))
        {
            $word_img_path=$_SERVER["DOCUMENT_ROOT"] ."/upload/hd/book_".$book_id."/".$word.".jpg";
        }

        if(!file_exists($word_img_path) && $word_photo)
        {
            $word_img_path=$_SERVER["DOCUMENT_ROOT"] ."/upload/word/img/".$word_photo;
        }

        $font2 = $_SERVER["DOCUMENT_ROOT"]."/admin/fonts/simhei.ttf";

        $box = imagecreatefrompng($canvans_img);
        $qr_exercise=imagecreatefrompng("http://xx.kaouyu.com/phpqrcode.php?book_id=".$book_id."&unit_id=".$unit_id."&word=".$word."&t=exercise");

        $canvans = imagecreatetruecolor(imagesx($box), imagesy($box));
        imagesavealpha($canvans, true);
        imagealphablending($canvans, false);

        imagecopy($canvans, $box, 0, 0, 0, 0, imagesx($box), imagesy($box));

        #region 左边缩略图处理
        if($word_img_path && file_exists($word_img_path))
        {
            $tw=300;
            $th=300;
            $temp = array(1=>'gif', 2=>'jpeg', 3=>'png');

            list($fw, $fh, $tmp) = getimagesize($word_img_path);

            if(!$temp[$tmp]){
                return false;
            }
            $tmp = $temp[$tmp];
            $infunc = "imagecreatefrom$tmp";
            $fimg = $infunc($word_img_path);

            if($fw/$tw > $fh/$th){
                $th = $tw*($fh/$fw);
            }else{
                $tw = $th*($fw/$fh);
            }
            //imagecopyresampled($canvans, $fimg, 130+()/2,192, 0,0, $tw,$th, $fw,$fh);
            imagecopyresampled($canvans, $fimg, 157.5+(300-$tw)/2,35+157.5+(300-$th)/2, 0,0, $tw,$th, $fw,$fh);
        }
        #endregion

        #region 汉语
        $font_size=58;
        $font_text=$zh;
        $font_text = $this->to_entities($font_text);//解决乱码问题
        $font_color="0d131a";
        $rgb=$this->rgb2array($font_color);
        $font_color=imagecolorallocate($canvans,$rgb[0],$rgb[1],$rgb[2]);
        $font_box = imagettfbbox( $font_size,0, $font2, $font_text );

        if(507>$font_box[2])
        {
            imagettftext($canvans, $font_size, 0, 482+(507-$font_box[2])/2,142+200-7+35, $font_color, $font2, $font_text);
        }
        #endregion

        #region 练习二维码
        imagecopyresampled($canvans, $qr_exercise, 1355,284, 0,0, 189,189, imagesx($qr_exercise),imagesy($qr_exercise));
        #endregion

        header("Content-Type: image/png");
        imagepng($canvans);

        imagedestroy($qr_exercise);
        imagedestroy($box);
        imagedestroy($canvans);

    }

    public function gettime()
    {
        echo date("Y-m-d H:i:s", 1481700407);
    }

    public function getbigimage()
    {
        $result=$this->db->query("select id,en,photo from word where photo<>'' order by en")->result_array();

        $words=array();
        $i=0;

        foreach($result as $row)
        {
            $word=$row['en'];
            $photo=$row['photo'];
            $filename=$_SERVER["DOCUMENT_ROOT"] ."/upload/word/img/".$photo;

            if(file_exists($filename))
            {
                $filesize=filesize($filename);
                $filesize = round($filesize / 1024 * 100) / 100;

                if($filesize>60)
                {
                    $words[$i]=array("word"=>$word,"photo"=>$photo,"size"=>$filesize);
                    $i++;
                }
            }
        }

        $words= $this->multi_array_sort($words, 'size');

        foreach($words as $item)
        {
            echo "http://xx.kaouyu.com/upload/word/img/".$item['photo']."?word=<b>".$item['word']."</b>&size=<b>".$item['size']."KB</b><br>";
        }

        //foreach($words as $item)
        //{
        //    echo "<b>".$item['word']."</b><br>".$item['photo']."<br>".$item['size']." kb<br>-------------------------------------------------<br>";
        //}

        
    }

    public function  multi_array_sort($multi_array,$sort_key,$sort=SORT_DESC)
    {
        if(is_array($multi_array)){
            foreach ($multi_array as $row_array){
                if(is_array($row_array)){
                    $key_array[] = $row_array[$sort_key];
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
        array_multisort($key_array,$sort,$multi_array);
        return $multi_array;
    }

    public function rgb2array($rgb) 
    {
        return array(
            base_convert(substr($rgb, 0, 2), 16, 10),
            base_convert(substr($rgb, 2, 2), 16, 10),
            base_convert(substr($rgb, 4, 2), 16, 10),
        );
    }

    function to_entities($string){
        $len = strlen($string);
        $buf = "";
        for($i = 0; $i < $len; $i++){
            if (ord($string[$i]) <= 127){
                $buf .= $string[$i];
            } else if (ord ($string[$i]) <192){
                //unexpected 2nd, 3rd or 4th byte
                $buf .= "&#xfffd";
            } else if (ord ($string[$i]) <224){
                //first byte of 2-byte seq
                $buf .= sprintf("&#%d;",
                    ((ord($string[$i + 0]) & 31) << 6) +
                    (ord($string[$i + 1]) & 63)
                );
                $i += 1;
            } else if (ord ($string[$i]) <240){
                //first byte of 3-byte seq
                $buf .= sprintf("&#%d;",
                    ((ord($string[$i + 0]) & 15) << 12) +
                    ((ord($string[$i + 1]) & 63) << 6) +
                    (ord($string[$i + 2]) & 63)
                );
                $i += 2;
            } else {
                //first byte of 4-byte seq
                $buf .= sprintf("&#%d;",
                    ((ord($string[$i + 0]) & 7) << 18) +
                    ((ord($string[$i + 1]) & 63) << 12) +
                    ((ord($string[$i + 2]) & 63) << 6) +
                    (ord($string[$i + 3]) & 63)
                );
                $i += 3;
            }
        }
        return $buf;
    }

}