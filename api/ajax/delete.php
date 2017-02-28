<?php

$full=$_GET["f"];

$p=substr($full,0,strrpos($full,"/"));

$f=substr($full,strrpos($full,"/")+1);

$files=glob($_SERVER["DOCUMENT_ROOT"].$p."/".$f);

foreach($files as $file)
{
    @unlink($file);
}
?>