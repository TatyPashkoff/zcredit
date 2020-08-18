<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="col-md-12">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab5_1" data-toggle="tab" aria-expanded="true">Общие</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab5_1">

                    <?= $form->field($model, 'username')->textInput(['maxlength' => true])->label('Имя пользователя') ?>

                    <?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                    <?php //= $form->field($model, 'settings')->textarea(['rows' => 6]) ?>

                    <?= $form->field($model, 'password')->textInput(['maxlength' => true]) ?>


                </div>


                <div class="clearfix"></div>

            </div>



        </div>
    </div>




    <?= $form->field($model, 'status')
        ->dropDownList(['Отключено','Включено'],
            $param = ['options' => [$model->status => ['Selected' => true]]]
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

