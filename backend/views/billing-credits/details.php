<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BillingHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Детализация договора';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-history-index">
    <h2>Кредитная история</h2>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'delay',
            'credit_date:date',
            'payment_date',
            'payment_type',
            [
                'label' => 'Тип оплаты',
                'format' => 'text',
                'value' => function($data){
                    if ($data->payment_type == 1) {return 'Payme';}
                    elseif ($data->payment_type == 2) {return 'Click';}
                    else {return 'Uzcard';}
                },
            ],
            'price',
        ],
    ]); ?>
</div>
