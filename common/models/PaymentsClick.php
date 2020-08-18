<?php

namespace common\models;

use Yii;



/**
 * This is the model class for table "payments_click".
 *
 * @property integer $id
    
 * @property integer $product_id
    
 * @property string $status
    
 * @property string $status_note
    
 * @property string $created
    
 * @property string $modified
    
 * @property string $currency
    
 * @property string $total
    
 * @property string $amount
    
 * @property string $delivery
    
 * @property string $tax
    
 * @property string $description
    
 * @property integer $user_id
    
 * @property integer $invoice_id
    
 * @property integer $payment_id
    
 * @property string $card_token
    
 * @property string $token
    
 * @property string $phone_number
    
 * @property string $merchant_trans_id
    
 * @property string $note
    
 */
class PaymentsClick extends \yii\db\ActiveRecord
{

    /** @var INPUT string */
    const INPUT = 'input';
    /** @var WAITING string */
    const WAITING = 'waiting';
    /** @var PREAUTH string */
    const PREAUTH = 'preauth';
    /** @var CONFIRMED string */
    const CONFIRMED = 'confirmed';
    /** @var REJECTED string */
    const REJECTED  = 'rejected';
    /** @var REFUNDED string */
    const REFUNDED = 'refunded';
    /** @var ERROR string */
    const ERROR = 'error';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payments_click';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'user_id', 'invoice_id', 'payment_id'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['total', 'amount', 'delivery', 'tax'], 'number'],
            [['description', 'note', 'description'], 'string'],
            [['status', 'token', 'phone_number'], 'string', 'max' => 50],
            [['status_note', 'card_token'], 'string', 'max' => 250],
            [['currency'], 'string', 'max' => 3],
            [['merchant_trans_id'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'status' => 'Status',
            'status_note' => 'Status Note',
            'created' => 'Created',
            'modified' => 'Modified',
            'currency' => 'Currency',
            'total' => 'Total',
            'amount' => 'Amount',
            'delivery' => 'Delivery',
            'tax' => 'Tax',
            'description' => 'Description',
            'user_id' => 'User ID',
            'invoice_id' => 'Invoice ID',
            'payment_id' => 'Payment ID',
            'card_token' => 'Card Token',
            'token' => 'Token',
            'phone_number' => 'Phone Number',
            'merchant_trans_id' => 'Merchant Trans ID',
            'note' => 'Note',
        ];
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
