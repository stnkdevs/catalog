<?php

namespace db;

class Connection {
    private static $connection_resource=null;
    public static function getConnection(){
        if (is_null(self::$connection_resource))
        {
            self::$connection_resource = self::connect();
        }
        return self::$connection_resource;
    }
    private static function connect(){
        global $config;
        $user=isset($config['user'])?$config['user']:'user';
        $pwd=isset($config['pwd'])?$config['pwd']:'';
        $dsn=isset($config['dsn'])?$config['dsn']:'';
        return new \PDO($dsn, $user, $pwd);
    }
}

function paramQuery($query, array $params){
    $param_names=[];
    $param_values=[];
    foreach ($params as $param=>$value){
        if (is_null($value)){
            $value='NULL';
        }
        else
        {
            $value=Connection::getConnection()->quote($value);
        }
            $param_names[]="@$param";
            $param_values[]= $value;        
    }
    return str_replace($param_names, $param_values, $query);
}

function multiQuery($query, array $callback_funcs){
    $pdo= Connection::getConnection();
    $result = $pdo->query($query);
    $i=0;

    if ($result)
    {
        do
        {
            if (isset($callback_funcs[$i])){
                $handler=$callback_funcs[$i];
                $handler($result);
                $i++;
            }
        }
        while($result->nextRowset());
    }
    else add_log_msg ($pdo->errorInfo());
}





