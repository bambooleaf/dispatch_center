<?php

/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/12/8
 * Time: 下午12:29
 */
require_once 'BaseInterface.php';
require_once MODEL.'ServiceConfigModel.php';
class BaseService implements BaseInterface
{

    private $bizContent;
    private $module;
    private $method;
    private $servicename;

    public function __construct($servicename)
    {
        $this->servicename = $servicename;
    }


    public function setBizContent($bizcontent){
        $this->bizContent = $bizcontent;
    }
    public function setModule($module){
        $this->module = $module;
    }
    public function setMethod($method){
        $this->method = $method;
    }

    public function getBizContent(){
        return $this->bizContent;
    }
    public function getModule(){
        return $this->module;
    }
    public function getMethod(){
        return strtoupper($this->method);
    }


    public function getHost($version=''){
        $model = new ServiceConfigModel();
        $services = $model->query_service_by_db($this->servicename);
        if(count($services)==0){
            return false;
        }elseif (count($services)==1){
            return 'http://'.$services[0]['ip'].':'.$services[0]['port'].'/';
        }else{
            $randomarray=[];
            foreach ($services as $key => $value){
                $randomarray = array_merge($randomarray,array_fill(0,$value['priority'],$key));

            }
            unset($key,$value);

            $randomkey = $randomarray[array_rand($randomarray)];
            return 'http://'.$services[$randomkey]['ip'].':'.$services[$randomkey]['port'].'/';
        }

    }

}