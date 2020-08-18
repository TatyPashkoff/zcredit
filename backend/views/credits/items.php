<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CreditItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Credit Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credit-items-index">

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить Credit Items', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'title',
            'credit_id',
            'price',
            'amount',
            'quantity',
            [
                'attribute' => 'summ',
                'content'=>function($data){
                    return  $data->price * $data->quantity ;
                }
            ],
            // 'article',

            ['class' => 'yii\grid\ActionColumn',
				'template'=>'{update} {delete}'
			],
        ],
    ]); ?>

</div>
