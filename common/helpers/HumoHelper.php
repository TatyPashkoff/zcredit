<?php


namespace common\helpers;

use Yii;
use SoapClient;
use SimpleXMLElement;
use SoapHeader;



class HumoHelper
{

    private static $url_test_scoring = "https://192.168.35.123:8443/ws/services/Issuing"; // test
    private static $url_scoring = "https://192.168.35.35:8443/ws/services/Issuing"; // prod
    private static $url_test_balance = "https://192.168.35.126:6677"; // test
    private static $url_balance = "https://192.168.35.22:6677"; // prod
    private static $url_test_phone = "http://192.168.35.128:13010"; // резервный, не тест теперь стал
    private static $url_phone = "http://192.168.35.27:13011";
    //private static $url_phone = "http://192.168.35.150:13010"; // теперь этот основной
    private static $url_discard_test = "http://192.168.35.126:11210";
    private static $url_discard = "http://192.168.35.22:11210";

    private static $merchant_id = '010950513654902';
    private static $terminal_id = '096106Y7';

    private static $login_test = 'aab';
    private static $password_test = '1234';
    private static $login = 'bil_zmarket';
    private static $password = '&2(&VeJJ';


    //private static $limit = '1000000';


    private function backOffice() {
        $wsdl = Yii::getAlias('@frontend/web/Issuing_PP.wsdl');  // - wsdl

        ini_set('default_socket_timeout', 300);
        ini_set('soap.wsdl_cache_enabled',0);
        ini_set('soap.wsdl_cache_ttl',0);

        $login_test = 'aab'; // test
        $password_test = '1234'; // test
        $login = 'bil_zmarket';
        $password = '&2(&VeJJ';

        return new SoapClient('file://' . $wsdl, [
            'trace' => 1,
            'location' => self::$url_scoring,
            'login' => self::$login,
            'password' => self::$password,
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

    // проверка смс информирования - получение client
    private function humoClient($card, $bank_c){
        $client = self::backOffice();
        $ex_session = 'ZMARKET-T' . time();

        $result = $client->listCustomers([  // работает
            'BANK_C' => $bank_c,
            'GROUPC' => '01',
            'EXTERNAL_SESSION_ID' => $ex_session,
        ], [
            'CARD' => $card,
            'BANK_C' => $bank_c,
            'LOCKING_FLAG' => 1
        ]);

        //Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        //$headers = Yii::$app->response->headers;
        //$headers->add('Content-Type', 'text/xml');
        //return $client->__getLastResponse();
        //print_r($result);
        // print_r($result['Details']->row->item[0]->value);
        return $result['Details']->row->item[0]->value;
    }


    // scoring
    public static function humoScoring($card,$bank_c,$limit){
        $client = self::backOffice();
        $ex_session = 'ZMARKET-T' . time();

        $date = date_parse_from_format("Y.n.j H:iP", date('Y-m-d\TH:i:s'));
        $y = $date['year'];
        $m = $date['month'] + 1;
        $d = '01T00:00:00';
        $date_start = $y - 1 . '-' . '0' . $m . '-' . $d;
        $date_end = $y . '-' . '0' . $m . '-' . $d;

        $result = $client->queryTransactionHistory([
            'BANK_C' => $bank_c,
            'GROUPC' => '01',
            'EXTERNAL_SESSION_ID' => $ex_session,
        ], [
            'CARD' => $card,
            'BEGIN_DATE' => $date_start, //'2019-05-01T00:00:00', // date('Y-m-d\TH:i:s')
            'END_DATE' => $date_end, //'2020-05-01T00:00:00', //date('Y-m-d\TH:i:s')
            'BANK_C' => $bank_c,
            'GROUPC' => '01',
            'LOCKING_FLAG' => 1
        ]);

        self::debug('scoring');
        self::debug($client);
        self::debug($result);

        //Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        //$headers = Yii::$app->response->headers;
        //$headers->add('Content-Type', 'text/xml');
        // return $client->__getLastResponse();
        // print_r ($result);
        $tran_type = ['110','111','113','114','115','206','208','225','227','229','314','315','316','614',
            '11b','11c','11C','11E','11G','11L','11V',
            '31a','31A','31b','31B','31D','31E','31G','31g','31K','31R','31W',
            '51a','51c','51G'];

        $date = date_parse_from_format("Y.n.j H:iP", date('Y-m-d\TH:i:s'));
        $scoring= [];
        $y = $date['year'] - 1;
        $m = $date['month'] + 1;

        $date = $y . '.';
        for($i=0;$i<13;$i++){
            $keys[] = $date.$m;
            $m += 1;
            if($m > 12){
                $m = 1;
                $y += 1;
                $date = $y . '.';
            }
        }
        $scoring = array_fill_keys($keys,  0);

        $arr = [];
        $i = 0;
        foreach($result['Details']->row as $row){
            //print_r ($row);
            foreach($row->item as $item => $value){
                if($value->name == 'TRAN_TYPE') {
                    $arr[$i][] =  $value->value;
                }
                if($value->name == 'TRAN_AMT') {
                    $arr[$i][] = $value->value;
                }
                if($value->name == 'TRAN_DATE_TIME') {
                    $arr[$i][] = $value->value;
                }
            }
            $i++;
        }
        //print_r($arr);
        foreach ($arr as $item => $value) {
            // var_dump($value[0] . ' - ');
            if (in_array($value[0], $tran_type)) { // если это пополнение
                //print_r($value[0] . ' : ' . $value[1] . ' : ' . $value[2] . '  -  ');
                if ($value[2]) {
                    $date = date_parse_from_format("Y.n.j H:iP", $value[2]);
                    //$date = strval($date['year'] . '.' . $date['month'] . '.' . $date['day']);
                    $date = strval($date['year'] . '.' . $date['month']);
                    // var_dump($date . ' + ' . $value[0]  );
                    foreach ($scoring as $s_date => $val) {
                        //var_dump($s_date);
                        if ($s_date == $date) {
                            // var_dump($s_date . ' = ' . $date . ' = ' . $value[1]);
                            $scoring[$s_date] += $value[1];
                        }
                    }
                }
            }
        }

        //print_r($scoring);
        foreach($scoring as $item => $value){
            //$value = $value >= self::$limit ? true : false;
            $value = $value >= $limit ? true : false;
            $scoring[$item] = $value;
        }
        return  json_encode($scoring);

    }

    // scoring exp 8443
    //  для получения правильного срока, вы можете сделать запрос на сервис с портом 8443
    public function humoGetExp($card,$bank_c){
        $client = self::backOffice();
        $ex_session = 'ZMARKET-T' . time();

        $result = $client->queryTransactionHistory([
            'BANK_C' => $bank_c,
            'GROUPC' => '01',
            'EXTERNAL_SESSION_ID' => $ex_session,
        ], [
            'CARD' => $card,
            'BEGIN_DATE' => '2020-05-01T00:00:00', // date('Y-m-d\TH:i:s')
            'END_DATE' => '2020-06-01T00:00:00', //date('Y-m-d\TH:i:s')
            'BANK_C' => $bank_c,
            'GROUPC' => '01',
            'LOCKING_FLAG' => 1
        ]);

        /*Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');*/
        //return $client->__getLastRequest();

        self::debug('get-exp');

        //self::debug($result);
        print_r($result);



        foreach($result['Details']->row as $row=>$item){

            //print_r ($item );
            foreach($item as $items => $value){
                foreach($value as $val){
                    //print_r ($val );
                    if($val->name == 'EXP_DATE') {
                        self::debug('EXP_DATE');
                        self::debug($val->value);
                        print_r($val->value) ;
                    }}
            }
        }


    }

    // balance
    public static function humoBalance($card){

        $request = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:IIACardServices">
    <soapenv:Header/>
    <soapenv:Body>
	<urn:getCardAccountsBalance>
	<primaryAccountNumber>{$card}</primaryAccountNumber>	
	</urn:getCardAccountsBalance>
    </soapenv:Body>
</soapenv:Envelope> 
XML;

        $curl = curl_init(self::$url_balance);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //curl_setopt($curl, CURLOPT_USERPWD, $id_test . ":" . $password_test);
        curl_setopt($curl, CURLOPT_USERPWD, self::$login . ":" . self::$password);
        curl_setopt($curl, CURLOPT_URL, self::$url_balance);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array('Content-Type: text/xml; charset=utf-8',
                'Content-Length: ' . strlen($request)));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        $verbose = fopen('php://temp', 'w+');
        curl_setopt($curl, CURLOPT_STDERR, $verbose);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); //Автоматический редирект
        curl_setopt($curl, CURLOPT_SSLVERSION, 'CURL_SSLVERSION_TLSv1_2');
        curl_setopt($curl, CURLOPT_SSLVERSION, 6);
        $result = curl_exec($curl);
        self::debug('balance');
        self::debug($request);
        self::debug($result);

//return $result;

            $result = simplexml_load_string($result);
            $result->registerXPathNamespace("SOAP-ENV encoding=UTF-8", "http://www.w3.org/2003/05/soap-envelope");
            $balance = $result->xpath('//balance')[0];
            $balance = $balance->availableAmount;
            return strval($balance);
    }

    //  cmc информирование - phone + fio
    public function humoSmsBanking($card, $bank_c){

        $client = self::humoClient($card, $bank_c);
        $card = $client . '-' . $bank_c;


        $request = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:AccessGateway">
    <soapenv:Header/>
    <soapenv:Body>
        <urn:export>
            <cardholderID>{$card}</cardholderID>
            <bankid>MB_STD</bankid>
        </urn:export>
    </soapenv:Body>
</soapenv:Envelope>
XML;

        $curl = curl_init(self::$url_phone);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, self::$login . ":" . self::$password);
        curl_setopt($curl, CURLOPT_URL, self::$url_phone);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array('Content-Type: text/xml; charset=utf-8',
                'Content-Length: ' . strlen($request)));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        $verbose = fopen('php://temp', 'w+');
        curl_setopt($curl, CURLOPT_STDERR, $verbose);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); //Автоматический редирект
        curl_setopt($curl, CURLOPT_SSLVERSION, 'CURL_SSLVERSION_TLSv1_2');
        curl_setopt($curl, CURLOPT_SSLVERSION, 6);

        $result = curl_exec($curl);
        self::debug('sms-banking');
        self::debug($request);
        self::debug($result);
        $result = simplexml_load_string($result)->xpath('//ag:exportResponse')[0];
        $phone_humo = strval((int)$result->Phone->msisdn);
        $exp = strval($result->Card->expiry);
        $exp = preg_replace('/[^0-9]/', '', $exp);
        $fio = strval($result->cardholderName);
        $inform = [$phone_humo, $fio, $exp];
        return $inform;

    }

    public static function HumoDiscard($card_h,$bank_c,$exp,$amount){
        $card = 9860 . $bank_c . $card_h;
        $merchant_id = self::$merchant_id;
        $terminal_id = self::$terminal_id;

        $bank = [
            '01'=>'Ipoteka', '21'=>'Turkiston', '26'=>'Infin', '02'=>'UzPSB', '03'=>'Agrobank', '04'=>'Asaka',
            '08'=>'Xalqbank', '12'=>'NBU', '13'=>'Mkredit', '14'=>'Savdogar', '15'=>'Turon', '16'=>'Hamkor',
            '17'=>'IpakYuli', '18'=>'Trastbank', '19'=>'Aloqa', '20'=>'KDB', '23'=>'Universal', '24'=>'Ravnaq',
            '25'=>'Davr', '27'=>'OFB', '28'=>'HiTech', '29'=>'UTBank', '30'=>'Saderat', '09'=>'AsiaAllianc',
            '06'=>'KishloqKB', '32'=>'MadadInvest', '10'=>'Kapital', '31'=>'AgroExpBank'
        ];

        foreach($bank as $k => $v){
            if($k == $bank_c){
                $centre_id = $v;
            }
        }

        $request = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:ebppif1="urn:PaymentServer">
 <SOAP-ENV:Body>
    <ebppif1:Payment>
     <billerRef>SOAP_SMS</billerRef>
     <payinstrRef>SOAP_SMS</payinstrRef>
       <details>
            <item>
			<name>pan</name>
			<value>{$card}</value>
			</item>
            <item>
			<name>expiry</name>
			<value>{$exp}</value>
			</item>
            <item>
			<name>ccy_code</name>
			<value>860</value>
			</item>
            <item>
			<name>amount</name>
			<value>{$amount}</value>
			</item>
            <item>
			<name>merchant_id</name>
			<value>{$merchant_id}</value>
			</item>
			<item>
			<name>terminal_id</name>
			<value>{$terminal_id}</value>
			</item>
            <item>
			<name>point_code</name>
			<value>100010104110</value>
			</item>
            <item>
			<name>centre_id</name>
			<value>{$centre_id}</value>
			</item>
		</details>
	<paymentOriginator>user</paymentOriginator>
	</ebppif1:Payment>
 </SOAP-ENV:Body>
 </SOAP-ENV:Envelope>
XML;

        $curl = curl_init(self::$url_discard);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, self::$login . ":" . self::$password);
        curl_setopt($curl, CURLOPT_URL, self::$url_discard);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array('Content-Type: text/xml; charset=utf-8',
                'Content-Length: ' . strlen($request)));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        $verbose = fopen('php://temp', 'w+');
        curl_setopt($curl, CURLOPT_STDERR, $verbose);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_SSLVERSION, 'CURL_SSLVERSION_TLSv1_2');
        curl_setopt($curl, CURLOPT_SSLVERSION, 6);
        $result = curl_exec($curl);

        self::debug('discard');
        self::debug($request);
        self::debug($result);
        //return $result;
        $result = simplexml_load_string($result)->xpath('//ebppif1:PaymentResponse')[0];
        $payment_id = strval((int)$result->paymentID);

        //return $payment_id;
        if($payment_id){
            $confirm = self::HumoDiscardConfirm($payment_id);
            return $confirm;
        }
    }

    private function HumoDiscardConfirm($payment_id){

        $request = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:ebppif1="urn:PaymentServer">
 <SOAP-ENV:Body>
   <ebppif1:Payment>
	 <paymentID>{$payment_id}</paymentID>
	 <confirmed>true</confirmed>
	 <finished>true</finished>
	 <paymentOriginator>user</paymentOriginator>
   </ebppif1:Payment>
 </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
XML;
        $curl = curl_init(self::$url_discard);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, self::$login . ":" . self::$password);
        curl_setopt($curl, CURLOPT_URL, self::$url_discard);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array('Content-Type: text/xml; charset=utf-8',
                'Content-Length: ' . strlen($request)));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        $verbose = fopen('php://temp', 'w+');
        curl_setopt($curl, CURLOPT_STDERR, $verbose);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_SSLVERSION, 'CURL_SSLVERSION_TLSv1_2');
        curl_setopt($curl, CURLOPT_SSLVERSION, 6);
        $res = curl_exec($curl);
        self::debug('confirm');
        self::debug($request);
        self::debug($res);
        //return $result;
        $result = simplexml_load_string($res);
        if ($result = $result->xpath('//ebppif1:PaymentResponse')[0]) {
            if (isset($result->paymentID)) {
                $payment_id = strval((int)$result->paymentID);
                $humo_arr = [];
                $humo_arr['payment_id'] = $payment_id;
                foreach ($result->details->item as $k => $v) {
                    $humo_arr[strval($v->name)] = strval($v->value);
                }

                unset($humo_arr['pan']);
                unset($humo_arr['expiry']);
                unset($humo_arr['ccy_code']);
                unset($humo_arr['amount']);
                unset($humo_arr['auth_msg_ref1']);
                return json_encode($humo_arr);
            }
        } else {
            $result = simplexml_load_string($res);
            $result->registerXPathNamespace("SOAP-ENV", "http://www.w3.org/2003/05/soap-envelope");
            $error = (int)$result->xpath('//error')[0];
            return $error;
        }


    }

    public function HumoReverse($centre_id,$payment_id){
        $merchant_id = self::$merchant_id;
        $terminal_id = self::$terminal_id;

        $request = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:PaymentServer">
<soapenv:Header/>
  <soapenv:Body>
	<urn:ReturnPayment>
		<paymentID>{$payment_id}</paymentID>
		<item>
		<name>merchant_id</name>
		<value>{$merchant_id}</value>
		</item>
		<item><name>centre_id</name>
		<value>{$centre_id}</value>
		</item>
		<item>
		<name>terminal_id</name>
		<value>{$terminal_id}</value>
		</item>
		<paymentOriginator>user</paymentOriginator>
	</urn:ReturnPayment>
</soapenv:Body>
</soapenv:Envelope>
XML;

        $curl = curl_init(self::$url_discard);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, self::$login . ":" . self::$password);
        curl_setopt($curl, CURLOPT_URL, self::$url_discard);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array('Content-Type: text/xml; charset=utf-8',
                'Content-Length: ' . strlen($request)));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        $verbose = fopen('php://temp', 'w+');
        curl_setopt($curl, CURLOPT_STDERR, $verbose);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_SSLVERSION, 'CURL_SSLVERSION_TLSv1_2');
        curl_setopt($curl, CURLOPT_SSLVERSION, 6);
        $result = curl_exec($curl);
        self::debug('reverse');
        self::debug($request);
        self::debug($result);
        return $result;
    }

    public static function HumoReco(){
        $login = self::$login;
        $terminal_id = self::$terminal_id;

        $request = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
 <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:SOAPENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:ebppif1="urn:PaymentServer">
 <SOAP-ENV:Body>
 <ebppif1:Payment>
 <billerRef>SOAP_RECO</billerRef>
 <payinstrRef>SOAP_RECO</payinstrRef>
 <details>
 <item>
 <name>terminal_id</name>
 <value>{$terminal_id}</value>
 </item>
 </details>
 <paymentOriginator>{$login}</paymentOriginator>
 </ebppif1:Payment>
 </SOAP-ENV:Body>
 </SOAP-ENV:Envelope> 
XML;

        $curl = curl_init(self::$url_discard);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, self::$login . ":" . self::$password);
        curl_setopt($curl, CURLOPT_URL, self::$url_discard);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array('Content-Type: text/xml; charset=utf-8',
                'Content-Length: ' . strlen($request)));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        $verbose = fopen('php://temp', 'w+');
        curl_setopt($curl, CURLOPT_STDERR, $verbose);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_SSLVERSION, 'CURL_SSLVERSION_TLSv1_2');
        curl_setopt($curl, CURLOPT_SSLVERSION, 6);
        $result = curl_exec($curl);
        self::debug('Reco');
        self::debug($request);
        self::debug($result);
        //return $result;
        $result = simplexml_load_string($result)->xpath('//ebppif1:PaymentResponse')[0];
        $payment_id = strval((int)$result->paymentID);
        $result = self::HumoRecoFinish($payment_id);
        return $result;
    }

    private function HumoRecoFinish($payment_id){
        $login = self::$login;

        $request = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
 <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:ebppif1="urn:PaymentServer">
 <SOAP-ENV:Body>
 <ebppif1:Payment> 
 <paymentID>{$payment_id}</paymentID>
 <confirmed>true</confirmed>
 <finished>true</finished>
 <paymentOriginator>{$login}</paymentOriginator>
 </ebppif1:Payment>
 </SOAP-ENV:Body>
 </SOAP-ENV:Envelope> 
XML;

        $curl = curl_init(self::$url_discard);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, self::$login . ":" . self::$password);
        curl_setopt($curl, CURLOPT_URL, self::$url_discard);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array('Content-Type: text/xml; charset=utf-8',
                'Content-Length: ' . strlen($request)));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        $verbose = fopen('php://temp', 'w+');
        curl_setopt($curl, CURLOPT_STDERR, $verbose);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_SSLVERSION, 'CURL_SSLVERSION_TLSv1_2');
        curl_setopt($curl, CURLOPT_SSLVERSION, 6);
        $result = curl_exec($curl);
        self::debug('RecoFinish');
        self::debug($request);
        self::debug($result);
        return $result;
    }


    public static function debug( $data,$clear=false){
        if($clear) @unlink($_SERVER['DOCUMENT_ROOT'] .'/debug_humo.txt');
        $f = fopen($_SERVER['DOCUMENT_ROOT'] .'/debug_humo.txt','a');
        $data = date('d.m / H:i:s') . ':  ' . json_encode($data,256) . PHP_EOL;
        fwrite($f,$data);
        fclose($f);
        if($clear) @unlink($_SERVER['DOCUMENT_ROOT'] .'/debug_humo1.txt');
        $f = fopen($_SERVER['DOCUMENT_ROOT'] .'/debug_humo1.txt','a');
        $data = date('d.m / H:i:s') . ':  ' . $data . PHP_EOL;
        fwrite($f,$data);
        fclose($f);
    }

}