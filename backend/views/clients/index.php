<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .user-confirm{
        padding: 5px;
        margin:0px 2px;
        color: #1cbb56;
    }
</style>
<div class="user-index">

    <?php /* <h1><?= Html::encode($this->title) ?></h1> */ ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



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
            'username',
            //'name',
            [
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
                'template' => '{update} {credit}',
                'buttons' => [
                    'credit' => function ($url, $model, $key) {
                        return '<a href="/admin/clients/credits?user_id='.$key.'" title="Список кредитов"><i class="fa fa-credit-card"></i></a>';
                    }]
            ],
        ],
    ]); ?>

</div>
