<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список кредитов пользователя ';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">

    <?php /* <p>
        <a href="/admin/clients/credits?user_id=<?=$user_id ?>" class="btn btn-success">Назад</a>
    </p> */ ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            [   'attribute' => 'status',
                'filter'=> ['1' => 'Вкл.', '0' => 'Откл.',],
                'content'=>function($model){
                    return  $model->status ? '<span class="label label-success">Вкл.</span>' : '<span class="label label-danger">Откл.</span>';
                }
            ],
           // 'username',
            //'name',
            /* [
                 'attribute'=>'phone',
                 'format' => 'html',
                 //'filter'=> '',// $roles = ['Гость', 1=>'Родитель',2=>'Школа',3=>'Гос. служба', 4=>'Менеджер', 9=>'Админ'],
                 'content'=>function($data) {
                     return $data->phone;
                 }
             ],

           /*[
                 'attribute'=>'role',
                 'format' => 'html',
                 'filter'=> $roles = [1=>'user',5=>'Менеджер',9=>'Админ'],
                 'content'=>function($data) {
                     $roles = [1=>'Пользователь',5=>'Менеджер', 9=>'Админ'];

                     return $roles [ $data->role ];
                 }
             ],*/

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {products} {payments} {delete}',
                'buttons' => [
                    'update' =>function ($url, $model, $key) {
                        return '<a href="/admin/clients/update-credit?id='.$key.'" title="Изменить"> <i class="glyphicon glyphicon-pencil"></i> </a> ';
                    },
                    'products' => function ($url, $model, $key) {
                        return '<a href="/admin/clients/credit-items?credit_id='.$key.'" title="Список товаров"> <i class="fa fa-bars"></i> </a> ';
                    },
                    'payments' => function ($url, $model, $key) {
                        return '<a href="/admin/clients/payments?credit_id='.$key.'" title="График погашения"> <i class="fa fa-bar-chart"></i> </a> ';
                    },

                    ]
            ],
        ],
    ]); ?>

</div>
