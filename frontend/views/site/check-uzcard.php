<?php


use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);

$this->title = Yii::t('app','Подтверждение смс на безакцептное списание от uzcard');

?>
<style>.title{color:#fff;}</style>



    <div class="row justify-content-center align-items-center mb-30px">
        <div class="col-3">
            <div class="logo mb-20px"><a href="#"><img src="/images/logo.png" alt="" class="img-fluid"></a></div>
        </div>
        <div class="col-5">
            <div class="text-right-logo mb-40px text-left"><?=Yii::t('app','Z-MARKET - БЫСТРО, ПРОСТО, ПОЛУЧИ')?></div>
        </div>
    </div><!--row-->
    <div class="reg-container black-bg w700 mb-30px">
        <?php /* <a href="#" class="btn btn-default mb-40px"><i class="fa fa-user" aria-hidden="true"></i> <?=Yii::t('app','Регистрация / Вход')?></a> */ ?>

        <?php $form = ActiveForm::begin(
            [
                'id' => 'check-form',
                'options' => [
                    'class' => 'form-horizontal',
                    //'enctype' => 'multipart/form-data',
                ]

            ]);

        ?>

        <h3 class="title"><?=Yii::t('app','Безакцептное списание от uzcard')?></h3>
        <h3 class="title"><?=Yii::$app->session->getFlash('info')?></h3>
        <div class="reg-container black-bg w700 mb-30px">


            <div class="form-group mb-30px">
                <label><?=isset($user->scoring) ? $user->scoring->phone : '' . ' '. Yii::t('app','На Ваш номер телефона отправлено смс с кодом подтверждения')?></label>
            </div>

            <div class="form-group mb-30px">
                <label><?=Yii::t('app','Введите код подтверждения из смс')?></label>
                <input type="text" class="form-control" name="code" required>
            </div>


        </div>

        <button type="submit" class="btn btn-transparent btn-reg-cont"><?=Yii::t('app','Продолжить')?> <i class="fa fa-play" aria-hidden="true"></i></button>

        <?php ActiveForm::end() ?>

    </div>




