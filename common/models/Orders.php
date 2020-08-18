<?php

namespace common\models;

use Yii;
use common\helpers\TextHelper;
use common\models\Images;

use yii\imagine\Image;
use yii\web\UploadedFile;


/**
 * This is the model class for table "orders".
 *
 * @property integer $id
    
 * @property integer $created_at
    
 * @property integer $kyc_id
    
 * @property integer $credit_id
    
 * @property integer $status
    
 */
class Orders extends \yii\db\ActiveRecord
{

    const ITEMS_COUNT = 20;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'kyc_id', 'credit_id','status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'kyc_id' => 'Kyc ID',
            'credit_id' => 'Credit ID',
            'status' => 'Status',
        ];
    }

    public function getCredit(){
        return $this->hasOne(Credits::className(),['id'=>'credit_id']);
    }

    public function getKyc(){
        return $this->hasOne(Kyc::className(),['id'=>'kyc_id']);
    }

    public function getClient(){
        return $this->hasOne(User::className(),['id'=>'user_id'])->viaTable('credits',['id'=>'credit_id'])->where(['role'=>User::ROLE_CLIENT]);
    }
    public function getSupplier(){
        return $this->hasOne(User::className(),['id'=>'supplier_id'])->viaTable('credits',['id'=>'credit_id'])->where(['role'=>User::ROLE_SUPPLIER]);
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
