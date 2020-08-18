<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "transactions".
 *
 * @property integer $id
 */
class VendorItems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vendor_items}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'discount', 'printer_number', 'seal_number', 'nds_state','margin_three','margin_six'], 'integer'],
            [['brand'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'user_id',
            'brand' => 'Бренд',
            'discount' => 'discount',
            'printer_number' => 'printer_number',
            'seal_number' => 'seal_number',
            'nds_state' => 'Ндс плательщик',
            'margin_three' => 'margin_three',
            'margin_six' => 'margin_six',
        ];
    }



    public function getUser(){

        return $this->hasOne(User::className(),['id'=>'user_id']);
    }




}


