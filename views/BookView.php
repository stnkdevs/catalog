<?php



namespace views;


class BookView {
    
    public function show($book){ ?>
<style>
    .book_view p:first{
        margin-left : 10%;
        margin-right: 10%;
        text-indent: 20px;
    }
</style>
<div class="book_view">
    <h2 align="center"><?=$book->title?></h2>
    <p align="justify"><?=empty($book->description)?'Описание отсутствует':htmlspecialchars($book->description)?></p>

<?php
$authors_str='';
if (isset($book->authors))
foreach($book->authors as $author){
    $authors_str.=htmlspecialchars($author->fullName()).' ';
}
$janrs_str='';
if (isset($book->janrs))
foreach($book->janrs as $janr){
    $janrs_str.=htmlspecialchars($janr->title).' ';
}
?>
<p>Авторы книги: <?=$authors_str?></p>
<p>Жанры: <?=$janrs_str?></p>
<p>Цена: <?= htmlspecialchars($book->price)?> грн</p>
</div>

<?php
    }
    
}
