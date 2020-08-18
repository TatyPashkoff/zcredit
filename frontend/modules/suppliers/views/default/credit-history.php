<?php
use common\models\Credits;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

\frontend\assets\MainAsset::register($this);


?>
    <style>
        label{
            color:#fff;
        }

        .btn-small{
            padding: 4px 0px 2px 0px !important;
        }


    </style>

	<?= $this->render('_header') ?>






    <div class="reg-container black-bg w800 mb-30px">

		<?= $this->render('_menu',['active'=>'credit_history']) ?>

        <?php $user = new \common\models\User() ; ?>

        <div class="title-with-border"><?=Yii::t('app','Выданные товары в кредит')?></div>


        <?php if( $credits ) {

            foreach ( $credits as $credit ) {
                if(!isset($credit->client)) continue; // не найден клиент, удален и тд
                // if($credit->id!=8) continue; echo $credit->id; print_r($credit); exit;
                ?>

                <table class="table">
                    <thead>
                    <tr>
                        <th><?=Yii::t('app','Товары в рассрочку') ?></th>
                        <th><?=Yii::t('app','Срок рассрочки') ?></th>
                        <th><?=Yii::t('app','Сумма договора') ?></th>
                        <?php /* <th><?=Yii::t('app','Первоначальный взнос') ?></th> */ ?>
                        <th><?=Yii::t('app','Платеж в месяц') ?></th>
                        <th><?=Yii::t('app','Доставка') ?></th>
                        <th><?=Yii::t('app','Остаток по рассрочке') ?></th>
                        <th><?=Yii::t('app','Оплаченные месяца') ?></th>
                        <th><?=Yii::t('app','Просрочка по платежу') ?></th>
                        <?php /* <th><?=Yii::t('app','График платежей') ?></th> */ ?>
                        <th><?=Yii::t('app','Погашение') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <?php /*<td><a href="/get-documents?id=<?=$credit->user_id ?>" download=""><?=Yii::t('app','Скачать документы клиента') ?></td> */ ?>
                        <td></td>
                        <td><?=$credit->credit_limit; //Credits::CREDIT_TYPES[$credit->credit_limit]?> мес.</td>
                        <td><?=$credit->price ?></td>
                        <?php /*<td><?=$credit->deposit_first ?></td> */ ?>
                        <td><?=$credit->deposit_month ?></td>
                        <td><?=$credit->delivery_date =='' ? '<span class="delivery-confirm" data-credit_id="'.$credit->id.'">' . Yii::t('app','Подтвердить доставку') .'</span>' : Yii::t('app','Дата доставки товара') .': ' . date('d.m.Y',$credit->delivery_date)  ?></td>
                        <td><?=number_format( $credit->getPaymentSum(),2,'.',' ') ?> <?=Yii::t('app','сум')?></td>
                        <td><?=$credit->getPaymentMonth(); //  number_format($credit->getPaymentSum(),2,'.',' ');?> </td>
                        <td><?=$credit->getPaymentDelay() // тек дата - дата крайняя ?> <?=Yii::t('app','дн.')?></td>
                        <td><?=Credits::PAYMENT_STATUS[ $credit->status] ?></td>
                    </tr>

                    <tr>
                        <td colspan="2"><?=Yii::t('app','Заемщик кредита')?>: <?=$credit->client->username . ' ' . $credit->client->lastname ?> <br>Телефон:<a href="tel:+<?=$credit->client->phone?>">+<?=$credit->client->phone ?></a></td>
                        <td><?=Yii::t('app','Дата оформления договора')?>:<br><?=date('d.m.Y',$credit->created_at)?></td>
                        <td colspan="2"><?=Yii::t('app','Дата внесения платежа')?>:<br><?=$credit->getFirstPayment()?></td>
                        <td colspan="1"><?=Yii::t('app','Дата доставки товара')?>:<br><?=$credit->delivery_date !='' ? date('d.m.Y',$credit->delivery_date) :'-' ?> </td>
                        <td colspan="1"><?=Yii::t('app','Дата платежа крайняя')?>:<br><?=$credit->getLastPayment()?></td>
                        <td colspan="2"><?=Yii::t('app','Срок погашения договора')?>:<br><?=date('d.m.Y',$credit->credit_date) ?></td>
                        <td><a href="/suppliers/credit-plan?id=<?=$credit->id ?>"><?=Yii::t('app','Детализация')?></a></td>

                    </tr>
                    </tbody>
                </table>

                <?php /*<div class="row">
                    <div class="col-3"><a href="/print-act?id=<?=$credit->id ?>" class="btn btn-default btn-small" target="_blank"><i class="fa fa-print"></i><?=Yii::t('app','Акт') ?></a></div>
                    <div class="col-3"><a href="/print-invoice?id=<?=$credit->id ?>"  class="btn btn-default btn-small" target="_blank"><i class="fa fa-print"></i><?=Yii::t('app','Счет-фактура') ?></a></div>
                    <div class="col-3"><a href="/print-graph?id=<?=$credit->id ?>" class="btn btn-default btn-small" target="_blank"><i class="fa fa-print"></i><?=Yii::t('app','План-график') ?></a></div>
                    <div class="col-3"><div href="#" class="btn btn-default btn-small <?=$credit->confirm == 0 ? 'credit-confirm':'' ?>" data-credit_id="<?=$credit->id ?>"><?=$credit->confirm == 0 ? Yii::t('app','Подтвердить договор') : '<i class="fa fa-check"></i> '. Yii::t('app','договор подвержден') ?></div></div>

                </div> */ ?>
            <?php } ?>

                <div class="pagination">
                    <?= LinkPager::widget([
                        'pagination' => $pagination,
                    ]);  ?>
                </div>

        <?php } ?>

    </div>




<?php
$msg_server_error = Yii::t('app','Ошибка сервера!');
$msg_delivery = Yii::t('app','Подтвердить доставку!');
$msg_credit_confirm = Yii::t('app','Подтвердить договор!');

$script = " 
$('document').ready(function(){
   
   $('.delivery-confirm').click(function(){
        
        if(!confirm('{$msg_delivery}')) return false;
        credit_id = $(this).data('credit_id');
        obj = $(this);
        $.ajax({
            type: 'post',
            url: '/suppliers/set-delivery-date',
            data: 'id='+credit_id+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                   obj.text(data.date);             
                }                
            },
            error: function(data){
               alert('{$msg_server_error}')
            }
    
         });
   });
   $('.credit-confirm').click(function(e){
        e.preventDefault();
         credit_id = $(this).data('credit_id');
        if(credit_id=='0') return false;
        if(!confirm('{$msg_credit_confirm}')) return false;
       
        obj = $(this);
        $.ajax({
            type: 'post',
            url: '/suppliers/credit-confirm',
            data: 'id='+credit_id+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                   alert(data.info);
                   obj.html(data.html);
                   obj.data('credit_id','0');
                }                
            },
            error: function(data){
               alert('{$msg_server_error}')
            }
    
         });
   });
	 
});";
$this->registerJs($script, yii\web\View::POS_END);
