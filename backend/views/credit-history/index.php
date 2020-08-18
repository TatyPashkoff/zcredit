<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CreditHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Credit Histories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credit-history-index">

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?//= Html::a('Добавить Credit History', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

             //'id',
             'credit_id',
             'credit_date:date',
            [
                'attribute' => 'user_id',
                'label'=>'User ID',
                'content'=>function($model){
                    return  @$model->client->id;
                }
            ],
            [
                'attribute' => 'clientFio',
                'label'=>'ФИО',
                'content'=>function($model){
                    return  @$model->client->lastname . '&nbsp;'. @$model->client->username. '&nbsp;'. @$model->client->patronymic ;
                }
            ],
            [
                'attribute' => 'phone',
                'label'=>'Телефон',
                'content'=>function($model){
                    return  @$model->client->phone ;
                }
            ],
             //'payment_type',
             //'payment_status',
            [
                'attribute' => 'user_confirm',
                'label'=>'Статус',
                'filter'=> ['1' => 'Оформлен', '0' => 'Не оформлен'],
                'content'=>function($data){
                if($data->credit){
                    return  $data->credit->user_confirm ? '<span class="label label-success">Оформлен</span>' : '<span class="label label-danger">Неn</span>';
                }}
            ],
             'price',
            [
                'attribute' => 'credit',
                'label'=>'Общая сумма долга',
                'content'=>function($model){
                    //return  @$model->credit->id;
                    if($model->credit){
                        return  number_format($model->credit->getPaymentDelaySum(),2,'.',' ');
                    }
                }
            ],

            ['class' => 'yii\grid\ActionColumn',
				'template'=>''
			],
        ],
    ]); ?>

</div>
