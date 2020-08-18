<?php

namespace common\models;

use Yii;
use common\helpers\TextHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;


/**
 * This is the model class for table "billing".
 *
 * @property integer $id

 * @property integer $client_id

 * @property integer $created_at

 * @property integer $amount

 * @property integer $bil_his_id
 *
 * @property integer $bil_pay_id
 *
 * @property integer $payment_type

 */
class Billing extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'billing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'created_at','payment_type', 'type', 'bil_his_id', 'bil_pay_id', 'status'], 'integer'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Клиент',
            'created_at' => 'Дата создания',
            'amount' => 'Сумма',
            'payment_type' => 'Платежная система',
            'type' => 'Тип',
            'state' => 'Статус',
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
