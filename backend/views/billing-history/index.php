<?php

use yii\helpers\Html;
use yii\grid\GridView;
use BillingHistory;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BillingHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пополнения биллинга';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-history-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'user_id',
            'created_at:date',
            'summ',
            [
                'attribute'=>'payment_type',
                'format' => 'text',
				'filter'=> ['1' => 'Payme-shop', '2' => 'Click-shop', '3' => 'Uzcard-transpay', '5' => 'Uzcard-shop'],
                'value' => function($data){
                    if ($data->payment_type == 1) {return 'Payme-shop';}
                    elseif ($data->payment_type == 2) {return 'Click-shop';}
                    elseif ($data->payment_type == 3) {return 'Uzcard-transpay';}
					elseif ($data->payment_type == 5) {return 'Uzcard-shop';}
                },
            ],
			[
                'attribute'=>'state',
                'format' => 'html',
                'filter'=> ['0' => 'Создана', '1' => 'Оплачена', '-1' => 'Отменена'],
                'content'=>function($data) {
                    if ($data->state == 0) {return '<span class="label label-primary">Создана</span>';}
                    elseif ($data->state == 1) {return '<span class="label label-success">Оплачена</span>';}
                    elseif ($data->state == -1) {return '<span class="label label-danger">Отменена</span>';}
                },
            ],
        ],
    ]); ?>

</div>
