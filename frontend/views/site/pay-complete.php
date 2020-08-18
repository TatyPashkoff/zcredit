<?php


use yii\widgets\ActiveForm;

\frontend\assets\MainAsset::register($this);

$this->title = Yii::t('app','Успешная оплата');

// уничтожаем сессию и все связанные с ней данные.
//Yii::$app->session->destroy();
?>


        <div class="container">
            <?php $form = ActiveForm::begin(
                [
                    'id' => 'register-form',
                    'action' => '/login',
                    'options' => [
                        'class' => 'form-horizontal',
                        'enctype' => 'multipart/form-data',
                    ]

                ]);

            ?>

            <div class="update__settings-container" style="margin: 60px 0px;">
                <h1 class="update__finish-headline" style="text-align: center;font-size: 30px;">
                    <?=Yii::t('app','Оплата прошла успешно!')?>
                </h1>
            </div>

            <span class="stage__subline">
        Если вы не получили смс, пожалуйста, обратитесь к оператору колцентра
    </span>
	
		<div style="text-align: center">
                                <a href="/clients" class="btn btn-default">На главную</a>
                            </div>



            <?php ActiveForm::end() ?>
        </div>

