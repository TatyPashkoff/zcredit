<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\CreditItems */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Оплата за месяц';

?>

<div class="credit-items-form">

    <?php 	$form = ActiveForm::begin(
        [
            'id' => 'payment-form',
				// 'enableClientValidation' => false,
				// 'enableAjaxValidation' => false,
			//'action' => $model->isNewRecord ? '/admin/clients' : '/admin/clients/update-payment?id=' . $model->id ,
            'options' => [
                // 'class' => 'form-horizontal',
               // 'enctype' => 'multipart/form-data',
            ]
        ]); ?>
    <?= $form->field($model, 'credit_id')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'delay')->textInput(['maxlength' => true]) ?>

    <div class="form-group field-credithistory-credit_date">
        <label class="control-label" for="credithistory-credit_date">Дата предстоящей оплаты</label>
        <input type="date" id="credithistory-credit_date" class="form-control" name="CreditHistory[credit_date]" value="<?=date('Y-m-d',$model->credit_date) ?>">

        <div class="help-block"></div>
    </div>

    <?php // echo $model->payment_date; exit; ?>
    <div class="form-group field-credithistory-payment_date">
        <label class="control-label" for="credithistory-payment_date">Дата оплаты</label>
        <input type="date" id="credithistory-payment_date" class="form-control" name="CreditHistory[payment_date]" value="<?=date('Y-m-d',$model->payment_date) ?>">

        <div class="help-block"></div>
    </div>

    <?= $form->field($model, 'payment_type')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, "payment_status")
        ->dropDownList([
            "0" => "Не оплачен",
            "1" => "Оплачен",
        ], $param = ["options" => [$model->isNewRecord ? 1 : $model->payment_status => ["selected" => true]]]);
    ?>

    <h3>Список товаров</h3>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <a href="/admin/clients/payments?credit_id=<?=$model->credit_id ?>" class="btn btn-success">Назад</a>
    </div>

    <?php ActiveForm::end(); ?>

</div>
