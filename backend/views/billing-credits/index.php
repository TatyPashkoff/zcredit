<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\Credits;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CreditsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Просрочка по кредитам';
$this->params['breadcrumbs'][] = $this->title;

?>
    <div class="credits-index" style="overflow-x:auto;">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'confirm',
                    'label' => 'Оформлен',
                    'filter'=> ['1' => 'Да', 0 => 'Нет'],
                    'content'=>function($data){
                        return  $data->user_confirm == 1 ? '<span class="label label-success">Да</span>' : '<span class="label label-danger">Нет</span>';
                    }
                ],
                ['attribute' => 'id'],
                'created_at:date',
                'deposit_month',
                //'PaymentDelay',
                [
                    'attribute' => 'PaymentDelay',
                    'label'=>'Просрочено дней',
                    //'filter'=> ['0' => 'Нет', '1' => 'Да'],
                    'content'=>function($model){
                        return  $model->getPaymentDelay();
                    }
                ],
                [
                    'attribute' => 'priceName',
                    'label'=>'Просрочка',
                    'filter'=> ['0' => 'Нет', '1' => 'Да'],
                    'content'=>function($model){
                        if($model->user_confirm == 1){
                            return  number_format($model->getPaymentDelaySum(),2,'.',' ');
                        }else{
                            return number_format(0,2,'.',' ');
                        }

                    }
                ],

                'user_id',
                [
                    'attribute' => 'clientFio',
                    'label'=>'ФИО',
                    'content'=>function($model){
                        return  @$model->client->lastname . '&nbsp;'. @$model->client->username. '&nbsp;'. @$model->client->patronymic;
                    }
                ],
                [
                    'attribute' => 'phone',
                    'label'=>'Телефон',
                    'content'=>function($model){
                        return  @$model->client->phone ;
                    }
                ],
                [
                    'attribute' => 'status',
                    'filter'=> ['1' => 'Погашен', '0' => 'Долг'],
                    'content'=>function($data){
                        return  $data->status ? '<span class="label label-success">Погашен</span>' : '<span class="label label-danger">Долг</span>';
                    }
                ],
                ['class' => 'yii\grid\ActionColumn',
                    'template'=>'{detail} {offer} {pay} {polis_list}',
                    'header'=>'Детализация',
                    'buttons' => [
                        'detail' => function ($url, $model, $key) {
                            $iconName = "th-list";

                            //Текст в title ссылки, что виден при наведении
                            $title = \Yii::t('yii', 'Детализация');

                            $id = 'info-'.$key;
                            $options = [
                                'title' => $title,
                                'aria-label' => $title,
                                'data-pjax' => '0',
                                'id' => $id,
                                'class' => 'detail',
                            ];

                            $url = '/credits/details?credit_id=' . $key;

                            //Для стилизации используем библиотеку иконок
                            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);

                            return Html::a($icon, $url, $options);
                        },
                        'offer' => function ($url, $model, $key) {

                            return '<a href="/credits/details-offer?credit_id='.$key.'" title="Офферта"><i class="glyphicon glyphicon-retweet"></i></a>';
                        },

                        'pay' => function ($url, $model, $key) {

                            return '<a href="/billing-credits/pay?credit_id='.$key.'" title="Списание"><i class="glyphicon glyphicon-usd"></i></a>';

                        },
                        'polis_list' => function ($url, $model, $key) {
                            //return Html::a('', ['send-customer-list', 'credit_id' => $key], ['class' => 'glyphicon glyphicon-hourglass', 'title' => 'Отправить задолжника в страховую']);
                        },
                    ],
                ],
            ],
        ]); ?>
<h1>Всего сумма просрочки:
<?php
$total = 0;
$count = Credits::find()->count();
$credits = Credits::find()->where(['user_confirm' => 1])->All();
    foreach ($credits as $credit) {
        $sum = $credit->getPaymentDelaySum();
        if ($sum > 0) {
            $total = $total + $sum;
        }
    }
    echo $total;
    ?>
</h1>
    </div>

<?php
/*
$js ="
                        $('.detail').on('click',function(event){
                                event.preventDefault();
                                var myModal = $('#myModal');
                                var modalBody = myModal.find('.modal-body');
                                var modalTitle = myModal.find('.modal-header');

                                modalTitle.find('h2').html('Информация.');
                                modalBody.html('Тут будет информация.');

                                myModal.modal('show');
                            }
                        );";


//Регистрируем скрипты
$this->registerJs($js, \yii\web\View::POS_READY );*/