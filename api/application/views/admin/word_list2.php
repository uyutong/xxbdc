<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('admin/header');?>


<div class="contents">
    <h1>
        单词列表 - 

        <?php if($this->input->get_post("orderby")=="book") {?>
        <a href="/index.php/admin/word/list2?orderby=word">按照单词字母排序</a>
        |
        按照书本排序
        <?php } else {?>
        按照单词字母排序
        |
        <a href="/index.php/admin/word/list2?orderby=book">按照书本排序</a>
        <?php } ?>
    </h1>
    <div class="article">
        <table class="table">
            <thead>
                <tr>
                    <th style="text-align:left">单词</th>
                    <th style="text-align:left">汉语</th>
                    <th style="text-align:left">二维码</th>
                    <th style="text-align:left">年级</th>
                    <th style="text-align:left">学期</th>
                    <th style="text-align:left">出版商</th>
                    <th style="text-align:left">单元-英</th>
                    <th style="text-align:left">单元-汉</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($words as $item): ?>
                <tr>
                    <td>
                        <?php echo $item['en']; ?>
                    </td>
                    <td>
                        <?php echo $item['zh'];?>
                    </td>
                    <td>
                        <a class="mark-link" href="/phpqrcode.php?book_id=<?php echo $item['book_id']; ?>&unit_id=<?php echo $item['unit_id']; ?>&word=<?php echo $item['en']; ?>&t=word" target="_blank">
                            单词二维码
                        </a>
                        |
                        <a class="mark-link" href="/phpqrcode.php?book_id=<?php echo $item['book_id']; ?>&unit_id=<?php echo $item['unit_id']; ?>&word=<?php echo $item['en']; ?>&t=exercise" target="_blank">
                            练习二维码
                        </a>
                    </td>
                    <td>
                        <?php echo $item['grade'];?>
                    </td>
                    <td>
                        <?php echo $item['semester'];?>
                    </td>
                    <td>
                        <?php echo $item['publisher'];?>
                    </td>
                    <td>
                        <?php echo $item['name'];?>
                    </td>
                    <td>
                        <?php echo $item['name_zh'];?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $this->load->view('admin/footer');?>