<?php


require_once 'config.php';

if (isset($config['timezone']))
    date_default_timezone_set($config['timezone']);
if (empty($config['root_uri']))
    $config['root_uri']='/';
else if(is_string($config['root_uri']) && $config['root_uri'][strlen($config['root_uri'])-1]!='/'){
    $config['root_uri']= "$config[root_uri]/";
}

function get_abs_path($string){
    global $config;
    return  "$config[root_uri]$string";
}

function add_log_msg($text){
    $f=fopen('admin/log.txt', 'a+');
    $text=date('d/m/Y H:i')."\r\n".$text."\r\n--------------------\r\n";
    fputs($f, $text);
    fclose($f);
}

function handle()
{
    $action= filter_input(INPUT_GET, 'a', FILTER_VALIDATE_REGEXP,
            ['options'=>['regexp'=>'/[a-z]\\w{0,20}/', 'default'=>NULL]]);
    if (!$action)
        $action='index';
    $action="action_$action";
    if (function_exists($action))
    {
        if ((new ReflectionFunction($action))->getNumberOfParameters()==0)
        {
            try
            {
                $action();
            }
            catch(Exception $ex)
            {
                add_log_msg($ex->getMessage());
                echo '<h1>Возникла ошибка. Она будет решена в ближайшее время</h1>';
            }
            
        }
        else return;
    }
    else
    {
        header('HTTP/1.1 404');
    }   
}

function autoload($classname){
    $classname= str_replace('\\', '/', $classname);
    $r_filename= str_replace('\\', '/', __DIR__)."/$classname.php";
    if(file_exists($r_filename))
    require_once($r_filename);
}

function get_view($path, $data=[]){
    if (!is_array($data)){
        $data=[];
    }
    $r_filename=__DIR__."/views/$path.php";
    if (file_exists($r_filename))
    {
        foreach($data as $var=>$val)
            $$var=$val;
        require_once $r_filename;
    }
    else
    {
        echo $r_filename;
    }
}

spl_autoload_register('autoload');
