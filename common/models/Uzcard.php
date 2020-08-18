<?php

namespace common\models;

use common\helpers\HumoHelper;
use common\helpers\SmsHelper;
use Yii;




/**
 * This is the model class for table "uzcard".
 *
 * @property integer $id
    
 * @property integer $created_at
    
 * @property integer $payment_date
    
 * @property integer $client_id
    
 * @property string $summ
    
 * @property integer $transaction_id
    
 * @property integer $status
    
 * @property string $order_num
    
 */
class Uzcard extends \yii\db\ActiveRecord
{


    const LOGIN = 'zmarket';
    const LOGIN_TEST = 'zmarket';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'uzcard';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'payment_date', 'client_id', 'transaction_id','credit_id'], 'integer'],
            [['summ'], 'number'],
            [['status'], 'string', 'max' => 1],
            //[['order_num'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => Yii::t('app','Создан'),
            'payment_date' => Yii::t('app','Дата оплаты'),
            'client_id' => Yii::t('app','Клиент'),
            'summ' => Yii::t('app','Сумма'),
            'transaction_id' => Yii::t('app','ID транзакции'),
            'status' => Yii::t('app','Статус'),
            'order_num' => Yii::t('app','Номер договора'),
        ];
    }

    public function getClient(){
        return $this->hasOne(User::className(),['id'=>'client_id'])->where(['role'=>User::ROLE_CLIENT]);
    }

    public function updateModel($new=false){

        $post = Yii::$app->request->post();

        if($this->load($post) ) {

            if( $new ){ // если создается только один раз при создании
                $this->created_at = time();
            }

            if( !$this->save() ){
                Yii::$app->session->setFlash('info-error','Ошибка при сохранении!');
                return false;
            }

            Yii::$app->session->setFlash('info-success','Сохранение успешно завершено!');

            return true;
        }
        return false;

    }

    // автосписание средств по CRON  - нужно тестить!!!
    // автопогашение просроченных кредитов по конкретному клиенту - боевой
    public function autoDiscard($user_id = null)
    {

        // поиск всех неоплаченных платежек, просроченные по времени для автоснятия средств
        if ($credit_history = CreditHistory::find()->with(['credit', 'client', 'contract'])->where(['<=', 'credit_date', time()])->andWhere(['payment_status' => '0'])->orderBy('credit_date')->All()) {

            foreach ($credit_history as $credit_item) { // по всем просроченным платежкам

                // поиск заданного клиента
                if (!is_null($user_id) && $user_id != $credit_history->client->id ) continue;
                if ($credit_item->credit->user_confirm == 0 ) continue; // пропустить неподтвержденные кредиты

                if ($user = $credit_item->client) {

                    $credit_item_price = $credit_item->price; // эту сумму отправляем в узкард

                    // списываем часть с лицевого счета
                    if ($user->summ > 0 &&  $user->summ < $credit_item->price) {
                        $credit_item_price = $credit_item->price - $user->summ;   // остаток суммы списания, который нужно списать с карты
                        $summ = $user->summ;
                        $credit_item->credit->credit -= $user->summ; // уменьшение задолженности
                        $credit_item->price = $credit_item->price - $summ;  // остаток задолженности
                        $credit_item->payment_status = 0;  // списалась часть суммы
                        $credit_item->payment_date = time();
                        $credit_item->payment_type = Payment::PAYMENT_TYPE_BILLING;
                        $user->summ = 0;  // снятие средств у пользователя в кошельке в кабинете zmarket

                        if ($credit_item->save() && $user->save() && $credit_item->credit->save()) {

                            $billing_payments = new BillingPayments();
                            $billing_payments->created_at = time();
                            $billing_payments->credit_item_id = $credit_item->id;
                            $billing_payments->credit_id = $credit_item->credit->id;
                            $billing_payments->user_id = $user->id;
                            $billing_payments->summ = $summ;
                            $billing_payments->debt = $credit_item_price;
                            $billing_payments->status = 1;
                            $billing_payments->save();

                            $info = '';
                            if ((int)$credit_item->credit->credit == 0) {
                                $credit_item->credit->status = Credits::PAYMENT_STATUS_PAYED;
                                $credit_item->credit->save();
                                $info = ' ' . Yii::t('app', 'Vash kredit polnostyu pogashen! S Uvazheniem, ZMARKET');
                            }else{
                                $info = ' ' . Yii::t('app', 'S Uvazheniem, ZMARKET');
                            }

                            SmsHelper::sendSms($user->phone, Yii::t('app', 'S Vashego licevogo scheta spisano '. $summ . '. Tekushaya zadolzhennost '  . $credit_item_price .  ' sum. ' . $info));
                            // log cvc
                            self::debug('sms');
                            self::debug($user->phone, Yii::t('app', 'Zmarket. Списание средств на сумму ') . $summ . $info);
                        }

                    }

                    // закрываем месяц с лицевого счета
                    if ($user->summ >= $credit_item->price) {
                        $credit_item->credit->credit -= $credit_item->price; // уменьшение задолженности
                        $user->summ -= $credit_item->price; // снятие средств у пользователя в кошельке в кабинете zmarket
                        $credit_item_price = 0; // ничего не отправлем в узкард

                        // сохранить все расчеты и закрыть месяц
                        $bp_price = $credit_item->price;
                        $credit_item->price = 0;  // остаток задолженности
                        $credit_item->payment_status = 1;  // списалась вся сумма
                        $credit_item->payment_date = time();
                        $credit_item->payment_type = Payment::PAYMENT_TYPE_BILLING;
                        if ($credit_item->save() && $user->save() && $credit_item->credit->save()) {

                            $billing_payments = new BillingPayments();
                            $billing_payments->created_at = time();
                            $billing_payments->credit_item_id = $credit_item->id;
                            $billing_payments->credit_id = $credit_item->credit->id;
                            $billing_payments->user_id = $user->id;
                            $billing_payments->summ = $bp_price;
                            $billing_payments->debt = $credit_item->price;
                            $billing_payments->status = 1;
                            $billing_payments->save();

                            $info = '';
                            if ((int)$credit_item->credit->credit == 0) {
                                $credit_item->credit->status = Credits::PAYMENT_STATUS_PAYED;
                                $credit_item->credit->save();
                                $info = ' ' . Yii::t('app', 'Vash kredit polnostyu pogashen! S Uvazheniem, ZMARKET');
                            }else{
                                $info = ' ' . Yii::t('app', 'S Uvazheniem, ZMARKET');
                            }

                            SmsHelper::sendSms($user->phone, Yii::t('app', 'S Vashego licevogo scheta spisano ' . $bp_price . '. Tekushaya zadolzhennost '  . $credit_item_price .  ' sum. ' . $info));
                            // log cvc
                            self::debug('sms');
                            self::debug($user->phone, Yii::t('app', 'Zmarket. Списание средств на сумму ') . $bp_price . $info);
                        }

                    }

                    // если еще осталась сумма списания, снимаем с карты
                    if ($credit_item_price > 0 && $credit = $credit_item->credit) {
                        $credit_item_price = $credit_item_price * 100;  // сумма в тийинах
                        $payment_status = 1;

                        // проверить баланс карты, если баланс меньше суммы списания, снять баланс
                        // humo или uzcard
                        if($user->auto_discard_type == 1) {
                            $balance = $this->cardsGet($user);
                            $balance = $balance['result'][0]['balance'];
                        }
                        if($user->auto_discard_type == 2) {
                            $scoring = Scoring::find()->where(['user_id' => $user->id])->one();
                            $card = '9860' . $scoring->bank_c . $scoring->card_h;
                            $balance = HumoHelper::humoBalance($card);
                        }
                        $debt_balance = 0;

                        if($balance > 10000 && $balance < $credit_item_price){
                            $debt_balance = $credit_item_price - $balance; // остаток задолженности
                            $payment_status = 0;
                            $credit_item_price = $balance;
                        }

                        // humo или uzcard
                        if($balance > 10000){  // тиинах
                            if($user->auto_discard_type == 1) {
                                $result = $this->discard($user, $credit_item_price);
                            }
                            if($user->auto_discard_type == 2) {
                                $result = HumoHelper::HumoDiscard($scoring->card_h,$scoring->bank_c,$scoring->exp,$credit_item_price);
                            }

                        }

                        $result = json_decode($result, true);

                        $price = ($credit_item->price*100 - $credit_item_price)/100;

                        //echo ' contract = ' . $credit->contract->id .  ', user ' . $user->id . ' , user->summ = '  . $user->summ . ' $credit->id = '  . $credit->id . ',   balance = ' . $balance .  ' ///  $credit_item->price =   ' . $credit_item->price . ' , ' . " остаток после списания =  " . $price .' <br>';


                        // если списались деньги
                        // humo или uzcard -?
                        $allow = false;
                        if(isset($result['result']['status']) && $result['result']['status'] == 'OK'){  // uzcard
                            $allow = true;
                        }
                        if(isset($result['merchant_id'])){  // Humo
                            $allow = true;
                        }
                        if ($allow) {

                            $credit_item->delay = $credit->getPaymentDelay(); // просрочка в днях
                            $credit->credit -= $credit_item_price/100; // уменьшение задолженности

                            $credit_item->payment_status = $payment_status;  // списалась вся сумма или частично(1,0)
                            $credit_item->payment_date = time();
                            $credit_item->payment_type = Payment::PAYMENT_TYPE_UZCARD;
                            $credit_item->price = $price; // остаток задолженности в сумах

                            if ($credit_item->save() && $user->save() && $credit->save()) {

                                //if( $result['result']['status'] == 'OK' ) {

                                // запись транзакции по всем платежкам
                                $payment = new Payment();
                                $payment->created_at = time();
                                $payment->payment_type = Payment::PAYMENT_TYPE_UZCARD;
                                $payment->state = Payment::PAYMENT_STATE_SUCCESS;
                                $payment->price = $credit_item_price/100; //$credit_item->price;
                                $payment->user_id = $user->id;
                                $payment->supplier_id = $credit->supplier_id;
                                $payment->credit_id = $credit->id;
                                $payment->credit_item_id = $credit_item->id;
                                $payment->user_id = $user->id;
                                $payment->status = $payment_status == 1 ? 'COMPLETE' : 'PART';
                                if (!$payment->save()) {
                                    return json_encode(['status' => 0, 'info' => Yii::t('app', 'error saving Payment' )]);
                                }


                                if($user->auto_discard_type == 1) {
                                    $trans_id = $result['result']['id'];
                                    unset($result['result']['id']);
                                    $utp = new UzcardPayments(); // учет снятия средств
                                    //if( $utp->load($data) ){
                                    foreach ($result["result"] as $k => $v)
                                        $utp->$k = strval($v);

                                    $utp->user_id = $user->id;
                                    $utp->payment_id = $payment->id;
                                    $utp->created_at = time();
                                    $utp->trans_id = $trans_id;
                                    $utp->credit_item_id = $credit_item->id;
                                    if (!$utp->save()) {
                                        return json_encode(['status' => 0, 'info' => Yii::t('app', 'error saving UzcardPayments')]);
                                    }
                                }
                                if($user->auto_discard_type == 2) {
                                    if($result['payment_id']){
                                        $humo_payments = new HumoPayments();
                                        $humo_payments->user_id = $user->id;
                                        $humo_payments->credit_item_id = $credit_item->id;
                                        $humo_payments->created_at = time();
                                        foreach($result as $k => $v){
                                            $humo_payments->$k = $v;
                                            if(!$humo_payments->save()){
                                                return json_encode(['status' => 0, 'info' => Yii::t('app', 'error saving HumoPayments')]);
                                            }
                                        }
                                    }
                                }
                                // если оплата через узкард
                                /*$uzcard = new Uzcard();
                                $uzcard->created_at = time();
                                $uzcard->payment_date = $credit_item->payment_date;
                                $uzcard->client_id = $user->id;
                                $uzcard->summ = $credit_item_price/100;// ??
                                $uzcard->transaction_id = $utp->trans_id;
                                $uzcard->status = 1;
                                $uzcard->credit_id = $credit->id;
                                if (!$uzcard->save()) {
                                    return json_encode(['status' => 0, 'info' => Yii::t('app', 'error saving Uzcard' )]);
                                }*/

                                //}
                                $billing_payments = new BillingPayments();
                                $billing_payments->created_at = time();
                                $billing_payments->credit_item_id = $credit_item->id;
                                $billing_payments->credit_id = $credit->id;
                                $billing_payments->user_id = $user->id;
                                $billing_payments->summ = $credit_item_price/100;// ??
                                $billing_payments->debt = $price; // остаток задолженности - ??
                                $billing_payments->contract_id = $credit->contract->id;
                                $billing_payments->status = 1;
                                if (!$billing_payments->save()) {
                                    return json_encode(['status' => 0, 'info' => Yii::t('app', 'error saving BillingPayments' )]);
                                }



                                $info = '';
                                if ((int)$credit->credit == 0) {
                                    $credit->status = Credits::PAYMENT_STATUS_PAYED;
                                    $credit->save();
                                    $info = ' ' . Yii::t('app', 'Vash kredit polnostyu pogashen! S Uvazheniem, ZMARKET');
                                }else{
                                    $info = ' ' . Yii::t('app', 'S Uvazheniem, ZMARKET');
                                }

                                SmsHelper::sendSms($user->phone, Yii::t('app', 'S Vashego licevogo scheta spisano ' . $payment->price . '. Tekushaya zadolzhennost '  . $debt_balance/100 .  ' sum. ' . $info));
                                // log cvc
                                self::debug('sms');
                                self::debug($user->phone, Yii::t('app', 'Zmarket. Списание средств на сумму ') . $payment->price . $info);
                            }
                        }else{
                            // если ошибка от узкарда
                            //echo 'ошибка -  '. $result['result']['respSV'] . '<br>';
                            //echo 'ошибка -  '. $result . '<br>';

                        }

                        //return true;
                    } else {
                        // если суммы списания не осталось, то закрываем месяц
                        // return true;
                    }
                }

            }

        }


        //return false;

    }


    // автосписание средств по CRON  - нужно тестить!!!
    // автопогашение просроченных кредитов по конкретному клиенту PAYMO
    public function autoDiscardPaymo($user_id=null){

        // поиск всех неоплаченных платежек, просроченные по времени для автоснятия средств
        if( $credit_history = CreditHistory::find()->with(['credit','client','contract'])->where(['<=','credit_date', time()])->andWhere(['payment_status'=>'0'])->orderBy('credit_date')->all() ){

            foreach ($credit_history as $credit_item) { // по всем просроченным платежкам

                // поиск заданного клиента
                if( !is_null($user_id) && $user_id != $credit_history->client->id ) continue;

                if ($user = $credit_history->client ) {
                    if ($user->summ >= $credit_item->price && $credit = $credit_item->credit) { // кредит и клиент

                        $user->summ -= $credit_item->price; // снятие средств у пользователя в кабинете zmarket

                        $credit_item->delay = $credit->getPaymentDelay(); // просрочка в днях
                        $credit->credit -= $credit_item->price; // уменьшение суммы задолженности

                        $credit_item->payment_status = 1;
                        $credit_item->payment_date = time();
                        $credit_item->payment_type = Payment::PAYMENT_TYPE_PAYMO;

                        if ( $credit_item->save() && $user->save() && $credit->save() ) {
                            $result = $this->discardPaymo($credit_item->contract->id,$credit_item->price);
                            if( $result['result']['status']=='OK' ) {

                                // запись транзакции по всем платежкам
                                $payment = new Payment();
                                $payment->created_at = time();
                                $payment->payment_type = Payment::PAYMENT_TYPE_UZCARD;
                                $payment->state = Payment::PAYMENT_STATE_SUCCESS;
                                $payment->price = $credit_item->price;
                                $payment->user_id = $user->id;
                                $payment->supplier_id = $credit->supplier_id;
                                $payment->credit_id = $credit->id;
                                $payment->credit_item_id = $credit_item->id;
                                $payment->user_id = $user->id;
                                $payment->status = 'COMPLETE';
                                $payment->save();

                                $data['UzcardPayments'] = $result['result'];
                                $trans_id = $result['result']['id'];
                                unset($result['result']['id']);

                                $utp = new UzcardPayments(); // учет снятия средств
                                if( $utp->load($data) ){
                                    $utp->user_id = $user->id;
                                    $utp->payment_id = $payment->id;
                                    $utp->created_at = time();
                                    $utp->trans_id = $trans_id;
                                    $utp->save();
                                }
                                //$billing_payments = new BillingPayments();
                                //$billing_payments->created_at = time();
                                //$billing_payments->contract_id =


                                $info = '';
                                if ((int)$credit->credit == 0) {
                                    $credit->status = Credits::PAYMENT_STATUS_PAYED;
                                    $credit->save();
                                    $info = ' ' . Yii::t('app', 'Zmarket. Ваш кредит полностью погашен!');
                                }

                                SmsHelper::sendSms($user->phone, Yii::t('app', 'Zmarket. Списание средств на сумму ') . $payment->price . $info);

                                return true;
                            }else{

                                return false;
                            }

                        }else{ // лог ошибок для анализа, только для лок
                            $errors = [];
                            if($credit_item->hasErrors()){
                                $errors['credit_item'] = $credit_item->getErrors();
                            }
                            if($user->hasErrors()){
                                $errors['user'] = $user->getErrors();
                            }
                            if($credit->hasErrors()){
                                $errors['credit'] = $credit->getErrors();
                            }
                            self::debug($errors);
                        }
                    }
                }
            }

        }

        return false;

    }


    // процесс снятия средств
    public static function discard(&$user,$sum_tiin){

        $request_id = 'zmarket_' . uniqid(rand(),1) . $user->id;

        //echo 'безакцептное списание ';

        $input = json_encode([
            'jsonrpc' => '2.0',
            'id' => $request_id,
            'method' => 'trans.pay',
            'params' => [
                'tran' => [
                    'cardId' => $user->scoring->token,
                    'amount' => $sum_tiin, // сумма в тийнах
                    'merchantId' => '90485570', //Yii::$app->user->id, //identity->vendor_id,
                    'ext' => $request_id,
                    'stan' => rand(),//'141220',
                    'refNum' => rand(),
                    'terminalId' => '92404056',
                    'port' => '12345',


                ]
            ],
        ]);
        //var_dump($input);
        $auth = self::getAuth();
        $curl = curl_init($auth['url']);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $auth['login'] . ":" . $auth['pw']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $input);

        $_result = curl_exec($curl);
        $result = $_result;

        /*self::debug('trans.pay');
        self::debug($input);
        self::debug($result);*/
        self::debug_pay('trans.pay');
        self::debug_pay($input);
        self::debug_pay($result);

        return $result;

    }




    // оповещение uzcard о заключении договора (при оформлении кредита)
    public static function sendOrder(&$credit,&$user){
        // Шаг 11-12
        // токен клиента - номер uzcard
        // сумма займа
        // срок займа



        return true;

    }


    // СКОРИНГ
    // получение токена
    /*public static function scoringRemoveToken($token){
        if(is_null($token) ) return json_encode(['status'=>0,'info'=>Yii::t('app','Не указан номер или срок карты!')]);
        $request_id = 'zmarket_' . time();

        $auth = self::getAuth();
        $curl = curl_init($auth['url']);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $auth['login'] . ":" . $auth['pw']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
            'jsonrpc' => '2.0',
            'id' => $request_id,
            'method' => 'remove.cards',
            'params' => [
                'cardId' => $token
            ],
        ]));

        $_result = curl_exec($curl);
        $result = json_decode($_result, JSON_UNESCAPED_UNICODE);

        return json_encode($result,JSON_UNESCAPED_UNICODE);

    } */

    // получение токена при регистрации клиента  сотправкой смс от Uzcard
    public static function scoringGetToken($card,$exp){
        if(is_null($card) || is_null($exp) || $card=='' || $exp=='') return json_encode(['status'=>0,'info'=>Yii::t('app','Не указан номер или срок карты!')]);

        $scoring_result = [];
        $request_id = 'zmarket_' . time();

        $input = json_encode([
            'jsonrpc' => '2.0',
            'id' => $request_id,
            'method' => 'cards.new',
            'params' => [
                'card' => [
                    'pan' => $card,
                    'expiry' => $exp
                ]
            ],
        ]);

        $auth = self::getAuth();
        $curl = curl_init($auth['url']);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $auth['login'] . ":" . $auth['pw']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $input);

        $_result = curl_exec($curl);
        $result = json_decode($_result, JSON_UNESCAPED_UNICODE);

        //return json_encode($result,JSON_UNESCAPED_UNICODE);
        self::debug('cards.new');
        self::debug($input);
        self::debug($result);
        if(isset($result['result']['pan'])){
                $scoring_result['Scoring'] = [
                    'token' => $result['result']['id'] ,
                    'pan' => $result['result']['pan'] ,
                    'exp' => $result['result']['expiry'] ,
                    'phone' => $result['result']['phone'] ,
                    'fullname' => $result['result']['fullName'] ,
                    'balance' => (string)($result['result']['balance'] / 100 ), // из тийин в суммы
                    'sms' => $result['result']['sms'] ? '1' : '0',
                ];

            $scoring_result['status'] = 1;
        }else{
            $scoring_result['status'] = 0;
            $scoring_result['info'] = $result['error']['message'];
        }
        return $scoring_result;

    }

    // cards.get
    public static function cardsGet(&$user){

        $request_id = 'zmarket_' . uniqid(rand(),1) . $user->id;

        if(!isset($user->scoring)) {
            return json_encode(Yii::t('app','Токен не задан!'),JSON_UNESCAPED_UNICODE);
        }

        $input = json_encode([
            'jsonrpc' => '2.0',
            'id' => $request_id,
            'method' => 'cards.get',
            'params' => [
                'ids' => [
                    $user->scoring->token
                ],
            ],
        ]);

        $auth = self::getAuth();
        $curl = curl_init($auth['url']);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $auth['login'] . ":" . $auth['pw']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $input);
        $_result = curl_exec($curl);
        $result = json_decode($_result, JSON_UNESCAPED_UNICODE);

        //return json_encode($result,JSON_UNESCAPED_UNICODE);

        self::debug_pay('cards.get');
        self::debug_pay($input);
        self::debug_pay($result);

        return $result;

    }

    public static function getBalance(&$token){

        $request_id = 'zmarket_' . uniqid(rand(),1) ;


        $input = json_encode([
            'jsonrpc' => '2.0',
            'id' => $request_id,
            'method' => 'cards.get',
            'params' => [
                'ids' => [
                    $token
                ],
            ],
        ]);

        $auth = self::getAuth();
        $curl = curl_init($auth['url']);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $auth['login'] . ":" . $auth['pw']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $input);
        $_result = curl_exec($curl);
        $result = json_decode($_result, JSON_UNESCAPED_UNICODE);

        //return json_encode($result,JSON_UNESCAPED_UNICODE);

        self::debug_pay('cards.get');
        self::debug_pay($input);
        self::debug_pay($result);

        return $result;

    }



    // trans.reverse
    public static function cardsReverse($tranId){

        $request_id = 'zmarket_' . time();


        $input = json_encode([
            'jsonrpc' => '2.0',
            'id' => $request_id,
            'method' => 'trans.reverse',
            'params' => [
                'tranId' => $tranId,
            ],
        ]);

        $auth = self::getAuth();
        $curl = curl_init($auth['url']);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $auth['login'] . ":" . $auth['pw']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $input);

        $_result = curl_exec($curl);
        $result = json_decode($_result, JSON_UNESCAPED_UNICODE);

        //return json_encode($result,JSON_UNESCAPED_UNICODE);
        self::debug('trans.reverse');
        self::debug($input);
        self::debug($result);
        print_r($result);
        //exit;
        return $result;

    }

    // получение токена при регистрации клиента  сотправкой смс от Uzcard
    public static function cardOtp($card,$exp){
        if(is_null($card) || is_null($exp) || $card=='' || $exp=='') return json_encode(['status'=>0,'info'=>Yii::t('app','Не указан номер или срок карты!')]);

        $scoring_result = [];
        $request_id = 'zmarket_' . time();

        $input = json_encode([
            'jsonrpc' => '2.0',
            'id' => $request_id,
            'method' => 'cards.new.otp',
            'params' => [
                'card' => [
                    'pan' => $card,
                    'expiry' => $exp
                ]
            ],
        ]);

        $auth = self::getAuth();
        $curl = curl_init($auth['url']);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $auth['login'] . ":" . $auth['pw']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $input);

        $_result = curl_exec($curl);
        $result = json_decode($_result, JSON_UNESCAPED_UNICODE);

        //return json_encode($result,JSON_UNESCAPED_UNICODE);
        self::debug('cards.new.otp');
        self::debug($input);
        self::debug($result);
        if(isset($result['result']['pan'])){
                $scoring_result['Scoring'] = [
                    'token' => $result['result']['id'] ,
                    'pan' => $result['result']['pan'] ,
                    'exp' => $result['result']['expiry'] ,
                    'phone' => $result['result']['phone'] ,
                    'fullname' => $result['result']['fullName'] ,
                    'balance' => (string)($result['result']['balance'] / 100 ), // из тийин в суммы
                    'sms' => $result['result']['sms'] ? '1' : '0',
                ];

            $scoring_result['status'] = 1;
        }else{
            $scoring_result['status'] = 0;
            $scoring_result['info'] = $result['error']['message'];
        }
        return $scoring_result;

    }

    // проверка ранее отправленного кода из смс клиента
    public static function cardVerify($token,$code){

        if(is_null($token) || is_null($code) || $code=='' || $token=='' ) return json_encode(['status'=>0,'info'=>Yii::t('app','Не указан код из смс или!')]);

        $scoring_result = [];
        $request_id = 'zmarket_' . time();

        $input = json_encode([
            'jsonrpc' => '2.0',
            'id' => $request_id,
            'method' => 'cards.new.verify',
            'params' => [
                'card' => [
                    'token' => $token,
                    'code' => $code
                ]
            ],
        ]);

        $auth = self::getAuth();
        $curl = curl_init($auth['url']);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $auth['login'] . ":" . $auth['pw']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $input);

        $_result = curl_exec($curl);
        $result = json_decode($_result, JSON_UNESCAPED_UNICODE);

        //return json_encode($result,JSON_UNESCAPED_UNICODE);
        self::debug('cards.new.verify');
        self::debug($input);
        self::debug($result);

        if(isset($result['result'][0]['pan'])){
            self::debug('OK');

            $data = $result['result'][0];
            $scoring_result = [
                "token"=> $data['id'],
                'status_uzcard' => $data['status'] ,
                'pan' => $data['pan'] ,
                'exp' => $data['expiry'] ,
                'phone' => $data['phone'] ,
                'fullname' => $data['fullName'] ,
                'balance' => (string)($data['balance'] / 100 ), // из тийин в суммы
                'sms' => $data['sms'] ? '1' : '0',
            ];
            $scoring_result['status'] = 1;
        }else{
            self::debug('ERROR');

            $scoring_result['status'] = 0;
            $scoring_result['info'] = json_encode($result); //['error']['message'];
        }
        return $scoring_result;

    }


    // проверка клиента на платежеспособность (при создании пользователя)
    // sum -  сумма в сум, не в тийин
    public static function scoring(&$user,$sum,$date_start,$date_end)
    {

        $scoring_result = [];

        if ( !isset($user)) {
            return json_encode(['status' => 0, 'error' => Yii::t('app', 'Клиент не найден!')], JSON_UNESCAPED_UNICODE);
        }

        $request_id = 'zMarket_' . $user->id;

        $card_token = $user->scoring->token;

        if($card_token) {
            $sum *= 100;

            $input = json_encode([
                'jsonrpc' => '2.0',
                'id' => $request_id,
                'method' => 'scoring.create.amount',
                'params' => [
                    'scoring' => [
                        'cardId' => $card_token, // Токен карты
                        'bdate' => $date_start, // '01112019', // Дата начала (DDMMYY)
                        'edate' => $date_end, // '01112020', // Дата конца (DDMMYY)
                        'templateId ' => '1', // ID шаблона скоринга
                        'amount' => $sum, // из сум в тийин
                    ]

                ],

            ]);

            $auth = self::getAuth();
            $curl = curl_init($auth['url']);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERPWD, $auth['login'] . ":" . $auth['pw']);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $input);


            $result = curl_exec($curl);

            $result = json_decode($result, JSON_UNESCAPED_UNICODE);
            self::debug('scoring.create.amount');

            self::debug($input);
            self::debug($result);


            if(isset($result['result'])) {

                //self::debug('has result for scoring.get.month');

                $scoring_id = $result['result']['id']; // идентификатор скоринга

                $auth = self::getAuth();
                $curl = curl_init($auth['url']);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($curl, CURLOPT_USERPWD, $auth['login'] . ":" . $auth['pw']);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

                curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
                    'jsonrpc' => '2.0',
                    'id' => $request_id,
                    'method' => 'scoring.get.month',
                    'params' => [
                        'id' => $scoring_id,
                    ],
                ]));

                self::debug('scoring.get.month');

                $result = curl_exec($curl);

                $result = json_decode($result, JSON_UNESCAPED_UNICODE);

                if (isset($result['result'])) {
                    $scoring_result['Scoring']['data'] = json_encode($result['result'], JSON_UNESCAPED_UNICODE);
                }else{
                    self::debug('ERROR: scoring.get.month');
                    $scoring_result = $result;
                }


            }else{
                $scoring_result = $result;
            }
        }

        self::debug($scoring_result);

        return $scoring_result;

    }

    // оплата с внешнего сервиса, либо пополнение баланса
    public static function payment($user,$contract,$sum,$payment_type){


        $billing_history = new BillingHistory();
        $billing_history->user_id = $user->id;
        $billing_history->contract_id = $contract->id;
        $billing_history->created_at = time();
        $billing_history->summ = $sum;
        $billing_history->payment_type = $payment_type;
        if(!$billing_history->save()){
            return json_encode($billing_history->getErrors(),JSON_UNESCAPED_UNICODE);
        }

        $user->balance += $sum;
        if(!$user->save()){
            return json_encode($user->getErrors(),JSON_UNESCAPED_UNICODE);
        }

        // погасить один или несколько просроченных месяцев
        if( self::autoDiscard($user->id) ){

            return true;
        }

        return false;


    }


    public static function getAuth(){

        if(self::TEST_MODE){ // если тестовый режим
            return ['url'=>self::APIURL_TEST, 'pw'=>self::PASSWORD_TEST, 'login' => self::LOGIN_TEST];
        }else{
            return ['url'=>self::APIURL, 'pw'=>self::PASSWORD, 'login' => self::LOGIN];
        }

    }

    public static function debug( $data,$clear=false){
        //if($clear) @unlink($_SERVER['DOCUMENT_ROOT'] .'/debug_uzcard.txt');
        $f = fopen($_SERVER['DOCUMENT_ROOT'] .'/debug_uzcard.txt','a');
        $data = date('d.m / H:i:s') . ':  ' . json_encode($data,256) . PHP_EOL;
        fwrite($f,$data);
        fclose($f);
    }

    public static function debug_pay( $data,$clear_pay=false){
        //if($clear_pay) @unlink($_SERVER['DOCUMENT_ROOT'] .'/debug_pay.txt');
        $f = fopen($_SERVER['DOCUMENT_ROOT'] .'/debug_pay.txt','a');
        $data = date('d.m / H:i:s') . ':  ' . json_encode($data,256) . PHP_EOL;
        fwrite($f,$data);
        fclose($f);
    }

    public static function debug_pay2( $data,$clear_pay2=false){
        //if($clear_pay2) @unlink($_SERVER['DOCUMENT_ROOT'] .'/debug_pay.txt');
        $f = fopen($_SERVER['DOCUMENT_ROOT'] .'/debug_pay.txt','a');
        $data = date('d.m / H:i:s') . ':  ' . json_encode($data,256) . PHP_EOL;
        fwrite($f,$data);
        fclose($f);
    }



}
