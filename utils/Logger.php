<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/12/8
 * Time: 下午4:16
 */

class Logger{
    static public function saveLog($line, $log_path = 'Request')
    {

        !is_string($line) && $line = json_encode($line, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if (!self::startWith($log_path, '/')) {
            $app_name = "dispatch_center/";
            $path = '/data/zues_application_log/'. $app_name . $log_path;
        } else {
            $path = $log_path;
        }
        if (LOG_TYPE == 'StdLog') {
            self::saveLogByStd($path, $line);
        } else if ((LOG_TYPE == 'SeasLog') && (class_exists('SeasLog'))) {
            $SeasLog_path = dirname($path);
            $model = mb_substr($path, strlen($SeasLog_path));
            SeasLog::setBasePath($SeasLog_path);
            SeasLog::setLogger($model);
            SeasLog::info($line);
        }
    }

    static public function startWith($str, $needle) {
        return strpos($str, $needle) === 0;
    }


    static public function saveLogByStd($log_path, $line)
    {
        if (!self::startWith($log_path, '/')) {
            $app_name = pathinfo(dirname(APPPATH),PATHINFO_BASENAME).DIRECTORY_SEPARATOR;
            $path = '/data/zues_application_log/'. $app_name . $log_path;
        } else {
            $path = $log_path;
        }
        $path = $path . date('Y') . '/' . date('m');
        $file = $path . '/' . date('Ymd') . '.log';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
            chmod($path, 0777);
        }
        $fp = fopen($file, "a");
        flock($fp, LOCK_EX);
        fwrite($fp, $line . "\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}
