<?php
namespace common\helpers;

use Yii;
use SoapClient;
use SimpleXMLElement;
use SoapHeader;

class UpayHelper {

    //public static $wsdl = 'http://91.212.89.86:9212/STAPI/STWS?wsdl';
    public static $wsdl = 'https://api.upay.uz/STAPI/STWS?wsdl'; //- обращайтесь лучше к этому, тот скоро закроют
    private static $login = 'zmarket';
    private static $password = '3M@rk3t!';
    private static $key = '93DB85ED909C13838FF95CCFA94CEBD9';

    const FINE_TRAFFIC_POLICE = 238; // Штрафы ГУБДД
    const BEELINE = 40; // +998 90/91
    const UMS = 132; // Mobiuz (UMS) +998 97
    const USELL = 8; // +998 93/94
    const UZMOBILE = 163; // +998 95/99
    const PERFECTUM = 5; // +998 98

    const TPS = 50; //
    const COMNET = 140; //
    const EVO = 130; //
    const UZONLINE = 120; //
    const SARKOR = 1; //

    /**
    Sarkor Telecom - 1
    Sharq Telekom - 4
    SkyLine - 14
    TPS - 50
    UzOnline - 120
    EVO - 130
    Comnet - 140
    FiberNet - 149
    Sonet - 151
    Nano Telecom - 192
    Cron Telecom - 200
    FreeLink - 202
    East Stark TV - 211
    Spectr-IT Интернет - 217
    ISTV Интернет - 223
    Buzton Internet - 248
    Scientific technologies (UzScinet) - 264
    AIRNET internet - 317
    AllNet - 344
    DGT - 384
    SOLA Wi-Fi - 467
     */

    public static function isTel($service_id){
        $tels = ['40','132','5','8','163'];
        if (in_array($service_id, $tels)) {
            return $tel = 1;
        }
        return $tel = 0;
    }

    public static function getSrc($service_id){
        $img = [
            '40' => ['beeline.png','beeline'],
            '132' => ['Mobiuz.png','Mobiuz'],
            '5' => ['perfectum_n.png','perfectum'],
            '8' => ['uceluz.png','uceluz'],
            '163' => ['uzmobile.png','uzmobile'],

            '1' => ['sarkor.png','sarkor'],
            '50' => ['tps.png','tps'],
            '120' => ['uzmobile.png','uzonline'],
            '130' => ['evo.png','evo'],
            '140' => ['comnet.png','comnet'],

            '149' => ['fibernet.png','fibernet'],
            '223' => ['istvinternet.png','istvinternet'],
            '202' => ['freelink.png','freelink'],
            '4' => ['st.png','Sharq Telekom'],
            '248' => ['buztoninternet.png','buztoninternet'],

        ];

        $src = $img[$service_id][0];
        $alt = $img[$service_id][1];
        $img = [$src, $alt];
        return ($img);
    }

    private static function backOffice() {

        $wsdl = 'http://91.212.89.86:9212/STAPI/STWS?wsdl';

        return new SoapClient($wsdl, [
            'trace' => 1,
            'UserName' => self::$login,
            'Password' => self::$password,
            'StPimsApiPartnerKey' => self::$key,
            'exceptions' => 0,
            'cache_wsdl' => WSDL_CACHE_MEMORY,
            'stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ])
        ]);
    }

    /**
     * updated_at можете всегда ставить значения 0
     * category_id если все категории хотите получить, то значение 0
     * 1 = Мобильные операторы
     * 2 = Интернет провайдеры
     * 10 = Гос услуги
     */
    public static function getServiceList($category_id = 1){
        $client = self::backOffice();

        $result = $client->getServiceList([
            'getServiceListRequest' => [
            'StPimsApiPartnerKey' => self::$key,
            'CategoryId' => $category_id,
            'Update_at' => '0',
            'Version' => '',
            'Lang' => 'ru',
            ]
        ]);

        self::debug('getServiceList');
        self::debug($client);
        self::debug($result);

        return $result;

    }

    public static function getSum($account, $service_id){
        $client = self::backOffice();

        $result = $client->findPersonalAccount([
            'PersonalAccountRequest' => [
                'StPimsApiPartnerKey' => self::$key,
                'PersonalAccount' => $account,
                'UserCredentials' => [
                    'Login' => '15777777777',
                    'Password' => 'pay777',
                ],
                'ServiceId' => $service_id,
                'RegionCode' => '',
                'SubRegionCode' => '',
                'Version' => '',
                'Lang' => 'ru',
            ]
        ]);

        self::debug('getSum');
        self::debug($client);
        self::debug($result);

        /*if(isset($result->return->NamedParam['1']->Value)){
            return $result->return->NamedParam['1']->Value;
        }else{
            return $result->return->Description;
        }*/
        /*if(isset($result->return->NamedParam['1']->Value)){
            $sum = $result->return->NamedParam['1']->Value;
            $fullname = $result->return->FullName;
            $info = $result->return->NamedParam['0']->Value;
            $return = ['sum' => $sum, 'fullname' => $fullname, 'info' => $info];
            return $return;
        }else{
            $sum = 0;
            $fullname = 'fullname';
            $info = $result->return->Result->Description;
            $return = ['sum' => $sum, 'fullname' => $fullname, 'info' => $info];
            return $return;
        }*/

        return $result;

    }

    public static function BankPayment($service_id, $account, $amount_with_tiyin){
        $partner_trans_id = time();
        // md5('zmarket4090319463420000015929104553M@rk3t!'); - пример генерации для мобильной
        //md5(Username + serviceId + acсount + AmountWithTiyin + PartnerTransId + password)
        $token = md5(self::$login .  $service_id . $account . $amount_with_tiyin . $partner_trans_id . self::$password);

        $client = self::backOffice();
        $result = $client->bankPayment([

            'bankPaymentRequest' => [
                'StPimsApiPartnerKey' => self::$key,
                'AccessToken' => $token,
                'ServiceId' => $service_id,
                'RegionCode' => '',
                'SubRegionCode' => '',
                'Account' => $account,
                'Amount' => '',
                'BankTransId' => '',
                'Type' => '',
                'From' => '',
                'To' => '',
                'AmountWithTiyin' => $amount_with_tiyin,
                'PartnerTransId' => $partner_trans_id,
                'Lang' => 'ru',
            ]

        ]);

        self::debug('BankPayment');
        self::debug($client);
        self::debug($result);
        return $result;

    }

    public static function BankCheckAccount($service_id, $account, $amount_with_tiyin){
        //$token = md5(UserName + serviceId + regionCode + subRegionCode + account + Password);
        $token = md5(self::$login .  $service_id . $account  . self::$password);

        $client = self::backOffice();

        $result = $client->BankCheckAccount([  // работает
            'StPimsApiPartnerKey' => self::$key,
            'AccessToken' => $token,
            'RegionCode' => '',
            'SubRegionCode' => '',
            'Account' => self::$login,
            'ServiceId' => $service_id,
            'Lang' => 'ru',
        ]);

        self::debug('BankCheckAccount');
        self::debug($client);
        self::debug($result);
        return $result;
    }




    public static function debug( $data,$clear=false){
        if($clear) @unlink($_SERVER['DOCUMENT_ROOT'] .'/debug_upay.txt');
        $f = fopen($_SERVER['DOCUMENT_ROOT'] .'/debug_upay.txt','a');
        $data = date('d.m / H:i:s') . ':  ' . json_encode($data,256) . PHP_EOL;
        fwrite($f,$data);
        fclose($f);
    }



}