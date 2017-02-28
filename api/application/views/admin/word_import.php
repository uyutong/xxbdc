<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('admin/header');?>

<link href="<?php echo base_url();?>admin/js/jquery.fs.dropper.css" rel="stylesheet" type="text/css" media="all" />
<script src="<?php echo base_url();?>admin/js/jquery.fs.dropper.js"></script>

<?php
$t=$_GET['t'];
$book_id=@$_GET['book_id'];
?>

<script type="text/javascript">
    ///index.php/admin/word/import?t=photo

    var dropper = {};
    $(function ()
    {
        dropper= $("#dropped_import").dropper({
            action: "/ajax/import.php",
           <?php if($t=="photo"): ?>postData: { "path": "/upload/word/img/", "normal": true }, //单词图片<?php endif; ?>
           <?php if($t=="audio_0"|| $t=="audio_1"): ?>postData: { "path": "/upload/word/mp3/", "normal": true }, //单词音频-女，男<?php endif; ?>
           <?php if($t=="video"): ?>postData: { "path": "/upload/word/mp4/", "normal": true }, //单词视频<?php endif; ?>
           <?php if($t=="exercise_audio"): ?>postData: { "path": "/upload/exercise/mp3/", "normal": true }, //单词视频<?php endif; ?>

            label: "+",
            maxQueue: 10000
        }).on("fileComplete.dropper", function (e, file, response)
        {
            var media = response.split('^')[0];
            var en = response.split('^')[1];

            <?php if($t=="photo"): ?>$.get("<?php echo site_url('admin/word/import_db/');?>/" + en + '/' + media + '/photo?book_id=<?php echo $book_id; ?>', function (data) { //单词图片<?php endif; ?>
            <?php if($t=="audio_0"): ?>$.get("<?php echo site_url('admin/word/import_db/');?>/" + en + '/' + media + '/audio_0', function (data) { //单词音频-女<?php endif; ?>
            <?php if($t=="audio_1"): ?>$.get("<?php echo site_url('admin/word/import_db/');?>/" + en + '/' + media + '/audio_1', function (data) { //单词音频-男<?php endif; ?>
            <?php if($t=="video"): ?>$.get("<?php echo site_url('admin/word/import_db/');?>/" + en + '/' + media + '/video?book_id=<?php echo $book_id; ?>', function (data) { //单词视频<?php endif; ?>
            <?php if($t=="exercise_audio"): ?>$.get("<?php echo site_url('admin/exercise/import_db/');?>/" + en + '/' + media + '/media', function (data) { //单词视频<?php endif; ?>

                if (data == 1) {
                    $("#box").append("<p>" + response + "</p>");
                }
            });

        });
    });


</script>



<div class="contents">
    <h1>批量导入 <?php echo $t; ?></h1>
    <div class="section">

        <ul class="create-article">

            <li>
                <div class="dropped" id="dropped_import"></div>
            </li>
            <li>
                <div id="box"></div>
            </li>
        </ul>

    </div>
</div>


<?php $this->load->view('admin/footer');?>