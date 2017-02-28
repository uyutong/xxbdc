<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>后台管理</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>admin/css/style.css" />

</head>
<body>

    <div class="login">
        <div class="error"><?php echo $info; ?></div>
        <?php echo form_open('admin/login/check');?>
        <ul>
            <li>
                <?php echo form_label('用户名:');?>
                <?php echo form_input('username',set_value('username'));?>
            </li>
            <li>
                <?php echo form_label('密 码:');?>
                <?php echo form_password('password',set_value('password'));?>
            </li>
            <li>
                <?php echo form_submit('submit','登陆');?>
            </li>
        </ul>
        <?php echo form_close();?>
    </div>

</body>
</html>
