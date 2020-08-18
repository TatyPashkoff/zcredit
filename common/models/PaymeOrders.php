<?php

namespace common\models;

use Yii;

class PaymeOrders extends \yii\db\ActiveRecord
{
	
	const STATE_CREATED                  = 1;
    const STATE_COMPLETED                = 2;
    const STATE_CANCELLED                = -1;
    const STATE_CANCELLED_AFTER_COMPLETE = -2;

    const REASON_RECEIVERS_NOT_FOUND         = 1;
    const REASON_PROCESSING_EXECUTION_FAILED = 2;
    const REASON_EXECUTION_FAILED            = 3;
    const REASON_CANCELLED_BY_TIMEOUT        = 4;
    const REASON_FUND_RETURNED               = 5;
    const REASON_UNKNOWN                     = 10;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payme_orders';
    }
    
        public function rules()
    {
        return [
            [['product_ids', 'amount', 'state', 'user_id', 'phone'], 'required']
        ];
    }

}
