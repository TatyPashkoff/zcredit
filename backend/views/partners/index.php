<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PartnersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'партнеры';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partners-index">

    <p>
        <?= Html::a('Добавить партнера', ['create'], ['class' => 'btn btn-success']) ?>
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
            [
                'attribute'=>'image',
                'content'=>function($data){
                    $path = 'https://zmarket.uz/uploads/partners/'.$data->id.'/thumb/'.$data->image;
                    return '<img width="150px" src="'.$path.'">';
            }
            ],
            'title',

             'email:email',
            'site',
             'phone',


            ['class' => 'yii\grid\ActionColumn',
				'template'=>'{update} {delete}',

			],
        ],
    ]); ?>

</div>
