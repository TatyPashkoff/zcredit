<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "humo_payments".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $credit_item_id
 * @property integer $created_at
 * @property string $payment_id
 * @property string $merchant_id
 * @property string $terminal_id
 * @property string $point_code
 * @property string $centre_id
 * @property string $internal_pan_masked
 * @property string $transaction_amount
 * @property string $cardholder_amount
 * @property string $cardholder_ccy_code
 * @property string $conversion_rate
 * @property string $auth_action_code
 * @property string $auth_action_code_final
 * @property string $auth_appr_code
 * @property string $auth_ref_number
 * @property string $auth_stan
 * @property string $auth_time
 * @property string $stip_client_id
 * @property string $card_type
 * @property string $merchant_name
 * @property string $acq_inst
 * @property string $auth_row_numb1
 * @property string $reconcile_info
 * @property string $iss_ref_data
 */

class HumoPayments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'humo_payments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id','credit_item_id','created_at'], 'integer'],
            [['payment_id','cardholder_ccy_code','auth_action_code','auth_action_code_final','auth_appr_code','auth_ref_number','auth_stan','auth_time','stip_client_id','card_type'], 'string'],
            [['acq_inst','auth_row_numb1','reconcile_info','iss_ref_data'], 'string'],
            [['merchant_id','terminal_id','point_code','centre_id','internal_pan_masked','transaction_amount','cardholder_amount','conversion_rate','merchant_name'], 'string'],
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
            'created_at' => 'Created At',
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
