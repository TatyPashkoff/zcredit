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
class Transactions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transactions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['paycom_transaction_id', 'paycom_time', 'paycom_time_datetime', 'create_time', 'amount', 'state', 'order_id'], 'required'],
            [['paycom_time_datetime', 'create_time', 'perform_time', 'cancel_time'], 'safe'],
            [['amount', 'state', 'reason', 'order_id'], 'integer'],
            [['paycom_transaction_id'], 'string', 'max' => 25],
            [['paycom_time'], 'string', 'max' => 13],
            [['receivers'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'paycom_transaction_id' => 'Paycom Transaction ID',
            'paycom_time' => 'Paycom Time',
            'paycom_time_datetime' => 'Paycom Time Datetime',
            'create_time' => 'Create Time',
            'perform_time' => 'Perform Time',
            'cancel_time' => 'Cancel Time',
            'amount' => 'Amount',
            'state' => 'State',
            'reason' => 'Reason',
            'receivers' => 'JSON array of receivers',
            'order_id' => 'Order ID',
        ];
    }
}
