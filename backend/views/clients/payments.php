<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CreditItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'График оплаты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credit-items-index">

    <a href="/admin/clients/credits?user_id=<?=$user_id ?>" class="btn btn-success">Назад</a>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [   'attribute' => 'payment_status',
                'filter'=> ['1' => 'Оплачено', '0' => 'Не оплачено',],
                'content'=>function($model){
                    return  $model->payment_status ? '<span class="label label-success">Оплачено</span>' : '<span class="label label-danger">Не оплачено</span>';
                }
            ],
            'credit_id',
            'credit_date:date',
            'payment_date:date',
            'payment_type',
            'price',

            ['class' => 'yii\grid\ActionColumn',
				'template'=>'{update} {delete}',
                'buttons' => [

                    'update'=> function ($url, $model, $key) {
                        return  '<a href="/admin/clients/update-payment?id='.$key.'" title="Изменить"><i class="glyphicon glyphicon-pencil"></i></a>';
                    },
                ]

			],
        ],
    ]); ?>

</div>
