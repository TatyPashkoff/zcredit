<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */


if (Yii::$app->controller->action->id === 'login') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */


    echo $this->render(
        'main-login',
        ['content' => $content]
    );
    
} else {

    /* if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    } */

    if( Yii::$app->controller->id=='stat' ){

    }else{

        //frontend\assets\AppAsset::register($this);

    }
	
frontend\assets\AppAsset::register($this);
	
	
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="fix-header card-no-border logo-center">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?php //= $this->render('header.php') ?>

        <?= $this->render('content.php',[
                'content' => $content
        ]) ?>

    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
