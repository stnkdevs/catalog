<?php

use models\Book;
use views\AuthorSelector;
use views\JanrSelector;
use models\Author;
use models\Janr;

function book_list(){
    
    $onPage=3; //On Page
    $args['limit']=$onPage+1;    
    
    if (isset($_POST['sort_field'])){
        $sort_field=$_POST['sort_field'];//validated in method
    }
    $args['desc']=&$_POST['desc'];
    
    if (isset($_POST['filter'])){
        $authors=(new AuthorSelector())->getAuthors($_POST);
        $janrs=(new JanrSelector())->getSelected($_POST);
        $page=0;        
    }
    else
    {
        $authors=json_decode(@$_POST['authors']);
        $janrs=json_decode(@$_POST['janrs']);
        $page= filter_input(INPUT_POST, 'page', FILTER_VALIDATE_INT, ['options'=>['default'=>NULL]]);
        $page+=isset($_POST['next'])?1:0;
        $page-=isset($_POST['prev'])?1:0;
    }
    $args['offset']=$page*$onPage;
    if (is_array($authors) && count($authors)==1){
        Author::identificate($authors);
        $args['author_id']=$authors[0]->id;
    }
    if (is_array($janrs) && count($janrs)==1 && $janrs[0]->id)
    {
        $args['janr_id']=$janrs[0]->id;
    }
    if (count($janrs)>1){
        $data['errors']['janrs']=true;
    }
    if (count($authors)>1){
        $data['errors']['authors']=true;
    }
    $args['sort_field']=@$sort_field;
    $books=Book::getBooks($args);
    if (count($books)>$onPage){
        $data['has_next']=true;
        array_pop($books);
    }
    $data['sort_field']=@$sort_field;
    $data['authors']=$authors;
    $data['janrs']=$janrs;
    $data['page']=$page;
    $data['books']=$books;
    return $data;
}

