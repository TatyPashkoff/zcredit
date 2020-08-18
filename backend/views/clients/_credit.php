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
     ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <a href="/admin/clients/credits?user_id=<?=$user_id ?>" class="btn btn-success">Назад</a>

    </div>

    <?php ActiveForm::end(); ?>

</div>
