<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Kyc */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kyc-form">

    <?php 	$form = ActiveForm::begin(
        [
            'id' => 'kyc-form',
				// 'enableClientValidation' => false,
				// 'enableAjaxValidation' => false,
				// 'action' => $model->isNewRecord ? '/admin/' : '/admin/.../update?id=' . $model->id ,
            'options' => [
                // 'class' => 'form-horizontal',
                'enctype' => 'multipart/form-data',
            ]
        ]); ?>
    <div class="form-group">
        <label>Дата создания:</label>
        <?=date('d.m.Y',$model->created_at) ?>
    </div>
    <?= $form->field($model, 'client_id')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, "status_verify")
        ->dropDownList([
            "0" => "Не подтвержден",
            "1" => "Подтвержден",
        ], $param = ["options" => [$model->isNewRecord ? 1 : $model->status_verify => ["selected" => true]]]);
    ?>

    <div class="form-group">
        <label>Дата верификации:</label>
        <?=date('d.m.Y',$model->date_verify) ?>
    </div>

    <?= $form->field($model, 'delay')->textInput() ?>

    <?= $form->field($model, 'salary')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'credit_month')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'credit_year')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'credit_rating')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, "status")
        ->dropDownList([
            "0" => "Отключен",
            "1" => "Включен",
        ], $param = ["options" => [$model->isNewRecord ? 1 : $model->status => ["selected" => true]]]);
     ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php $script = "$('document').ready(function(){
    
	$(document).on('change','.image',function(){
	  var input = $(this)[0];
	  var obj = $(this);
	  if ( input.files && input.files[0] ) {
		if ( input.files[0].type.match('image.*') ) {
		  var reader = new FileReader();
		  reader.onload = function(e){ $('img#'+obj.attr('id')).attr('src', e.target.result);}
		  reader.readAsDataURL(input.files[0]);
		} else console.log('is not image mime type');
	  } else console.log('not isset files data or files API not support');  
	});  
	$('.img_preview').click(function(e){ e.preventDefault(); $('#img_preview.image').click(); });});";$this->registerJs($script, yii\web\View::POS_END);