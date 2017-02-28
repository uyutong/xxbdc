<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('admin/header');?>

<link href="<?php echo base_url();?>admin/js/jquery.fs.dropper.css" rel="stylesheet" type="text/css" media="all">
<script src="<?php echo base_url();?>admin/js/jquery.fs.dropper.js"></script>

<script type="text/javascript">
    $(function ()
    {
        $("#dropped_mp3_0").dropper({
            action: "/ajax/upload.php",
            postData: { "path": "/upload/word/mp3/" },
            maxQueue: 1
        })

        $("#dropped_mp3_1").dropper({
            action: "/ajax/upload.php",
            postData: { "path": "/upload/word/mp3/" },
            maxQueue: 1
        })

        $("#dropped_mp3_2").dropper({
            action: "/ajax/upload.php",
            postData: { "path": "/upload/word/google/", "retain": true },
            maxQueue: 1
        })

        $("#dropped_mp4").dropper({
            action: "/ajax/upload.php",
            postData: { "path": "/upload/word/mp4/" },
            maxQueue: 1
        })

        $("#dropped_photo").dropper({
            action: "/ajax/upload.php",
            postData: { "path": "/upload/word/img/" },
            maxQueue: 1
        })

    });

    function checkWord(w)
    {
        w = trim(w);

        if (w)
        {
            $.get("<?php echo site_url('admin/tools/check');?>?w=" + w + "&id=<?php echo $word['id']; ?>" + "&a=" + (new Date()).getTime(), function (data) {

                if(data==1)
                {
                    alert(w + " - 已经添加过了，无须重复添加！");
                    return;
                }
            });
        }
    }

    function trim(str) {
        return str.replace(/(^\s*)|(\s*$)/g, "");
    }
</script> 

<div class="contents">
    <h1>单词管理</h1>
    <div class="section">
        <div class="error2"><?php echo $info; ?></div>

        <?php echo form_open('admin/word/save')?>
        <?php echo form_hidden('id',set_value('id',$word['id'])); ?>
        <ul class="create-article">

            <li>
                <label>单词:</label>
                <?php echo form_input('en',set_value('en',$word['en'],false),"onblur='checkWord(this.value)'");?>
            </li>

            <li>
                <label>中文:</label>
                <?php echo form_input('zh',set_value('zh',$word['zh'],false));?>
            </li>

            <li>
                <label>音标:</label>
                <?php echo form_input('phonetic',set_value('phonetic',$word['phonetic'],false));?>
            </li>

            <li>
                <label>发音-女:</label>
                <div class="dropped" id="dropped_mp3_0"></div>
                <?php echo form_hidden('audio_0',set_value('audio_0',$word['audio_0'],false))?>
            </li>

            <li>
                <label>发音-男:</label>
                <div class="dropped" id="dropped_mp3_1"></div>
                <?php echo form_hidden('audio_1',set_value('audio_1',$word['audio_1'],false))?>
            </li>

            <li>
                <label>发音-google:</label>
                <div class="dropped" id="dropped_mp3_2"></div>
                <?php echo form_hidden('audio_2',set_value('audio_2',$word['audio_2'],false))?>
            </li>

            <li>
                <label>视频:</label>
                <div class="dropped" id="dropped_mp4"></div>
                <?php echo form_hidden('video',set_value('video',$word['video'],false))?>
            </li>

            <li>
                <label>图片</label>
                <div class="dropped" id="dropped_photo"></div>
                <?php echo form_hidden('photo',set_value('photo',$word['photo'],false))?>
            </li>
           

            <li>
                <input type="submit" name="submit" value="保存">
                &nbsp;
                <a href="<?php echo site_url('admin/word/index'); ?>">取消</a>
            </li>
        </ul>
        <?php echo form_close();?>

    </div>
</div>

<?php $this->load->view('admin/footer');?>