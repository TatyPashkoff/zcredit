<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Credits */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="credits-form">

    <?php 	$form = ActiveForm::begin(
        [
            'id' => 'credits-form',
				// 'enableClientValidation' => false,
				// 'enableAjaxValidation' => false,
				// 'action' => $model->isNewRecord ? '/admin/' : '/admin/.../update?id=' . $model->id ,
            'options' => [
                // 'class' => 'form-horizontal',
                'enctype' => 'multipart/form-data',
            ]
        ]); ?>
    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'supplier_id')->textInput() ?>

    <?= $form->field($model, 'credit_limit')->textInput() ?>

    <?= $form->field($model, 'deposit_first')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deposit_month')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'quantity')->textInput() ?>

<?= $form->field($model, "status")
        ->dropDownList([
            "0" => "Отключен",
            "1" => "Включен",
        ], $param = ["options" => [$model->isNewRecord ? 1 : $model->status => ["selected" => true]]]);
     ?>    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <a href="/credits/sendpolis?id=<?= $model->id ?>">Отправить полис</a>

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