<?php
class Connect{
    static function ConnectionDataBase (){
        $param=require_once $_SERVER['DOCUMENT_ROOT']."/config/db_param.php"; 
            //ROOT.'/config/db_param.php';
        $connection=new mysqli($param['host'],$param['user'],$param['password'],$param['database']);
        if ($connection->connect_errno) {
            echo "Connect failed:{$connection->connect_errno}";
            exit();
        };
        return $connection;
    }   
}