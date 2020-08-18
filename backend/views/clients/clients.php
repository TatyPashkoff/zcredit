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
                    return '+998-' . $data->phone;
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
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>

</div>
