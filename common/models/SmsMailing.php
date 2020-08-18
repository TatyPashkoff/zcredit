<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "transactions".
 *
 * @property integer $id
 * @property string $paycom_transaction_id
 * @property string $paycom_time
 * @property string $paycom_time_datetime
 * @property string $create_time
 * @property string $perform_time
 * @property string $cancel_time
 * @property integer $amount
 * @property integer $state
 * @property integer $reason
 * @property string $receivers
 * @property integer $order_id
 */
class SmsMailing extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'smsmailing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description','datestart','dateend'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Текст смс рассылки',
            'datestart' => 'Начальная дата',
            'dateend' => 'Конечная дата',
        ];
    }
}
