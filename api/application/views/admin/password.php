<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('admin/header');?>

<div class="contents">
<h1>密码修改 </h1>
    <div class="error2"><?php echo $info; ?></div>
<?php echo form_open('admin/password/save');?>
<ul class="create-cate">
 <li><?php echo form_label('旧密码:');?>
 <?php echo form_input('old_password');?></li>
  <li><?php echo form_label('新密码:');?>
 <?php echo form_password('password1');?></li>
 <li> <?php echo form_label('重输新密码:');?>
 <?php echo form_password('password2');?></li>
 <li><?php echo form_submit('submit','修改密码');?></li>
 </ul>
 <?php echo form_close();?>
</div>
<?php $this->load->view('admin/footer');?>