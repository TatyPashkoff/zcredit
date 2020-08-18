<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "paymo".
 */

class Paymo extends \yii\db\ActiveRecord
{

    private static $url_token = 'https://api.pays.uz:8243/token';
    private static $url_scoring = 'https://api.pays.uz:8243/scoring/get-monthly';

    private static $key = 'CyTRhrbG8E4sv2pz6jiiUkj98p4a';
    private static $secret = '9Vf5Pali74Wyf9I3KnMOb_54Issa';

    const STORE_ID = 1;



    // получение токена
    public static function getToken(){

        $data = 'grant_type=client_credentials';

        $ch = curl_init(self::$url_token);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, self::$key . ':' . self::$secret);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-type: application/x-www-form-urlencoded',
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result, true);
        return $result['access_token'];

    }

    // проверка по месяцам
    public static function scoring($token,$summ='100000000',$card='8600312905897001',$exp=null,$percent=50){

        $data = json_encode([
            'card_number' => $card,
            'card_expiry' => $exp,
            'amount' => $summ,
            'percent' => $percent,
        ]);

        $ch = curl_init(self::$url_scoring);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_POST , true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);
        $result = curl_exec($ch);

        curl_close($ch);

        return json_decode($result,true);

    }

    //Добавление карты партнера для получения токена
    public static function addCard($card='8600312905897001',$exp='2302')
    {
        /*  return
            card_id         ID карты в системе PAYMO
            pan         Маскированный номер карты
            expiry         Дата истечения карты в формате YYmm
            card_holder         Имя картодержателя
            phone         Номер телефона, привязанный к карте
        */

        $token = self::getToken();

        self::debug($token);

        $data = json_encode([
            'card_number' => $card, // тестовая карта: 8600312905897001  23 02
            'card_expiry' => $exp, // ггмм
        ]);

        $ch = curl_init(self::$url_scoring);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_POST , true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);
        $result = curl_exec($ch);
        self::debug($result);
        print_r($result); exit;

        curl_close($ch);
        $result = json_decode($result,true);

        return $result;

        $scoring_data['Scoring'] = [
            'token' => $result['result']['card_id'], // ID карты в системе PAYMO
            'pan' => $result['result']['pan'], //Маскированный номер карты
            'exp' => $result['result']['expiry'],
            'phone' => $result['result']['phone'],
            'fullname' => $result['result']['card_holder'],
            'balance' => 0,
            'sms' => 0,
        ];

        return $scoring_data;

    }

    // Создание рекуррентного платежа с отправкой смс для подтверждения
    public static function paySheduleCreate($user_id,$phone,$card_id,$date_start,$date_end)
    {
        $token = self::getToken();

        $data = json_encode([
            'payment'=> [
                'store_id'=> self::STORE_ID,
                'date_start'=> $date_start . 'T08:00:00', //'2019-12-16T08:00:00'
                'date_finish'=> $date_end . 'T08:00:00', //'2020-12-16T08:00:00'
                'account'=> $user_id,
                'cards'=> [$card_id],
                'pay_day'=> '1',
                'pay_time'=> '8:00',
                'login'=> $phone,
                'repeat_interval'=> 5, // в часах
                'repeat_low_balance'=> false,
                'paym_tech'=> 6,
                'repeat_times'=> 10 // кол-во дней для повтора
            ],
            'lang'=> 'ru'
        ]);

        $ch = curl_init( self::$url_scoring );
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_POST , true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);
        $result = curl_exec($ch);

        curl_close($ch);

        $result = json_decode($result,true);
        return $result['sheduler_id'];
    }

    // Подтверждение рекуррентного платежа
    // $sheduler_id - id рекуррентного платежа
    // code- смс код отправленный paymo кдиенту карты
    public static function paySheduleConfirm($sheduler_id,$code){

        $token = self::getToken();

        $data = json_encode([
            'sheduler_id'=>$sheduler_id,
            'otp'=> $code
        ]);

        $ch = curl_init(self::$url_scoring);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_POST , true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);
        $result = curl_exec($ch);

        curl_close($ch);
        $result = json_decode($result,true);
        return $result['sheduler_id'];
    }

    public static function debug( $data,$clear=false){
        if($clear) @unlink($_SERVER['DOCUMENT_ROOT'] .'/debug_paymo.txt');
        $f = fopen($_SERVER['DOCUMENT_ROOT'] .'/debug_paymo.txt','a');
        $data = date('d.m / H:i:s') . ':  ' . json_encode($data,256) . PHP_EOL;
        fwrite($f,$data);
        fclose($f);
    }





}
