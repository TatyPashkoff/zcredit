<?php


use yii\web\View;
use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);

$this->title = Yii::t('app','Регистрация клиента');

?>
<style>
    .fix_numb {
        font-size: 24px;
        float: left;
        width: 20%;
        padding-top: 10px;
        color: #009f80;
        font-family: Roboto,sans-serif;
        font-weight: 400;
    }
    #phone {
        width: 80%;
        padding: 10px 15px 5px;
        margin-left: 20%;
    }
</style>


<div class="container" style="text-align: center">
    <a href="/">
        <img src="/images/reg-logo.png" alt="" style="width: 220px;margin-top: 40px;margin-bottom: 20px;">
    </a>
</div>
    <div class="reg-container reg-client__container">

        <?php $form = ActiveForm::begin(
            [
                'id' => 'register-form',
                'options' => [
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                ]

            ]);

        ?>

        <div class="form-group mb-30px hook-stp">
            <h2 class="reg-client__headline"><?=Yii::t('app','Регистрация партнера')?></h2>

            <span class="fix_numb hook-fix-nmb">+(998)</span>
            <input type="text" class="form-control s-uniq-phone" name="User[phone]" id="phone" required>


        </div>
        <div>
            <p style="font-size:14px;color: #6EBD8F;margin-bottom: 30px;">
                Ознакомлен с <a href="/publicofferforvendor.PDF"> публичной офертой  </a> &nbsp;&nbsp;&nbsp;<input type="checkbox" name="offer" value="1" checked>

            </p>

        </div>

        <div style="color: #6EBD8F;margin-bottom: 30px;">

            Если у вас уже имеется аккаунт <a style="color: #0014ff;Q" href="/login"><?=Yii::t('app','Войдите') ?></a>
        </div>

        <button type="submit" class="btn btn-default check-otp"><?=Yii::t('app','Дальше')?> <i class="fa fa-play" aria-hidden="true"></i></button>


        <?php ActiveForm::end() ?>

    </div>

<?php
$script = "$('document').ready(function(){
$('#phone').mask('99 999-99-99');
});";

$this->registerJs($script, yii\web\View::POS_END);