<?php

/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/12/8
 * Time: 下午4:22
 */

class HttpUtil
{

    static protected function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;

        return false;
    }
    static public function getSignContent($params) {
        ksort($params);

        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === self::checkEmpty($v) && "@" != substr($v, 0, 1)) {

                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }

        unset ($k, $v);
        return $stringToBeSigned;
    }


    static public function executeGet($url, $data, $extra_header = '')
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url."?".self::getSignContent($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/
       537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36");
        $headers = array();
        if (!empty($extra_header)) {
            array_push($headers, 'Content-Type: application/json; charset=utf-8');
            array_push($headers, $extra_header);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }


    /**
     * @param $url
     * @param $post_data
     * @param string $patten
     * @param string $extra_header
     * @param int $connect_timeout_ms
     * @param int $timeout_ms
     * @return mixed
     */
    static public function executePost($url, $post_data, $patten = 'json', $extra_header = '', $connect_timeout_ms = 0, $timeout_ms = 0)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS, $connect_timeout_ms);
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, $timeout_ms);

        switch ($patten) {
            case 'json':
                if (is_array($post_data)) {
                    $post_string = json_encode($post_data, JSON_UNESCAPED_SLASHES);
                } else {
                    $post_string = $post_data;
                }
                curl_setopt($curl, CURLOPT_POSTFIELDS, $post_string);
                $headers = array();
                array_push($headers, 'Content-Type: application/json; charset=utf-8');
                array_push($headers, 'Content-Length: ' . strlen($post_string));
                if (!empty($extra_header)) {
                    array_push($headers, $extra_header);
                }

                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                break;
            case 'form':
                if (!is_array($post_data)) {
                    exit('请传入数组格式！');
                }
                $post_string = $post_data;
                curl_setopt($curl, CURLOPT_POSTFIELDS, $post_string);
                curl_setopt($curl, CURLOPT_HTTPHEADER,
                    array('content-type: multipart/form-data; charset=utf-8')
                );
                break;
            default :
                exit('请指明类型');
        }

        $output = curl_exec($curl);
        if (curl_errno($curl) !== 0) {
            Logger::saveLog(json_encode($output), 'curl_error');
            Logger::saveLog(json_encode(curl_error($curl)), 'curl_error');
            curl_close($curl);
            return null;
        }
        curl_close($curl);
        return $output;
    }


}