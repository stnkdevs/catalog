<?php header('Content-type: text/html; charset=utf-8');        
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <script type="text/javascript" src="<?=get_abs_path('js/jquery.js')?>"></script>
        <title></title>
         <link rel="stylesheet" href="<?=get_abs_path('css/book_order.css')?>">
    </head>
    <body>
        <div class="content">
        <?php
        $bv=new views\BookView();
        $bv->show($book);
        ?>  
        <br><br>
        <div class="contact">
            <h3 align="center">Форма заказов</h3>
            <?php if (!isset($hide_form)): ?>
        <form method="post">
            <table>
                <tr><td>Адрес</td><td><input type="text" name="adress"></td></tr>
                <tr><td>ФИО <td><input type="text" name="person"></td></tr>
                <tr><td>Колличество экземпляров: </td><td><input type="number" name="count"></td></tr>
                <tr><td></td><td><input type="submit" value="Отправить сообщение"></td></tr>
            </table>
            <div class="msg">
            <?php if (@$error):?><p>Проверьте правильность заполнения формы! Все поля обязательны.</p>
            <?php endif ?>
            <?php endif ?>
            <?php if (isset($form_sent) && $form_sent): ?>           
            <p>Заявка успешно отправлена</p>
            <?php elseif(isset($form_sent) && !$form_sent): ?>
            <p>Проблема при отправке заявки</p>
            <?php endif ?>
            </div>
        </form>
        </div>
        </div>
    </body>   
</html>

