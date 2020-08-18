<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список товаров в кредите пользователя ';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">

    <a href="/admin/clients/credits?user_id=<?=$user_id ?>" class="btn btn-success">Назад</a>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'credit_id',
            'title',
            'amount',
            'price',
            'quantity',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {products} {delete}',
                'buttons' => [
                    'update'=> function ($url, $model, $key) {
                        return  '<a href="/admin/clients/update-item?id='.$key.'" title="Изменить"><i class="glyphicon glyphicon-pencil"></i></a>';
                    },
                    'products' => function ($url, $model, $key) {
                        return  ''; // '<a href="/admin/clients/credits-items?id='.$key.'" title="Список товаров"><i class="fa fa-bars"></i></a>';
                    }]
            ],
        ],
    ]); ?>

</div>
