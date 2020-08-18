<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true])->label('Имя пользователя') ?>


    <?= $form->field($model, 'role')
        ->dropDownList(['9'=>'Админ','3'=>'Менеджер KYC','4'=>'API пользователь'] ,
            $param = ['options' => [$model->status => ['Selected' => true]]]
        );
    ?>
    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?php //= $form->field($model, 'settings')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'password')->textInput(['maxlength' => true]) ?>
	
    

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

