<?php
use common\models\Credits;


\frontend\assets\ZpayAsset::register($this);


?>
    <style>
        label {
            color: #fff;
        }

    </style>

<?= $this->render('_header') ?>

    <div class="reg-container black-bg  mb-30px">

    <!--START OF THE FOURTHPAGE FIRST SECTION-->
    <div class="payment-page-section2">
        <div class="heading">Оплатить услуги с помощью zPay </div>
        <hr>
        <!----------------------------------------------------------------------------->
        <div class="column-page4">
            <div class="success-payment-container">
                <img src="../assets/images/like.png" alt="success">
                <div class="title-success">ПЛАТЕЖ ПРОШЕЛ УСПЕШНО !</div>
                <div class="button-wrappers">
                    <button class="btn-outline-secondary-page4">Печать квитанции</button>
                    <button class="btn-filled-secondary-page4">НАЗАД</button>
                </div>
            </div>
        </div>
    </div>
    <!--FINISH OF THE FOURTHPAGE FIRST SECTION-->


    <!--START OF COMPANY-CARDS-CONTAINER-->
    <div class="company-cards-container">
        <div class="column">
            <div class="heading"> Оплатить услуги с помощью zPay </div>
            <hr>
            <!-- START ROW OF COMPANY-CARDS-1-->
            <div class="row-container">
                <div class="title-container">
                    <div class="img-wrapper">
                        <img src="../assets/icons/phone.svg" alt="icon">
                    </div>
                    <span>Мобильные операторы</span>
                </div>
            </div>
            <div class="row">
                <?php $cnt = 1;
                $end = 1; ?>
                <?php foreach ($services as $service): ?>
                <div class="company-cards" id="<?= $service->service_id ?>">
                    <img src="../assets/images/<?= $service->img ?>" alt="<?= $service->name ?>">
                    <span><?= $service->name ?></span>
                </div>
                <?php if ($cnt == 5) { ?>
            </div>
            <div class="row-container">
                <div class="title-container">
                    <?php if ($end == 5): ?>
                        <div class="img-wrapper">
                            <img src="../assets/icons/phone.svg" alt="icon">
                        </div>
                        <span>Интернет провайдеры</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">

                <?php $cnt = 0;
                }
                ?>
                <?php $cnt++;
                $end++ ?>
                <?php endforeach; ?>

                <!--END OF THE ROW OF COMPANY-CARDS-1-->
            </div>
        </div>
        <!--END OF COMPANY-CARDS-CONTAINER-->

    </div>

<?php

$script = " 
$('document').ready(function(){

$('.company-cards').click(function(){
    id = $(this).attr('id');
     //console.log(id);     
      window.location.href = '/clients/zpay-services?id='+id;  
});

		 	 
});";
$this->registerJs($script, yii\web\View::POS_END);