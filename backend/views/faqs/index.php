<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FaqsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Часто задаваемые вопросы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faqs-index">

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
            'attribute' => 'status',
            'filter'=> ['1' => 'Вкл.', '0' => 'Откл.'],
            'content'=>function($data){
                return  $data->status ? '<span class="label label-success">Вкл.</span>' : '<span class="label label-danger">Откл.</span>';
            }
            ],
            'question',
            // 'answer',

            ['class' => 'yii\grid\ActionColumn',
				'template'=>'{update} {delete}'
			],
        ],
    ]); ?>

</div>
