<?php

namespace common\models;

use Yii;
use common\helpers\TextHelper;
use common\models\Images;

use yii\imagine\Image;
use yii\web\UploadedFile;


/**
 * This is the model class for table "credit_items".
 *
 * @property integer $id
    
 * @property integer $credit_id
    
 * @property string $price
    
 * @property string $amount
 *
 * @property string $discount_sum

 * @property integer $quantity
    
 * @property string $title
    
 * @property string $article
    
 */
class CreditItems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'credit_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['credit_id', 'quantity'], 'integer'],
            [['price', 'amount','discount_sum'], 'number'],
            [['title'], 'string', 'max' => 128],
            [['article'], 'string', 'max' => 32]
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
            'price' => 'Price',
            'amount' => 'Amount',
            'discount_sum' => 'Discount_sum',
            'quantity' => 'Quantity',
            'title' => 'Title',
            'article' => 'Article',
        ];
    }

    // получение кредита
    public function getCredit(){

        return $this->hasOne(Credits::className(),['id'=>'credit_id']);

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
