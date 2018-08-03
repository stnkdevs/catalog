<?php

namespace models;

use db\Connection;

class Janr {
    public $id, $title;
    
    public static function getJanrs(){
        $pdo=Connection::getConnection();
        $query="SELECT id, title FROM `janr` ORDER BY position";
        $result=$pdo->query($query);
        $janrs=[];
        if ($result) {
            while ($janr = $result->fetchObject('\models\Janr')) {
                $janrs[]=$janr;
            }
            $result->closeCursor();
        }
        else add_log_msg($pdo->errorInfo());
        return $janrs;
    }
    
    public static function identificate($janrs){
        if (is_array($janrs))
        {
            $pdo=Connection::getConnection();
            foreach($janrs as $janr)
            {
                if (($janr instanceof Janr) && is_null($janr->id)){
                    $query= \db\paramQuery(
                            'SELECT id FROM janr WHERE title=@title', 
                            ['title'=>$janr->title]);
                    $result=$pdo->query($query);
                    if ($result && $row = $result->fetch(\PDO::FETCH_ASSOC)){
                        $janr->id=$row['id'];
                        $result->closeCursor();
                    }
                }
            }
        }
    }
    
    public static function addRange($janrs){
        if (is_array($janrs))
        {
            $pdo=Connection::getConnection();
            foreach($janrs as $janr)
            {
                if (($janr instanceof Janr) && is_null($janr->id)){
                    $query= \db\paramQuery(
                            'INSERT INTO janr (title) VALUES (@title); ', 
                            ['title'=>$janr->title]);
                    if ($pdo->query($query)){
                        $janr->id= $pdo->lastInsertId();
                    }
                }
            }
        }
    }
}

