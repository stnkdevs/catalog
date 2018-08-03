<?php

namespace views;

use models\Book;

use views\JanrSelector;

use views\AuthorSelector;


class BookForm{
    
private function error_label($errors, $property){
    if (isset($errors[$property])){
        $property_error=$errors[$property];
        if (is_array($property_error)){
            echo '<ul>';
            foreach($property_error as $msg)
                echo "<li>$msg";
            echo '</ul>';
        }
        else echo "<p class=\"validation_msg\">$property_error</p>";
    }
}

public function show($book=null, $errors=[]){

    $fields=['title'=>'Title', 'description'=>'Description',
        'price'=>'Price'];       
?>
<style>
    .book_form textarea{
        width: 450px;
        height: 200px;
        resize: none;
    }
    .book_form input[name=book_title]{
        width: 450px;
    }
    .book_form .form_table {
        border-spacing: 20px 25px;
    }
    .book_form .field{       
        font-size: 16px;
    }
    .validation_msg{
        color: red;
    }
</style>
<div class="book_form">
<input type="hidden" name="book_id" value="<?=htmlspecialchars(@$book->id)?>">
<table class="form_table">
    <tr>
        <td>Название книги</td>
        <td>
            <input type="text" name="<?="book_title"?>" value="<?= htmlspecialchars(@$book->title) ?>" class="field">
            <?=$this->error_label($errors, 'title') ?>
        </td>
    </tr>
    <tr>
        <td>Описание</td>
        <td><textarea name="book_description" class="field"><?= htmlspecialchars(@$book->description) ?></textarea></td>
    </tr>
    <tr>
        <td>Цена</td>
        <td>
            <input type="text" name="<?="book_price"?>" class="field" value="<?= htmlspecialchars(@$book->price) ?>">
            <?=$this->error_label($errors, 'price') ?>
        </td>
    </tr>
    <tr>
        <td>Авторы</td>
        <td>
            <?php
            $as=new AuthorSelector();
            if(isset($book->authors))
                $as->setAuthors($book->authors);
            echo $as->show();
            $this->error_label($errors, 'authors');
            ?>
        </td>
    </tr>
    <tr>
        <td>Жанры</td>
        <td>
            <?php
            $js=new JanrSelector();
            if (isset($book->janrs))
                $js->setSelected($book->janrs);
            echo $js->show();
            $this->error_label($errors, 'janrs');?>
        </td>
    </tr>
</table>
</div>

<?php

}

public function getBook($env){
    $book=new Book();
    $book->id=$env['book_id'];
    $book->title=$env['book_title'];
    $book->description=$env['book_description'];
    $book->price=$env['book_price'];  
    $as=new AuthorSelector();
    $book->authors=$as->getAuthors($env);
    $js=new JanrSelector();
    $book->janrs=$js->getSelected($env); 
    return $book; 
}

}
