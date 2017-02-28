<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('admin/header');?>

<script type="text/javascript">
    function addWord()
    {
        if($("#txtWordId").val()=="")
        {
            alert("请选择单词！（是选择，不是输入）")
        }
        else
        {
            $.get("<?php echo site_url('admin/word_in_unit/create/'.$book['id'].'/'.$unit['id']);?>/"+$("#txtWordId").val(), function (data) {

                if(data==1)
            {
                location.href=location.href;
                }
            });
        }

        return false;
    }


    $(function () {


        $("#txtWord").autocomplete({
            source: "<?php echo site_url('admin/tools/search');?>",
            minLength:1,
            select: function( event, ui ) {
                $("#txtWord").val(ui.item.value);
                $("#txtWordId").val(ui.item.id);
            }
        }).focus().autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li>")
              .append("<div><b>" + item.value  + "</b><br><i>"+item.label+"</i><br><i>" + item.desc + "</i></div>")
              .appendTo(ul);
        };;
    });
</script>

<div class="contents">
    <h1>
        单词管理 - (共<?php echo count($words);?>个) / <?php echo $book['name'].' / '.$book['grade'].' / '.$book['semester'].' / '.$book['publisher'].' / '.$unit['name']; ?> -
        <a href="<?php echo site_url('admin/unit/index/'.$book['id'])?>">返回</a>
        <form onsubmit="return addWord();">
            <input type="text" name="txtWord" id="txtWord" autocomplete="off" />
            <input type="hidden" name="txtWordId" id="txtWordId" />
            <input type="submit"  value="添加" />
        </form>
    </h1>
    <div class="article">
        <?php echo form_open('article/batch_operation ');?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th style="text-align:left">单词</th>
                    <th>汉语</th>
                    <th>音标</th>
                    <th>音频-女</th>
                    <th>音频-男</th>
                    <th>音频-google</th>
                    <th>视频</th>
                    <th>图片</th>
                    <th>单词二维码</th>
                    <th>练习二维码</th>
                    <th>印刷图</th>
                    <th>例句</th>
                    <th>搭配</th>
                    <th>练习</th>
                    <th>操作</th>
                </tr>
            </thead>

            <tbody class="DragSort" data-table="word_in_unit">
                <?php foreach ($words as $item): ?>
                <tr data-id="<?php echo $item['id']; ?>">
                    <td class="set"><?php echo $item['id']; ?> </td>
                    <td>
                        <?php echo $item['en']; ?>
                    </td>
                    <td class="set"><?php echo $item['zh'];?></td>
                    <td class="set"><?php echo $item['phonetic'];?></td>
                    <td class="set">
                        <a class="mark-link" href="/upload/word/mp3/<?php echo $item['audio_0']; ?>" target="_blank">
                            <?php echo $item['audio_0']; ?>
                        </a>
                    </td>
                    <td class="set">
                        <a class="mark-link" href="/upload/word/mp3/<?php echo $item['audio_1']; ?>" target="_blank">
                            <?php echo $item['audio_1']; ?>
                        </a>
                    </td>
                    <td class="set">
                        <a class="mark-link" href="/upload/word/google/<?php echo $item['audio_2']; ?>" target="_blank">
                            <?php echo $item['audio_2']; ?>
                        </a>
                    </td>
                    <td class="set">
                        <a class="mark-link" href="/upload/word/mp4/<?php echo $item['video']; ?>" target="_blank">
                            <?php echo $item['video']; ?>
                        </a>
                    </td>
                    <td class="set">
                        <a href="<?php echo base_url(); ?>upload/word/img/<?php echo $item['photo']; ?>" target="_blank">
                            <img src="<?php echo base_url(); ?>upload/word/img/<?php echo $item['photo']; ?>" height="48" />
                        </a>
                    </td>
                    <td class="set">
                        <a href="/phpqrcode.php?book_id=<?php echo $item['book_id']; ?>&unit_id=<?php echo $item['unit_id']; ?>&word=<?php echo $item['en']; ?>&t=word" target="_blank">
                            <img src="/phpqrcode.php?book_id=<?php echo $item['book_id']; ?>&unit_id=<?php echo $item['unit_id']; ?>&word=<?php echo $item['en']; ?>&t=word" height="48" />
                        </a>
                    </td>
                    <td class="set">
                        <a href="/phpqrcode.php?book_id=<?php echo $item['book_id']; ?>&unit_id=<?php echo $item['unit_id']; ?>&word=<?php echo $item['en']; ?>&t=exercise" target="_blank">
                            <img src="/phpqrcode.php?book_id=<?php echo $item['book_id']; ?>&unit_id=<?php echo $item['unit_id']; ?>&word=<?php echo $item['en']; ?>&t=exercise" height="48" />
                        </a>
                    </td>
                    <td class="set">
                        <a class="mark-link" href="/index.php/tools/createwordimg?book_id=<?php echo $item['book_id']; ?>&unit_id=<?php echo $item['unit_id']; ?>&word_id=<?php echo $item['word_id']; ?>" target="_blank">
                            单词
                        </a>
                        |
                        <a class="mark-link" href="/index.php/tools/createexerciseimg?book_id=<?php echo $item['book_id']; ?>&unit_id=<?php echo $item['unit_id']; ?>&word_id=<?php echo $item['word_id']; ?>" target="_blank">
                            练习
                        </a>
                    </td>
                    <td class="set">
                        <a class="mark-link" title='例句' href="<?php echo site_url('admin/example/index/1/'.$item['word_id']);?>">
                            <?php echo $item['example_total']; ?>
                        </a>
                    </td>
                    <td class="set">
                        <a class="mark-link" title='搭配' href="<?php echo site_url('admin/example/index/2/'.$item['word_id']);?>">
                            <?php echo $item['collocation_total']; ?>
                        </a>
                    </td>
                    <td class="set">
                        <a class="mark-link" title='练习' href="<?php echo site_url('admin/exercise/index/'.$item['word_id']);?>">
                            <?php echo $item['exercise_total']; ?>
                        </a>
                    </td>
                    <td class="set">
                        <a class="tips" data-content="编辑" href="<?php echo site_url('admin/word/create/'.$item['word_id'])?>"><i class="iconfont edit">&#xf2bf;</i>&nbsp;</a>|
                        <a class="tips" data-content="删除" href="<?php echo site_url('admin/word_in_unit/delete/'.$book['id'].'/'.$unit['id'].'/'.$item['id'])?>" onclick="return confirm('确定删除吗？')"><i class="iconfont">&#xf4c4;</i></a>
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