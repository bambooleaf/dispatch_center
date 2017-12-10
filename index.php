<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/12/8
 * Time: 下午12:17
 * 基础服务调度中心,用于请求分发
 */

define('MODULE', __DIR__ . '/module/');
define('UTILS', __DIR__ . '/utils/');
define('CONFIG', __DIR__ . '/config/');
define('DATABASE', __DIR__ . '/database/');
define('MODEL', __DIR__ . '/model/');
define('ENVIRONMENT', 'development');
define('APPPATH', '.' . DIRECTORY_SEPARATOR);
date_default_timezone_set('Asia/Shanghai');

require_once 'core/App.php';
require_once 'core/ErrorCode.php';

//TODO 改为自动加载
require_once 'config/constants.php';

switch (ENVIRONMENT) {
    case 'development':
        error_reporting(E_ALL);
        break;

    case 'testing':
    case 'production':
        ini_set('display_errors', 0);
        if (version_compare(PHP_VERSION, '5.3', '>=')) {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        } else {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        }
        break;

    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'The application environment is not set correctly.';
        exit(1); // EXIT_ERROR
}
$app = App::getInstance();

/*
 * 请求方式只接受POST请求
 * 上报数据类型为:
 * {
 *      "service":"pay",//请求基础服务类型
 *      "method":"post",//请求的方法类型
 *      "bizcontent":{},//具体业务参数
 *      "version":1.0,//为以后多版本分类预留
 *      "module":"pay/postpay"//请求的具体方法
 * }
 * */
$requestParam = file_get_contents('php://input');

try {
    $requestParam = json_decode(file_get_contents('php://input'), true);
} catch (Exception $e) {
    $app->respose(new ErrorCode(ERRCODE_ILLEGAL_BODY, "不合法的报文"));
}


/*
 * 校验请求参数
 * */
if (!$app->check_request($requestParam)) {
    $app->respose(new ErrorCode(ERRCODE_ILLEGAL_PARAM, "请求参数校验失败"));
}

/**
 * 初始化服务
 */
$service = $app->initService($requestParam['service'], $requestParam['version']);
if (!$service) {
    $app->respose(new ErrorCode(ERRCODE_INVALID_SERVICE, "服务尚未注册或不存在"));
}

$service->setBizContent($requestParam['bizcontent']);
$service->setModule($requestParam['module']);
$service->setMethod($requestParam['method']);

$app->execute($service);


$app->respose(new ErrorCode(ERRCODE_SUCCESS, "请求成功"));




