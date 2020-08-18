<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\KycSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kyc';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kyc-index">



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
                'attribute' => 'status_verify',
                'filter'=> ['1' => 'Вкл.', '0' => 'Откл.'],
                'content'=>function($data){
                    return  $data->status_verify ? '<span class="label label-success">Вкл.</span>' : '<span class="label label-danger">Откл.</span>';
                }
            ],
            'created_at:date',
            [
                'attribute' => 'client_id',
                'label'=>'Клиент',
                'filter' => false,
                'content'=>function($data){
                    return  @$data->client->username . '&nbsp;'. @$data->client->lastname ;
                }
            ],            // 'status_verify',
            'date_verify:date',
            'delay',
            'salary',
            'credit_month',
            'credit_year',
            'credit_rating',

            ['class' => 'yii\grid\ActionColumn',
				'template'=>'{update} {delete}'
			],
        ],
    ]); ?>

</div>
