<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('admin/header');?>

<link href="<?php echo base_url();?>admin/js/jquery.fs.dropper.css" rel="stylesheet" type="text/css" media="all">
<script src="<?php echo base_url();?>admin/js/jquery.fs.dropper.js"></script>


<script type="text/javascript">
    $(function ()
    {

        $("#dropped_mp3_title").dropper({
            action: "/ajax/upload.php",
            postData: { "path": "/upload/exercise/mp3/" },
            maxQueue:1
        })

        $("#dropped_item").dropper({
            action: "/ajax/upload.php",
            postData: { "path": "/upload/exercise/img/" },
            label:"+"
        })

        $("#dropped_audio").dropper({
            action: "/ajax/upload.php",
            postData: { "path": "/upload/exercise/mp3/", "normal": true },
            label: "+",
            maxQueue: 1
        }).on("fileComplete.dropper", function (e, file, response)
        {
            insertText(document.getElementById("txtItems"), "^" + response);
            //alert(response);
        });
    });
</script> 



<div class="contents">
    <h1>习题维护 - <?php echo $word['en']; ?></h1>
    <div class="section">
        <div class="error2"><?php echo $info; ?></div>

        <?php echo form_open('admin/exercise/save/'.$exercise['word_id'])?>
        <?php echo form_hidden('id',set_value('id',$exercise['id'])); ?>
        <ul class="create-article">
            <li>
                <label>题型:</label>
                <select name="type" onchange="changeType()" id="type">
                    <option value="1" <?php echo set_select("type","1","1"==$exercise['type']) ;?>>看图选择题</option>
                    <option value="2" <?php echo set_select("type","2","2"==$exercise['type']) ;?>>选图填空</option>
                    <option value="3" <?php echo set_select("type","3","3"==$exercise['type']) ;?>>句子排序</option>
                    <option value="4" <?php echo set_select("type","4","4"==$exercise['type']) ;?>>听力单项选择</option>
                    <option value="5" <?php echo set_select("type","5","5"==$exercise['type']) ;?>>听力填空匹配</option>
                    <option value="6" <?php echo set_select("type","6","6"==$exercise['type']) ;?>>图片匹配题</option>
                </select>
            </li>
            <li class="iform" id="lQuestion">
                <?php echo form_label('题干:');?><?php echo form_input('question',set_value('question',$exercise['question'],false),"style='width:980px;'");?>

                <div id="pTitleAudio" style="margin:20px 0px 0px 80px;">
                    <div class="dropped" id="dropped_mp3_title"></div>
                    <?php echo form_hidden('media',set_value('media',$exercise['media'],false))?>
                </div>
            </li>

            <li class="iform" id="lTextarea" style="position:relative;">
                <div id="pic_box" style="position:absolute; top:0px; left:78px; padding:6px; z-index:1000; width :980px; height:120px">
                    <div class="dropped" id="dropped_item"></div>
                </div>
                <div id="audio_box" style="left: 2px; padding: 6px; position: absolute; top: 40px; z-index: 1000;">
                    <div class="dropped" id="dropped_audio"></div>
                </div>
                <?php echo form_label('选项:<br>一行一个');?><?php echo form_textarea('items',set_value('items',$exercise['items'],false),"style='width:980px; height:120px' id='txtItems'");?>
            </li>

            <li class="iform"><?php echo form_label('答案:');?><?php echo form_input('answer',set_value('answer',$exercise['answer'],false),"style='width:980px;' onblur='changeAnswer(this)'");?></li>

            <li>
                <input type="submit" name="submit" value="保存" >
                &nbsp;
                <a href="<?php echo site_url('admin/exercise/index/'.$exercise['word_id']); ?>">取消</a>
            </li>
        </ul>
        
        <hr />
         
        <div>
            <img src='/admin/img/1.png' id="example" />
        </div>

    </div>
</div>



<script type="text/javascript">
    function changeType()
    {
        if ($("#type").val() == "0")
        {
            $(".iform").hide();
        }
        else
        {
            $(".iform").show();
        }

        //看图选择题
        if ($("#type").val() == "1")
        {
            $("#lQuestion").show();
            $("#pTitleAudio").show();

            $("#lTextarea").show();
            $("#pic_box").hide();
            $("#pic_box").parent().find("textarea").css("visibility", "visible");

            $("#audio_box").hide();
        }

        //选图填空
        if ($("#type").val() == "2")
        {
            $("#lQuestion").show();

            $("#lTextarea").show();
            $("#pic_box").show();
            $("#pic_box").parent().find("textarea").css("visibility", "hidden");

            $("#audio_box").hide();
        }

        //句子排序
        if ($("#type").val() == "3")
        {
            $("#lQuestion").show();

            $("#lTextarea").hide();
            $("#pic_box").hide();
            $("#pic_box").parent().find("textarea").css("visibility", "visible");

            $("#audio_box").hide();
        }

        //听力单项选择
        if ($("#type").val() == "4")
        {
            $("#lQuestion").show();
            $("#pTitleAudio").show();

            $("#lTextarea").show();
            $("#pic_box").hide();
            $("#pic_box").parent().find("textarea").css("visibility", "visible");

            $("#audio_box").hide();
        }

        //听力填空匹配
        if ($("#type").val() == "5")
        {
            $("#lQuestion").hide();

            $("#lTextarea").show();
            $("#pic_box").hide();
            $("#pic_box").parent().find("textarea").css("visibility", "visible");

            $("#audio_box").show();
        }

        //图片匹配题
        if ($("#type").val() == "6") {
            $("#lQuestion").hide();

            $("#lTextarea").show();
            $("#pic_box").show();
            $("#pic_box").parent().find("textarea").css("visibility", "hidden");

            $("#audio_box").hide();
        }

        $("#example").attr("src", "/admin/img/" + $("#type").val() + ".png");
    }


    function insertText(obj, str)
    {
        if (document.selection)
        {
            var sel = document.selection.createRange();
            sel.text = str;
        }
        else if (typeof obj.selectionStart === 'number' && typeof obj.selectionEnd === 'number')
        {
            var startPos = obj.selectionStart,
                endPos = obj.selectionEnd,
                cursorPos = startPos,
                tmpStr = obj.value;
            obj.value = tmpStr.substring(0, startPos) + str + tmpStr.substring(endPos, tmpStr.length);
            cursorPos += str.length;
            obj.selectionStart = obj.selectionEnd = cursorPos;
        }
        else
        {
            obj.value += str;
        }
    }

    changeType();

    function changeAnswer(el)
    {
        if ($("#type").val() == 3)
        {
            var el = $(el);
            var s = el.val();

            s = s.replace(/##/g, '^');

            el.val(s);
        }
    }
</script>

<?php $this->load->view('admin/footer');?>