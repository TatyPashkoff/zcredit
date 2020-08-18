<?php

namespace common\models;

use common\helpers\SmsHelper;
use common\helpers\UtilsHelper;
use Yii;
use common\models\Images;



/**
 * This is the model class for table "payments".
 *
 * @property integer $id
    
 * @property integer $payment_type
    
 * @property string $price
    
 * @property integer $state
    
 * @property integer $created_at
    
 * @property integer $user_id
    
 * @property integer $supplier_id
    
 * @property integer $credit_id
    
 * @property integer $credit_item_id
    
 * @property string $status
    
 */
class Payment extends \yii\db\ActiveRecord
{

    const PAYMENT_TYPE_PAYME = 1;
    const PAYMENT_TYPE_CLICK = 2;
    const PAYMENT_TYPE_UZCARD = 3;
    const PAYMENT_TYPE_PAYMO = 4;
	const PAYMENT_TYPE_MYUZCARD = 5;
    const PAYMENT_TYPE_BILLING = 9;

    const PAYMENT_STATE_SUCCESS = 1;
    const PAYMENT_STATE_ERROR = 0;
    const PAYMENT_STATE_WAIT = 2;

    const PAYME_URL = 'https://checkout.paycom.uz';
    const PAYME_URL_TEST = 'https://test.paycom.uz';
    const PAYME_MERCHANT_ID = '5dd679cf61fd56952ab2d1bc';
    const PAYME_KEY = '&bBCTfhFVuHb9nFK7XKzr2ohEP&ajA9oxqan';
    const PAYME_KEY_TEST = 'DUZK2hgs7dFEpO1uZmvma&&A5gToP@R2qR?0';

    const CLICK_URL = 'https://my.click.uz/pay/';
    const CLICK_SECRET = '9NIYATx0Pol';
    const CLICK_MERCHANT_ID = '9968';
    const CLICK_MERCHANT_USER_ID = '14106';
    const CLICK_SERVICE_ID = '14548';

    const UZCARD_URL = '/uzcard-payment';
    const UZCARD_MERCHANT_ID = '90050043806';
    const UZCARD_TERMINAL_ID = '9146734';
    const UZCARD_SECRET = '9146gj7393ls&s(734';

    const TYPE_PAY = 0; // оплата
    const TYPE_FILL = 1; // пополнение


    const SECRET = 'bsjhdT834hsigw'; // для подписи
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'user_id', 'supplier_id', 'credit_id', 'credit_item_id','payment_type', 'state','type'], 'integer'],
            [['price'], 'number'],
            ['status','string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payment_type' => 'Payment Type',
            'price' => 'Price',
            'state' => 'State',
            'created_at' => 'Created At',
            'user_id' => 'User ID',
            'supplier_id' => 'Supplier ID',
            'credit_id' => 'Credit ID',
            'credit_item_id' => 'Credit Item ID',
            'status' => 'Status',
        ];
    }

    public function getSettings(){
        return $this->hasOne(SuppliersSettings::className(),['supplier_id'=>'supplier_id']);
    }

    public function getContract(){
        return $this->hasOne(Contracts::className(),['credit_id'=>'credit_id']);
    }
    public function getCredit(){
        return $this->hasOne(Credits::className(),['id'=>'credit_id']);
    }

    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }

    public static function getPaymentType($type){
        switch($type){
            case Payment::PAYMENT_TYPE_PAYME:
                $res = 'Payme';
                break;

            case Payment::PAYMENT_TYPE_CLICK:
                $res = 'Click';
                break;

            case Payment::PAYMENT_TYPE_UZCARD:
                $res = Yii::t('app','Автосписание Uzcard');
                break;

            case Payment::PAYMENT_TYPE_PAYMO:
                $res = 'Paymo';
                break;

            case Payment::PAYMENT_TYPE_BILLING:
                $res = Yii::t('app','Биллинг клиента');
                break;
            default:
                $res = Yii::t('app','Не определен');
        }
        return $res;
    }

    // завершение оплаты для всех платежных систем
    public static function paymentOld($order_id,$payment_type){

        // операция с оплатой кредита
        if( $payment = Payment::find()->where(['id'=>$order_id])->one() ){
            $payment->state = Payment::PAYMENT_STATE_SUCCESS; // установить статус
            $payment->created_at = time();
            $payment->status = 'completed';
            if($payment->save()) {

                // поиск оплаты конкретной даты
                if ($credit_history = CreditHistory::find()->where(['id' => $payment->credit_item_id])->one()) {
                    $credit_history->payment_date = time();
                    $credit_history->payment_type = $payment_type;
                    $credit_history->payment_status = Payment::PAYMENT_STATE_SUCCESS;
                    $credit_history->save();

                    $credit = Credits::find()->where(['id' => $credit_history->credit_id])->one();
                    $credit->credit = $credit->credit - $credit_history->price;
                    if ((int)$credit->credit <= 0.1 && $credit->credit>0) { // без учета копеек???
                        $credit->status = 1; // кредит погашен
                        $credit->credit = 0;
                    }
                    $credit->save();

                }

                Yii::$app->session->setFlash('info', Yii::t('app', 'Оплата успешно завершена!'));

                return $payment;
            }else{
                Yii::$app->session->setFlash('info', Yii::t('app','Ошибка при оплате!') );

            }

        }else{ // если заказа нет в бд
            Yii::$app->session->setFlash('info', Yii::t('app','Ошибка, заказ не найден!') );
        }
        return false;

    }


    public static function payment($user_id){

        UtilsHelper::debug('Списание средств с баланса клиента: ' . $user_id . ' для автопогашения задолженности.' . time());

        if( !$credits = Credits::find()->select('id')->where(['user_id'=>$user_id,'status'=>[0,9]])->all() ) {
            UtilsHelper::debug('Просроченные кредиты не найдены для клиента ' . $user_id);
            return false;
        }

        $ids = [];
        foreach ($credits as $credit){
            $ids[] = $credit->id;
        }

        // UtilsHelper::debug('кредиты: ' . json_encode($ids));

        // поиск всех просроченных месяцев по всем кредитам клиента
        if( $credit_items = CreditHistory::find()->with('credit')->where(['credit_id'=>$ids,'payment_status'=>0])->andWhere(['<=','credit_date',time()])->orderBy('credit_date')/*->groupBy('credit_id')*/->all() ) {

            $discard_sum = 0; // общая сумма для погашения

            $user = User::findOne($user_id);
            $user_balance = $user->summ;
            foreach ($credit_items as $credit_item) {

                // UtilsHelper::debug('кредит: ' . $credit_item->id );

                // если нет кредитов пропуск
                if (!isset($credit_item->credit)) continue;

                // если сумма на балансе минус сумма просроченных кредитов меньше, то  пропуск, иначе снятие с баласа
                if ($user_balance - ($discard_sum + $credit_item->price) < 0) continue;

                $discard_sum += $credit_item->price; // накапливаем для снятия

                // запись транзакции по оплате кредита
                $payment = new Payment();
                $payment->created_at = time();
                $payment->payment_type = Payment::PAYMENT_TYPE_BILLING; // тип сервиса
                $payment->state = Payment::PAYMENT_STATE_SUCCESS;
                $payment->price = $credit_item->price; // требуемая оплата
                $payment->user_id = $user_id;
                $payment->supplier_id = $credit_item->credit->supplier_id;
                $payment->credit_id = $credit_item->credit_id;
                $payment->credit_item_id = $credit_item->id;
                $payment->status = 'COMPLETE';
                $payment->type = Payment::TYPE_PAY; // оплата
                if (!$payment->save()) {
                    UtilsHelper::debug($payment->getErrors());
                    return false;
                }

                // Запись транзакции оплаты биллинга - списание средств с клиента
                $billing_payments = new BillingPayments();
                $billing_payments->created_at = time();
                $billing_payments->contract_id = $credit_item->credit->contract_id;
                $billing_payments->credit_id = $credit_item->credit_id;
                $billing_payments->credit_item_id = $credit_item->id;
                $billing_payments->user_id = $user_id;
                $billing_payments->summ = $credit_item->price; // сумма оплаты в сум
                $billing_payments->status = Payment::PAYMENT_STATE_SUCCESS;
                if (!$billing_payments->save(false)) {
                    UtilsHelper::debug($billing_payments->getErrors());
                    return false;
                }

                // проверка погашения всего кредита
                if ((int)$credit_item->credit->credit == 0) {
                    $credit_item->credit->status = Credits::PAYMENT_STATUS_PAYED;
                    $credit_item->credit->save(false);

                    //$info = ' ' . Yii::t('app', 'Zmarket. Ваш кредит полностью погашен!') . ' № ' . $credit_item->credit->contract_id;
                    //SmsHelper::sendSms($user->phone, $info);
                    if ($contract = Contracts::find()->where(['id' => $credit_item->credit->contract_id])->one()) {
                        $contract->status = Payment::PAYMENT_STATE_SUCCESS; // договор закрыт , кредит полностью погашен
                        $contract->save(false);
                    }

                }

                // оплачиваем кредит за месяц
                $credit_item->payment_date = time();
                $credit_item->payment_type = Payment::PAYMENT_TYPE_BILLING;
                $credit_item->payment_status = Payment::PAYMENT_STATE_SUCCESS;
                $credit_item->save(false);

                // вычитаем сумму погашения
                if($credit_item->credit->credit - $credit_item->price >=0 ){
                    $credit_item->credit->credit -= $credit_item->price;
                    $credit_item->credit->save(false);
                }


            } // foreach - credit-items


            // если сумма позволяет, то списать с баланса клиента
            if ($discard_sum > 0 && $user_balance - $discard_sum >= 0) {
                $user->summ -= $discard_sum;
                $user->save(false);
                return true;
            }


        }else{
            UtilsHelper::debug('Нет кредитов' );

        }

        return false;

    }


}
