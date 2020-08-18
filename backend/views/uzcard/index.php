<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UzcardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Uzcards';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uzcard-index">

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить Uzcard', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'created_at',
            // 'payment_date',
            // 'client_id',
            // 'summ',
            // 'transaction_id',
            [
            'attribute' => 'status',
            'filter'=> ['1' => 'Вкл.', '0' => 'Откл.'],
            'content'=>function($data){
                return  $data->status ? '<span class="label label-success">Вкл.</span>' : '<span class="label label-danger">Откл.</span>';
            }
            ],
                    // 'order',

            ['class' => 'yii\grid\ActionColumn',
				'template'=>'{update} {delete}'
			],
        ],
    ]); ?>

</div>
