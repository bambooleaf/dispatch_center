<?php

/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/12/8
 * Time: 下午8:46
 */
interface BaseInterface
{
    public function setBizContent($bizcontent);
    public function setModule($module);
    public function setMethod($method);
    public function getModule();
    public function getMethod();
    public function getBizContent();

}