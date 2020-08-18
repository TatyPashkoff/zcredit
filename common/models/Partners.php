<?php

namespace common\models;

use Yii;
use common\helpers\TextHelper;
use common\models\Images;
use yii\helpers\ArrayHelper;

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
class Partners extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partners';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer' ],
            [['image'], 'string', 'max' => 16],
            [['imagebaner'], 'string', 'max' => 30],
            [['title', 'email', 'site'], 'string', 'max' => 255],
            [['shortdesсription'], 'string'],
            [['description'], 'string'],
            [['phone'], 'string', 'max' => 32],
            [['cat_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => Yii::t('app','Статус'),
            'type' => Yii::t('app','Отрасль'),
            'email' => 'Email',
            'image' => Yii::t('app','Превью для партнера'),
            'imagebaner' => Yii::t('app','Банер для партнера'),
            'title' => Yii::t('app','Название'),
            'shortdesсription' => Yii::t('app','Краткий лозунг'),
            'description' => Yii::t('app','Описание'),
            'site' => Yii::t('app','Сайт'),
            'phone' => Yii::t('app','Телефон'),
            'cat_id' => Yii::t('app','Категория'),
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


            
            // сохранение превью изображения

            if( $file = UploadedFile::getInstance($this, 'tmp_image' ) ){

                if( ! preg_match('/image\//',$file->type) ) return false; // загружена не картинка!


                $fname = time() . '.' . $file->extension;

                $path = Yii::getAlias("@frontend/web/uploads/partners");
                if(!is_dir($path)) @mkdir($path,0777);

                $path = Yii::getAlias("@frontend/web/uploads/partners/" . $this->id . '/' );

                @unlink($path . $this->image);
                @unlink($path . 'thumb/'. $this->image);

                if(!is_dir($path)) @mkdir($path);
                if(!is_dir($path.'thumb')) @mkdir($path .'thumb',0777);



                $filepath = $path . $fname;

                // основная картинка - оригинал
                $file->saveAs($filepath);

                // эскиз
                Image::thumbnail($filepath, 250, 250)
                    ->save($path . 'thumb/' . $fname , ['quality' => 100]);



                $this->image = $fname;
                if(!$this->save()){
                    //print_r($this->getErrors()); exit;
                    return false;
                }

            }


            // сохранение рекламного банера

            if( $file = UploadedFile::getInstance($this, 'tmp_imagebaner' ) ){


                if( ! preg_match('/image\//',$file->type) ) return false; // загружена не картинка!


                $fname = time() . '.' . $file->extension;


                $path = Yii::getAlias("@frontend/web/uploads/partners");

                if(!is_dir($path)) @mkdir($path);

                $path = Yii::getAlias("@frontend/web/uploads/partners/" . $this->id . '/' );
                @unlink($path . $this->imagebaner);
                @unlink($path . 'thumb/'. $this->imagebaner);

                if(!is_dir($path)) @mkdir($path);
                if(!is_dir($path.'thumb')) @mkdir($path .'thumb');



                $filepath = $path . $fname ;

                // основная картинка - оригинал
                $file->saveAs($filepath);

                // эскиз
                Image::thumbnail($filepath, 250, 250)
                    ->save($path . 'thumb/' . $fname , ['quality' => 100]);



                $this->imagebaner = $fname;
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

    public function getImage(){
        return '/uploads/partners/' . $this->id .'/'. $this->image;
    }
}
