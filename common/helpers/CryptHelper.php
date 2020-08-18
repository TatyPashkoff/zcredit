<?php
namespace common\helpers;

use Yii;

class CryptHelper {

    //private static $key = 'Kr5KHY+t5Hc0x8F+YSNN/ADE7O2c1RrU2exBjWbw1iQ='; //test
    private static $key = 'Kr5KHY+t5Hc0x8F+YSNN/ADE7O2c1RrU2exBjWbw1iQ='; //  - старый ключ
  //  private static $key = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDIxR5gPARFP/peY5MIPmqCblKuVZ4nl2UArCpDv5hhoqZ2mOlz7i3p+PilWYPYyt0aXLd17DgpjLEgGZoNs/IsvqOZywWOZjbyAx7xeA+hl/aAhkZdmr619iBjW1gROP79KQoVyH2VDUQZ2lqB2o1VJf4Z38RtAVoxZaYn0GNHhQIDAQAB';

    // шифрование
    public static function encode(&$data){

        $key     = base64_decode(self::$key, true);
        $result = openssl_encrypt($data, "aes-256-ecb", $key, 0);

        return $result;

    }

    // расшифровка
    public static function decode(&$data){
        $key     = base64_decode(self::$key, true);
        return openssl_decrypt($data, "aes-256-ecb", $key);
    }

    public static function checkSign($data,$signature){
        $path = Yii::getAlias('@frontend/web/');
        $certificateCApemContent = file_get_contents($path . 'SignPublicKey');
        $pubkeyid                = openssl_pkey_get_public($certificateCApemContent);

        $ok = openssl_verify($data, base64_decode($signature), $pubkeyid);

        if ($ok == 1) {
            $result = "ЭЦП корректна";
        } elseif ($ok == 0) {
            $result = "ЭЦП не корректна!";
        } else {
            $result = "Error: " . openssl_error_string();
        }

        openssl_free_key($pubkeyid);
        return $result;
    }

}