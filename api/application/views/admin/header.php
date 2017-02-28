<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<?php
if(! get_cookie('ADMINID'))
{
    redirect("admin/login");
}   
 ?>
<head>
<meta charset="utf-8">
<title>后台管理</title>
<base href="<?php echo base_url().'admin/'; ?>;" />
<link rel="stylesheet" type="text/css" href="css/ionicons.min.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<script type="text/javascript" src="js/jquery.bundle.js"></script>
<link rel="stylesheet" href="css/jquery.webui-popover.css">
<script type="text/javascript" src="js/jquery.webui-popover.min.js"></script>	
<script language="javascript" type="text/javascript">
$(function (){
$('.tags').webuiPopover({trigger:'hover', width:'30%',placement:'right'});
$('.cate').webuiPopover({trigger:'hover', width:'auto',placement:'right'});
$('.tips').webuiPopover({ trigger: 'hover', width: 'auto', placement: 'top' });
$('.tip-photo').webuiPopover({ trigger: 'hover', width: 'auto', placement: 'left' });

$(".DragSort").sortable({
    helper: function(e, ui) {
        ui.children().each(function() {
            $(this).width($(this).width());
            $(this).height($(this).height());
        });
        return ui;
    },
    placeholder: 'sortable-placeholder',
    start: function(event, ui) {
        ui.placeholder.html(ui.item.html());
    },
    stop: function (event, ui) {
        saveOrder();
    }
});

});

function saveOrder() {
    var table = $(".DragSort").data("table");
    var data = $(".DragSort tr").map(function () { return $(this).data('id'); }).get();


    $.get("<?php echo site_url('admin/tools/sort');?>?t=" + table + "&i=" + data.join(',') + "&a=" + (new Date()).getTime(), function () {
        //location.href = location.href;
    });
};
</script>
</head>
<body>
<div class="topbar">
<a class="logo" href="<?php echo site_url('admin/home');?>"><i class="iconfont">&#xf38f;</i></a>
<a class="name">管理控制台</a>
    <?php echo form_open('admin/word/index','style="float:left;"')?>
    <input type="text" name="title" style="margin-top:10px; margin-left:10px; box-shadow:none;" placeholder="搜索单词" />
    <?php echo form_close();?>
<a class="login-out" href="<?php echo site_url('admin/login/logout');?>"><i class="iconfont">&#xf2a9;</i></a>
<a class="user">Hi, <?php echo get_cookie("ADMINNAME");?></a>
</div>
<div class="view-body">
<div class="sidebar">
<div class="menus"><i class="iconfont">&#xf35c;</i></div>
<ul>
<li class="cate" data-content="书本管理"><a <?php if($this->uri->segment(2)=='book'){echo "class='on'";}?>  href="<?php echo site_url('admin/book/index');?>"><i class="iconfont">&#xf3e7;</i></a></li>
<li class="cate" data-content="单词管理"><a <?php if($this->uri->segment(2)=='word' && $this->uri->segment(3)<>'list2'){echo "class='on'";}?> href="<?php echo site_url('admin/word/index');?>"><i class="iconfont">&#xf453;</i></a></li>
<li class="cate" data-content="单词列表"><a <?php if($this->uri->segment(3)=='list2'){echo "class='on'";}?> href="<?php echo site_url('admin/word/list2');?>"><i class="iconfont">&#xf13f;</i></a></li>
<li class="cate" data-content="修改密码"><a <?php if($this->uri->segment(2)=='password'){echo "class='on'";}?> href="<?php echo site_url('admin/password');?>"><i class="iconfont">&#xf457;</i></a></li>
</ul>
</div>