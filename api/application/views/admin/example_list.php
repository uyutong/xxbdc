<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('admin/header');?>

<div class="contents">
    <h1>
        <?php if($this->uri->segment(4)=='1'){echo "例句";}else{echo "搭配";}?>  - <?php echo $word['en']; ?> <a class="tips" data-content="添加" href="<?php echo site_url('admin/example/create/').$this->uri->segment(4)."/".$this->uri->segment(5)?>"> <i class="iconfont">&#xf217;</i></a>
    </h1>
    <div class="article">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th style="text-align:left">英文</th>
                    <th style="text-align:left">中文</th>
                    <th>音频</th>
                    <th>操作</th>
                </tr>
            </thead>

            <tbody class="DragSort" data-table="word_example">
                <?php foreach ($examples as $item): ?>
                <tr data-id="<?php echo $item['id']; ?>">
                    <td class="set"><?php echo $item['id']; ?> </td>
                    <td>
                        <?php echo $item['en']; ?>
                    </td>
                    <td>
                        <?php echo $item['zh']; ?>
                    </td>
                    <td class="set">
                        <a class="mark-link" href="<?php echo base_url(); ?>upload/word/example/<?php echo $item['audio']; ?>" target="_blank">
                            <?php echo $item['audio']; ?>
                        </a>
                    </td>
                    <td class="set">
                        <a class="tips" data-content="编辑" href="<?php echo site_url('admin/example/create/'.$item['type'].'/'.$item['word_id'].'/'.$item['id'])?>"><i class="iconfont edit">&#xf2bf;</i>&nbsp;</a>|
                        <a class="tips" data-content="删除" href="<?php echo site_url('admin/example/delete/'.$item['type'].'/'.$item['word_id'].'/'.$item['id'])?>" onclick="return confirm('确定删除吗？')"><i class="iconfont">&#xf4c4;</i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

            
        </table>
        <p>* 拖拽可排序</p>
    </div>
</div>

<?php $this->load->view('admin/footer');?>