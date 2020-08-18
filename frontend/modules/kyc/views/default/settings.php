<?php
use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);


?>

	<?= $this->render('_header') ?>

    <div class="reg-container black-bg w800 mb-30px">

		<?= $this->render('_menu',['active'=>'settings']) ?>

        <div class="title-with-border"><?=Yii::t('app','Настройки')?></div>

        <?php
        $form = ActiveForm::begin(
            [
                'id' => 'clients-form',
                //'enableClientValidation' => false,
                //'enableAjaxValidation' => false,
                'options' => [
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                ]

            ]);

        ?>

        <div class="row mb-40">

            <div class="col-4">
                <?= $form->field($model, 'id')->textInput(['maxlength' => true,'readonly'=>true]) ?>
            </div>

            <div class="col-4">
                <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-4">
                <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
            </div>

        </div>

        <button type="submit" class="btn btn-default m-40"><?=Yii::t('app','Сохранить') ?></button>

        <?php ActiveForm::end() ?>

        </div>

<?php
$script = "$('document').ready(function(){
	
	$('#user-phone').mask('+(999)-99 999-99-99');
	
});";

$this->registerJs($script, yii\web\View::POS_END);

