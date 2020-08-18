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

        <div class="update__client-container">
            <div class=" h2 update__client-title">
                <?=Yii::t('app','Личная информация')?>
            </div>

            <h3 class="update__main__headline update__main-client__headline">
                <?=$model->username?>
                <?php if($model_kyc->status_verify){ ?>
                <i class="fa fa-check"></i>
                <?php } ?>
            </h3>
            <span class="update__main__id">
                    ID: <?=$model->id?>
                </span>
            <?php if($model_kyc->status_verify){ ?>
            <span class="update__main__data">
                    <?=Yii::t('app','Дата верификации')?>: <?=date('d.m.Y',$model_kyc->date_verify) ?>
            </span>
            <?php } ?>
        </div>

        <!-- old: row mb-40 products-block -->
            <div class="cabinet-middle">
                <div class="row">
                <div class="col-sm-3">
                    <div class="update__main__item">
                        <!-- old: info-group mb-30px-->
                        <div class="update__main__item-container">
                            <!-- old: title-info -->
                            <h3 class="update__main__item-title"><?=Yii::t('app','Лицевой счет')?></h3>
                            <!-- old: circle -->
                            <div class="update__main__item-count"><?=number_format($model->balance,2,'.',' ') ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="update__main__item">
                        <!-- old: info-group mb-30px-->
                        <div class="update__main__item-container">
                            <!-- old: title-info -->
                            <h3 class="update__main__item-title"><?=Yii::t('app','Общая задолженность')?></h3>
                            <!-- old: circle -->
                            <div class="update__main__item-count">
                                <?=number_format(Credits::getPaymentDelaySumAll($model->id),2,'.',' ') ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="update__main__item">
                        <!-- old: info-group mb-30px-->
                        <div class="update__main__item-container">
                            <!-- old: title-info -->
                            <h3 class="update__main__item-title"><?=Yii::t('app','Годовой лимит рассрочки')?></h3>
                            <!-- old: circle -->
                            <div class="update__main__item-count">
                                3 000 000
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="update__main__item update__hook-last">
                        <!-- old: info-group mb-30px-->
                        <div class="update__main__item-container">
                            <!-- old: title-info -->
                            <h3 class="update__main__item-title">Z-Coin</h3>
                            <!-- old: circle -->
                            <div class="update__main__item-count">0</div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <!-- old: col-4 -->





        <div class="row mb-50px update__cnt-client">
            <div class="col-sm-3">
                <div class="update__main__item">
                    <!-- old: info-group mb-30px-->
                    <div class="update__main__item-container">
                        <!-- old: title-info -->
                        <h3 class="update__main__item-title"><?=Yii::t('app','Номер телефона')?></h3>
                        <!-- old: circle -->
                        <div class="update__main__item-count update__main__item-phone-n fs-16px">
                            <?=$model->phone ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="update__main__item">
                    <!-- old: info-group mb-30px-->
                    <div class="update__main__item-container">
                        <!-- old: title-info -->
                        <h3 class="update__main__item-title">
                            <?=Yii::t('app','Адрес')?>
                        </h3>
                        <!-- old: circle -->
                        <div class="update__main__item-count update__main__item-phone-n fs-16px">
                            <?=$model->address ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="update__main__item update__hook-last">
                    <!-- old: info-group mb-30px-->
                    <div class="update__main__item-container">
                        <!-- old: title-info -->
                        <h3 class="update__main__item-title">
                            <?=Yii::t('app','Серия паспорта')?>
                        </h3>
                        <!-- old: circle -->
                        <div class="update__main__item-count update__main__item-phone-n fs-16px">
                            <?=$model->passport_id .' ' . $model->passport_serial ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="update__score-container">
            <div class="update__score-header">
                <h2 class="update__score-headline update__client-title">
                    <?=Yii::t('app','Пополнение лицевого счета')?>
                </h2>
            </div>

            <div class="update__score-content">
                <div class="update__score-item">
                    <h2 class="update__main__item-title update__score-content__headline">
                        <?=Yii::t('app','Сумма просроченной задолженности')?>
                    </h2>
                    <span class="update__score-content__subline">
                         <?=number_format(Credits::getPaymentDelaySumAll($model->id),2,'.',' ') ?>
                    </span>
                </div>
                <div class="update__score-item update__score-item-hook">

                    <div class="update__score-list">
                        <h2 class="update__main__item-title update__score-content__headline">
                            <?=Yii::t('app','Выберите способ пополнения')?>
                        </h2>
                        <div class="example">
                            <label class="radio-button">
                                <input type="radio" class="radio-button__input" id="choice1-1" name="payme" data-id="1">
                                <span class="radio-button__label">
                                    <img class="update__acc-logo" src="/images/payme_logo.png" alt="">
                                </span>
                                <span class="radio-button__control"></span>
                            </label>
                            <label class="radio-button">
                                <input type="radio" class="radio-button__input" id="choice1-2" name="click" data-id="2">
                                <span class="radio-button__label">
                                    <img class="update__acc-logo" src="/images/click_logo.png" alt="">
                                </span>
                                <span class="radio-button__control"></span>
                            </label>
                            <div class="btn btn-default m-40 update__settings-btn" style="padding: 13px 60px 13px;">
                                <?=Yii::t('app','Пополнить')?>
                            </div>
                        </div>

                    </div>



                </div>
            </div>
        </div>

        </div>

<?php
$msg_pay = Yii::t('app','Укажите платежную систему!');
$script = " 
$('document').ready(function(){  
	 var pay_type = 0;
	 
	 $('.radio-button__input').click(function(){
	    pay_type = $(this).data('id');   
	 });
	 $('.update__settings-btn').click(function(){
	    if(pay_type==0){
	        alert('{$msg_pay}')
	        return false;
	    }
	    if(pay_type==1){
	        window.location.href = 'https://payme.uz/';
	    }else if(pay_type==2){
	    	window.location.href = 'https://my.click.uz/';
	    }
	    
	 })
	 
	 
});";
$this->registerJs($script, yii\web\View::POS_END);
