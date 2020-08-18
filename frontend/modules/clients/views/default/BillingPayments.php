<?php

namespace common\models;

use Yii;
use common\helpers\TextHelper;
use common\models\Images;

use yii\imagine\Image;
use yii\web\UploadedFile;


/**
 * This is the model class for table "billing_payments".
 *
 * @property integer $id
    
 * @property integer $credit_item_id
    
 * @property integer $credit_id
    
 * @property integer $user_id
    
 * @property integer $created_at
    
 * @property string $summ
    
 * @property integer $status
    
 */
class BillingPayments extends \yii\db\ActiveRecord
{

    public $rest;
    public $fio;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'billing_payments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['credit_item_id', 'credit_id', 'user_id', 'created_at','status','contract_id'], 'integer'],
            [['summ', 'debt'], 'number'],

        ];
    }




    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'credit_item_id' => 'Credit Item ID',
            'credit_id' => 'Кредит',
            'user_id' => 'ID клиента',
            'created_at' => 'Дата оплаты',
            'summ' => 'Сумма',
            'debt' => 'Задолженность',
            'status' => 'Статус',
            //'rest' => 'Остаток задолженности',
        ];
    }

    public function getContract(){
        return $this->hasOne(Contracts::className(),['credit_id'=>'credit_id']);
    }
    public function getCredit(){
        return $this->hasOne(Credits::className(),['id'=>'credit_id']);
    }

    public function getHistory(){
        return $this->hasOne(CreditHistory::className(),['credit_id'=>'credit_id']);
    }

    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }

    public function getClient(){
        return $this->hasOne(User::className(),['id'=>'user_id']);
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
