<?php

use common\models\Credits;
use yii\widgets\LinkPager;

\frontend\assets\MainAsset::register($this);


?>
    <style>
        label{
            color:#fff;
        }
    </style>
	
	<?= $this->render('_header') ?>

    <div class="reg-container black-bg w800 mb-30px">
	
		<?= $this->render('_menu',['active'=>'credit_history']) ?>

        <div class="title-with-border"><?=Yii::t('app','Список полученных товаров в рассрочку')?></div>

        <?php if($credits) {
            foreach ($credits as $credit) {
               // if($credit->id!=8) continue;  echo $credit->id;       print_r($credit); exit;
                ?>
                <table class="table">
                    <thead>
                    <tr>
                        <?php /*<th><?=Yii::t('app','Подтверждение') ?></th> */ ?>
                        <th><?=Yii::t('app','Срок рассрочки') ?></th>
                        <th><?=Yii::t('app','Сумма кредита') ?></th>
                        <th><?=Yii::t('app','Первоначальный взнос') ?></th>
                        <th><?=Yii::t('app','Платеж в месяц') ?></th>
                        <th><?=Yii::t('app','Оплаченные месяца') ?></th>
                        <th><?=Yii::t('app','Остаток по рассрочке') ?></th>
                        <th><?=Yii::t('app','Просрочка по платежу') ?></th>
                        <th><?=Yii::t('app','График платежей') ?></th>
                        <th><?=Yii::t('app','Погашение') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <?php /*<td><?=$credit->user_confirm ==0 ? '<a href="#" class="confirm-credit" data-id="'.$credit->id .'">'.Yii::t('app','Подтвердить кредит') .'</a>' : Yii::t('app','Подтвержден') ?></span><?php /* <a href="/" download=""><?=Yii::t('app','Скачать документы клиента') ?></a> * / ?></td> */ ?>
                        <td><?=$credit->credit_limit; //Credits::CREDIT_TYPES[$credit->credit_limit]?> <?=Yii::t('app','мес.')?></td>
                        <td><?=$credit->price  ?></td>
                        <td><?=$credit->deposit_first ?></td>
                        <td><?=$credit->deposit_month ?></td>
                        <td><?=$credit->getPaymentMonth(); //  number_format($credit->getPaymentSum(),2,'.',' ');?> </td>
                        <td><?=number_format( $credit->getPaymentSum(),2,'.',' ') ?> <?=Yii::t('app','сум')?></td>
                        <td><?=$credit->getPaymentDelay()   // тек дата - дата крайняя ?> <?=Yii::t('app','дн.')?></td>
                        <td><a href="/clients/credit-plan?id=<?=$credit->id ?>" class="<?=$credit->user_confirm ==0 ? 'no-confirm' : ''?>"><?=Yii::t('app','Детализация')?></a></td>
                        <td><?=Credits::PAYMENT_STATUS[ $credit->status] ?></td>
                    </tr>

                    <tr>
                        <td colspan="2"><?=Yii::t('app','Поставщик кредита')?>: <?=$credit->supplier->company //Credit Asia ?> <br><?=Yii::t('app','Телефон:')?><a href="tel:+<?=$credit->supplier->phone?>">+<?=$credit->supplier->phone ?></a></td>
                        <td colspan="1"><?=Yii::t('app','Дата оформления кредита')?>:<br><?=date('d.m.Y',$credit->created_at)?></td>
                        <td colspan="1"><?=Yii::t('app','Дата доставки товара')?>:<br><?=$credit->delivery_date !='' ? date('d.m.Y',$credit->delivery_date) :'-' ?> </td>
                        <td colspan="2"><?=Yii::t('app','Дата внесения платежа')?>:<br><?=$credit->getFirstPayment()?></td>
                        <td colspan="2"><?=Yii::t('app','Дата платежа крайняя')?>:<br><?=$credit->getLastPayment()?></td>
                        <td colspan="2"><?=Yii::t('app','Срок погашения кредита')?>:<br><?=date('d.m.Y',$credit->credit_date) ?></td>
                    </tr>
                    </tbody>
                </table>
            <?php } ?>

            <div class="pagination">
                <?= LinkPager::widget([
                    'pagination' => $pagination,
                ]);  ?>
            </div>

        <?php } ?>

    </div>




<?php
/*
$msg_server_error = Yii::t('app','Ошибка сервера!');
$msg_code = Yii::t('app','Необходимо ввести код подтверждения из смс!');
$msg_enter_code = Yii::t('app','Введите код из смс');
$msg_credit_not_found = Yii::t('app','Кредит не найден!');
$msg_confirm = Yii::t('app','Для просмотра детализации необходимо подтвердить кредит кодом из смс!');


$script = " 
$('document').ready(function(){
    /*$('.no-confirm').click(function(e){
        e.preventDefault();
        alert('{$msg_confirm}');
        return false;
        
    })* /

   $('.confirm-credit').click(function(e){
        e.preventDefault();
        id = $(this).data('id');
        code = prompt('{$msg_enter_code}');
        if(code.length==0){
            alert('{$msg_code}');
            return false;
        }
        $.ajax({
            type: 'post',
            url: '/clients/credit-confirm',
            data: 'id='+id+'&code='+code+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                   alert(data.info)  
                   window.location.href = '/clients/credit-history';
                }else{
                    alert('{$msg_credit_not_found}');
                } 
                
            },
            error: function(data){
               alert('{$msg_server_error}');

            }
    
         });
   })
	 
});";
$this->registerJs($script, yii\web\View::POS_END); */
