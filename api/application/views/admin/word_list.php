<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('admin/header');?>

<script type="text/javascript" src="<?php echo base_url();?>admin/fancybox/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>admin/fancybox/jquery.fancybox.css" media="screen" />

<script type="text/javascript">
    var refresh = 0;

    function addToUnit(word_id) {
        var word = $("#w" + word_id).text();

        $(".p-word").text(word);
        $("#txtWordId").val(word_id);

        changeBook();

        loadWords();

        $.fancybox.open('#inline1', {
            afterClose: function () {
                if (refresh)
                {
                    location.href = location.href;
                }
            }
        });
    }

    function saveToUnit() {
        var book_id = $("#ddlBook").val();
        var unit_id = $("#ddlUnit" + book_id).val();
        var word_id = $("#txtWordId").val();

        $.get("<?php echo site_url('admin/word_in_unit/create/');?>/" + book_id + '/' + unit_id + '/' + word_id, function (data) {
            if (data == 1) {
                refresh = 1;
                loadWords();
            }
        });
    }

    function changeBook() {
        $(".ddlUnit").hide();
        $("#ddlUnit" + $("#ddlBook").val()).show();
    }

    function loadWords()
    {
        $("#wordlist").html('');

        $.get("<?php echo site_url('admin/word_in_unit/list_json/');?>/" + $("#txtWordId").val(), function (data) {

            var json = JSON.parse(data);
            var html = "";

            for (i = 0; i < json.length; i++)
            {
                var item = json[i];

                html += '<tr id="tr' + item['id'] + '"><td align="left">' + $(".p-word").text() + '</td><td align="left">' + item['grade'] + item['semester'] + item['name'] + '</td><td align="left">' + item['publisher'] + '</td><td align="left">' + item['unit_name'] + '</td><td><a href="javascript:void(0);" onclick="deleteWord(' + item['id'] + ')"><i class="iconfont">&#xf4c4;</i></a></td></tr>';
            }

            $("#wordlist").html('<table class="table">' + html + '</table>');

            $.fancybox.update();
        });
    }

    function deleteWord(id)
    {
        if (confirm("确定删除吗？"))
        {
            refresh = 1;

            $.get("<?php echo site_url('admin/word_in_unit/delete/1/1/');?>" + id, function () {
                $("#tr" + id).remove();

                $.fancybox.update();
            });
        }
    }

    
</script>

<div id="inline1" style="display: none; width:600px; margin-top:22px; margin-bottom:22px; text-align:center;">
    <b class="p-word"></b>
    
    <input type="hidden" id="txtWordId" />

    <select id="ddlBook" style="width:auto; padding-left:0px;" onchange="changeBook();">
        <?php foreach ($books as $item): ?>
        <option value='<?php echo $item['id'] ;?>'><?php echo $item['grade']." ".$item['semester'] ." ".$item['name'] ." ".$item['publisher'] ?></option>
        <?php endforeach; ?>
    </select>

    <?php foreach ($books as $item): ?>
    <select class="ddlUnit" id="ddlUnit<?php echo $item['id'] ;?>" style="width:auto; padding-left:0px; display:none;">
        <?php foreach ($units as $unit): ?>
            <?php if($item['id']==$unit['book_id']):?>
                <option value='<?php echo $unit['id'];?>'><?php echo $unit['name']; ?></option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>
    <?php endforeach; ?>

    <input type="button" value="添加" onclick="saveToUnit();" style="margin-left:0px;" />

    <div id="wordlist">

    </div>
</div>

<div class="contents">
    <h1>
        单词管理 - (共<?php echo $words_count;?>个) <a class="tips" data-content="添加单词" href="<?php echo site_url('admin/word/create')?>"><i class="iconfont">&#xf217;</i></a>
        <?php echo form_open('admin/word/index')?>
        <?php echo form_input('title',set_value('title'),'id="txtTitle"')?>
        <?php echo form_submit('search','查询');?>
        <?php echo form_close();?>
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
                    <th>例句</th>
                    <th>搭配</th>
                    <th>练习</th>
                    <th>操作</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($words as $item): ?>
                <tr>
                    <td class="set"><?php echo $item['id']; ?> </td>
                    <td id="w<?php echo $item['id']; ?>">
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
                        <a class="mark-link" href="<?php echo base_url(); ?>upload/word/img/<?php echo $item['photo']; ?>" target="_blank">
                            <img src="<?php echo base_url(); ?>upload/word/img/<?php echo ($item['photo']!=""?$item['photo']:"no.png"); ?>" height="48" />
                        </a>
                    </td>
                    <td class="set">
                        <a class="mark-link" title='例句' href="<?php echo site_url('admin/example/index/1/'.$item['id']);?>">
                            <?php echo $item['example_total']; ?>
                        </a>
                    </td>
                    <td class="set">
                        <a class="mark-link" title='搭配' href="<?php echo site_url('admin/example/index/2/'.$item['id']);?>">
                            <?php echo $item['collocation_total']; ?>
                        </a>
                    </td>
                    <td class="set">
                        <a class="mark-link" title='练习' href="<?php echo site_url('admin/exercise/index/'.$item['id']);?>">
                            <?php echo $item['exercise_total']; ?>
                        </a>
                    </td>
                    <td class="set">
                        <a class="<?php echo $item['in_unit_total']>0? "mark-link":""; ?>" href="javascript:void(0);" onclick="addToUnit(<?php echo $item['id']; ?>)"><i class="iconfont edit">&#xf4ca;</i>(<?php echo $item['in_unit_total']; ?>)&nbsp;</a>|
                        <a class="tips" data-content="编辑" href="<?php echo site_url('admin/word/create/'.$item['id'])?>"><i class="iconfont edit">&#xf2bf;</i>&nbsp;</a>|
                        <a class="tips" data-content="删除" href="<?php echo site_url('admin/word/delete/'.$item['id'])?>" onclick="return confirm('确定删除吗？')"><i class="iconfont">&#xf4c4;</i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="6">
                        <?php echo $this->pagination->create_links();?>
                    </td>
                </tr>
            </tfoot>
        </table>
        <?php echo form_close();?>
    </div>
</div>

<script type="text/javascript">
    $("#txtTitle").val($("#txtTitle").val().replace("&#039;", "'"));
</script>

<?php $this->load->view('admin/footer');?>