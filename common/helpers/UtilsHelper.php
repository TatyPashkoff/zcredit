<?php
namespace common\helpers;

class UtilsHelper {

    const DEBUG_MODE = true; // режим записи отладочной информации

    // отправка заявки на создание полиса
    public static function debug($data,$clear=false){

        if(!self::DEBUG_MODE) return false;

        $path = $_SERVER['DOCUMENT_ROOT'] .'/debug_api.txt';
        //if( $clear ) unset($path);
        $f = fopen($path,'a');
        $data = date('d.m / H:i:s') . ':  ' . json_encode($data,JSON_UNESCAPED_UNICODE) . PHP_EOL;
        fwrite($f,$data);
        fclose($f);
    }

    public static function debugSms($data){
        if(!self::DEBUG_MODE) return false;
        $path = $_SERVER['DOCUMENT_ROOT'] .'/debug_sms.txt';
        $f = fopen($path,'a');
        fwrite($f,$data);
        fclose($f);
    }

}