<?php

use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);

?>
    <style>
        label{
            color:#fff;
        }

        .list-company{
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
        }
    </style>
	
	<?= $this->render('_header') ?>

    <div class="reg-container black-bg w800 mb-30px">
	
		<?= $this->render('_menu',['active'=>'credit_history']) ?>


        <h3 class="title"><?=Yii::t('app','Ежемесячная оплата')?></h3>
        <h4 class="title"><?=Yii::t('app','Оплата на очередную дату')?>: <?=date('d.m.Y',$credit_items->credit_date) ?></h4>
        <p class="title"><?=Yii::t('app','Поставщик кредита')?>: <?=$credit_items->supplier->company ?></p>
        <p class="title"><?=Yii::t('app','Телефон')?>: <a href="tel:+<?=$credit_items->supplier->phone ?>">+<?=$credit_items->supplier->phone ?></a></p>
        <p class="title"><?=Yii::t('app','Сумма к оплате')?>: <?=number_format($credit_items->price,2,'.',' ') ?> <?=Yii::t('app','сум')?></p>

        <div class="list-company">
                <a href="#" class="payment-type active" data-pay_type="1"><img alt="" src="/images/payme.png"></a>

                <a href="#" class="payment-type" data-pay_type="2"><img alt="" src="/images/click.png"></a>
                <a href="#" class="payment-type" data-pay_type="3"><img alt="" src="/images/uzcard.png"></a>

        </div>

        <button class="btn btn-default credit-payment"><?=Yii::t('app','Оплатить')?></button>

    </div>

<?php

    $merchant_id = \common\models\Payment::PAYME_MERCHANT_ID;

    $form_payme = ActiveForm::begin(['id' => 'form-payme', 'action' => \common\models\Payment::PAYME_URL_TEST]);

    $price = $credit_items->credit->price * 100; // PAYME в тийинах

    ?>


    <?php // Идентификатор WEB Кассы  ?>
    <input type="hidden" name="merchant" value="<?= $merchant_id ?>"/>

    <?php // Поля Объекта Account
    ?>
    <input type="hidden" name="account[order_id]" value="<?= $order_id ?>"/>

    <?php /* ==================== НЕ ОБЯЗАТЕЛЬНЫЕ ПОЛЯ ====================== --> */ ?>
    <input type="hidden" name="lang" value="<?= $lang ?>"/>

    <input type="hidden" name="currency" value="860"/>

    <input type="hidden" name="callback" value="http://<?=$_SERVER['SERVER_NAME'] ?>/payments/<?= $order_id ?>/:transaction"/>

    <?php // Сумма платежа в тиинах   ?>
    <input type="hidden" name="amount" value="<?= $price ?>"/> <?php // {сумма чека в ТИИНАХ * 100}  ?>

    <?php

    ActiveForm::end();



    $action_click = \common\models\Payment::CLICK_URL;

    $form_click = ActiveForm::begin(['id' => 'form-click', 'action' => $action_click]);

    $secret = \common\models\Payment::CLICK_SECRET;
    $date = date("Y-m-d h:i:s");
    $merchantID = \common\models\Payment::CLICK_MERCHANT_ID;
    $merchantUserID = \common\models\Payment::CLICK_MERCHANT_USER_ID;
    $serviceID = \common\models\Payment::CLICK_SERVICE_ID;

    $transID = $order_id; // id - заказа

    // оплата CLICK в суммах, не тийинах
    $transAmount = number_format($credit_items->credit->price, 2, '.', '');
    $signString = md5($date . $secret . $serviceID . $transID . $transAmount);
    $returnURL = "http://{$_SERVER['SERVER_NAME']}/clients/checkout";  // возврат после оплаты в click
    ?>
    <input id="click_amount_field" type="hidden" name="MERCHANT_TRANS_AMOUNT" value="<?= $transAmount ?>" class="click_input"/>
    <input type="hidden" name="MERCHANT_ID" value="<?= $merchantID ?>"/>
    <input type="hidden" name="MERCHANT_USER_ID" value="<?= $merchantUserID ?>"/>
    <input type="hidden" name="MERCHANT_SERVICE_ID" value="<?= $serviceID ?>"/>
    <input type="hidden" name="MERCHANT_TRANS_ID" value="<?= $transID ?>"/>
    <input type="hidden" name="MERCHANT_TRANS_NOTE" value="Оплата"/>
    <input type="hidden" name="SIGN_TIME" value="<?= $date ?>"/>
    <input type="hidden" name="SIGN_STRING" value="<?= $signString ?>"/>
    <input type="hidden" name="RETURN_URL" value="<?= $returnURL ?>"/>

    <?php
    ActiveForm::end();

    $action_uzcard = \common\models\Payment::UZCARD_URL;

    $form_uzcard = ActiveForm::begin(['id' => 'form-uzcard', 'action' => $action_uzcard]);

    $secret = \common\models\Payment::UZCARD_SECRET;
    $date = date("Y-m-d h:i:s");

    $merchantID = \common\models\Payment::UZCARD_MERCHANT_ID;
    $terminalID = \common\models\Payment::UZCARD_TERMINAL_ID;

    $transID = $order_id; // id - заказа

    // оплата UZCARD в тийинах
    $transAmount = number_format($credit_items->credit->price *100, 2, '.', '');
    $signString = md5($date . $secret . $terminalID . $transID . $transAmount);
    $returnURL = "http://{$_SERVER['SERVER_NAME']}/clients/checkout";  // возврат после оплаты в uzcard
    ?>
    <input id="click_amount_field" type="hidden" name="MERCHANT_TRANS_AMOUNT" value="<?= $transAmount ?>" class="click_input"/>
    <input type="hidden" name="MERCHANT_ID" value="<?= $merchantID ?>"/>
    <input type="hidden" name="MERCHANT_TERMINAL_ID" value="<?= $terminalID ?>"/>
    <input type="hidden" name="MERCHANT_TRANS_ID" value="<?= $transID ?>"/>
    <input type="hidden" name="SIGN_TIME" value="<?= $date ?>"/>
    <input type="hidden" name="SIGN_STRING" value="<?= $signString ?>"/>
    <input type="hidden" name="RETURN_URL" value="<?= $returnURL ?>"/>

    <?php
    ActiveForm::end();

$msg_payment = Yii::t('app','Укажите способ оплаты!');

$script = " 
$('document').ready(function(){
    var pay_type = 0;
   
   $('.payment-type').click(function(e){
        e.preventDefault();
        $('.payment-type').removeClass('active');
        $(this).addClass('active');
        pay_type = $(this).data('pay_type');
   })
   
   $('.credit-payment').click(function(){                
        if(pay_type==0){
            alert('{$msg_payment}');
            var scrollTop = $('.type_payment').offset().top;              
            $(document).scrollTop(scrollTop);
            return false;
        }        
        ///alert(pay_type);
        alert('в платежку');
        switch(pay_type){
            case 1: // payme
                $('form#form-payme').submit();
                break;
            case 2: // click
                $('form#form-click').submit();
                break;                
            case 3: // uzcard
                $('form#form-uzcard').submit();
                break;
            case 4: // cash
                window.location
                break;
        }      
        
    })
	 
});";
$this->registerJs($script, yii\web\View::POS_END);
