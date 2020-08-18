<?php

namespace common\models;

use Yii;
use common\helpers\TextHelper;
use common\models\Images;

use yii\imagine\Image;
use yii\web\UploadedFile;


/**
 * This is the model class for table "uzcard_payments".
 *
 * @property integer $id
    
 * @property integer $user_id
    
 * @property integer $credit_item_id
    
 * @property integer $payment_id
    

    
 * @property integer $created_at
    
 * @property string $username
    
 * @property string $refNum
    
 * @property string $ext
    
 * @property string $pan
    
 * @property string $pan2
    
 * @property string $expiry
    
 * @property string $tranType
    
 * @property string $date7
    
 * @property string $date12
    
 * @property string $amount
    
 * @property string $currency
    
 * @property string $stan
    
 * @property string $field38
    
 * @property string $field48
    
 * @property string $field91
    
 * @property string $merchantId
    
 * @property string $terminalId
    
 * @property string $resp
    
 * @property string $respText
    
 * @property string $respSV
    
 * @property string $status
    
 */
class UzcardPayments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'uzcard_payments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'credit_item_id', 'payment_id', 'created_at', 'trans_id'], 'integer'],
            [['username'], 'string', 'max' => 128],
            [['refNum', 'pan', 'pan2', 'expiry', 'tranType', 'amount'], 'string', 'max' => 32],
            [['date7'], 'string', 'max' => 12],
            [['date12'], 'string', 'max' => 12],
            [['currency'], 'string', 'max' => 3],
            [['stan', 'field38', 'field48', 'field91', 'merchantId', 'terminalId', 'resp', 'respSV', 'status'], 'string', 'max' => 16],
            [['respText', 'ext'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'credit_item_id' => 'Credit Item ID',
            'payment_id' => 'Payment ID',
            'Столбец 4' => 'Столбец 4',
            'created_at' => 'Created At',
            'username' => 'Username',
            'refNum' => 'Ref Num',
            'ext' => 'Ext',
            'pan' => 'Pan',
            'pan2' => 'Pan2',
            'expiry' => 'Expiry',
            'tranType' => 'Tran Type',
            'date7' => 'Date7',
            'date12' => 'Date12',
            'amount' => 'Amount',
            'currency' => 'Currency',
            'stan' => 'Stan',
            'field38' => 'Field38',
            'field48' => 'Field48',
            'field91' => 'Field91',
            'merchantId' => 'Merchant ID',
            'terminalId' => 'Terminal ID',
            'resp' => 'Resp',
            'respText' => 'Resp Text',
            'respSV' => 'Resp Sv',
            'status' => 'Status',
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
