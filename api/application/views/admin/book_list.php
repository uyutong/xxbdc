<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('admin/header');?>

<div class="contents">
    <h1>
        书本管理 - (共<?php echo count($books);?>本) <a class="tips" data-content="添加书本" href="<?php echo site_url('admin/book/create')?>"> <i class="iconfont">&#xf217;</i></a>
    </h1>
    <div class="article">
        <?php echo form_open('article/batch_operation ');?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th style="text-align:left">课本名称</th>
                    <th>年级</th>
                    <th>学期</th>
                    <th>出版社</th>
                    <th>封面</th>
                    <th>单元</th>
                    <th>单词</th>
                    <th>资源包</th>
                    <th>版本</th>
                    <th>操作</th>
                </tr>
            </thead>

            <tbody class="DragSort" data-table="book">
                <?php foreach ($books as $item): ?>
                <tr data-id="<?php echo $item['id']; ?>">
                    <td class="set"><?php echo $item['id']; ?> </td>
                    <td>
                        <?php echo $item['name']; ?>
                    </td>
                    <td class="set"><?php echo $item['grade'];?></td>
                    <td class="set"><?php echo $item['semester'];?></td>
                    <td class="set"><?php echo $item['publisher'];?></td>
                    <td class="set">
                        <a class="mark-link"  href="<?php echo base_url(); ?>upload/book/<?php echo $item['photo']; ?>" target="_blank">
                            <img src="<?php echo base_url(); ?>upload/book/<?php echo $item['photo']; ?>" height="48" />
                        </a>
                    </td>
                    <td class="set">
                        <?php echo "<a class='mark-link' title='单元列表' href='". site_url('admin/unit/index/'.$item['id'])."'>".$item["unit_total"]."</a>";?>
                    </td>
                    <td class="set">                        
                        <?php echo "<a class='mark-link' title='单元列表' href='". site_url('admin/unit/index/'.$item['id'])."'>".$item["word_total"]."</a>";?>
                    </td>
                    <td class="set">
                        <?php echo  "<a class='mark-link' title='下载资源包' href='". site_url('admin/unit/resource/'.$item['id'])."'>book_".$item["id"].".zip</a>";?>
                    </td>
                    <td class="set">
                        <?php echo  "<a class='mark-link' title='版本维护' href='". site_url('admin/version/index/'.$item['id'])."'>版本维护</a>";?>
                    </td>
                    <td class="set">
                        <a class="tips" data-content="编辑" href="<?php echo site_url('admin/book/create/'.$item['id'])?>"><i class="iconfont edit">&#xf2bf;</i>&nbsp;</a>|
                        <a class="tips" data-content="删除" href="<?php echo site_url('admin/book/delete/'.$item['id'])?>" onclick="return confirm('确定删除吗？')"><i class="iconfont">&#xf4c4;</i></a>
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