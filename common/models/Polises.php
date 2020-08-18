<?php

namespace common\models;

use Yii;
use common\helpers\TextHelper;
use common\models\Images;

use yii\imagine\Image;
use yii\web\UploadedFile;


/**
 * This is the model class for table "polises".
 *
 * @property integer $id
    
 * @property integer $credit_id
    
 * @property integer $contract_id
    
 * @property integer $client_id
    
 * @property integer $supplier_id
    
 * @property integer $created_at
    
 * @property integer $contractRegistrationID
    
 * @property string $polisSeries
    
 * @property string $polisNumber
    
 * @property integer $status
    
 */
class Polises extends \yii\db\ActiveRecord
{
    const ITEMS_COUNT = 20;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'polises';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['credit_id', 'contract_id', 'client_id', 'supplier_id', 'created_at', 'contractRegistrationID','status'], 'integer'],
            [['polisSeries', 'polisNumber'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'credit_id' => 'Credit ID',
            'contract_id' => 'Contract ID',
            'client_id' => 'Client ID',
            'supplier_id' => 'Supplier ID',
            'created_at' => 'Created At',
            'contractRegistrationID' => 'Contract Registration ID',
            'polisSeries' => 'Polis Series',
            'polisNumber' => 'Polis Number',
            'status' => 'Status',
        ];
    }

    // получаем клиента
    public function getClient()
    {
        return $this->hasOne(User::className(), ['id' => 'client_id']);
    }
    // получаем поставщика
    public function getSupplier()
    {
        return $this->hasOne(User::className(), ['id' => 'supplier_id']);
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
