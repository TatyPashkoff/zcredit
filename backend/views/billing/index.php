<?php

use yii\helpers\Html;
use yii\grid\GridView;
use BillingHistory;
use BillingPayments;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BillingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Финансовый отчет';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'client_id',
            'created_at:date',
            'amount',
            [
                'attribute'=>'type',
                'format' => 'text',
                'filter'=> ['1' => 'Пополнение', '2' => 'Автосписание'],
                'value' => function($data){
                    if ($data->type == 1) {return 'Пополнение';}
                    elseif ($data->type == 2) {return 'Автосписание';}
                },
            ],
            [
                'attribute'=>'payment_type',
                'format' => 'text',
                'filter'=> ['1' => 'Payme-shop', '2' => 'Click-shop', '3' => 'Uzcard-transpay', '4' => 'Карта', '5' => 'Uzcard-shop'],
                'value' => function($data){
                    if ($data->payment_type == 1) {return 'Payme-shop';}
                    elseif ($data->payment_type == 2) {return 'Click-shop';}
                    elseif ($data->payment_type == 3) {return 'Uzcard-transpay';}
                    elseif ($data->payment_type == 4) {return 'Карта';}
                    elseif ($data->payment_type == 5) {return 'Uzcard-shop';}
                },
            ],
            [
                'attribute'=>'status',
                'format' => 'html',
                'filter'=> ['0' => 'Создана', '1' => 'Оплачена', '-1' => 'Отменена'],
                'content'=>function($data) {
                    if ($data->status == 0) {return '<span class="label label-primary">Создана</span>';}
                    elseif ($data->status == 1) {return '<span class="label label-success">Оплачена</span>';}
                    elseif ($data->status == -1) {return '<span class="label label-danger">Отменена</span>';}
                },
            ],
        ],
    ]); ?>

</div>
