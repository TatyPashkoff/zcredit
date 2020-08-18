<?php

namespace common\models;

use Yii;
use common\helpers\TextHelper;
use common\models\Images;

use yii\imagine\Image;
use yii\web\UploadedFile;


/**
 * This is the model class for table "credits".
 *
 * @property integer $id
    
 * @property integer $created_at
    
 * @property integer $user_id
    
 * @property integer $supplier_id
    
 * @property integer $credit_limit
    
 * @property string $deposit_first
    
 * @property string $deposit_month
    
 * @property string $price
 * @property string $credit
 * @property integer $nds
 * @property string $confirm
 * @property string $confirm_date
 * @property string $user_confirm
 * @property string $user_confirm_date

 * @property integer $quantity
    
 * @property integer $status
    
 */
class Credits extends \yii\db\ActiveRecord
{

    public $payment_sum;
    public $quantity;
    public $credits_count;
    public $itemsName;
    public $supplierSum;
    
    const ITEMS_COUNT = 20;

    const ZMARKET_SUM = 3000000; // кредитное плечо, сум

    const CREDIT_TYPES = [
        0 => '3 мес.',
        1 => '3 мес.',
        2 => '6 мес.',
        3 => '9 мес.',
        4 => '12 мес.',
        5 => '1 год  3 мес.',
        6 => '1 год 6 мес.',
        7 => '1 год 9 мес.',
        8 => '2 года',
    ];

    const PAYMENT_STATUS_PAYED = 1;
    const PAYMENT_STATUS_OFF = 0;

    const PAYMENT_STATUS = [
        0 => 'Не оформлен',
        1 => 'Оформлен',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'credits';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'user_id', 'supplier_id', 'credit_limit', 'quantity','date_start','credit_date','delivery_date','status','confirm','user_confirm','confirm_date','user_confirm_date','contract_id','nds','prefix_act','api_user','service_type'], 'integer'],
            [['deposit_first', 'deposit_month', 'price','credit'], 'number'],
            [['code_confirm'],'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID кредита',
            'created_at' => Yii::t('app','Создан'),
            'user_id' => Yii::t('app','ID Клиента'),
            'supplier_id' => Yii::t('app','ID Поставщика'),
            'credit_limit' => Yii::t('app','Срок кредита'),
            'deposit_first' => Yii::t('app','Начальный взнос'),
            'deposit_month' => Yii::t('app','Ежемесячный взнос'),
            'price' => Yii::t('app','Сумма кредита'),
            'credit' => Yii::t('app','Задолженность'),
            'quantity' => Yii::t('app','Кол-во товаров'),
            'status' => Yii::t('app','Статус погашения'),
            'user_confirm' => Yii::t('app','Статус подтверждения'),
            'itemsName' => Yii::t('app','Наименование (-я)'),
            'supplierSum' => Yii::t('app','Сумма оплаты поставщику (со скидкой)'),
            'supplierConfirmSum' => Yii::t('app','Сумма подтвержденных кредитов (без скидки)'),
            'company' => Yii::t('app','Компания'),
            'client_id' => Yii::t('app','ФИО'),
            'api_user' => Yii::t('app','API пользователь'),
            'rest' => 'Остаток задолженности',
            'service_type' => 'Вид услуг',
        ];
    }

    // получение договора
    public function getContract(){

        return $this->hasOne(Contracts::className(),['credit_id'=>'id']);
    }
    // получение полиса
    public function getPolis(){

        return $this->hasOne(Polises::className(),['credit_id'=>'id']);
    }

    // получение полиса Asko
    public function getAsko(){

        return $this->hasOne(Asko::className(),['credit_id'=>'id']);
    }


    public function getPayments(){

        return $this->hasMany(CreditHistory::className(),['credit_id'=>'id'])->orderBy('payment_date DESC');
    }

// получение кол-ва оплаченных месяцев
    public function getPayment(){

        return $this->hasOne(Payment::className(),['credit_id'=>'id']);
    }

    public function getHistory(){
        return $this->hasMany(CreditHistory::className(),['credit_id'=>'id']);
    }

    // получение кол-ва оплаченных месяцев
    public function getPaymentsAsc(){

        return $this->hasMany(CreditHistory::className(),['credit_id'=>'id']);
    }

   // состав кредита
    public function getCreditItems(){

        return $this->hasMany(CreditItems::className(),['credit_id'=>'id']);
    }

    public function getCreditItemsGroup(){

        return $this->hasMany(CreditItems::className(),['credit_id'=>'id'])->select('SUM(quantity) as quantity, COUNT(credit_id) as credits_count')->groupBy('credit_id');
    }

    // получение поставщика
    public function getSupplier(){

        return $this->hasOne(User::className(),['id'=>'supplier_id'])->where(['role'=>User::ROLE_SUPPLIER]);

    }
    // получение клиента заемщика
    public function getClient(){

        return $this->hasOne(User::className(),['id'=>'user_id'])->where(['role'=>User::ROLE_CLIENT]);

    }

    // сумма всех оплат
    public function getPaymentSum(){
        $sum = 0;
        if(isset($this->payments)) {
            foreach ($this->payments as $payment) {
                if($payment->payment_status ==1 ) $sum += $payment->price;
            }
        }
        if($this->price - $sum > 0) $sum = $this->price - $this->deposit_first - $sum;
        return $sum;
    }

    // оплата за месяц
    public function getPaymentMonth(){
        $cnt = 0;
        if(isset($this->payments)) {
            foreach ($this->payments as $payment) {
                if($payment->payment_status==1){
                    $cnt++; // каждый оплаченный месяц
                }
            }
        }
        return $cnt;
    }

    // просрочка по оплате дней
    public function getPaymentDelay(){
        $res = 0;
        if(isset($this->payments)) {
            foreach ($this->payments as $payment) {
                if($payment->payment_status == 1) continue;
                if( $payment->credit_date < time()) {
                    // первая запись
                    $pay = time() - $payment->credit_date; // - $payment->credit_date;
                    $res = (int)($pay / 86400); // просрочено дней
                    $res = abs($res);
                    return $res; 
                }
                //break; // учесть все месяцы задолженности, не только предстоящий
            }
        }
        return $res;
    }

    // просрочка по оплате сумма
    public function getPaymentDelaySum(){
        $res = 0;
        if(isset($this->payments)) {
            foreach ($this->payments as $payment) {
                if($payment->payment_status==1) continue;
                if( $payment->credit_date<time()) {
                    // первая запись
                    $res += $payment->price;// просрочено сумма
                   // break;
                }

            }
        }
        return $res;
    }

    // просрочка всех кредитов , сумма
    public static function getPaymentDelaySumAll($user_id){
        $res = 0;
        if($credits = Credits::find()->with('payments')->where(['user_id'=>$user_id, 'user_confirm' => 1])->all()) {
            foreach ($credits as $credit) {
                if(!isset($credit->payments)) continue;
                foreach ($credit->payments as $payment) {
                    if ($payment->payment_status == 1) continue;
                    if ($payment->credit_date < time()) {
                        $res += $payment->price;// просрочено сумма
                    }
                }
            }
        }
        return $res;
    }

    // сумма всех выданных непогашенных кредитов
    public static function getPaymentSumAll($user_id){
        $res = 0;
        if($credit = Credits::find()->select('SUM(credit_items.discount_sum * credit_items.quantity ) as payment_sum')->joinWith("creditItems")->where(['user_id'=>$user_id,'status'=>0])->andWhere(['user_confirm' => 1])->one()) {
             $res = $credit->payment_sum;
        }
        return $res;
    }

    // последняя дата оплаты
    public function getLastPayment(){
        $res = '-';
        if(isset($this->payments) && isset($this->payments[0])) {
            if($this->payments[0]->payment_date!='') $res = date('d.m.Y',$this->payments[0]->payment_date);
        }
        return $res;
    }
    // последняя дата оплаты
    public function getNextPayment(){
        $res = '-';
        if(isset($this->payments) ) {
            foreach ($this->payments as $payment){
                if($payment->payment_status==0){
                    $res =  date('d.m.Y',$payment->credit_date );
                    break;
                }
            }

        }
        return $res;
    }

    // дата первого взноса
    public function getFirstPayment(){
        $res = '-';
        if(isset($this->payments)) {
            $cnt = count($this->payments);
            if ($cnt > 0 ) {
                $res = $this->payments[$cnt - 1]->payment_status==1 ? date('d.m.Y', $this->payments[$cnt - 1]->payment_date): '-';
            }

        }
        return $res;
    }

    public function updateModel($new=false){

        $post = Yii::$app->request->post();

        if($this->load($post) ) {

            if( $new ){ // если создается только один раз при создании
                //$this->date = time();
            }

            
            if( !$this->save() ){
                Yii::$app->session->setFlash('info-error','Ошибка при сохранении!');
                print_r($this->getErrors());
                exit;

                return true;
            }


            
            Yii::$app->session->setFlash('info-success','Сохранение успешно завершено!');

            return true;
        }
        return false;

    }



}
