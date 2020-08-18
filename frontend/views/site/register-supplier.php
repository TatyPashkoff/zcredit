<?php


use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);

$this->title = Yii::t('app','Регистрация поставщика');

?>

<style>


</style>


<div class="container">
    <div class="row">
        <div class="offset-sm-4 col-sm-4">
            <div class="reg-container black-bg w700 mb-30px">
                <h2><?=Yii::t('app','Регистрация / Вход')?></h2>
                <?php $form = ActiveForm::begin(
                    [
                        'id' => 'check-form',
                        'options' => [
                            'class' => 'form-horizontal',
                            //'enctype' => 'multipart/form-data',
                        ]

                    ]);

                ?>

                <div class="reg-container reg-hook-container black-bg w700 mb-30px">

                    <div class="form-group mb-30px">
                        <input type="text" class="form-control required" name="User[username]" required placeholder="<?=Yii::t('app','Ваше имя')?>">
                    </div>
                    <div class="form-group mb-30px">
                        <input type="text" class="form-control required" name="User[lastname]" required placeholder="<?=Yii::t('app','Ваша фамилия')?>">
                    </div>

                    <div class="form-group mb-30px">
                        <input type="text" class="form-control required" name="User[company]" required placeholder="<?=Yii::t('app','Наименование организации')?>">
                    </div>
                    <div class="form-group mb-30px">
                        <input type="text" class="form-control required" name="User[inn]" required placeholder="<?=Yii::t('app','ИНН организации')?>">
                    </div>
                    <div class="form-group mb-30px">
                        <input type="text" class="form-control required" name="User[address]" required placeholder="<?=Yii::t('app','Юридический адрес организации')?>">
                    </div>

                    <div class="reg-check mb-30px">
                        <label class="check"> <?=Yii::t('app','Политика конфиденциальности')?> <span class="checkbox"><input type="checkbox" name="User[policy]"></span></label>
                    </div>

                </div>

                <button type="submit" class="btn btn-default check-otp"><?=Yii::t('app','Дальше')?> <i class="fa fa-play" aria-hidden="true"></i></button>


                <?php ActiveForm::end() ?>

            </div>

        </div>
    </div>
</div>
<?php

$msg_policy = Yii::t('app','Необходимо подтвердить Политику конфиденциальности');
$msg_required_field = Yii::t('app','Необходимо заполнить данное поле!');



$script = " 
$('document').ready(function(){
    
	 $('.btn-reg-cont').click(function(){
	 	 
	 	if( !$('.check').hasClass('checked') ){
	 	    alert('{$msg_policy}');
	 	    return false;
	 	}
	 		 		 	 
	    var submit = true;
	    $('.required').each(function(){
	        if($(this).val().length==0){
	            $(this).focus();
	            alert('{$msg_required_field}');
	            submit = false;
	            return false;
	        }
	    })  
	    if(!submit) return false;
        
        if(submit) $('form#check-form').submit();
	 })


});";
$this->registerJs($script, yii\web\View::POS_END);
