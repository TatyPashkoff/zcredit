<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\number\NumberControl;

/* @var $this yii\web\View */
/* @var $searchModel common\models\StockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Акции';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="markers-index">

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить акцию Markers', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'title',
            'date_start:date',
            'date_end:date',
            'sum',
            'percent',
            'company',
            'margin',

            [
            'attribute' => 'status',
            'filter'=> ['1' => 'Вкл.', '0' => 'Откл.'],
            'content'=>function($data){
                return  $data->status ? '<span class="label label-success">Вкл.</span>' : '<span class="label label-danger">Откл.</span>';
            }
            ],


        
            ['class' => 'yii\grid\ActionColumn',
				'template'=>'{update} {delete}'
			],
        ],
    ]); ?>

</div>
