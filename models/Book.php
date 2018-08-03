<?php

namespace models;

use db\Connection;

class Book {
    public $id, $title, $description, $price, $janrs, $authors;

    
    public static function getBooks($args){
        $desc=isset($args['desc'])?'desc':'';
        $sort_field='id';
        $sort_fields=['id', 'title', 'price'];
        $author_id=filter_var(@$args['author_id'], FILTER_VALIDATE_INT, ['options'=>['default'=>NULL]]);
        $janr_id=filter_var(@$args['janr_id'], FILTER_VALIDATE_INT, ['options'=>['default'=>NULL]]);
        if (isset($author_id))
            $where_list[]=" EXISTS (SELECT * FROM book_author ba WHERE book.id=ba.book_id AND ba.author_id=$author_id) ";
        if (isset($janr_id))
            $where_list[]=" EXISTS (SELECT * FROM book_janr bj WHERE book.id=bj.book_id AND bj.janr_id=$janr_id) "; 
        if (!empty($where_list))
            $where='WHERE '.implode (' AND ', $where_list);
        else $where='';
        if (isset($args['sort_field']) && array_search($args['sort_field'], $sort_fields))
            $sort_field=$args['sort_field'];
        if (isset($args['limit']) && $args['limit'])
            $limit="LIMIT $args[limit]";
        if (isset($args['offset']) && is_int($args['offset']))
            $offset="OFFSET $args[offset]";
        $temp= uniqid();
        $query="CREATE TEMPORARY TABLE $temp AS SELECT id, title, price FROM book $where ORDER BY $sort_field $desc  $limit $offset;"
            . " SELECT * FROM $temp; "
            . " SELECT t.id book_id, a.name, a.surname, a.fname, a.id FROM $temp t INNER JOIN book_author ba ON t.id=ba.book_id INNER JOIN author a ON ba.author_id=a.id; "
            . "SELECT t.id book_id, j.title, j.id FROM $temp t INNER JOIN book_janr bj ON t.id=bj.book_id INNER JOIN janr j ON bj.janr_id=j.id;"
            . "DROP TABLE $temp;";
        $books=[];
        
        $empty=function($result){};
        $book_func=function($result) use (&$books){
            while($book= $result->fetchObject('\models\Book'))
               $books[$book->id]=$book; 
        };

        $authors_func=function($result) use (&$books){
            while($author = $result->fetchObject('\models\Author')){
                $books[$author->book_id]->authors[]=$author; 
                unset($author->book_id);
            }
        };

        $janrs_func=function($result) use (&$books){
            while($janr= $result->fetchObject('\models\Author')){
               $books[$janr->book_id]->janrs[]=$janr; 
               unset($janr->book_id);
            }
        };
        
        $callback_funcs=[$empty, $book_func, $authors_func, $janrs_func, $empty];
        $f=new Connection();
        \db\multiQuery($query, $callback_funcs);
        
           
           
        return $books;
        
    }
    
    public static function getBook($id){
        $pdo= Connection::getConnection();
        $query="SELECT id, title, description, price FROM book WHERE id=@book_id; ";
        $query.="SELECT a.id, a.name, a.fname, a.surname FROM book_author ba INNER JOIN author a ON ba.author_id=a.id WHERE ba.book_id=@book_id ORDER BY ba.position;";
        $query.="SELECT j.id, j.title FROM book_janr bj INNER JOIN janr j ON bj.janr_id=j.id WHERE bj.book_id=@book_id";
        $query= \db\paramQuery($query, ['book_id'=>$id]);       
        $book=null;
        $book_func=function($result) use(&$book){
            $book = $result->fetchObject('\models\Book');
            $book->authors=[];
            $book->janrs=[];
        };
        
        $authors_func=function($result) use(&$book){

            while($author=$result->fetchObject('\models\Author'))
               $book->authors[]=$author;  
        };
           
        
        $janrs_func=function($result) use(&$book){
            while ($janr= $result->fetchObject('\models\Janr'))
               $book->janrs[]=$janr;
        };
        
        \db\multiQuery($query, [$book_func, $authors_func, $janrs_func]);
        
        return $book;
        
        
    }
    
    private function saveAuthors(){
        if (is_null($this->id) || !is_array($this->authors))
            return false;
        $position=0;
        
        Author::identificate($this->authors);
        Author::addRange($this->authors);
        
        foreach ($this->authors as $author){
            if (is_null($author->id))
                continue;
            $values[]="($this->id, $author->id, $position)";
            $position++;
        }
        $pdo=Connection::getConnection();
         $author_query="DELETE FROM book_author WHERE book_id=@book_id;"
                    . "INSERT INTO book_author (book_id, author_id, position) VALUES ".implode(',', $values).';';
         $author_query= \db\paramQuery($author_query, ['book_id'=>$this->id]);
         if ($result=$pdo->query($author_query)){//!
             $result->closeCursor();
             return true;
         }
         else return false;
    }
    
    private function saveJanrs(){
        if (is_null($this->id) || !is_array($this->janrs))
            return false;
        foreach ($this->janrs as $janr){
            $values[]="($this->id, $janr->id)";
        }
        $pdo=Connection::getConnection();
        $janr_query="DELETE FROM book_janr WHERE book_id=@book_id;"
                    . "INSERT INTO book_janr (book_id, janr_id) VALUES ".implode(',', $values).';';
        $janr_query= \db\paramQuery($janr_query, ['book_id'=>$this->id]);
        if ($result=$pdo->query($janr_query)){
            $result->closeCursor();
            return true;
        }
        else
        {
            add_log_msg($pdo->errorInfo());
            return false;
        }
    }
    
    public function save(){
        $pdo=Connection::getConnection();
        if (is_null($this->id))
            $query="INSERT INTO book (title, description, price) VALUES (@title, @description, @price)";
        else $query="UPDATE book SET title=@title, description=@description, price=@price WHERE id=@book_id;";
        $query= \db\paramQuery($query, 
                [
                    'title'=>$this->title,
                    'price'=>$this->price,
                    'description'=>$this->description,
                    'book_id'=>&$this->id,
                ]);
        $result=$pdo->query($query);
        if ($result){
            if (is_null($this->id))
                $this->id= $pdo->lastInsertId();
            $this->saveAuthors();
            $this->saveJanrs();
            return true;
        }
        else return false;
    } 
    
    public function validate(){
        $errors=[];
        $options=['default'=>NULL];
        $this->id=filter_var($this->id, FILTER_VALIDATE_INT, ['options'=>['default'=>NULL]]);
        $this->title=mb_substr(trim($this->title), 0, 80);
        if (empty($this->title))
            $this->title=null;
        $this->description=mb_substr(trim($this->description), 0, 200);
        $this->price=filter_var($this->price, FILTER_VALIDATE_FLOAT, ['options'=>['default'=>NULL, 'min_range'=>0.01, 'max_range'=>1000000]]); 
        if (!is_array($this->authors) || count($this->authors)<1 || count($this->authors)>10)
            $errors['authors']='Укажите колличество авторов от 1 до 10';
        if (!is_array($this->janrs) || count($this->janrs)<1 || count($this->janrs)>5)
            $errors['janrs']='Укажите колличество выбраных жанров от 1 до 5';
        $property_msg=[ 'title'=>'Название книги обязателено',
                        'description'=>'Неверное описание', 
                        'price'=>'В качестве дробного разделителя используйте ".". Цена должна быть от 0.01 до 1000 000' ];
        foreach($property_msg as $property=>$msg){
            if (is_null($this->$property)){
                $errors[$property]=$msg;
            }
        }
        return $errors;
    }
}
