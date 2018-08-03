<?php header('Content-type: text/html; charset=utf-8');        
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <script type="text/javascript" src="<?= get_abs_path('js/jquery.js')?>"></script>
        <title></title>
        <link rel="stylesheet" href="<?=get_abs_path('css/book_list.css')?>">
        <script type="text/javascript" src="<?=get_abs_path('js/book_list.js')?>"></script>
    </head>
    <body>
         <div class="body_wrapper">
             
        <div class="book_filter">
            <form method="post">   
            <?php 
            use views\JanrSelector;
            use views\AuthorSelector;
            use views\BookItemView;
            if (!isset($authors))
                $authors=[];
            if (!isset($janrs))
                $janrs=[];
            $janr_s=new JanrSelector();
            $janr_s->setSelected($janrs);
            $janr_s->show();
            ?>
            <?php if (isset($errors['janrs'])): ?><p>Выберите 1 жанр</p><?php endif?>
            <br>
            <?php
            $author_s=new AuthorSelector();
            $author_s->setAuthors($authors);
            $author_s->show();
            ?>
            <?php if (isset($errors['authors'])): ?><p>Выбирете 1 автора</p><?php endif?>
            <br>
            Сортировать по: <select name="sort_field">
                <option value="id">не указано</option>
                <option value="title" <?='title'==@$sort_field?'selected':''?>>по названию</option>
                <option value="price" <?='price'==@$sort_field?'selected':''?>>по цене</option>
            </select><br><br>
            <input type="checkbox" name="desc" <?=isset($_POST['desc'])?'checked':''?>>&nbsp;По убиванию<br><br>
            <input type="submit" name="filter" value="Применить фильтр">
            </form>
            <?php if (@$can_add_book): ?>
            <form method="get">
                <input type="hidden" name="a" value="book"><br>
                <input type="submit" value="Добавить книгу" style="">
            </form>
            <?php endif ?>
        </div> 
        
        <form method="post">
        <div class="results">
            <?php if (count($books)==0):?>
            <h1>Не найдено ни одной книги по заданому фильтру</h1>            
            <?php endif ?>
            <?php foreach($books as $book):?>
            <?=$bookitemview->show($book)?><br>
            <?php endforeach; ?>
            <div class="paginator">
                <input type="hidden" name="pagination" value="used">
                <input type="hidden" name="page" value="<?=@$page?>">
                <?php if(@$page>0):?><input type="submit" name="prev" value="<<ПРЕДЫДУЩАЯ">
                <?php endif; if (@$has_next):?>
                <input type="submit" name="next" value="СЛЕДУЮЩАЯ>>">
                <?php endif?>
                <input type="hidden" name="authors" value="<?= htmlspecialchars(json_encode($authors))?>">
                <input type="hidden" name="janrs" value="<?=htmlspecialchars(json_encode($janrs))?>">
                <input type="hidden" name="sort_field" value="<?=@$sort_field?>">
                <?php if(isset($_POST['desc'])):?><input type="hidden" name="desc" value="1"><?php endif ?>
            </div>
        </div>
        </form>
    </div>
    </body>   
</html>

