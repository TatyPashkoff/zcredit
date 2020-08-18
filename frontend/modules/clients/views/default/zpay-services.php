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

    <!--START OF THE THIRDPAGE FIRST SECTION-->
    <div class="payment-page-section2">
        <div class="heading">Оплатить услуги с помощью zPay</div>
        <hr>
        <!-- ----------------------------------------------------------------------- -->
        <div class="column-page3">
            <div class="col-container-page3">
                <div class="card-company-container">
                    <?php
                    echo '<img src="../assets/images/' . $img->img . ' " alt="' . $img->name . '">';
                    echo '<span>' . $img->name . '</span>';
                    ?>

                </div>
                <div class="card-page3">
                    <img src="../assets/images/cardsZmarket3.png" alt="card-2">
                    <div class="card-type-page3">CashBank (zCoins)</div>
                    <div class="amount-page3"><?= $model->cashback ? number_format($model->cashback, 2, '.', ' ') : '0.00' ?></div>
                </div>
            </div>
            <!-- ----------------------------------------------------------------------- -->
            <div class="row-page3">
                <!-- ----------------------------------------------------------------------- -->
                <div class="container-column-page3">
                    <div class="input-title">Ваш <?= $text = $tel ? 'номер телефона' : 'логин'; ?> </div>
                    <div class="input-wrapper">
                        <?php if ($tel): ?>
                            <div class="tel-mask">(+998)</div>
                            <input type='text' id="account" class="primary-outline-input" placeholder='90 999-09-09'/>
                        <?php endif; ?>
                        <?php if (!$tel): ?>
                            <input type='text' id="account" class="primary-outline-input2" placeholder='1234567'/>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- ----------------------------------------------------------------------- -->
                <div class="container-column-page3">
                    <div class="input-title">Сумма платежа</div>
                    <input type='text' class="primary-outline-input2" id="sum" placeholder='300,000 СУМ'/>
                </div>
                <button class="btn-page3 " id="pay"><h1>ОПЛАТИТЬ</h1></button>
            </div>
        </div>
    </div>
    <!--FINISH OF THE THIRDPAGE FIRST SECTION-->

    <!--START OF COMPANY-CARDS-CONTAINER-->
    <div class="company-cards-container">
        <div class="column">
            <div class="heading"> Оплатить услуги с помощью zPay</div>
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
                <!-- START ROW OF COMPANY-CARDS-1-->
                <!--<div class="row-container">
                    <div class="title-container">
                        <div class="img-wrapper">
                            <img src="../assets/icons/phone.svg" alt="icon">
                        </div>
                        <span>Мобильные операторы</span>
                    </div>
                </div>
                <div class="row">
                    <div class="company-cards" id = "40">
                        <img src="../assets/images/beeline.png" alt="beeline">
                        <span>BEELINE</span>
                    </div>
                    <div class="company-cards" id="132">
                        <img src="../assets/images/Mobiuz.png" alt="mobiuz">
                        <span>Mobiuz</span>
                    </div>
                    <div class="company-cards" id="5">
                        <img src="../assets/images/perfectum_n.png" alt="perfectum">
                        <span>perfectum mobile</span>
                    </div>
                    <div class="company-cards" id="8">
                        <img src="../assets/images/uceluz.png" alt="uceluz">
                        <span>ucell</span>
                    </div>
                    <div class="company-cards" id="163">
                        <img src="../assets/images/uzmobile.png" alt="uzmoblie">
                        <span>uzmobile</span>
                    </div>
                </div>-->
                <!--END OF THE ROW OF COMPANY-CARDS-1-->


                <!-- START ROW OF COMPANY-CARDS-2-->
                <!--<div class="row-container">
                    <div class="title-container">
                        <div class="img-wrapper">
                            <img src="../assets/icons/phone.svg" alt="icon">
                        </div>
                        <span>Интернет провайдеры</span>
                    </div>
                </div>
                <div class="row">
                    <div class="company-cards" id = "50">
                        <img src="../assets/images/tps.png" alt="tps" >
                        <span>tps</span>
                    </div>
                    <div class="company-cards" id = "140">
                        <img src="../assets/images/comnet.png" alt="coment" >
                        <span>comnet</span>
                    </div>
                    <div class="company-cards" id = "130">
                        <img src="../assets/images/evo.png" alt="evo" >
                        <span>evo</span>
                    </div>
                    <div class="company-cards" id = "120">
                        <img src="../assets/images/uzmobile.png" alt="uz-online" >
                        <span>uzonline</span>
                    </div>
                    <div class="company-cards" id = "1">
                        <img src="../assets/images/sarkor.png" alt="sarkor" >
                        <span>sarkor telecom</span>
                    </div>
                </div>-->
                <!--END OF THE ROW OF COMPANY-CARDS-2-->

                <!-- START ROW OF COMPANY-CARDS-3-->
                <!-- <div class="row-container">
                     <div class="title-container">
                         <div class="img-wrapper">
                             <img src="../assets/icons/phone.svg" alt="icon">
                         </div>
                         <span>Интернет провайдеры</span>
                    </div>
                </div>
                <div class="row">
                    <div class="company-cards" id = "149">
                        <img src="../assets/images/fibernet.png" alt="FiberNet">
                        <span>FiberNet</span>
                    </div>
                    <div class="company-cards" id = "223">
                        <img src="../assets/images/istvinternet.png" alt="ISTV internet">
                        <span>ISTV internet</span>
                    </div>
                    <div class="company-cards" id = "202">
                        <img src="../assets/images/freelink.png" alt="FreeLink">
                        <span>FreeLink</span>
                    </div>
                    <div class="company-cards" id = "4">
                        <img src="../assets/images/st.png" alt="Sharq Telekom">
                        <span>Sharq Telekom</span>
                    </div>
                    <div class="company-cards" id = "248">
                        <img src="../assets/images/buztoninternet.png" alt="Buston Internet">
                        <span>Buston Internet</span>
                    </div>
                </div>-->
                <!--END OF THE ROW OF COMPANY-CARDS-3-->
            </div>
        </div>
        <!--END OF COMPANY-CARDS-CONTAINER-->

    </div>

<?php
$cashback = $model->cashback ? $model->cashback : 0;
$msg_server_error = Yii::t('app', 'Что-то пошло не так. Скорее всего, вы ввели неправильный логин или номер телефона');
$msg_error = Yii::t('app', 'Платеж не удался!');
$sum_error = $model->cashback > 0 ? Yii::t('app', 'Сумма оплаты не может быть больше ' . $model->cashback . ' сум !') : Yii::t('app', 'Недостаточно средств для оплаты!');
$script = " 
$('document').ready(function(){
var service_id = {$service_id};
var cashback = {$cashback};
var tel = {$tel};
console.log(cashback);            

$('.company-cards').click(function(){
    id = $(this).attr('id');
     //console.log(id);     
      window.location.href = '/clients/zpay-services?id='+id;  
});

$('#pay').click(function(){	
        sum = $('#sum').val(); 
        if(cashback - sum >= 0){
            account = $('#account').val();              
            const p = /[^0-9]+/g;
            account =  account.replace (p, '');
            $.ajax({
                type: 'post',
                url: '/clients/zpay-services',
                data: 'service_id='+service_id+'&account='+account+'&sum='+sum+'&_csrf=' + yii.getCsrfToken(), 
                dataType: 'json',
                success: function(data){                   
                    if(data.result.return.Result.code === 'OK'){ 
                      console.log(data.result.return.Result.code); 
                          window.location.href = '/clients/zpay-finish';                                                                   
                    }else{
                        alert('{$msg_error}');                         
                         console.log(data.result.return.Result.code);
                         console.log(data.result.return.Result.message);
                    }
                },
                error: function(data){
                   alert('{$msg_server_error}'); 
                }
            }); 
        }else{
            alert('{$sum_error}');
        } 	  
        
	     
})  

	if(tel) $('#account').mask('99 999-99-99');	 	 
});";
$this->registerJs($script, yii\web\View::POS_END);