<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */


?>
    <style>
        .layer1 {
            /* background-color: #009F80; /* Цвет фона слоя */
            padding: 5px; /* Поля вокруг текста */
            float: left; /* Обтекание по правому краю */
            width: 200px; /* Ширина слоя */
        }
    </style>
<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="col-md-12">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab5_1" data-toggle="tab" aria-expanded="true">Общие</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab5_1">
                    <?php
                    $filial = $model->filial == 1 ? $filial = $form->field($model, 'address_filial')->textInput(['maxlength' => true])->label('Адрес филиала') : '';
                    ?>

                    <?= $form->field($model, 'id')->textInput(['maxlength' => true])->label('ID (логин)*') ?>
                    <?= $form->field($model, 'password_login')->textInput(['maxlength' => true])->label('Пароль для входа*') ?>
                    <?= $form->field($model, 'username')->textInput(['maxlength' => true])->label('Имя директора') ?>
                    <?= $form->field($model, 'brand')->textInput(['maxlength' => true])->label('Бренд') ?>
                    <?= $form->field($model, 'company')->textInput(['maxlength' => true])->label('Название компании') ?>
                    <?= $form->field($model, 'inn')->textInput(['maxlength' => true])->label('ИНН') ?>
                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'address')->textInput(['maxlength' => true])->label('Юридический адрес') ?>
                    <?= $filial ?>
                    <div class="layer1">
                    <?= $form->field($model, 'nds_state')
                        ->dropDownList(['Нет','Да'],
                            $param = ['options' => [$model->nds_state => ['Selected' => true]]]
                        ); ?>
                    </div>
                    <div class="layer1">
                        <?= $form->field($model, 'discount')->textInput(['maxlength' => true])->label('Скидка от магазина %') ?>
                    </div>
                    <?//= $form->field($model, 'nds')->textInput(['maxlength' => true]) ?>
                    <div class="layer1">
                        <?= $form->field($model, 'margin_three')->textInput(['maxlength' => true])->label('Маржа 3 мес %') ?>
                    </div>
                    <div class="layer1">
                        <?= $form->field($model, 'margin_six')->textInput(['maxlength' => true])->label('Маржа 6 мес %') ?>
                    </div>
                    <div class="layer1">
                        <?= $form->field($model, 'margin_nine')->textInput(['maxlength' => true])->label('Маржа 9 мес %') ?>
                    </div>
                    <div class="layer1">
                        <?= $form->field($model, 'seal_number')->textInput(['maxlength' => true])->label('Номер печати') ?>
                    </div>
                    <div class="layer1">
                        <?= $form->field($model, 'printer_number')->textInput(['maxlength' => true])->label('Номер принтера') ?>
                    </div>
                </div>


                <div class="clearfix"></div>

            </div>



        </div>
    </div>




    <?= $form->field($model, 'status')
        ->dropDownList(['Отключено','Включено'],
            $param = ['options' => [$model->status => ['Selected' => true]]]
    ); ?>

    <?= $form->field($model, 'service_type')
        ->dropDownList(['Товары','Услуги','Товары и Услуги'],
            $param = ['options' => [$model->service_type => ['Selected' => true]]]
        ); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>


<?php

$script = "
$(document).ready(function(){
    
    $('#user-role').change(function(e){        
        if( $(this).val() == 9 ){
            $('#manager-accept').fadeOut();            
        }else{
           $('#manager-accept').fadeIn();
        }
        
    });
});
";
$this->registerJs($script, yii\web\View::POS_END);

