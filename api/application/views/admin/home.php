<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('admin/header');?>

<div class="contents">
    <h1>
        欢迎来到后台首页！
        
    </h1>

    <dl>
        <dt>环境配置信息</dt>
        <dd>
            PHP版本：<?php echo PHP_VERSION;?>
        </dd>
        <dd>
            Mysql类型：<?php echo $this->db->platform();?> / <?php echo $this->db->version();?>
        </dd>
        <dd>
            服务器端信息：<?PHP echo $_SERVER['SERVER_SOFTWARE']; ?>
        </dd>
        <dd>
            服务器操作系统： <?PHP echo PHP_OS; ?>
        </dd>
        <dd>
            上传限制：<?php $max_upload = ini_get("file_uploads") ? ini_get("upload_max_filesize") : "Disabled"; echo $max_upload;?>
        </dd>
        <dd>
            服务器时间：<?php echo date("Y-m-d H:i:s",time());?>
        </dd>
    </dl>

    <div class="calendar">
        <?php echo $this->calendar->generate();?>
    </div>

    <div style="clear:both;"></div>

</div>


<?php $this->load->view('admin/footer');?>