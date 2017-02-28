<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('admin/header');?>
 
<link rel="stylesheet" href="css/jquery-ui.min.css" type="text/css" />

<style type="text/css">
    .ui-datepicker .ui-datepicker-prev span, .ui-datepicker .ui-datepicker-next span { 
        text-indent:initial;
    }
</style>

<div class="contents">
    <h1>
        <?php echo $book['grade'].$book['semester'].$book['name'].$book['publisher']; ?> app 版本维护
    </h1>
    <div class="section">
        <div class="error2"><?php echo $info; ?></div>

        <?php echo form_open('admin/version/save/'.$book['id'])?>
        <?php echo form_hidden('id',set_value('id',$version['id'])); ?>
        <ul class="create-article">

            <li>
                <label>发布状态:</label>
                <select name="status">
                    <option value="1" <?php echo set_select("status","1","1"==$version['status']) ;?>>正式发布</option>
                    <option value="0" <?php echo set_select("status","0","0"==$version['status']) ;?>>不发布</option>
                </select>
            </li>

            <li>
                <label>平台:</label>
                <select name="platform">
                    <option value="ios" <?php echo set_select("platform","ios","ios"==$version['platform']) ;?>>ios</option>
                    <option value="android" <?php echo set_select("platform","android","android"==$version['platform']) ;?>>android</option>
                </select>
            </li>

            <li>
                <label>发布日期:</label>
                <?php echo form_input('release_time',set_value('release_time',$version['release_time'],false),"id='txtTime'");?>
            </li>

            <li>
                <label>版本编号:</label>
                <?php echo form_input('version',set_value('version',$version['version'],false));?>
            </li>


            <li>
                <label>更新地址:</label>
                <?php echo form_input('url',set_value('url',$version['url'],false));?>
            </li>

            <li>
                <label>文件大小:</label>
                <?php echo form_input('size',set_value('size',$version['size'],false));?>
            </li>

            <li>
                <label>更新说明:</label>
                <?php echo form_textarea('info',set_value('info',$version['info'],false));?>
            </li>

            


            <li>
                <input type="submit" name="submit" value="保存">
                &nbsp;
                <a href="<?php echo site_url('admin/version/index/'.$book['id']); ?>">取消</a>
            </li>
        </ul>
        <?php echo form_close();?>

    </div>
</div>

<script type="text/javascript">
    $("#txtTime").datepicker({"dateFormat":"yy-mm-dd"}).attr("readonly","readonly");
</script>

<?php $this->load->view('admin/footer');?>