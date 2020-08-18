<?php
namespace common\helpers;

use Yii;

class PolisHelper {

    private static $login = 'ZMarket';


    // отправка заявки на создание полиса
    public static function getPolisForCredit($request_id,&$credit){

        $signature = ''; //uniqid();

        $input = json_encode([
                "original"=>[
                    "method"=>"GetPolisForCredit",
                    "requestId"=>"{$request_id}",
                    "aboutCredit"=>[
                         "sum"=>(int)$credit->price, // без тийинов!!!
                         "contractNumber"=>"{$credit->contract->id}",
                         "dateOfContract"=> date('d-m-Y',$credit->contract->created_at), //"01-01-2019"
                         "periodStart"=>date('d-m-Y',$credit->contract->date_start), //"01-01-2019",
                         "periodEnd"=>date('d-m-Y',$credit->contract->date_end), //"01-01-2020"
                    ],
                    "aboutBorrower"=>[
                         "clientId"=>"1",
                         "name"=>$credit->client->username . ' ' . $credit->client->lastname,
                         "individualOrentity"=>$credit->client->orentity,
                         "itn"=>$credit->client->inn,
                         "phone"=>$credit->client->phone,
                         "passportSeries"=>$credit->client->passport_serial,
                         "passportID"=>$credit->client->passport_id,
                         "passportIssueDate"=>date('d-m-Y',strtotime($credit->client->passport_date)), // "01-01-2019",
                         "passportIssuer"=>$credit->client->passport_issuer,
                         "address"=>$credit->client->address, // "ул Ахмад дониш дом 54 кв 2",
                         "region"=>$credit->client->region_id
                    ]
                ],
                "signature"=>"{$signature}"
        ],JSON_UNESCAPED_UNICODE);

        $data_string = CryptHelper::encode($input);

        $ch = curl_init(self::$url);
        curl_setopt($ch, CURLOPT_USERPWD, self::$login.':' . self::$password );
        curl_setopt($ch, CURLOPT_POST , true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode( CryptHelper::decode($result) , true);

        //CryptHelper::checkSign('test',$result['signature']);
        self::debug('GetPolisForCredit');
        self::debug($input);
        self::debug($result);
        //return json_encode(['status'=>0,'info'=>Yii::t('app','Ошибка. ' . json_encode($data,JSON_UNESCAPED_UNICODE))]);
        return $result;

    }

    // проверка полиса
    public static function сheckTransaction($request_id,&$credit){

        $signature = ''; //uniqid();

        $input = json_encode([
                "original"=>[
                    "method"=>"CheckTransaction",
                    "requestId"=>$request_id,
                     "sum"=>(int)$credit->price,
                     "contractNumber"=>"{$credit->contract->id}",
                     "dateOfContract"=> date('d-m-Y',$credit->contract->created_at),
                ],
                "signature"=>"{$signature}"
        ],JSON_UNESCAPED_UNICODE);

        $data_string = CryptHelper::encode($input);

        $ch = curl_init(self::$url);
        curl_setopt($ch, CURLOPT_USERPWD, self::$login.':' . self::$password );
        curl_setopt($ch, CURLOPT_POST , true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode( CryptHelper::decode($result), true);

        self::debug('CheckTransaction');
        self::debug($input);
        self::debug($result);
        //CryptHelper::checkSign('test',$result['signature']);

        return $result;

    }

    // проверка полиса
    public static function sendCustomerList($request_id, $bordero_id, $maxPackageNum, $curPackageNum,&$clients){
        $signature = '';
        /*
        пример массива с клиентами должниками
        $clients => [
               "clientId ": "1",
               "polisSeries": "VUS",
               "polisNumber": 20845,
               "sum": 7000.36,
               "date": '01-01-2019',
            ],
        */

        //print_r($clients) ;
        //return json_encode($clients);
        $input = json_encode([
                "original"=>[
                    "method"=>"SendCustomerList",
                    "requestId"=>"{$request_id}",
                    "uniqueBorderoID"=>$bordero_id,
                    "maxPackageNum"=>$maxPackageNum,
                    "curPackageNum"=>$curPackageNum,
                    "polis" => $clients
                ],
                "signature"=>"{$signature}"
        ],JSON_UNESCAPED_UNICODE);

        //return $input;

        $data_string = CryptHelper::encode($input);
        //return $data_string;

        $ch = curl_init(self::$url);
        curl_setopt($ch, CURLOPT_USERPWD, self::$login.':' . self::$password );
        curl_setopt($ch, CURLOPT_POST , true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode( CryptHelper::decode($result), true);

        self::debug('send customers');
        self::debug($input);
        self::debug($result);
        //CryptHelper::checkSign('test',$result['signature']);

        //return $result['error']['code']==0 ? true : false;
        return $result;

    }


    public static function debug( $data,$clear=false){
        if($clear) @unlink($_SERVER['DOCUMENT_ROOT'] .'/debug_polis.txt');
        $f = fopen($_SERVER['DOCUMENT_ROOT'] .'/debug_polis.txt','a');
        $data = date('d.m / H:i:s') . ':  ' . json_encode($data,256) . PHP_EOL;
        fwrite($f,$data);
        fclose($f);
    }



}