<?php

/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/12/8
 * Time: 下午12:39
 */
class ErrorCode
{
    private $code;
    private $msg;
    public function __construct($code=0,$msg='')
    {
        $this->code = $code;
        $this->msg = $msg;
    }

    public function getCode(){
        return $this->code;
    }

    public function getMsg(){
        return $this->msg;
    }

}