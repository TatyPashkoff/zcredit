<?php

namespace common\models;

use Yii;
use common\helpers\TextHelper;
use common\models\Images;

use yii\imagine\Image;
use yii\web\UploadedFile;


/**
 * This is the model class for table "suppliers_settings".
 *
 * @property integer $id
    
 * @property integer $supplier_id
    
 * @property string $payme_merchant_id
    
 * @property string $click_secret
    
 * @property string $click_merchant_id
    
 * @property string $click_merchant_user_id
    
 * @property string $click_service_id
    
 * @property integer $use_payme
    
 * @property integer $use_click
 * @property integer $deposit_first
 * @property integer $deposit_month

 */
class SuppliersSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'suppliers_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['supplier_id','use_payme', 'use_click'], 'integer'],
            [['deposit_first','deposit_month'],'number'],
            [['payme_merchant_id', 'click_secret', 'click_merchant_id', 'click_merchant_user_id', 'click_service_id'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplier_id' => 'Supplier ID',
            'payme_merchant_id' => 'Payme Merchant ID',
            'click_secret' => 'Click Secret',
            'click_merchant_id' => 'Click Merchant ID',
            'click_merchant_user_id' => 'Click Merchant User ID',
            'click_service_id' => 'Click Service ID',
            'use_payme' => 'Use Payme',
            'use_click' => 'Use Click',
            'deposit_first' => Yii::t('app','Первоначальный взнос'),
            'deposit_month' => Yii::t('app','Ежемесячный взнос'),
        ];
    }


    public function updateModel($new=false){

        $post = Yii::$app->request->post();

        if($this->load($post) ) {

            $this->use_payme = isset($post['use_payme'])? 1:0;
            $this->use_click = isset($post['use_click'])? 1:0;

            if( !$this->save() ){
                Yii::$app->session->setFlash('info-error','Ошибка при сохранении!');
                print_r($this->getErrors());
                return false;
            }
            
            Yii::$app->session->setFlash('info-success','Сохранение успешно завершено!');

            return true;
        }
        return false;

    }

}
