<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Stock */
/* @var $form yii\widgets\ActiveForm */



?>

<div class="stock-form">
    <style>
        .layer1 {
            /* background-color: #009F80; /* Цвет фона слоя */
            padding: 5px; /* Поля вокруг текста */
            float: left; /* Обтекание по правому краю */
            width: 200px; /* Ширина слоя */
        }
        .datepicker-days {
            display: block !important;
        }
    </style>

    <?php 	$form = ActiveForm::begin(
        [
            'id' => 'stock-form',
            'enableClientValidation' => false,
            'enableAjaxValidation' => false,

            'options' => [
                // 'class' => 'form-horizontal',
                'enctype' => 'multipart/form-data',
            ]
        ]);

    ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'company')->dropDownList($vendors, ['multiple' => true]); ?>
    <div class="layer1">
    <?='<label>Дата начала</label>';
        echo DatePicker::widget([
        'name' => 'Stock[date_start]',
        'value' => date("m/d/Y", $model->date_start),
        'options' => ['placeholder' => 'Выберите начальную дату'],
        'pluginOptions' => [
        'format' => 'dd-mm-yyyy',
        'todayHighlight' => true,

            ]
        ]);?>
    </div>
    <div class="layer1">
        <?='<label>Дата окончания</label>';
        echo DatePicker::widget([
            'name' => 'Stock[date_end]',
            'value' => date("m/d/Y", $model->date_end),
            'options' => ['placeholder' => 'Выберите начальную дату'],
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'todayHighlight' => true,

            ]
        ]);?>
    </div>

    <div class="clearfix"></div>

    <?= $form->field($model, 'sum')->textInput(['maxlength' => true]) ?>
    <?//= $form->field($model, 'margin_three')->textInput(['maxlength' => true]) ?>
    <?//= $form->field($model, 'margin_six')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'percent')->textInput(['maxlength' => true]) ?>



    <?= $form->field($model, "status")
        ->dropDownList([
            "0" => "Отключена",
            "1" => "Включена",
        ], $param = ["options" => [$model->isNewRecord ? 1 : $model->status => ["selected" => true]]]);
    ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
