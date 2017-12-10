<?php

/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/12/8
 * Time: 下午8:10
 */

require_once CONFIG.'db_config.php';
class DB
{
    private $host=DB_HOST;
    private $database=DB_DATABASE;
    private $username=DB_USERNAME;
    private $password=DB_PASSWORD;
    private $pdo;
    public function __construct()
    {
        //TODO 读写分离
        $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);//创建一个pdo对象
    }

    public function getPdo(){
        return $this->pdo;
    }
}