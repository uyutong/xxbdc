<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('admin/header');?>



<link href="<?php echo base_url();?>admin/js/jquery.fs.dropper.css" rel="stylesheet" type="text/css" media="all">
<script src="<?php echo base_url();?>admin/js/jquery.fs.dropper.js"></script>


<script type="text/javascript">
    $(function ()
    {
        $("#dropped_photo").dropper({
            action: "/ajax/upload.php",
            postData: { "path": "/upload/unit/" },
            maxQueue: 1
        })
        
    });
</script> 
 

<div class="contents">
    <h1>单元管理 - <?php echo $book['name'].' / '.$book['grade'].' / '.$book['semester'].' / '.$book['publisher']; ?></h1>
    <div class="section">
        <div class="error2"><?php echo $info; ?></div>

        <?php echo form_open('admin/unit/save/'.$book['id'])?>
        <?php echo form_hidden('id',set_value('id',$unit['id'])); ?>
        <ul class="create-article">

            <li>
                <label>单元图片</label>
                <div class="dropped" id="dropped_photo"></div>
                <?php echo form_hidden('photo',set_value('photo',$unit['photo'],false))?>
            </li>
            
            

            <li>
                <label>英文名称:</label>
                <?php echo form_input('name',set_value('name',$unit['name'],false));?>
            </li>

            <li>
                <label>中文名称:</label>
                <?php echo form_input('name_zh',set_value('name_zh',$unit['name_zh'],false));?>
            </li>

            <li>
                <label>单词颜色:</label>
                <?php echo form_input('word_color',set_value('word_color',$unit['word_color'],false));?>(例如：000000)
            </li>

            <li>
                <label>背景颜色:</label>
                <?php echo form_input('bg_color',set_value('bg_color',$unit['bg_color'],false));?>(例如：000000)
            </li>


            <li>
                <input type="submit" name="submit" value="保存">
                &nbsp;
                <a href="<?php echo site_url('admin/unit/index/'.$book['id']); ?>">取消</a>
            </li>
        </ul>
        <?php echo form_close();?>

    </div>
</div>

<?php $this->load->view('admin/footer');?>