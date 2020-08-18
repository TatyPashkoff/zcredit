<?php


use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);

$this->title = Yii::t('app','Регистрация клиента');

?>



<div class="container" style="text-align: center">
    <a href="/">
        <img src="/images/reg-logo.png" alt="" style="width: 220px;margin-top: 40px;margin-bottom: 20px;">
    </a>
</div>
    <div class="reg-container reg-hook-container black-bg w700 mb-30px">

        <?php $form = ActiveForm::begin(
            [
                'id' => 'register-form',
                'options' => [
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                ]

            ]);

        ?>

        <div class="flex-parent">
            <div class="input-flex-container">
                <div class="form-group col-sm-12 ">
                    <input type="text" class="form-control  required" name="User[username]" required placeholder="Имя">
                </div>
            </div>
            <div class="input-flex-container">
                <div class="form-group col-sm-12 ">
                    <input type="text" class="form-control  required" name="User[lastname]" required placeholder="Фамилия">
                </div>
            </div>
            <div class="input-flex-container">
                <div class="form-group col-sm-12 ">
                    <input type="text" class="form-control  required" name="User[company]" required placeholder="Компания">
                </div>
            </div>
        </div>

        <!--<div class="reg-container tst black-bg w700 mb-30px">
            <div style="width: 30%;
    margin: auto;" class="form-group mb-30px">
                <label><?/*=Yii::t('app','Ваше имя')*/?></label>
                <input type="text" class="form-control required" name="User[username]" required>
            </div>
            <div style="width: 30%;
    margin: auto;" class="form-group mb-30px">
                <label><?/*=Yii::t('app','Ваша фамилия')*/?></label>
                <input type="text" class="form-control required" name="User[lastname]" required>
            </div>
            <div  style="width: 30%;
    margin: auto;" class="form-group mb-30px">
                <label><?/*=Yii::t('app','Название компании')*/?></label>
                <input type="text" class="form-control required" name="User[company]" required>
            </div>

        </div>-->



        <button type="submit" class="btn btn-default check-otp"><?=Yii::t('app','Дальше')?> <i class="fa fa-play" aria-hidden="true"></i></button>


        <?php ActiveForm::end() ?>

    </div>

