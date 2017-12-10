<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/12/8
 * Time: 下午4:23
 */
require_once DATABASE.'DB.php';

class ServiceConfigModel{
    public function __construct()
    {
        $db = new DB();
        $this->conn = $db->getPdo();
    }

    public function query_service_by_db($servicename){
        $sql = "SELECT * FROM dispatch_config WHERE servicename= ?";
        $stmt = $this->conn->prepare($sql);
        $rs = $stmt->execute(array($servicename));
        $result = [];
        if ($rs) {
            // PDO::FETCH_ASSOC 关联数组形式
            // PDO::FETCH_NUM 数字索引数组形式
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $result[] = $row;
            }
        }
        return $result;
    }

    public function query_service_by_redis($servicename){
        //TODO 通过redis查询
    }
}