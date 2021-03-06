<?php

namespace common\models;

use Yii;
use common\helpers\TextHelper;
use yii\data\ActiveDataProvider;
use yii\imagine\Image;
use yii\web\UploadedFile;


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
class PartnersFilials extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partners_filials';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string' ],
            [['description'], 'string' ],
            [['photo'], 'string' ],
            [['phone'], 'string' ],
            [['address'], 'string' ],
			[['workhour'], 'string' ],
			[['cards'], 'string' ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app','Название'),
            'description' => Yii::t('app','Описание'),
            'photo' => Yii::t('app','Фото'),
            'phone' => Yii::t('app','Телефон'),
            'address' => Yii::t('app','Адрес'),
			'workhour' => Yii::t('app','Режим работы'),
			'cards' => Yii::t('app','Способы оплаты'),
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

            // сохранение фото для блока филиала

            if( $file = UploadedFile::getInstance($this, 'photo' ) ){

                if( ! preg_match('/image\//',$file->type) ) return false; // загружена не картинка!


                $fname = time() . '.' . $file->extension;

                $path = Yii::getAlias("@frontend/web/uploads/partners/filials/");
                if(!is_dir($path)) @mkdir($path);

                $path = Yii::getAlias("@frontend/web/uploads/partners/filials/" . $this->id . '/' );
                @unlink($path . $this->photo);
                @unlink($path . 'thumb/'. $this->photo);

                if(!is_dir($path)) @mkdir($path);
                if(!is_dir($path.'thumb')) @mkdir($path .'thumb');



                $filepath = $path . $fname;

                // основная картинка - оригинал
                $file->saveAs($filepath);

                // эскиз
                Image::thumbnail($filepath, 250, 250)
                    ->save($path . 'thumb/' . $fname , ['quality' => 100]);



                $this->photo = $fname;
                if(!$this->save()){
                    //print_r($this->getErrors()); exit;
                    return false;
                }

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

}
