<?php

namespace models;

use db\Connection;

class Author {
    
    public $id, $name, $fname, $surname;
    
    public function validate(){
        foreach(['name', 'surname', 'fname'] as $property){
            $this->$property=is_null($this->$property)?'':$this->$property;
            $this->$property= mb_substr(trim($this->$property), 0, 80);
        }
    }
    
    public function fullName(){
        $name= mb_substr($this->name, 0, 1).'. ';
        $name.= mb_substr($this->fname, 0, 1).'. ';
        $name.= $this->surname;
        return $name;
    }

    public static function getAuthors($surname=null, $limit=10){
        try
        {
            $pdo=Connection::getConnection();
            $surname=str_replace(['%', '_'], ['\%', '\_'], $surname);
            $surname="$surname%";
            $surname=$pdo->quote($surname);          
            if (isset($surname))
                $condition=" WHERE surname LIKE $surname ";
            else $condition='';
            $query="SELECT id, name, fname, surname FROM `author` $condition ORDER BY `surname` LIMIT 10;";
            $result=$pdo->query($query);
            $authors=[];
            if ($result) {
                while ($author=$result->fetchObject('\models\Author')) {
                    $authors[]=$author;
                }
                $result->closeCursor();
                // Close $RESULT ?
            }
            return $authors;
        }
        catch(PDOException $ex){
            add_log_msg($ex->getMessage());
            return [];
        }
    }
    
    public static function identificate($authors){
        $pdo=Connection::getConnection();
        if (is_array($authors))
        {
            foreach($authors as $author)
            {
                if (($author instanceof Author) && is_null($author->id)){
                    $query= \db\paramQuery(
                            'SELECT id FROM author WHERE name=@name AND fname=@fname AND surname=@surname', 
                            ['name'=>$author->name, 'fname'=>$author->fname, 'surname'=>$author->surname]);
                    $result=$pdo->query($query);
                    if ($result && $row = $result->fetch(\PDO::FETCH_ASSOC)){
                        $author->id=$row['id'];
                        $result->closeCursor();
                    }
                }
            }
        }
    }
    
    public static function addRange($authors){
        $pdo=Connection::getConnection();
        if (is_array($authors))
        {
            foreach($authors as $author)
            {
                if (($author instanceof Author) && is_null($author->id)){
                    $query= \db\paramQuery(
                          'INSERT INTO author (name, fname, surname) VALUES (@name, @fname, @surname); ', 
                            ['name'=>$author->name, 'fname'=>$author->fname, 'surname'=>$author->surname]);
                    $result=$pdo->query($query);
                    if ($result){
                        $author->id= $pdo->lastInsertId();
                    }
                }
            }
        }
    }
    
}

