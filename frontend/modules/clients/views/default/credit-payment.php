<?php

use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);

?>
    <style>
        label{
            color:#fff;
        }

        /*.list-company{
            background: #fff;
            width: 60%;
            padding:10px;
            margin: 10px auto;
            border-radius:5px;
        }
        .list-company .payment-type{
            padding:10px;
            border-radius: 5px;
        }
        .list-company .payment-type.active{
            border:2px solid #24f3af;
        }*/
    </style>
	
	<?= $this->render('_header') ?>

    <div class="reg-container black-bg w800 mb-30px">
	
		<?= $this->render('_menu',['active'=>'credit_history']) ?>


        <h3 class="title"><?=Yii::t('app','Ежемесячная оплата')?></h3>
        <h4 class="title"><?=Yii::t('app','Оплата на очередную дату')?>: <?=date('d.m.Y',$credit_items->credit_date) ?></h4>
        <p class="title"><?=Yii::t('app','Поставщик кредита')?>: <?=$credit_items->supplier->company ?></p>
        <p class="title"><?=Yii::t('app','Телефон')?>: <a href="tel:+<?=$credit_items->supplier->phone ?>">+<?=$credit_items->supplier->phone ?></a></p>
        <p class="title"><?=Yii::t('app','Сумма к оплате')?>: <?=number_format($credit_items->price,2,'.',' ') ?> <?=Yii::t('app','сум')?></p>


        <button class="btn btn-default credit-payment" data-id="<?=$order_id ?>"><?=Yii::t('app','Оплатить')?></button>

    </div>
<?php
$sign = md5($credit_items->price . \common\models\Payment::SECRET . $order_id );

$form = ActiveForm::begin(['id' => 'form-billing', 'action' => '/clients/payment']) ?>

        <input type="hidden" name="sum" value="<?=$credit_items->price ?>">
        <input type="hidden" name="order_id" value="<?=$order_id ?>">
        <input type="hidden" name="sign" value="<?=$sign ?>">
        <input type="hidden" name="sign_a" value="<?= $credit_items->price . \common\models\Payment::SECRET . $order_id ?>">



<?php
ActiveForm::end();

$msg_confirm = Yii::t('app','Подтвердить оплату');

$script = " 
$('document').ready(function(){
  
   $('.credit-payment').click(function(){                
        if(!confirm('{$msg_confirm}')){
            return false;
        }
        $('form#form-billing').submit();
   })
	 
});";
$this->registerJs($script, yii\web\View::POS_END);
