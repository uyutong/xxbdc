<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('admin/header');?>

<div class="contents">
    <h1>
        单元管理 - <?php echo $book['name'].' / '.$book['grade'].' / '.$book['semester'].' / '.$book['publisher']; ?> (共<?php echo count($units);?>单元) <a class="tips" data-content="添加单元" href="<?php echo site_url('admin/unit/create/'.$book['id'])?>"> <i class="iconfont">&#xf217;</i></a>
    </h1>
    <div class="article">
        <?php echo form_open('article/batch_operation ');?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th style="text-align:left">英文名称</th>
                    <th style="text-align:left">中文名称</th>
                    <th>单元图片</th>
                    <th>单词</th>
                    <th>二维码</th>
                    <th>印刷图</th>
                    <th>操作</th>
                </tr>
            </thead>

            <tbody class="DragSort" data-table="unit">
                <?php foreach ($units as $item): ?>
                <tr data-id="<?php echo $item['id']; ?>">
                    <td class="set"><?php echo $item['id']; ?> </td>
                    <td>
                        <?php echo $item['name']; ?>
                    </td>
                    <td>
                        <?php echo $item['name_zh']; ?>
                    </td>
                    <td class="set">
                        <a class="mark-link"  href="<?php echo base_url(); ?>upload/unit/<?php echo $item['photo']; ?>" target="_blank">
                            <img src="<?php echo base_url(); ?>upload/unit/<?php echo $item['photo']; ?>" height="48" />
                        </a>
                    </td>
                    <td class="set">
                        <?php echo  "<a class='mark-link' title='单词列表' href='". site_url('admin/word_in_unit/index/'.$book['id'].'/'.$item['id'])."'>".$item["word_total"]."</a>";?>
                    </td>
                    <td class="set">
                        <?php echo  "<a class='mark-link' title='二维码批量下载' href='". site_url('admin/word_in_unit/qrcode/'.$book['id'].'/'.$item['id'])."'>".$item['name'].".zip</a>";?>
                    </td>
                    <td class="set">
                        <?php echo  "<a class='mark-link' title='印刷图' href='". site_url('admin/word_in_unit/createimage/'.$book['id'].'/'.$item['id'])."'>".$item['name'].".zip</a>";?>
                    </td>
                    <td class="set">
                        <a class="tips" data-content="编辑" href="<?php echo site_url('admin/unit/create/'.$book['id'].'/'.$item['id'])?>"><i class="iconfont edit">&#xf2bf;</i>&nbsp;</a>|
                        <a class="tips" data-content="删除" href="<?php echo site_url('admin/unit/delete/'.$book['id'].'/'.$item['id'])?>" onclick="return confirm('确定删除吗？')"><i class="iconfont">&#xf4c4;</i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

            
        </table>
        <?php echo form_close();?>
        <p>* 拖拽可排序</p>
    </div>
</div>

<?php $this->load->view('admin/footer');?>