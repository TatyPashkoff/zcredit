<?php

namespace common\models;

use Yii;
use common\helpers\TextHelper;
use yii\data\ActiveDataProvider;


/**
 * This is the model class for table "partners".
 *
 * @property integer $id

 * @property integer $status

 * @property string $image

 * @property string $title_ru

 * @property string $title_uz

 * @property string $title_tr

 * @property string $title_en

 * @property string $email

 * @property string $site

 * @property string $phone

 */
class PartnersCats extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partners_cats';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_name'], 'string' ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cat_name' => Yii::t('app','Название категории'),
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
                //print_r($this->getErrors());
                return false;
            }
            
            Yii::$app->session->setFlash('info-success','Сохранение успешно завершено!');

            return true;
        }
        return false;

    }

    public function search($params)
    {
        $query = PartnersCats::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        return $dataProvider;
    }

    public function selectPartnersCats() {
        $partnersCats = PartnersCats::find()
            ->asArray()
            ->all();
        //var_dump($partnersCats);die();
        return $partnersCats;
    }
}
