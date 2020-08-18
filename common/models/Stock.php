<?php

namespace common\models;

use Yii;
use common\helpers\TextHelper;
use common\models\Images;
use yii\imagine\Image;



/**
 * This is the model class for table "stock".
 *
 * @property integer $id

 * @property integer $title

 * @property integer $date_start

 * @property integer $date_end

 * @property integer $sum

 * @property string $percent

 * @property string $status

 */
class Stock extends \yii\db\ActiveRecord
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
        return 'stock';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','date_start', 'date_end', 'percent', 'status','margin'], 'integer'],
            [['sum'], 'number'],
            [['title','company'],'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => Yii::t('app','Название акции'),
            'date_start' => Yii::t('app','Дата начала'),
            'date_end' => Yii::t('app','Дата окончания'),
            'sum' => Yii::t('app','Сумма'),
            'percent' => Yii::t('app','Скидка в процентах'),
            'margin' => Yii::t('app','Маржа в процентах'),
            'company' => Yii::t('app','Название компании'),
            'status' => Yii::t('app','Статус'),
        ];
    }


    public function updateModel($new=false){


        $post = Yii::$app->request->post();
        if($post){
            $company_list =  $post['Stock']['company'];
            $date_start = strtotime(htmlspecialchars ($post['Stock']['date_start']));
            $date_end = strtotime(htmlspecialchars ($post['Stock']['date_end']));

        }

        if($this->load($post) ) {
            $this->company = implode(",", $company_list);
            $this->date_start = $date_start;
            $this->date_end =  $date_end;

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
