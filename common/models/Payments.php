<?php

namespace common\models;

use Yii;
use common\helpers\TextHelper;
use common\models\Images;

use yii\imagine\Image;
use yii\web\UploadedFile;


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
class Payments extends \yii\db\ActiveRecord
{

    const PAYMENT_TYPE_PAYME = 1;
    const PAYMENT_TYPE_CLICK = 2;
    const PAYMENT_TYPE_UZCARD = 3;

    const PAYMENT_STATE_SUCCESS = 1;
    const PAYMENT_STATE_ERROR = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'user_id', 'supplier_id', 'credit_id', 'credit_item_id','payment_type', 'state','status'], 'integer'],
            [['price'], 'number'],
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

    // завершение оплаты для всех платежных систем
    public static function payment($order_id){

        // операция с оплатой кредита
        if( $payment = Payments::find()->where(['id'=>$order_id])->one() ){
            $payment->state = 2; // установить статус
            $payment = 'completed';
            if($payment->save()) {

                if ($credit_history = CreditHistory::find()->where(['id' => $payment->credit_item_id])->one()) {
                    $credit_history->payment_date = time();
                    $credit_history->payment_type = 1;
                    $credit_history->payment_status = 1;
                    $credit_history->save();

                    $credit = Credits::find()->where(['id' => $credit_history->credit_id])->one();
                    $credit->credit = $credit->credit - $credit_history->price;
                    if ((int)$credit->credit == 0) { // без учета копеек???
                        $credit->status = 1; // кредит погашен
                    }
                    $credit->save();

                }

                Yii::$app->session->setFlash('info', Yii::t('app', 'Оплата успешно завершена!'));
            }else{
                Yii::$app->session->setFlash('info', Yii::t('app','Ошибка при оплате!') );

            }

        }else{ // если заказа нет в бд
            Yii::$app->session->setFlash('info', Yii::t('app','Ошибка при оплате!') );
        }

    }



}
