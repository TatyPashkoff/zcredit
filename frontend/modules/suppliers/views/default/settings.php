<?php
use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);


?>
    <style>
        .title{
            color:#fff;
        }


    </style>

	<?= $this->render('_header') ?>

    <div class="reg-container black-bg w800 mb-30px">

		<?= $this->render('_menu',['active'=>'settings']) ?>

        <div class="title-with-border"><?//=Yii::t('app','Настройки')?></div>

        <?php
        $form = ActiveForm::begin(
            [
                'id' => 'clients-form',
                //'enableClientValidation' => false,
                //'enableAjaxValidation' => false,
                'options' => [
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                ]

            ]);



        ?>

        

        <div class="row mb-40">

            <div class="col-4">
                <?= $form->field($model, 'id')->textInput(['maxlength' => true,'readonly'=>true])->label('Ваш ID (логин для входа)')  ?>
            </div>

            <div class="col-4">
                <?= $form->field($model, 'username')->textInput(['maxlength' => true])->label('ФИО') ?>
            </div>

            <div class="col-4">
                <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>
            </div>

        </div>

        <div class="row mb-40">

            <div class="col-4">
                <?= $form->field($model, 'password_login')->textInput(['maxlength' => true])->label('Ваш пароль для входа') ?>
            </div>

            <div class="col-4">
                <?//= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
            </div>

           <!-- <div class="col-4">
                <?/*= $form->field($model, 'company')->textInput(['maxlength' => true]) */?>
            </div>-->

        </div>

        <div class="row mb-40">

            <div class="col-4">
                <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-4">
                <?= $form->field($model, 'inn')->textInput(['maxlength' => true])->label('ИНН') ?>
            </div>

        </div>
        <?php /*
        <div class="row mb-40">

            <div class="col-4">
                <?= $form->field($settings, 'deposit_first')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-4">
                <?= $form->field($settings, 'deposit_month')->textInput(['maxlength' => true]) ?>
            </div>



        </div> */ ?>

        <?php /*<h3 class="title"><?=Yii::t('app','Платежная система Payme')?></h3>

        <div class="row mb-40">

            <div class="col-4">
                <div class="reg-check mb-30px">
                    <label class="check <?=$settings->use_payme ? 'checked':'' ?>" data-id="use_payme"> <?=Yii::t('app','Включить оплату PAYME')?><span class="checkbox"><input type="checkbox" id="use_payme" name="use_payme" value="1" <?=$settings->use_payme ? 'checked':'' ?>></span></label>
                </div>
            </div>
            <div class="col-4">
                <?= $form->field($settings, 'payme_merchant_id')->textInput(['maxlength' => true]) ?>
            </div>

        </div>
        <h3 class="title"><?=Yii::t('app','Платежная система Click')?></h3>

        <div class="row mb-40">
            <div class="col-4">
                <div class="reg-check mb-30px">
                    <label class="check <?=$settings->use_click ? 'checked':'' ?>" data-id="use_click"> <?=Yii::t('app','Включить оплату CLICK')?><span class="checkbox"><input type="checkbox" id="use_click" name="use_click" value="1" <?=$settings->use_click ? 'checked':'' ?>></span></label>
                </div>
            </div>
            <div class="col-4">
                <?= $form->field($settings, 'click_secret')->textInput(['maxlength' => true]) ?>
                <?= $form->field($settings, 'click_merchant_id')->textInput(['maxlength' => true]) ?>

            </div>

            <div class="col-4">
                <?= $form->field($settings, 'click_merchant_user_id')->textInput(['maxlength' => true]) ?>
                <?= $form->field($settings, 'click_service_id')->textInput(['maxlength' => true]) ?>
            </div>

        </div> */ ?>


        <button type="submit" class="btn btn-default m-40"><?=Yii::t('app','Сохранить') ?></button>

        <?php ActiveForm::end() ?>

        </div>


<?php
$script = "$('document').ready(function(){
	
	$('#user-phone').mask('+(999)-99 999-99-99');
	
	$('label.check').click(function(){
	    if($(this).hasClass('checked')){
	        $('#'+ $(this).data('id')).prop('checked',false);
	        
	    }else{
	        $('#'+ $(this).data('id')).prop('checked',true);

	    }
	});
});";

$this->registerJs($script, yii\web\View::POS_END);

