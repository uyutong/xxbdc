<?php
$book_id=$_GET['book_id'];
$unit_id=$_GET['unit_id'];
$exercise=@$_GET['exercise'];
$word=@$_GET['word'];

//http://dachutimes.com.cn/handbook.php?book_id=2&unit_id=38&word=mark

if(isset($exercise))
{
    //echo "book_id: ".$book_id;
    //echo "<br>";
    //echo "unit_id: ".$unit_id;
    //echo "<br>";
    //echo "exercise: ".$exercise;
    //echo "<br>";
    //echo "<h2>练习：".$exercise."</h2>";

    //echo '<script type="text/javascript">location.href = "/www/#/word_exercise/'.$book_id.'/'.$unit_id.'/'.$exercise.'";</script>';

    echo '<script type="text/javascript">location.href = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxa15d78d04e5070ff&redirect_uri=http://xx.kaouyu.com/wx_redirect.php&response_type=code&scope=snsapi_base&state='.$book_id.'^'.$unit_id.'^exercise^'.$exercise.'#wechat_redirect";</script>';
    
}

if(isset($word))
{
    //echo "book_id: ".$book_id;
    //echo "<br>";
    //echo "unit_id: ".$unit_id;
    //echo "<br>";
    //echo "word: ".$word;
    //echo "<br>";
    //echo "<h2>单词：".$word."</h2>";

    //echo '<script type="text/javascript">location.href = "/www/#/word_detail/'.$book_id.'/'.$unit_id.'/'.$word.'";</script>';

    echo '<script type="text/javascript">location.href = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxa15d78d04e5070ff&redirect_uri=http://xx.kaouyu.com/wx_redirect.php&response_type=code&scope=snsapi_base&state='.$book_id.'^'.$unit_id.'^word^'.$word.'#wechat_redirect";</script>';
}
?>