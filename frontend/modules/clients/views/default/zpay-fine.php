<?php

use common\models\Credits;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

\frontend\assets\ZpayAsset::register($this);


?>
    <style>
        label {
            color: #fff;
        }

    </style>

<?= $this->render('_header') ?>

    <div class="reg-container black-bg  mb-30px">

    <!--START OF THE SECONDPAGE FIRST SECTION-->
    <div class="payment-page-section1">
        <div class="heading">Оплатить штраф ГУБДД в Рассрочку на 3 месяца</div>
        <hr>
        <!-- ----------------------------------------------------------------------- -->
        <div class="column-page2 ">
            <div class="row-page2">
                <div class="col-page2">
                    <div class="container-column-page2-1">
                        <div class="input-title">Номер постановления</div>
                        <div class="primary-filled-input">
                            <h1><?= $account ?></h1>
                        </div>
                    </div>
                    <div class="container-column-page2-2">
                        <div class="input-title">Услуга</div>
                        <div class="primary-filled-input">
                            <h1><?= $zmarket_sum - $upay_sum ?></h1>
                        </div>
                    </div>
                    <div class="card-page2">
                        <img src="../assets/images/cardsZmarket2.png" alt="card-2">
                        <div class="card-type-page2">Лимит рассрочки</div>
                        <div class="amount-page2">
                            <?= $credit_year = $model->kyc->credit_year - Credits::getPaymentSumAll($model->id) < 0 ? 0 : $model->kyc->credit_year - Credits::getPaymentSumAll($model->id); ?>
                        </div>
                    </div>
                </div>
                <div class="col2-page2">
                    <div class="container-column-page2">
                        <div class="input-title">Ф.И.О</div>
                        <div class="primary-filled-input">
                            <h1><?= $fullname ?></h1>
                        </div>
                    </div>
                    <div class="container-column-page2">
                        <div class="input-title">Сумма платежа</div>
                        <div class="primary-filled-input">
                            <h1><?= $sum ?></h1>
                        </div>
                    </div>
                    <div class="container-column-page2">
                        <div class="input-title">Итог</div>
                        <div class="primary-filled-input">
                            <h1><?= $zmarket_sum ?></h1>
                        </div>
                    </div>
                    <div class="outline-input-box">
                        <span><?= $info ?></span>
                    </div>
                </div>
            </div>
            <button id = "send-order" class="button-primary-outline"><h1>ОПЛАТИТЬ</h1></button>
        </div>
    </div>
    <!--FINISH OF THE SECONDPAGE FIRST SECTION-->



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
$msg_server_error = Yii::t('app','Ошибка сервера!');
$msg_error = Yii::t('app','Платеж не удался!');
$script = " 
$('document').ready(function(){
var account = '{$account}';
//var sum = '{$sum}';
var sum = 100;  
var upay_sum = {$upay_sum};
var zmarket_sum = {$zmarket_sum};

$('.company-cards').click(function(){
    id = $(this).attr('id');
     //console.log(id);     
      window.location.href = '/clients/zpay-services?id='+id;  
});

$('#send-sms').click(function(){ 
        code = $('#code').val();   
        $.ajax({        
            type: 'post',
            url: '/clients/upay-sms',
            data: 'code='+code+'&sum='+sum+'&_csrf=' + yii.getCsrfToken(), 
            dataType: 'json',
            success: function(data){
                if(data.result.status){ 
                  console.log(data);
	                /*setTimeout(function() { 
                      window.location.href = '/client/upay-confirm';
                    }, 2000) */ 
	                	                
                }else{
                    alert('{$msg_error}' + ' ' + data.result.description);
                    console.log(data);
                    console.log(data.result.description);
                }
            },
            error: function(data){
               alert('{$msg_server_error}');
            }
        }); 
	     
	 }) 
	 
$('#send-order').click(function(){	 	  
        $.ajax({        
            type: 'post',
            url: '/clients/upay-confirm',
            data: 'account='+account+'&sum='+sum+'&_csrf=' + yii.getCsrfToken(), 
            dataType: 'json',
            success: function(data){
                if(data.result.status){ 
                  console.log(data);
	                /*setTimeout(function() { 
                      window.location.href = '/client/upay-confirm';
                    }, 2000) */ 
	                	                
                }else{
                    alert('{$msg_error}' + ' ' + data.result.description);
                    console.log(data);
                    console.log(data.result.description);
                }
            },
            error: function(data){
               alert('{$msg_server_error}');
            }
        }); 
	     
	 })  
		 	 
});";
$this->registerJs($script, yii\web\View::POS_END);