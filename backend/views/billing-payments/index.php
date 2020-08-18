<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BillingPaymentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Оплаты биллинга';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-payments-index">



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'status',
                'filter'=> ['1' => 'Да.', '0' => 'Нет.'],
                'content'=>function($data){
                    return  $data->status ? '<span class="label label-success">Да</span>' : '<span class="label label-danger">Нет</span>';
                }
            ],
            // 'id',
            // 'credit_item_id',
            'credit_id',
            'created_at:date',
            'summ',
            'user_id',
            'fio'=>[
                'attribute'=>'fio',
                'label'=>'ФИО',
                //'format' => 'html',
                'content'=>function($data) {
                    return $data->client->username . ' ' . $data->client->lastname;
                }
            ],

            'debt',
            /*'rest'=>[
                'attribute'=>'rest',
                //'format' => 'html',
                'content'=>function($data) {
                    return $data->credit->deposit_month == $data->history->price ? number_format(0, 2, '.', ' ') : $data->credit->deposit_month - ($data->credit->deposit_month - $data->history->price);
                    //return  $data->credit->deposit_month . ' - ' .  $data->history->price;
                }
            ],*/

        
            /*['class' => 'yii\grid\ActionColumn',
				'template'=>'{update}'
			],*/
        ],
    ]); ?>

</div>
