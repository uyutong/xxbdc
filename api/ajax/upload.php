<?php

if ($_FILES["file"]["error"] > 0)
{
    echo "error";
}
else
{
    $path= $_POST["path"];
    $fileIndex=$_POST["fileIndex"];

    $oldname=$_FILES['file']['name'];
    $newfile=time().$fileIndex.substr($oldname, strrpos( $oldname,"."));

    if(isset($_POST['retain'])&&$_POST["retain"])
    {
        $newfile=$oldname;
    }

    move_uploaded_file($_FILES["file"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] .$path . $newfile);

    echo $newfile;

    if(isset($_POST['size']))
    {
        $sizes=explode("_",$_POST['size']);
        $x=$sizes[0];
        $y=$sizes[1];
        $w=$sizes[2];
        $h=$sizes[3];
        $f=$_SERVER["DOCUMENT_ROOT"].$path.$newfile;
        $imgtype= exif_imagetype($f);

        // "GIF";
        if($imgtype=="1")
        {
            $targ_w = $targ_h = 360;
            $jpeg_quality = 100;

            $img_r = imagecreatefromgif($f);
            $dst_r = ImageCreateTrueColor($targ_w, $targ_h);

            imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $targ_w, $targ_h, $w, $h);

            imagegif($dst_r, $_SERVER["DOCUMENT_ROOT"] .$path.$x."_".$y."_".$w."_".$h."-".$newfile);
        }

        // "JPEG";
        if($imgtype=="2") 
        {
            $targ_w = $targ_h = 360;
            $jpeg_quality = 100;

            $img_r = imagecreatefromjpeg($f);
            $dst_r = ImageCreateTrueColor($targ_w, $targ_h);

            imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $targ_w, $targ_h, $w, $h);

            imagejpeg($dst_r, $_SERVER["DOCUMENT_ROOT"] .$path.$x."_".$y."_".$w."_".$h."-".$newfile, $jpeg_quality);
        }

        // "PNG";
        if($imgtype=="3")
        {
            $targ_w = $targ_h = 360;
            $jpeg_quality = 100;

            $img_r = imagecreatefrompng($f);
            $dst_r = ImageCreateTrueColor($targ_w, $targ_h);

            imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $targ_w, $targ_h, $w, $h);

            imagepng($dst_r, $_SERVER["DOCUMENT_ROOT"] .$path.$x."_".$y."_".$w."_".$h."-".$newfile);
        }
    }
}
?>