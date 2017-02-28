<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('admin/header');?>
<div class="contents">
    <h1>
        练习 - <?php echo $word['en']; ?>
        <a class="tips" data-content="添加练习" href="<?php echo site_url('admin/exercise/create/'.$word['id']); ?>"><i class="iconfont">&#xf217;</i></a>
    </h1>
    <div class="article">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>单词</th>
                    <th>题型</th>
                    <th>题干</th>
                    <th>选项</th>
                    <th>音频/图片</th>
                    <th>答案</th>
                    <th>操作</th>
                </tr>
            </thead>

            <tbody class="DragSort" data-table="word_exercise">
                <?php foreach ($exercise as $item): ?>
                <tr data-id="<?php echo $item['id']; ?>">
                    <td class="set"><?php echo $item['id']; ?> </td>
                    <td class="set"><?php echo $word['en'];?></td>
                    <td class="set">
                        <?php
                          $arr=array("请选择","看图选择题","选图填空","句子排序","听力单项选择","听力填空匹配","图片匹配题");
                          echo $arr[$item['type']];
                        ?>
                    </td>
                    <td class="set">
                        <?php echo $item['question'];?>
                    </td>
                    <td class="set">
                        <?php echo str_replace("\n","<br>",$item['items']);?>
                    </td>
                    <td class="set">
                        <a class="mark-link" href="/upload/exercise/mp3/<?php echo $item['media']; ?>" target="_blank">
                            <?php echo $item['media']; ?>
                        </a>
                    </td>
                    <td class="set">
                        <?php echo $item['answer'];?>
                    </td>
                    <td class="set">
                        <a class="tips" data-content="编辑" href="<?php echo site_url('admin/exercise/create/'.$word['id'].'/'.$item['id'])?>"><i class="iconfont edit">&#xf2bf;</i>&nbsp;</a>|
                        <a class="tips" data-content="删除" href="javascript:void(0);" onclick="deleteExercise(<?php echo $item['id']; ?>)"><i class="iconfont">&#xf4c4;</i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p>* 拖拽可排序</p>
    </div>
</div>
<script type="text/javascript">
    function deleteExercise(id)
    {
        if (confirm('确定删除吗？'))
        {
            $.get("/index.php/admin/exercise/delete/" + id, function ()
            {
                location.href = location.href;
            });
        }
    }
</script>
<?php $this->load->view('admin/footer');?>