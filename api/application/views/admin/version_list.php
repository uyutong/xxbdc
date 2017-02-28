<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('admin/header');?>

<div class="contents">
    <h1>
        <?php echo $book['grade'].$book['semester'].$book['name'].$book['publisher']; ?> app 版本维护<a class="tips" data-content="添加" href="<?php echo site_url('admin/version/create/').$this->uri->segment(4); ?>"> <i class="iconfont">&#xf217;</i></a>
    </h1>
    <div class="article">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>版本编号</th>
                    <th>平台</th>
                    <th>更新地址</th>
                    <th>更新说明</th>
                    <th>文件大小</th>
                    <th>发布日期</th>
                    <th>发布状态</th>
                    <th>操作</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($versions as $item): ?>
                <tr data-id="<?php echo $item['id']; ?>">
                    <td class="set"><?php echo $item['id']; ?> </td>
                    <td class="set">
                        <?php echo $item['version']; ?>
                    </td>
                    <td class="set">
                        <?php echo $item['platform']; ?>
                    </td>
                    <td class="set">
                        <?php echo $item['url']; ?>
                    </td>
                    <td class="set">
                        <?php echo str_replace("\r\n","<br>", $item['info']); ?>
                    </td>
                    <td class="set">
                        <?php echo $item['size'].""; ?>
                    </td>
                    <td class="set">
                        <?php echo $item['release_time']; ?>
                    </td>
                    <td class="set">
                        <?php echo $item['status']; ?>
                    </td>
                    <td class="set">
                        <a class="tips" data-content="编辑" href="<?php echo site_url('admin/version/create/'.$item['book_id'].'/'.$item['id'])?>"><i class="iconfont edit">&#xf2bf;</i>&nbsp;</a>|
                        <a class="tips" data-content="删除" href="<?php echo site_url('admin/version/delete/'.$item['book_id'].'/'.$item['id'])?>" onclick="return confirm('确定删除吗？')"><i class="iconfont">&#xf4c4;</i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

            
        </table>
    </div>
</div>

<?php $this->load->view('admin/footer');?>