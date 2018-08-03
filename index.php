<?php

require_once 'page_handler.php';

use models\Author;
use models\Book;
use views\JanrSelector;
use views\AuthorSelector;

handle();



function action_authors(){
    if (isset($_REQUEST['surname'])){
        $surname=@$_REQUEST['surname'];
        $surname= mb_substr($surname, 0, min([80, mb_strlen($surname)]));
    }
    else $surname=null;
    $limit= filter_var(@$_REQUEST['limit'], FILTER_VALIDATE_INT,
            ['options'=> [ 'default'=>10, 'min_range'=>1, 'max_range'=>100] ]);
    $authors=Author::getAuthors($surname, $limit);
    header('Content-type: application/json');
    echo json_encode($authors);
}

function send_order($msg){
    global $config;
    $to=@$config['admin_email'];
    if(!@mail($to, 'Order', $msg)){
        $f=fopen('admin/failed_mails.txt', 'a+');
        fputs($f, $msg."\r\n");
        fclose($f);
        return false;
    }
    else return true;
}

function action_order(){
    $id= filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options'=>['default'=>NULL]]);
    if (is_null($id) || !$book=models\Book::getBook($id))      
    {
        echo 'Not Found book';
        return;
    }
    $data['book']=$book;
    
    if ($_SERVER['REQUEST_METHOD']=='POST'){
        $count= filter_input(INPUT_POST, 'count', FILTER_VALIDATE_INT, ['options'=>['default'=>NULL]]);
        $adress=mb_substr(trim(@$_POST['adress']), 0, 50);
        $person=mb_substr(trim(@$_POST['person']), 0, 50);
        if (empty($count) || empty($adress) || empty($person)){
            $data['error']=true;
        }
        else
        {
            ob_start();
            get_view('mail', [
                'adress'=>$adress,
                'person'=>$person,
                'count'=>$count,
                'book_view_link'=> $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                'book_title'=>$book->title,
            ]);
            $text= ob_get_contents();
            ob_end_clean();
            $data['form_sent']=send_order($text);
        }
    }
    
    get_view('book_order', $data);
}



function action_index(){
    require_once 'booklist.php';
    $data= book_list();
    $data['bookitemview']=new\views\BookItemView();
    $data['bookitemview']->link_template='?a=order&id=@id';
    get_view('book_list', $data);
}


    







    
