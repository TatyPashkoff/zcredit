<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pages".
 *
 * @property string $id
 * @property string $page
 * @property string $data
 */
class Pages extends \yii\db\ActiveRecord
{
    
    public $tmp_image; // 1 изображение для about блока
    public $tmp_banner; // баннер
    public $tmp_images; // 3 изображения для инфо блока
    public $tmp_images_menu; // 3 изображения для блока меню
    public $tmp_gallery_images; // N изображений слайдера
    
    public $price; // прайс в текстовом формате doc
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data'], 'string'],
            [['page'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'page' => Yii::t('app', 'Страница'),
            'data' => Yii::t('app', 'Данные'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */


}
