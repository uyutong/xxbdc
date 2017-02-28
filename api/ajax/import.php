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

    move_uploaded_file($_FILES["file"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] .$path . $newfile);

    echo $newfile."^".substr($oldname,0, strrpos( $oldname,"."));
}
?>