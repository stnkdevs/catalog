<?php

namespace views;

class BookItemView{
    
/*
 * @id will be replaced on book's ID
 */    
public $link_template;

public function getLink($book){
    return htmlspecialchars(str_replace('@id', $book->id, @$this->link_template));
}

public function show($book){ 


    
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


<div class="book_view_short">
    <a href="<?=$this->getLink($book)?>"><h3 align="center"><?= htmlspecialchars($book->title)?></h3></a>
    <h4>Авторы: <?=$authors_str?></h4>
    <h4>Жанры: <?=$janrs_str?></h4>           
</div>

<style>
    .book_view_short{
        border-top: outset 2px;
        border-left: outset 2px;
        padding: 10px;
        background-color: rgb(257, 257, 250);
    }
</style>

<?php 
    }
}
