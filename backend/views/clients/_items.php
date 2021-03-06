<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\CreditItems */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="credit-items-form">

    <?php 	$form = ActiveForm::begin(
        [
            'id' => 'credit-items-form',
				// 'enableClientValidation' => false,
				// 'enableAjaxValidation' => false,
			'action' => $model->isNewRecord ? '/admin/clients' : '/admin/clients/update-item?id=' . $model->id ,
            'options' => [
                // 'class' => 'form-horizontal',
                'enctype' => 'multipart/form-data',
            ]
        ]); ?>
    <?= $form->field($model, 'credit_id')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'quantity')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php //= $form->field($model, 'article')->textInput(['maxlength' => true]) ?>


    <h3>Список товаров</h3>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <a href="/admin/clients/credits?user_id=<?=$user_id ?>" class="btn btn-success">Назад</a>
    </div>

    <?php ActiveForm::end(); ?>

</div>
