<?php
use common\models\Credits;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

\frontend\assets\ZpayAsset::register($this);


?>
    <style>
        label{
            color:#fff;
        }

    </style>

<?= $this->render('_header') ?>


    <div class="reg-container black-bg  mb-30px">

    <!--START OF THE HEADER-->
    <div class="header">
        <div class="transparent">
            <div class="heading-title">Мои карты</div>
            <div class="header-content">
                <div class="card">
                    <img src="../assets/images/cardsZmarket1.png" alt="card-1">
                    <div class="card-type">Лицевой счёт</div>
                    <div class="amount"><?= $model->summ ? $model->summ : 0 ?></div>
                </div>
                <div class="card">
                    <img src="../assets/images/cardsZmarket2.png" alt="card-2">
                    <div class="card-type">Лимит рассрочки</div>
                    <?php $credit_year =  $model->kyc->credit_year - Credits::getPaymentSumAll($model->id) < 0 ? 0 : $model->kyc->credit_year - Credits::getPaymentSumAll($model->id) ; ?>
                    <div class="amount"><?= number_format($credit_year,2,'.',' ');?></div>
                </div>
                <div class="card">
                    <img src="../assets/images/cardsZmarket3.png" alt="card-3">
                    <div class="card-type">CashBank (zCoins)</div>
                    <div class="amount"><?= $model->cashback ? number_format($model->cashback,2,'.',' ') : '0.00' ?></div>
                </div>
                <div class="card">
                    <img src="../assets/images/cardsZmarket4.png" alt="card-4">
                    <div class="card-type">Общая задолженность</div>
                    <div class="amount"> <?=number_format(Credits::getPaymentDelaySumAll($model->id),2,'.',' ') ?></div>
                </div>
            </div>
            <!--HEADER CARDS MOBILE VERSION-->
            <div class="header-content-mobile">
                <div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="card">
                                <img src="../assets/images/cardsZmarket1.png" alt="card-1">
                                <div class="card-type">Лицевой счёт</div>
                                <div class="amount"><?= $model->summ ? $model->summ : 0 ?></div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="card">
                                <img src="../assets/images/cardsZmarket2.png" alt="card-2">
                                <div class="card-type">Лимит рассрочки</div>
                                <?php $credit_year =  $model->kyc->credit_year - Credits::getPaymentSumAll($model->id) < 0 ? 0 : $model->kyc->credit_year - Credits::getPaymentSumAll($model->id) ; ?>
                                <div class="amount"><?= number_format($credit_year,2,'.',' ');?></div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="card">
                                <img src="../assets/images/cardsZmarket3.png" alt="card-3">
                                <div class="card-type">CashBank (zCoins)</div>
                                <div class="amount"><?= $model->cashback ? number_format($model->cashback,2,'.',' ') : '0.00' ?></div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="card">
                                <img src="../assets/images/cardsZmarket4.png" alt="card-4">
                                <div class="card-type">Общая задолженность</div>
                                <div class="amount"><?=number_format(Credits::getPaymentDelaySumAll($model->id),2,'.',' ') ?></div>
                            </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleFade" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleFade" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--END OF THE HEADER-->


    <!--START OF THE HOMEPAGE-SECTION1-->
    <!--<div class="homepage-section-one">
            <div class="heading">Оплатить штраф ГУБДД в Рассрочку на 3 месяца</div>
            <hr>
            <div class="row">
                <div class="image-wrapper">
                    <img src="../assets/images/undraw_towing_6yy4.svg" alt="fine">
                </div>
                <form>
                    <div class="container-column">
                        <?php /*$form = ActiveForm::begin(
                            [
                                'id' => 'register-form',
                                'options' => [
                                    'class' => 'form-horizontal',
                                    //'enctype' => 'multipart/form-data',
                                ]

                            ]);

                        */?>
                        <div class="container-column">
                            <div class="input-title">
                                Номер постановления
                            </div>
                            <div class="input-container">
                                <select name="dropdown" id="dropdown">
                                    <option value="AC">AC</option>
                                    <option value="AR">AR</option>
                                    <option value="GA">GA</option>
                                    <option value="KV">KV</option>
                                    <option value="NA">NA</option>
                                    <option value="NV">NV</option>
                                    <option value="RA">RA</option>
                                    <option value="RJ">RJ</option>
                                    <option value="RR">RR</option>
                                    <option value="RS">RS</option>
                                    <option value="UZ">UZ</option>
                                </select>
                                <input name = "number" type="text" required placeholder="66666666">
                            </div>
                        </div>
                        <button class="btn-primary-filled">ОПЛАТИТЬ</button>
                        <?php /*ActiveForm::end() */?>

            </div>
        </div>-->
    <!--END OF THE HOMEPAGE-SECTION1-->

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
            <!--
			</div>
			-->
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