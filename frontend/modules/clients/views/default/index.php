<?php
use common\models\Credits;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

\frontend\assets\CabinetAsset::register($this);
?>

<?= $this->render('_header') ?>
    <style>
        .container {
            max-width:100% !important;
        }
        .payment-section {
            margin-bottom:80px;
        }
    </style>

    <div class="container-row">

        <!---------------------------Profile-tab-start------------------------->
        <div class="aside-profile-tab" id="block_id">
            <div class="personal-info-top">
                <div class="user-avatar"><img src="../assets/icons/user-avatar.png" alt="avatar"></div>
                <div class="username"><?=$model->username . ' '.$model->lastname;?></div>
                <div class="user-id">ZMARKET ID: <?=$model->id;?></div>
            </div>
            <div class="personal-info-box">
                <div class="info-1">
                    <div class="tab-icons"><img src="../assets/icons/check.png" alt="status"></div>
                    <div class="info-text">Статус:</div>
                    <?php if($model_kyc->status_verify == 0){ ?>
                        <div class="info-value">На стадии верификации</div>
                    <? }else { ?>
                        <div class="info-value">Вы успешно верифицированы</div>
                    <? } ?>
                </div>
                <div class="info-2">
                    <div class="tab-icons"><img src="../assets/icons/phone.png" alt="phone"></div>
                    <div class="info-text">Номер телефона:</div>
                    <div class="info-value"><?=$model->phone;?></div>
                </div>
                <? if(isset($model->passport_serial)) { ?>
                    <div class="info-3">
                        <div class="tab-icons"><img src="../assets/icons/passport.png" alt="passport-number"></div>
                        <div class="info-text">Серия паспорта:</div>
                        <div class="info-value"><?=$model->passport_serial . ' ' . substr_replace($model->passport_id, '****', -4, 4); ?></div>
                    </div>
                <? } ?>
                <div class="info-3">
                    <div class="tab-icons"><img src="../assets/icons/question.png" alt="FAQ"></div>
                    <div onclick="location.href='https://telegram.im/@zmarketsupports'" class="info-text">Центр справки и поддержки</div>
                </div>
                <div class="info-3">
                    <div class="tab-icons"><img src="../assets/icons/support.png" alt="support-center"></div>
                    <div class="info-text">Cлужба поддержки: </div>
                    <a class="info-value"><a href="tel:+998954790770">+998 95 479 07 70 </a></div>
            </div>
            <?php if($model_kyc->status_verify== 0){ ?>
                <button onclick="location.href='/clients/settings'" class="button-settings">РЕДАКТИРОВАТЬ ПРОФИЛЬ</button>
            <? } ?>
        </div>
        <!---------------------------Profile-tab-end------------------------->


        <? switch ($model_kyc->abandon) {
            case 0:
                $message_abandon = 'Не завершена регистрация, пожалуйста возобновите ее.';
                break;
            case 1:
                $message_abandon = 'Не подтвержден смс от Узкард.';
                break;
            case 2:
                $message_abandon = 'Срок вашей карты истек, выполните привязку карты заного.';
                break;
            case 3:
                $message_abandon = 'Денег нет но вы держитесь, пополните вашу карту!';
                break;
            case 4:
                $message_abandon = 'У Вас уже имеется кредит, погасите его!';
                break;
                break;
            case 5:
                $message_abandon = 'Вы не добавили фотографии!';
                break;
            case 6:
                $message_abandon = 'Ваш регион не подходит, из за короновируса работаем только по Ташкенту';
                break;
            case 7:
                $message_abandon = 'Приносим извинения, неполадки на стороне смс провайдера!';
                break;
            case 8:
                $message_abandon = 'Ваш запрос принят, дождитесь подтверждения!';
                break;
            case 9:
                $message_abandon = 'ВСЕ ГУД!';
                break;

        }
        ?>

        <!---------------------Content-Container-Start----------------------->
        <div class="content-container">
            <?php if($model_kyc->status_verify == 0 and $model_kyc->abandon <= 8 and $model->kyc->show_abandon == 0){ ?>
                <!-------------------ALERT-DANGER-START------------------------------>
                <div class="alert-danger-user-cabinet-container">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <p>Вы не верифицированы в нашей системе ваш ID: <strong><?=$model->id;?></strong><strong></p><p> <? if (!$model_kyc->abandon) {echo 'Причина: определяется';}else{echo 'Причина: '.$message_abandon;}?></strong></p>
                        <p>Пройдите в <a href="/clients/settings">настройки</a> и заполните данные для верификации</p>
                        <button type="button" class="close hide-abandon" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <!-----------------------ALERT-DANGER-END----------------------------->
            <? } ?>

            <!---------------------Content-Container-Start----------------------->
            <?php if($model_kyc->status_verify == 1 and $model_kyc->abandon == 9 and $model->kyc->show_abandon == 0){ ?>
                <!-------------------ALERT-SUCCESS-START------------------------------>
                <div class="alert-success user-cabinet-container">
                    <div class="alert alert-sucess alert-dismissible fade show" role="alert">
                        <p>Вы успешно верифицированы в нашей системе ваш ID: <strong><?=$model->id;?></strong><strong></p><p> <? if (!$model_kyc->abandon) {echo 'Причина: определяется';}else{echo 'Причина: '.$message_abandon;}?></strong></p>
                        <p>Пройдите в раздел <a href="/vendors">Партнеры</a> и получите информацию о них</p>
                        <button type="button" class="close hide-abandon" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <!-----------------------ALERT-SUCCESS-END----------------------------->
            <? } ?>

            <!---------------------Title-Section-Start--------------------------->
            <!--
        <div style="margin-top:20px;" class="section-title"><?=Yii::t('app','Личная информация')?></div>
        <div class="section-devider"></div>
        <div class="secondary-name">Ваш ID: <?=$model->id ?></div>
        <div class="date-of-verification"><?=Yii::t('app','Дата верификации')?>: <?=date('d.m.Y',$model_kyc->date_verify) ?></div>
        -->
            <!---------------------Title-Section-End----------------------------->


            <!---------------------CREDIT-CARDS-SECTION-START-------------------->
            <div class="credit-cards-section">
                <div class="credit-cards-row">
                    <div class="credit-cards1">
                        <div class="card-name"><?=Yii::t('app','Лицевой счет')?></div>
                        <div class="card-amount">
                            <?= number_format( $model->summ ? $model->summ : 0,0,'.',' ');?><span> cум</span>
                        </div>

                    </div>
                    <div class="credit-cards2">
                        <div class="card-name"><?=Yii::t('app','Лимит рассрочки')?></div>
                        <?php $credit_year =  $model->kyc->credit_year - Credits::getPaymentSumAll($model->id) < 0 ? 0 : $model->kyc->credit_year - Credits::getPaymentSumAll($model->id) ; ?>
                        <div class="card-amount"><?= number_format($credit_year,0,'.',' ');?><span> cум</span></div>
                    </div>
                    <div class="credit-cards3">
                        <div class="card-name"><?=Yii::t('app','Cashback (zCoins)')?></div>
                        <div class="card-amount"><?= $model->cashback ? $model->cashback : 0 ?><span> cум</span></div>

                    </div>
                    <!--
                    <div class="credit-cards4">
                        <div class="card-name"><?=Yii::t('app','Общая задолженность')?></div>
                        <div class="card-amount"><?=number_format(Credits::getPaymentDelaySumAll($model->id),2,'.',' ') ?></div>

                    </div>
                    -->
                </div>
            </div>
            <!---------------------CREDIT-CARDS-SECTION-END-------------------->


            <!---------------------MOBILE-CREDIT-CARDS-SECTION-START----------->

            <div class="carousel-cards-mobile">
                <div id="carouselExampleIndicators" interval="false" class="carousel slide" data-ride="carousel" data-interval="false">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="carousel-caption-top">
                                <h4>Лимит рассрочки</h4>
                            </div>
                            <img class="d-block w-100" src="../assets/images/card2.png" alt="Second slide">
                            <div class="carousel-caption-bottom">
                                <h5><?= number_format($credit_year,0,'.',' ');?> <span>cум</span></h5>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="carousel-caption-top">
                                <h4>Лицевой счёт</h4>
                            </div>
                            <img class="d-block w-100" src="../assets/images/card1.png" alt="First slide">
                            <div class="carousel-caption-bottom">
                                <h5><?= number_format( $model->summ ? $model->summ : 0,0,'.',' ');?> <span>cум</span></h5>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="carousel-caption-top">
                                <h4>CashBank (zCoins)</h4>
                            </div>
                            <img class="d-block w-100" src="../assets/images/card3.png" alt="Third slide">
                            <div class="carousel-caption-bottom">
                                <h5><?= $model->cashback ? $model->cashback : 0 ?> <span>cум</span></h5>
                            </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="sr-only">Previous</span>
                        <img src="../assets/icons/left-arrow.png" alt="icon">
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="sr-only">Next</span>
                        <img src="../assets/icons/right-arrow.png" alt="icon">
                    </a>
                </div>
            </div>
            <!---------------------MOBILE-CREDIT-CARDS-SECTION-END------------->



            <!---------------------PAYMENT-SECTION-START------------------------>
            <div class="payment-section">
                <? if(Credits::getPaymentDelaySumAll($model->id) !== 0) {?>
                    <div class="secondary-title">
                        <div class="debt-title">Сумма просроченной задолженности :</div>
                        <div class="debt-amount"><?=number_format(Credits::getPaymentDelaySumAll($model->id),2,'.',' '). ' СУММ' ?></div>
                    </div>
                <? } ?>
                <div class="section-title">Пополнение лицевого счета с карты</div>
                <div class="section-devider"></div>
                <div class="full-balance">
                    <div class="container-full-balance-card">
                        <h4>
                            <?php
                            if($model->auto_discard_type == 1){
                                echo 'UZCARD';
                            }
                            if($model->auto_discard_type == 2){
                                echo 'HUMO';
                            }
                            ?>
                        </h4>
                        <h5 id="balance"> <span>cум</span></h5>
                        <div class='full-balance-info-row'>
                            <h3>
                                <?php
                                if(isset($model->scoring)){
                                    $humo = '9860' . $model->scoring->bank_c . $model->scoring->card_h;
                                    $humo = substr_replace($humo, '******', -10, 6);
                                    echo $model->scoring->pan ? $model->scoring->pan : $humo;
                                }else{
                                    echo ' ';
                                }
                                ?>
                            </h3>
                            <h2>
                                <?php
                                $exp_m = mb_substr($model->scoring->exp, 0, 2);
                                $exp_y = mb_substr($model->scoring->exp, 2, 2);
                                $exp = $exp_m . '/' . $exp_y;
                                echo $model->scoring ? $exp : ' ';
                                ?>
                            </h2>
                        </div>
                    </div>
                    <div class="full-balance-form">
                        <div class="full-balance-form-column">
                            <span>Введите сумму</span>
                            <input class="full-balance-input amount" placeholder="1000 сум"></input>
                            <button class="btn-primary-outline pay">ПОПОЛНИТЬ</button>
                        </div>
                    </div>
                </div>
                <div class="secondary-title2">Другие способы пополнения лицевого счета</div>
                <div class="btn-wrapper">
                    <button onclick="location.href='https://my.click.uz/auth'" class='btn-secondary-outline'> <img src="../assets/icons/click_logo.png" alt="icon"></button>
                    <button onclick="location.href='https://payme.uz'" class='btn-secondary-outline'><img class="payme" src="../assets/icons/payme_logo.png" alt="icon"></button>
                    <button onclick="location.href='https://myuzcard.uz/'" class='btn-secondary-outline'><img src="../assets/icons/myUzCard.svg" alt="icon"></button>
                    <button onclick="location.href='https://play.google.com/store/apps/details?id=uz.kapitalbank.android&hl=ru'" class='btn-secondary-outline'><img src="../assets/icons/apelsin.png" alt="icon"><span>apelsin</span></button>
                </div>
            </div>
            <!---------------------PAYMENT-SECTION-END------------------------>

        </div>
        <!---------------------Content-Container-End------------------------->
    </div>
<?
$msg_text = Yii::t('app','Вы уверены что хотите отключить уведомление? Больше оно не появится!');
$msg_server_error = Yii::t('app','Ошибка сервера!');
$script = "
$('document').ready(function(){
    function discharge(){
        $('.full-balance-input').val(String($('.full-balance-input').val().replace(/[^0-9.]/g,'')).replace(/\B(?=(\d{3})+(?!\d))/g, \" \"));
        }
        
        discharge();
        
        $('.full-balance-input').keyup(function(){
        discharge();
    });
    
    $('.hide-abandon').click(function(){
    
	    if(!confirm('{$msg_text}')) return false;
	    let abandon_id = 1;
	    let client_id = {$model->id};
	    $.ajax({
            type: 'post',
            url: '/clients/hide-abandon',
            data: 'abandon_id='+abandon_id+'&client_id='+client_id+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data.status){
                   alert(data.info);
                }
            },
            error: function(data){
               alert('{$msg_server_error}')
            }
         });
	})
	
/////////////////////////////////////////////////////
status = 1;
  if(status){  
     $.ajax({
        type: 'post',
        url: '/clients/pay',
        data: '_csrf=' + yii.getCsrfToken(), 
        dataType: 'json',
        success: function(data){ 
        console.log(data);                     
            if(data.status){        
            balance = data.balance / 100;
               $('#balance').text(balance+' сум');                                
            }                            
        },
        error: function(data){
        //console.log(data);
          //  alert('no');                                      
        }
    });
   }    

$('.pay').click(function(){	
    amount = $('.amount').val(); 
    const p = /[^0-9]+/g;
    amount =  amount.replace (p, '');
    if(balance < amount){
        alert('Не достаточно средств на карте');
    }else{
        $.ajax({
            type: 'post',
            url: '/clients/pay',
            data: 'amount='+amount+'&_csrf=' + yii.getCsrfToken(), 
            dataType: 'json',
            success: function(data){ 
            console.log(data);                     
                if(data.status){
                alert(data.info);        
                    setTimeout(function() { 
                         location.reload();     
                    }, 1000)                                  
                }                            
            },
            error: function(data){
            //console.log(data);
              //  alert('no');                                      
            }
        });
    }   
     
 
})

});";
$this->registerJs($script, yii\web\View::POS_END);
?>