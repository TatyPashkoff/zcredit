<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\UrlManager;


/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Вендоры';

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

    <p>
        <?php

        // echo Yii::$app->controller->id;
        if( !Yii::$app->user->isGuest && Yii::$app->user->identity->role == 9 && Yii::$app->controller->id == 'managers'){ ?>
            <?= Html::a('Добавить администратора', ['managers/create-admin'], ['class' => 'btn btn-success']) ?>
        <?php } ?>

    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'created_at:date',
            'id',
            [   'attribute' => 'status',
                'filter'=> ['1' => 'Вкл.', '0' => 'Откл.',],
                'content'=>function($data){
                    return  $data->status ? '<span class="label label-success">Вкл.</span>' : '<span class="label label-danger">Откл.</span>';
                }
            ],
            //'username',
            'brand',
            'company',
            [
                'attribute'=>'filial',
                'format' => 'html',
                'filter'=> ['1' => 'Филиал', '' => 'Нет',],
                'content'=>function($data) {
                    return  $data->filial ? '<span class="label label-success">Филиал</span>' : '<span class="label label-danger">Нет</span>';
                }
            ],
            [
                'attribute'=>'phone',
                'format' => 'html',
                'content'=>function($data) {
                    return $data->phone;
                }
            ],
            [
                'attribute'=>'nds_state',
                'format' => 'html',
                'filter'=> ['1' => 'Да', '0' => 'Нет',],
                'content'=>function($data) {
                    return  $data->nds_state ? '<span class="label label-success">Да</span>' : '<span class="label label-danger">Нет</span>';
                }
            ],
           // 'nds',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {print} {delete} {create} ',
                'buttons' => [
                    'create' => function ($url, $model, $key) {
                        if($model->filial == 0)
                        return Html::a('', ['create-filial', 'id' => $key], ['class' => 'fa fa-plus']);
                    },
                    'print' => function ($url, $model, $key) {
                        return Html::a('', ['print', 'id' => $key], ['class' => 'fa fa-print']);
                    }

                ]
            ],
        ],
    ]); ?>

</div>
