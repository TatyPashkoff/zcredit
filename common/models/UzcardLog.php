<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "uzcard_log".
 *
 * @property int $id
 * @property int $click_trans_id
 * @property int $service_id
 * @property int $click_paydoc_id
 * @property int $merchant_trans_id
 * @property int $merchant_prepare_id
 * @property string $amount
 * @property int $action
 * @property int $error
 * @property string $error_note
 * @property string $sign_time
 * @property string $sign_string
 */
class UzcardLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'uzcard_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'click_trans_id', 'service_id', 'click_paydoc_id', 'merchant_trans_id', 'amount', 'action', 'error', 'error_note', 'sign_time', 'sign_string'], 'required'],
            [[ 'click_trans_id', 'service_id', 'click_paydoc_id', 'merchant_trans_id', 'action', 'error'], 'integer'],
            [['amount'], 'number'],
            [['error_note', 'sign_time', 'sign_string'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'click_trans_id' => 'Click Trans ID',
            'service_id' => 'ID сервиса',
            'click_paydoc_id' => 'Click Paydoc ID',
            'merchant_trans_id' => 'Merchant Trans ID',
            'merchant_prepare_id' => 'Merchant Prepare ID',
            'amount' => 'Сумма',
            'action' => 'Действие',
            'error' => 'Ошибка',
            'error_note' => 'Ошибка',
            'sign_time' => 'Время подписи',
            'sign_string' => 'Подпись',
        ];
    }
}
