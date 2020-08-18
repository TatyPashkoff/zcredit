<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BillingServicesSearchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Оплата сервисов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-services-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',

            [
                'attribute' => 'status',
                'filter' => ['1' => 'оплачен', '0' => 'не оплачен',],
                'content' => function ($data) {
                    return $data->status == 1 ? '<span class="label label-success">оплачен</span>' : '<span class="label label-danger">не оплачен</span>';
                },
            ],
            'user_id',
            'created_at:date',
            'amount',
            'service_type',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{upd} '
            ],
        ],
    ]); ?>

</div>
