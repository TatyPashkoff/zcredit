<?php

namespace common\models;

use Yii;
use common\helpers\TextHelper;
use common\models\Images;
use yii\imagine\Image;



/**
 * This is the model class for table "services".
 *
 * @property integer $id

 * @property integer $img

 * @property integer $service_id

 * @property string $name

 * @property string $status

 */
class Services extends \yii\db\ActiveRecord
{


    const STOCK_STATUS = [
        0 => 'активная',
        1 => 'не активная',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'services';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_id', 'status'], 'integer'],
            [['name','img'],'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'service_id' => 'Сервис',
            'img' => 'Фото',
            'name' => Yii::t('app','Название компании'),
            'status' => Yii::t('app','Статус'),
        ];
    }


    public function updateModel($new=false){

        $post = Yii::$app->request->post();

        if($this->load($post) ) {

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
