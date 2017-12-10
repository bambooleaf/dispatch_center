<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/12/8
 * Time: 下午1:12
 */

/*
 * 全局错误码定义
 * 0,请求成功
 * 40000,不合法的请求参数
 * 40004,服务不存在
 * */
define('ERRCODE_SUCCESS',0);
define('ERRCODE_ILLEGAL_BODY',40000);
define('ERRCODE_ILLEGAL_PARAM',40001);
define('ERRCODE_INVALID_SERVICE',40004);
define('ERRCODE_EXCEPTION_SERVICE',50000);



/*
 * 日志相关
 * */
define('LOG_TYPE', 'SeasLog'); //SeasLog or StdLog
//define('LOG_TYPE', 'StdLog'); //SeasLog or StdLog