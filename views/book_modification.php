<?php header('Content-type: text/html; charset=utf-8');

$text_send=empty($book->id)?'Добавить книгу':'Сохранить';
        
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <script type="text/javascript" src="<?= get_abs_path('js/jquery.js')?>"></script>
        
        <title></title>
        <style>
            body{
                background-color: rgb(210, 220, 255);
            }
            input[type="submit"]{
                width: 250px;
                height: 50x;
                font-size: 18px;
                border-radius: 10px;
                background-color: lightblue;
                display: block;
                margin-right: 120px;
                margin-left: auto;
            }
            .main_form{
                position: relative;
                border: 1px solid;
                margin: auto;
                width: 700px;
                right: 0px;
                left: 0px;
                padding-bottom: 15px;
                background-color: rgb(247, 247, 250);
            }
            .main_form .save_msg{
                background: yellow;
                padding: 10px;
            }
        </style>
    </head>
    <body>
        
        <form method="post">
         <div class="main_form"> 
        <?php if ($_SERVER['REQUEST_METHOD']=='POST' && isset($saved)): ?>
        <p class="save_msg">
        <?php if ($saved): ?>Изменения сохранены
        <?php else: ?>
        Не удалось сохранить данные о книге. Попробуйте позже
        <?php endif ?>
        <?=date('(H:i:s)')?>
        </p><?php endif ?>   
        <?php
        $bf=new views\BookForm();
        $bookform_errors=isset($bookform_errors)?$bookform_errors:[];
        $bf->show($book, $bookform_errors);
        ?>
            
        <input type="submit" value="<?=$text_send?>">
         </div>
        </form>
    </body>
</html>
