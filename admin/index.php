<?php

require_once '../page_handler.php';

handle();

use models\Author;

use views\BookForm;

function action_book()
{
    $data=[
        'book'=>&$book,
          ];
    if ($_SERVER['REQUEST_METHOD']=='GET'){
        $id= filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options'=>['default'=>NULL]]);
        if (is_null($id) || !$book=models\Book::getBook($id)){
            $book=new models\Book();
        }
    }
    else if ($_SERVER['REQUEST_METHOD']=='POST')
    {
        $bookform=new views\BookForm();
        $book=$bookform->getBook($_POST);
        $errors=$book->validate();
        if (count($errors)>0){
            $data['bookform_errors']=$errors;
        }
        else
        {
            $data['saved']=$book->save();
        }
    }
    get_view('book_modification', $data);
}



function action_index(){
    require_once '../booklist.php';
    $data=book_list();
    $data['bookitemview']=new\views\BookItemView();
    $data['bookitemview']->link_template='?a=book&id=@id';
    $data['can_add_book']=true;
    get_view('book_list', $data);
}





