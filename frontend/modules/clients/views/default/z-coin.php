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

    </style>

<?= $this->render('_header') ?>


    <div class="reg-container black-bg  mb-30px">

        <!--START OF THE HEADER-->
        <div class="header">
            <div class="transparent">
                <div class="heading-title">Ваш баланс</div>
                <div class="header-content">
                    <div class="account-balance">
                        <div class="illustration-container">
                            <img src="../assets/images/illustration.svg" alt="">
                        </div>
                        <div class="account-balance-row">
                            <img src="../assets/icons/pay.png" alt="card">
                            <h1><!--1,300,468 СУМ-->
                                <?= $credit_year =  $model->kyc->credit_year - Credits::getPaymentSumAll($model->id) < 0 ? 0 : $model->kyc->credit_year - Credits::getPaymentSumAll($model->id) ;?>
                            </h1>
                            <div class="balance-date">
                                <?= date('d-m-Y'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="account-bonus">
                        <div class="illustration-container">
                            <img src="../assets/images/illustration2.svg" alt="">
                        </div>
                        <div class="account-balance-row">
                            <img src="../assets/icons/pay.png" alt="card">
                            <h1><?= $model->cashback ? $model->cashback : 0 ?> СУМ</h1>
                            <div class="balance-date">
                                <?= date('d-m-Y'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--END OF THE HEADER-->


        <br>
        <div class="update__client-container">
            <div class="update__client-title">
                <div class="heading"><?=Yii::t('app','Оплатить штраф ГУБДД в Рассрочку на 3 месяца')?></div>
            </div>
        </div>

        <!--START OF THE HOMEPAGE-SECTION1-->
        <div class="homepage-section-one">

            <div class="row">
                <div class="image-wrapper">
                    <div class="letters">
                        <span>AC</span>
                        <span>AR</span>
                        <span>GA</span>
                        <span>KV</span>
                        <span>NA</span>
                        <span>NV</span>
                        <span>RA</span>
                        <span>RJ</span>
                        <span>RR</span>
                        <span>RS</span>
                        <span>UZ</span>
                    </div>
                    <img src="../assets/images/undraw_towing_6yy4.svg" alt="fine">
                </div>

                <?php $form = ActiveForm::begin(
                    [
                        'id' => 'register-form',
                        'options' => [
                            'class' => 'form-horizontal',
                            //'enctype' => 'multipart/form-data',
                        ]

                    ]);

                ?>
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
                <?php ActiveForm::end() ?>

            </div>
        </div>
        <!--END OF THE HOMEPAGE-SECTION1-->

        <div class="update__client-container">
            <div class="update__client-title">
                <div class="heading"><?=Yii::t('app','Оплатить услуги с помощью zPay ')?></div>
            </div>
        </div>

        <!--START OF COMPANY-CARDS-CONTAINER-->
        <div class="company-cards-container">




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
            </div>
            <!--END OF THE ROW OF COMPANY-CARDS-1-->

            <!-- START ROW OF COMPANY-CARDS-2-->
            <div class="row-container">
                <div class="title-container">
                    <div class="img-wrapper">
                        <img src="../assets/icons/phone.svg" alt="icon">
                    </div>
                    <span>Интернет провайдеры</span>
                </div>
            </div>
            <div class="row">
                <div class="company-cards">
                    <img src="../assets/images/tps.png" alt="tps">
                    <span>tps</span>
                </div>
                <div class="company-cards">
                    <img src="../assets/images/comnet.png" alt="coment">
                    <span>comnet</span>
                </div>
                <div class="company-cards">
                    <img src="../assets/images/evo.png" alt="evo">
                    <span>evo</span>
                </div>
                <div class="company-cards">
                    <img src="../assets/images/uzmobile.png" alt="uzmobile">
                    <span>uzonline</span>
                </div>
                <div class="company-cards">
                    <img src="../assets/images/sarkor.png" alt="sarkor">
                    <span>sarkor telecom</span>
                </div>
            </div>
            <!--END OF THE ROW OF COMPANY-CARDS-2-->

            <!-- START ROW OF COMPANY-CARDS-3-->
            <div class="row-container">
                <div class="title-container">
                    <div class="img-wrapper">
                        <img src="../assets/icons/phone.svg" alt="icon">
                    </div>
                    <span>Кредиты</span>
                </div>
            </div>
            <div class="row">
                <div class="company-cards">
                    <img src="../assets/images/ipakyolibank.png" alt="ipak">
                    <span>ipak yoli</span>
                </div>
                <div class="company-cards">
                    <img src="../assets/images/kreditdavrbank.png" alt="davr">
                    <span>kredit davr bank</span>
                </div>
                <div class="company-cards">
                    <img src="../assets/images/crediton.png" alt="crediton">
                    <span>crediton</span>
                </div>
                <div class="company-cards">
                    <img src="../assets/images/alfa_kredit.png" alt="alfa">
                    <span>alfa</span>
                </div>
                <div class="company-cards">
                    <img src="../assets/images/technomart.png" alt="technomart">
                    <span>technomart</span>
                </div>
            </div>
            <!--END OF THE ROW OF COMPANY-CARDS-3-->
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