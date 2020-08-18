<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Partners */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="partners-form">

        <?php 	$form = ActiveForm::begin(
            [
                'id' => 'partnerscat-form',
                //'enableClientValidation' => false,
                //'enableAjaxValidation' => false,
                // 'action' => $model->isNewRecord ? '/admin/' : '/admin/.../update?id=' . $model->id ,
                'options' => [
                    //'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                ]
            ]);?>




        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tabLang_1" data-toggle="tab" aria-expanded="true">RU</a></li>
            </ul>
            <div class="tab-content">

                <div class="tab-pane active" id="tabLang_1">

                    <?= $form->field($model, 'cat_name')->textInput(['maxlength' => true,'required'=>true]) ?>
                </div>

            </div>
        </div>





        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>