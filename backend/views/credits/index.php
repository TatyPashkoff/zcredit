<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\Credits;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CreditsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Кредиты';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="credits-index" style="overflow-x:auto;">

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php /* <p>
        <?= Html::a('Добавить Credits', ['create'], ['class' => 'btn btn-success']) ?>
    </p> */ ?>

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
            [
                'attribute' => 'status',
                'filter'=> ['1' => 'Погашен', '0' => 'Долг'],
                'content'=>function($data){
                    return  $data->status ? '<span class="label label-success">Погашен</span>' : '<span class="label label-danger">Долг</span>';
                }
            ],
             'created_at:date',
            // 'user_id',
            // 'supplier_id',

            [
                'attribute' => 'credit_limit',
                'content'=>function($model){
                    return $model->credit_limit . ' мес';// \common\models\Credits::CREDIT_TYPES[$data->credit_limit] ;
                }
            ],
            [
                'attribute' => 'polis',
                'label'=>'Номер страхового полиса',
                'content'=>function($model){
                    return  @$model->polis->id ? @$model->polis->id : @$model->asko->Contract_number;
                }
            ],

            [
                'attribute' => 'asko',
                'label'=>'Страховая премия',
                'content'=>function($model){
                    return   @$model->asko->Insurance_premium;
                }
            ],

            //'deposit_first',
            'deposit_month',
            'price',

            /*[
                'attribute' => 'supplierConfirmSum',
                'content'=>function($model){
                    if ($credit_amount = Credits::find()->select('SUM(credit_items.amount * credit_items.quantity) as payment_sum')->joinWith("creditItems")->where(['user_id' => $model->user_id, 'credit_id' => $model->id, 'status' => 0])->andWhere(['user_confirm' => 1])->one()) {
                        $res = $credit_amount->payment_sum ? $credit_amount->payment_sum : 0;
                    }
                    return  $res;

                }
            ],*/

            ['attribute' => 'supplierSum', 'value' => function($model){
                $sum = 0;
                if(isset($model->creditItems)) {
                    $npp = 0;
                    $cnt = 0;

                    $sum_price = 0;
                    $s = 0;
                    $nds_sum = 0;
                    $clear_price = 0;


                    $nds = $model->credit_limit == 3 ? 1.25 : 1.35;
                    if (!$model->nds) {
                        $nds += 1.15;
                    }
                    $nds_title = '15';
                    foreach ($model->creditItems as $credit_item) {
                        $npp++;
                        $cnt += $credit_item->quantity;

                        $nds_percent = 1;
                        $reverse_nds_percent = 1.15;
                        //$discount = Yii::$app->user->identity->discount ? Yii::$app->user->identity->discount : 0;
                        // $discount_s = $credit_item->amount * $discount / 100;
                        // $discount_sum = $credit_item->amount - $discount_s; // стоимость товара со скидкой

                        if (!$model->nds) {
                            $nds_percent = 1.15;
                            $reverse_nds_percent = 1;
                            $nds_title = "(без ндс)";
                        }

                        // Сумма товара с процентами но без НДС если таковой имеется
                        $item_price = $credit_item->discount_sum / $reverse_nds_percent /* * $percent*/;
                        $sum_price += $credit_item->discount_sum  * $credit_item->quantity;
                        $clear_price += $credit_item->discount_sum * $credit_item->quantity;
                        // сумма ндс
                        $nds_sum = $credit_item->discount_sum - $item_price;


                        if($model->nds){
                            $sum += $item_price + $nds_sum;
                        } else {
                            $sum += $item_price;
                        }
                    }
                }
                //return $sum . ' ' ;
                return $sum_price;


        }],

            ['attribute' => 'itemsName', 'value' => function($model){return implode(', ',ArrayHelper::getColumn($model->creditItems, 'title'));}],
            //['attribute' => 'quantity', 'value' => function($model){return count($model->creditItems);}], // тут считает кол-во строк, а не кол-во товаров
            /*[
                    'attribute' => 'quantity',
                'value' => function($model){
                    if (isset($model->creditItems)) {
                        $cnt = 0;
                        foreach ($model->creditItems as $credit_item) {
                            $cnt += $credit_item->quantity;
                        }
                    }
                    return $cnt;
                }
            ],*/
            'supplier_id',
            'user_id',
            [
                'attribute' => 'clientFio',
                'label'=>'ФИО',
                'content'=>function($model){
                    return  @$model->client->lastname . '&nbsp;'. @$model->client->username. '&nbsp;'. @$model->client->patronymic ;
                }
            ],
            [
                'attribute' => 'company',
                'label'=>'Компания',
                'content'=>function($model){
                    return  @$model->supplier->company ;
                }
            ],
           // 'api_user',
            [
                'attribute' => 'api_user',
               //'label'=>'Компания',
                'content'=>function($model){
                    return  @$model->api_user ;
                }
            ],


            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{detail} {offer}',
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

                        //Обработка клика на кнопку


                        return Html::a($icon, $url, $options);
                    },
                    'offer' => function ($url, $model, $key) {


                        return '<a href="/credits/details-offer?credit_id='.$key.'" title="Офферта"><i class="glyphicon glyphicon-retweet"></i></a>';
                    },
                ],
            ],



            //['class' => 'yii\grid\ActionColumn',
				//'template'=>'{update} {delete} ',

            //],//
        ],
    ]); ?>

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