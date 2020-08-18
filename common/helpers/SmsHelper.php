<?php
namespace common\helpers;

use Yii;

class SmsHelper {

    private static $login = 'zmarket';



    // отправка смс с текстом на номер
    public static function sendSms($phone,$text){


        if($_SERVER['SERVER_NAME']=='crm1.loc'){ // для теста
            return false;
        }




        $sms = [
            'messages' => [
                [
                    "recipient" => $phone,
                    "message-id" => "dos".time(),
                    'sms' => [
                        "originator" =>  "3700",
                        'content' => [
                            'text' => $text
                        ],

                    ],
                ],
            ]
        ];

        $data_string = json_encode($sms);

        $ch = curl_init('http://91.204.239.44/broker-api/send');
        curl_setopt($ch, CURLOPT_USERPWD,self::$login.':' . self::$password);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        curl_close($ch);
        UtilsHelper::debug('Отправка смс: '.$phone . ' ' . $text);
        UtilsHelper::debug($result);

    }

    // случ число
    public static function generateCode($len=4){

        if($len<4 || $len>10) $len=4;

        $str = str_shuffle('0123456789123456789123456789');

        return mb_substr($str,1,$len);

    }

	
}