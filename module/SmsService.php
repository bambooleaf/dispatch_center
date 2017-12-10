<?php

/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/12/8
 * Time: 下午12:30
 */
require_once 'BaseService.php';
class SmsService extends BaseService
{
    public function __construct($version)
    {
        parent::__construct('sms');
    }

}