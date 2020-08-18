<?php


use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);

$this->title = Yii::t('app','Регистрация клиента');
// уничтожаем сессию и все связанные с ней данные.
Yii::$app->session->destroy();
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

        <div class="reg-container black-bg w700 mb-30px">


            <h2><?=Yii::t('app','Спасибо за регистрацию! В течении 30 минут с Вами свяжется наш сотрудник отдела продаж. С Уважением команда zMarket! ')?></h2>

        </div>

        <button type="submit" class="btn btn-default check-otp"><?=Yii::t('app','Войти в кабинет')?></button>
        <input type="hidden" class="form-control" name="complete">


        <?php ActiveForm::end() ?>

    </div>

