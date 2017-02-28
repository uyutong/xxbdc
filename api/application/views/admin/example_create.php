<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('admin/header');?>



<link href="<?php echo base_url();?>admin/js/jquery.fs.dropper.css" rel="stylesheet" type="text/css" media="all">
<script src="<?php echo base_url();?>admin/js/jquery.fs.dropper.js"></script>


<script type="text/javascript">
    $(function ()
    {
        $("#dropped_mp3").dropper({
            action: "/ajax/upload.php",
            postData: { "path": "/upload/word/example/" },
            maxQueue: 1
        })
        
    });
</script> 
 

<div class="contents">
    <h1>
        <?php if($this->uri->segment(4)=='1'){echo "例句";}else{echo "搭配";}?> - <?php echo $word['en']; ?>
    </h1>
    <div class="section">
        <div class="error2"><?php echo $info; ?></div>

        <?php echo form_open('admin/example/save/'.$this->uri->segment(4).'/'.$word['id'])?>
        <?php echo form_hidden('id',set_value('id',$example['id'])); ?>
        <ul class="create-article">

            <li>
                <label>英文:</label>
                <?php echo form_input('en',set_value('en',$example['en'],false));?>
            </li>

            <li>
                <label>中文:</label>
                <?php echo form_input('zh',set_value('zh',$example['zh'],false));?>
            </li>

            <li>
                <label>音频:</label>
                <div class="dropped" id="dropped_mp3"></div>
                <?php echo form_hidden('audio',set_value('audio',$example['audio'],false))?>
            </li>


            <li>
                <input type="submit" name="submit" value="保存">
                &nbsp;
                <a href="<?php echo site_url('admin/example/index/'.$this->uri->segment(4).'/'.$word['id']); ?>">取消</a>
            </li>
        </ul>
        <?php echo form_close();?>

    </div>
</div>

<?php $this->load->view('admin/footer');?>