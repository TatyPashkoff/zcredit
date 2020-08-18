<?php

use yii\helpers\Html;
use frontend\assets\LoginAsset;
use common\models\Mobile_Detect;

$detect = new Mobile_Detect;


$lang = Yii::$app->session->get('lang');
if($lang=='') $lang = 'ru';


    LoginAsset::register($this);



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
	<? if (!$detect->isMobile()) { ?>
	<header id="header" style="color:#000; font-size:18px;; background:#fff;">
		<div class="container">
		  <div class="row align-items-center">
			<div class="col-6 col-sm-4 col-xl-2 ">
			  <div class="logo">
				<a href="/"><img src="/images/reg-logo.jpg" alt="" class="img-fluid" style="margin-left:290%;"></a>
			  </div>
			</div>
			<div class="col-4 col-sm-6 col-xl-6">

		  </div>
			<div class="col-xl-4 d-none d-xl-block">
			  <span>+998 95 479 0770</span>
			<a href="https://telegram.im/@zmarketsupports" class="user-block" style="margin: 0 10px 0 15px">
				<img src="/images/headphone.jpg" alt="">
			</a>
			  <div class="language-list">
				<ul class="list-unstyled">
					<li><a href="/lang/ru">РУ</a></li>
					<li><a href="/lang/uz">UZ</a></li>
				</ul>
			  </div>
			  </div>
		  </div>
		</div><!--container-->
  </header>
	<? } ?>
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
