<?php \frontend\assets\MainAsset::register($this);

use yii\widgets\ActiveForm;

$this->title = Yii::t('app','Проверка карты');

?>

<?= $this->render('_header') ?>


<div class="container">
    <div class="reg-container black-bg mb-30px text-center" style="margin: 60px 0">
        <div class="inline-block" style="display: inline-block">
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

                <div class="form-group uzcard-ad">
                    <label><?=Yii::t('app','Введите смс код подтверждения привязки карты')?></label>
                    <input type="text" class="form-control" id="user_sms_code" name="code" required>
                </div>


                <span class="stage__subline">На балансе Вашего мобильного телефона должно быть не менее 100 сум</span>
            </div>

            <button type="submit" class="btn btn-default m-40 btn-reg-cont"><?=Yii::t('app','Дальше')?> <i class="fa fa-play" aria-hidden="true"></i></button>

        </div>
        <?php ActiveForm::end() ?>

    </div>
</div>


