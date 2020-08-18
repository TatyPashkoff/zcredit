<?php

namespace common\models;

use Yii;
use common\helpers\TextHelper;
use common\models\Images;
use yii\imagine\Image;



/**
 * This is the model class for table "stock_items".
 *
 * @property integer $id

 * @property integer $stock_id

 * @property integer $user_id

 * @property integer $credit_id

 * @property integer $credit_sum

 * @property string $stock_sum

 * @property string $stock_discount

 */
class StockItems extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stock_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','stock_id', 'user_id', 'credit_id', 'credit_sum','stock_sum','stock_discount'], 'integer'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stock_id' => Yii::t('app','stock_id'),
            'user_id' => Yii::t('app','user_id'),
            'credit_id' => Yii::t('app','credit_id'),
            'credit_sum' => Yii::t('app','credit_sum'),
            'stock_sum' => Yii::t('app','stock_sum'),
            'stock_discount' => Yii::t('app','stock_discount'),

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
