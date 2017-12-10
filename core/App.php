<?php

/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/12/8
 * Time: 下午12:39
 * 单例类
 */

require_once UTILS . 'HttpUtil.php';
require_once UTILS . "Logger.php";

class App
{

    static public $_instance;
    private $responseData = null;

    private function __construct()
    {

    }

    private function __clone()
    {

    }


    static public function getInstance()
    {
        if (!self::$_instance) {
            return new self();
        }
        return self::$_instance;
    }


    public function initService($serviceName, $version = '')
    {
        $classname = ucfirst($serviceName . "Service");
        if (file_exists(MODULE . '/' . $classname . '.php')) {
            require_once MODULE . '/' . $classname . '.php';
            if (class_exists($classname, true)) {
                return new $classname($version);
            }
        }
        return null;
    }

    public function execute($service)
    {
        try {
            if ($service->getMethod() == 'GET') {
                $this->responseData = HttpUtil::executeGet($service->getHost() . $service->getModule(), $service->getBizContent());
            } elseif ($service->getMethod() == 'POST') {
                $this->responseData = HttpUtil::executePost($service->getHost() . $service->getModule(), $service->getBizContent());
            } else {
                return false;
            }
        } catch (Exception $e) {
            Logger::saveLog($e->getMessage(),"service_request_error");
        }
    }


    public function respose($errCode)
    {
        if ($this->responseData === null) {
            $retData = [
                'status' => ERRCODE_EXCEPTION_SERVICE,
                'msg' => '服务访问异常'
            ];
        } elseif ($errCode->getCode() === 0) {
            $retData = [
                'status' => $errCode->getCode(),
                'msg' => $errCode->getMsg(),
                'data' => json_decode($this->responseData, true)
            ];
        } else {
            $retData = [
                'status' => $errCode->getCode(),
                'msg' => $errCode->getMsg()
            ];
        }
        echo json_encode($retData, true);
        exit();
    }


    public function check_request($param)
    {
        if (!is_array($param) || !isset($param['service']) || !isset($param['method']) || !isset($param['bizcontent']) || !isset($param['version']) || !isset($param['module'])) {
            return false;
        }
        Logger::saveLog(json_encode($param,true),"access_log");
        return true;
    }


}