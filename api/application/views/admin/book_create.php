<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('admin/header');?>



<link href="<?php echo base_url();?>admin/js/jquery.fs.dropper.css" rel="stylesheet" type="text/css" media="all">
<script src="<?php echo base_url();?>admin/js/jquery.fs.dropper.js"></script>


<script type="text/javascript">
    $(function ()
    {
        $("#dropped_photo").dropper({
            action: "/ajax/upload.php",
            postData: { "path": "/upload/book/" },
            maxQueue: 1
        })
        
    });
</script> 
 

<div class="contents">
    <h1>书本管理</h1>
    <div class="section">
        <div class="error2"><?php echo $info; ?></div>

        <?php echo form_open('admin/book/save')?>
        <?php echo form_hidden('id',set_value('id',$book['id'])); ?>
        <ul class="create-article">
            <li>
                <label>年级:</label>
                <select name="grade">
                    <option value="一年级" <?php echo set_select("grade","一年级","一年级"==$book['grade']) ;?>>一年级</option>
                    <option value="二年级" <?php echo set_select("grade","二年级","二年级"==$book['grade']) ;?>>二年级</option>
                    <option value="三年级" <?php echo set_select("grade","三年级","三年级"==$book['grade']) ;?>>三年级</option>
                    <option value="四年级" <?php echo set_select("grade","四年级","四年级"==$book['grade']) ;?>>四年级</option>
                    <option value="五年级" <?php echo set_select("grade","五年级","五年级"==$book['grade']) ;?>>五年级</option>
                    <option value="六年级" <?php echo set_select("grade","六年级","六年级"==$book['grade']) ;?>>六年级</option>
                </select>
            </li>

            <li>
                <label>学期:</label>
                <select name="semester">
                    <option value="上册" <?php echo set_select("semester","上册","上册"==$book['semester']) ;?>>上册</option>
                    <option value="下册" <?php echo set_select("semester","下册","下册"==$book['semester']) ;?>>下册</option>
                </select>
            </li>

            <li>
                <label>出版商:</label>
                <select name="publisher">
                    <option value="人教版（一年级起点）" <?php echo set_select("publisher","人教版（一年级起点）","人教版（一年级起点）"==$book['publisher']) ;?>>人教版（一年级起点）</option>
                    <option value="人教版（三年级起点）" <?php echo set_select("publisher","人教版（三年级起点）","人教版（三年级起点）"==$book['publisher']) ;?>>人教版（三年级起点）</option>
                    <!--<option value="北师大版" <?php echo set_select("publisher","北师大版","北师大版"==$book['publisher']) ;?>>北师大版</option>
                    <option value="沪教版" <?php echo set_select("publisher","沪教版","沪教版"==$book['publisher']) ;?>>沪教版</option>
                    <option value="鲁教版" <?php echo set_select("publisher","鲁教版","鲁教版"==$book['publisher']) ;?>>鲁教版</option>
                    <option value="冀教版" <?php echo set_select("publisher","冀教版","冀教版"==$book['publisher']) ;?>>冀教版</option>
                    <option value="浙教版" <?php echo set_select("publisher","浙教版","浙教版"==$book['publisher']) ;?>>浙教版</option>-->
                </select>
            </li>

            <li>
                <label>封面<br />(342X460)</label>
                <div class="dropped" id="dropped_photo"></div>
                <?php echo form_hidden('photo',set_value('photo',$book['photo'],false))?>
            </li>
            
            

            <li>
                <label>书名:</label>
                <?php echo form_input('name',set_value('name',$book['name'],false));?>
            </li>


            <li>
                <input type="submit" name="submit" value="保存">
                &nbsp;
                <a href="<?php echo site_url('admin/book'); ?>">取消</a>
            </li>
        </ul>
        <?php echo form_close();?>
        <p>*为了保证前台显示效果，上传的图片长宽，请尽量按照说明中的大小</p>
    </div>
</div>

<?php $this->load->view('admin/footer');?>