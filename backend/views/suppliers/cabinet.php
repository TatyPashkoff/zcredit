<?php

use yii\helpers\Html;
use frontend\assets\AppAsset;
use frontend\assets\MainAsset;


$lang = Yii::$app->session->get('lang');
if($lang=='') $lang = 'ru';



if( Yii::$app->controller->id=='site' && Yii::$app->controller->action->id=='index'  ){

    MainAsset::register($this);

}else{

    AppAsset::register($this);

}


if($page = \common\models\Pages::find()->where(['page'=>'contacts'])->one()){
    $page = json_decode($page->data,true);

}else{
    $page = false;
}




?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="fsvs">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">

    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">


    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
<?php $this->beginBody() ?>


<section id="content">
    <div class="container">
        <?= $content ?>
    </div>

</section><!--content-->




<?php // page_wrap

// информация о событиях
if( $info = Yii::$app->session->getFlash('info') ){
    if(isset($info['msg'])) $info = $info['msg'];
}else{
    $info ='';
}


$script = "
$(document).ready(function () { 
    var info_send = '{$info}';
    if( info_send.length >0 ) {
        alert(info_send);
    }
});";
$this->registerJs($script, yii\web\View::POS_END);
?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
