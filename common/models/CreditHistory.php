<?php

namespace common\models;

use Yii;
use common\helpers\TextHelper;
use common\models\Images;

use yii\imagine\Image;
use yii\web\UploadedFile;


/**
 * This is the model class for table "credit_history".
 *
 * @property integer $id
    
 * @property integer $credit_id
    
 * @property integer $payment_date
    
 * @property integer $payment_type
    
 * @property integer $payment_status
    
 * @property string $price
    
 */
class CreditHistory extends \yii\db\ActiveRecord
{

    public $summ;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'credit_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['credit_id', 'payment_date','delay','payment_type', 'payment_status'], 'integer'],
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
            'credit_id' => Yii::t('app','ID кредита'),
            'delay' => Yii::t('app','Просрочка'),
            'payment_date' => Yii::t('app','Дата оплаты'),
            'credit_date' => Yii::t('app','Дата предстоящей оплаты'),
            'payment_type' => Yii::t('app','Платежная система'),
            'payment_status' => Yii::t('app','Статус оплаты'),
            'price' => Yii::t('app','Сумма мес'),
        ];
    }

    // получение кредита
    public function getCredit(){
        return $this->hasOne(Credits::className(),['id'=>'credit_id']);
    }

    public function getClient(){
        return $this->hasOne(User::className(),['id'=>'user_id'])->viaTable('credits',['id'=>'credit_id']);
    }

    // получение поставщика
    public function getSupplier(){
        return $this->hasOne(User::className(),['id'=>'supplier_id'])->where(['role'=>User::ROLE_SUPPLIER])->viaTable('credits',['id'=>'credit_id']);
    }

    // получение договора
    public function getContract(){
        return $this->hasOne(Contracts::className(),['id'=>'contract_id'])->viaTable('credits',['id'=>'credit_id']);
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
