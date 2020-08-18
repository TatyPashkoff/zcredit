<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\NotifySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notifies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notify-index">

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить Notify', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'created_at',
            // 'user_id',
            // 'code',
            [
            'attribute' => 'status',
            'filter'=> ['1' => 'Вкл.', '0' => 'Откл.'],
            'content'=>function($data){
                return  $data->status ? '<span class="label label-success">Вкл.</span>' : '<span class="label label-danger">Откл.</span>';
            }
            ],
                    // 'state',
            // 'msg',

            ['class' => 'yii\grid\ActionColumn',
				'template'=>'{update} {delete}'
			],
        ],
    ]); ?>

</div>
